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

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Routing\ClassResourceInterface;
use JMS\Serializer\SerializationContext;
use Sulu\Bundle\EventBundle\Entity\Event;
use Sulu\Bundle\EventBundle\Event\EventManager;
use Sulu\Bundle\EventBundle\Event\Exception\EventDependencyNotFoundException;
use Sulu\Bundle\EventBundle\Event\Exception\EventNotFoundException;
use PHPCR\ItemNotFoundException;
use Sulu\Component\Rest\Exception\EntityNotFoundException;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactory;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\RestController;
use Sulu\Component\Rest\RestHelperInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * EventController
 *
 * @package    Sulu\Bundle\EventBundle\Controller
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventController extends RestController implements ClassResourceInterface
{

    /**
     * Serialization groups for event
     *
     * @var array
     */
    protected static $eventSerializationGroups = [
        'fullEvent',
        'fullEventEntryFee',
        'fullEventOrganizer',
        'partialCategory',
    ];

    /**
     * Returns the event manager
     *
     * @return EventManager
     */
    private function getManager()
    {
        return $this->get('sulu_event.event_manager');
    }

    /**
     * Returns all fields that can be used by list
     *
     * @Get("events/fields")
     * @return mixed
     */
    public function getFieldsAction()
    {
        return $this->handleView(
            $this->view(
                array_values(
                    $this->getManager()->getFieldDescriptors()
                )
            )
        );
    }

    /**
     * Returns a list of event categories
     *
     * @Get("events/categories")
     * @param Request $request
     * @return mixed
     */
    public function getCategoriesAction(Request $request)
    {
        /** @var RestHelperInterface $restHelper */
        $restHelper = $this->get('sulu_core.rest_helper');

        /** @var DoctrineListBuilderFactory $factory */
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');

        $listBuilder = $factory->create(EventManager::$eventCategoryEntityName);

        $restHelper->initializeListBuilder(
            $listBuilder,
            array(
                'id' => new DoctrineFieldDescriptor(
                    'id',
                    'id',
                    EventManager::$eventCategoryEntityName,
                    'public.id',
                    array(),
                    true
                ),
                'name' => new DoctrineFieldDescriptor(
                    'name',
                    'name',
                    EventManager::$eventCategoryEntityName,
                    'public.name',
                    array(),
                    true
                )
            )
        );

        $list = new ListRepresentation(
            $listBuilder->execute(),
            'categories',
            'get_event_categories',
            $request->query->all(),
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );

        $view = $this->view($list, 200);

        return $this->handleView($view);
    }

    /**
     * Retrieves and shows a event with the given ID
     *
     * @param Request $request
     * @param integer $eventId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Request $request, $eventId)
    {
        $locale = $this->getLocale($request);
        $view = $this->responseGetById(
            $eventId,
            function ($eventId) use ($locale) {
                /** @var Event $event */
                $event = $this->getManager()->findByIdAndLocale($eventId, $locale);

                return $event;
            }
        );

        $view->setSerializationContext(
            SerializationContext::create()->setGroups(
                static::$eventSerializationGroups
            )
        );

        return $this->handleView($view);
    }

    /**
     * Returns a list of events
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction(Request $request)
    {
        /** @var RestHelperInterface $restHelper */
        $restHelper = $this->get('sulu_core.rest_helper');

        /** @var DoctrineListBuilderFactory $factory */
        $factory = $this->get('sulu_core.doctrine_list_builder_factory');

        $listBuilder = $factory->create(EventManager::$eventEntityName);

        $restHelper->initializeListBuilder($listBuilder, $this->getManager()->getFieldDescriptors());

        $list = new ListRepresentation(
            $listBuilder->execute(),
            'events',
            'get_events',
            $request->query->all(),
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );

        $view = $this->view($list, 200);

        return $this->handleView($view);
    }

    /**
     * Delete event with given id
     *
     * @param string $eventId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($eventId)
    {
        $view = $this->responseDelete(
            $eventId,
            function ($eventId) {
                try {
                    $this->getManager()->delete($eventId);
                } catch (ItemNotFoundException $ex) {
                    throw new EntityNotFoundException(EventManager::$eventEntityName, $eventId);
                }
            }
        );

        return $this->handleView($view);
    }

    /**
     * Creates and stores a new event.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request)
    {
        try {
            $event = $this->getManager()->save(
                $request->request->all(),
                $this->getLocale($request)
            );

            $view = $this->view($event, 200);
        } catch (EventDependencyNotFoundException $exc) {
            $exception = new EntityNotFoundException($exc->getEntityName(), $exc->getId());
            $view = $this->view($exception->toArray(), 400);
        }

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @param integer $eventId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putAction(Request $request, $eventId)
    {
        try {
            $event = $this->getManager()->save(
                $request->request->all(),
                $this->getLocale($request),
                $eventId
            );

            $view = $this->view($event, 200);
        } catch (EventNotFoundException $exc) {
            $exception = new EntityNotFoundException($exc->getEntityName(), $exc->getId());
            $view = $this->view($exception->toArray(), 404);
        } catch (EventDependencyNotFoundException $exc) {
            $exception = new EntityNotFoundException($exc->getEntityName(), $exc->getId());
            $view = $this->view($exception->toArray(), 400);
        }

        return $this->handleView($view);
    }
}
