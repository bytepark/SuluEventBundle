<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Bundle\EventBundle\Api\EventEntryFee;
use Sulu\Bundle\EventBundle\Entity\EventEntryFee as EventEntryFeeEntity;
use Sulu\Bundle\EventBundle\Event\EventManager;
use Sulu\Component\Rest\Exception\EntityNotFoundException;
use Sulu\Component\Rest\Exception\RestException;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactory;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\RestController;
use Sulu\Component\Rest\RestHelperInterface;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineJoinDescriptor;
use Symfony\Component\HttpFoundation\Request;

/**
 * EventEntryFeeController
 *
 * @package    Sulu\Bundle\EventBundle\Controller
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventEntryFeeController extends RestController implements ClassResourceInterface
{

    protected $joinDescriptors;

    /**
     * @return EventManager
     */
    private function getManager()
    {
        return $this->get('sulu_event.event_manager');
    }

    /**
     * Returns all fields that can be used by list
     *
     * @Get("entryfee/fields")
     * @return mixed
     */
    public function getFieldsAction()
    {
        return $this->handleView($this->view(array_values($this->getManager()->getEntryFeeFieldDescriptors())));
    }

    /**
     * @Get("entryfee/{entryFeeId}")
     *
     * @param int $entryFeeId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($entryFeeId)
    {
        $view = $this->responseGetById(
            $entryFeeId,
            function ($entryFeeId) {
                return $this->getDoctrine()
                    ->getRepository(EventManager::$eventEntryFeeEntityName)
                    ->find($entryFeeId);
            }
        );

        return $this->handleView($view);
    }

    /**
     * @Get("entryfee")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction(Request $request)
    {
        /** @var RestHelperInterface $restHelper */
        $restHelper = $this->get('sulu_core.rest_helper');

        /** @var DoctrineListBuilderFactory $factory */
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');

        $listBuilder = $factory->create(EventManager::$eventEntryFeeEntityName);

        $restHelper->initializeListBuilder($listBuilder, $this->getManager()->getEntryFeeFieldDescriptors());

        $filter['event'] = $request->get('event', null);

        foreach ($filter as $key => $value) {
            $this->joinDescriptors['event'] = new DoctrineFieldDescriptor(
                'id',
                'event',
                EventManager::$eventEntityName . 'id',
                '',
                array(
                    EventManager::$eventEntityName . 'id' => new DoctrineJoinDescriptor(
                        EventManager::$eventEntityName . 'id',
                        EventManager::$eventEntryFeeEntityName . '.event'
                    )
                ),
                false
            );

            $listBuilder->where($this->joinDescriptors[$key], $value);
        }

        $list = new ListRepresentation(
            $listBuilder->execute(),
            'entryfee',
            'get_evententry_fees',
            $request->query->all(),
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );

        $view = $this->view($list, 200);

        return $this->handleView($view);
    }

    /**
     * @Put("entryfee/{entryFeeId}")
     *
     * @param int     $entryFeeId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction($entryFeeId, Request $request)
    {
        try {
            $objectManager = $this->getDoctrine()->getManager();
            $entryFee = $this->getEntityById(EventManager::$eventEntryFeeEntityName, $entryFeeId);
            $this->processEntryFeeData($entryFee, $request);

            $objectManager->persist($entryFee);
            $objectManager->flush();
            $view = $this->view(
                new EventEntryFee(
                    $entryFee,
                    $this->getUser()->getLocale()
                ),
                200
            );
        } catch (EntityNotFoundException $enfe) {
            $view = $this->view($enfe->toArray(), 404);
        } catch (RestException $re) {
            $view = $this->view($re->toArray(), 400);
        }

        return $this->handleView($view);
    }

    /**
     * Processes the data for an entry fee from an request
     *
     * @param EventEntryFeeEntity $entryFee
     * @param Request $request
     * @throws RestException
     */
    protected function processEntryFeeData(EventEntryFeeEntity $entryFee, Request $request)
    {
        $entryFee->setValidUntilDate(new \DateTime($request->get('validUntilDate')));
        $entryFee->setPrice(str_replace(',', '.', $request->get('price')));

        $event = $this->getEntityById(
            EventManager::$eventEntityName,
            $request->get('eventId')
        );
        $entryFee->setEvent($event);
    }

    /**
     * Returns an entity for a specific id
     *
     * @param string $entityName
     * @param int    $entryFeeId
     *
     * @return mixed
     * @throws EntityNotFoundException
     */
    protected function getEntityById($entityName, $entryFeeId)
    {
        $objectManager = $this->getDoctrine()->getManager();
        $entity = $objectManager->getRepository($entityName)->find($entryFeeId);

        if (!$entity) {
            throw new EntityNotFoundException($entityName, $entryFeeId);
        }

        return $entity;
    }

    /**
     * @Delete("entryfee/{entryFeeId}")
     *
     * @param int $entryFeeId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($entryFeeId)
    {
        $delete = function ($entryFeeId) {
            $objectManager = $this->getDoctrine()->getManager();
            $entryFee = $this->getEntityById(EventManager::$eventEntryFeeEntityName, $entryFeeId);
            $objectManager->remove($entryFee);
            $objectManager->flush();
        };

        $view = $this->responseDelete($entryFeeId, $delete);

        return $this->handleView($view);
    }

    /**
     * @Post("entryfee")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        try {
            $objectManager = $this->getDoctrine()->getManager();
            $entryFee = new EventEntryFeeEntity();
            $this->processEntryFeeData($entryFee, $request);

            $objectManager->persist($entryFee);
            $objectManager->flush();

            $view = $this->view(
                new EventEntryFee(
                    $entryFee,
                    $this->getUser()->getLocale()
                ),
                200
            );
        } catch (EntityNotFoundException $enfe) {
            $view = $this->view($enfe->toArray(), 404);
        } catch (RestException $re) {
            $view = $this->view($re->toArray(), 400);
        }

        return $this->handleView($view);
    }
}
