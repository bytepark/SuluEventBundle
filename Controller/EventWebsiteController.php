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

use Behat\Transliterator\Transliterator;
use Sulu\Bundle\EventBundle\Event\EventManagerInterface;
use Sulu\Bundle\EventBundle\Util\FilterUtils;
use Sulu\Bundle\WebsiteBundle\Resolver\ParameterResolverInterface;
use Sulu\Bundle\WebsiteBundle\Resolver\RequestAnalyzerResolverInterface;
use Sulu\Component\Webspace\Analyzer\RequestAnalyzerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * EventWebsiteController
 *
 * @package    Sulu\Bundle\EventBundle\Controller
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventWebsiteController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var RequestAnalyzerInterface
     */
    private $requestAnalyzer;

    /**
     * @var RequestAnalyzerResolverInterface
     */
    private $requestAnalyzerResolver;

    /**
     * @var RouterInterface
     */
    private $routing;

    /**
     * @var ParameterResolverInterface
     */
    private $parameterResolver;

    /**
     * @var int
     */
    private $cacheMaxAge;

    /**
     * @var int
     */
    private $cacheSharedMaxAge;

    /**
     * @param EngineInterface $templating
     * @param EventManagerInterface $eventManager
     * @param RequestAnalyzerInterface $requestAnalyzer
     * @param RequestAnalyzerResolverInterface $requestAnalyzerResolver
     * @param RouterInterface $routing
     * @param ParameterResolverInterface $parameterResolver
     * @param int $cacheMaxAge
     * @param int $cacheSharedMaxAge
     */
    public function __construct(
        EngineInterface $templating,
        EventManagerInterface $eventManager,
        RequestAnalyzerInterface $requestAnalyzer,
        RequestAnalyzerResolverInterface $requestAnalyzerResolver,
        RouterInterface $routing,
        ParameterResolverInterface $parameterResolver,
        $cacheMaxAge = 240,
        $cacheSharedMaxAge = 240
    )
    {
        $this->templating = $templating;
        $this->eventManager = $eventManager;
        $this->requestAnalyzer = $requestAnalyzer;
        $this->requestAnalyzerResolver = $requestAnalyzerResolver;
        $this->routing = $routing;
        $this->parameterResolver = $parameterResolver;
        $this->cacheMaxAge = $cacheMaxAge;
        $this->cacheSharedMaxAge = $cacheSharedMaxAge;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $page = $request->get('page', 1);

        $filter = array();
        $filter['isTopEvent'] = $request->get('is_top_event', null);
        $filter['categories'] = $request->get('categories', null);
        $filter['dateFrom'] = $request->get('date_from', null);
        $filter['dateTo'] =$request->get('date_to', null);

        $filter = FilterUtils::locationFilter($request, $filter);
        $filter['searchString'] = $request->get('search_string', null);

        $response = $this->templating->renderResponse(
            'SuluEventBundle:templates:list.html.twig',
            array_merge(
                array(
                    'eventCategories' => $this->eventManager->getCategories(),
                    'eventCountries' => $this->eventManager->getCountries(),
                    'eventsForMap' => $this->slimDownEventsForMap($this->eventManager->findEventsForMap($filter))
                ),
                $this->eventManager->findFilteredEvents($page, $filter)
            )
        );

        $response->setMaxAge($this->cacheMaxAge);
        $response->setSharedMaxAge($this->cacheSharedMaxAge);

        return $response;
    }

    /**
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction(Request $request)
    {
        $event = $this->eventManager->findByIdAndLocale($request->get('id'), 'de', true);

        if (!$event) {
            throw new NotFoundHttpException(404);
        }

        $filter['lat'] = $event->getLatitude();
        $filter['long'] = $event->getLongitude();
        $filter['area'] = 100;

        $requestData = $this->requestAnalyzerResolver->resolve($this->requestAnalyzer);

        $resolverData = $this->parameterResolver->resolve(array(), $this->requestAnalyzer, null, false);

        $response = $this->templating->renderResponse(
            'SuluEventBundle:templates:detail.html.twig',
            array_merge(
                array(
                    'event' => $event,
                    'urls' => isset($resolverData['urls']) ? $resolverData['urls'] : array(),
                ),
                $requestData
            )
        );

        $response->setMaxAge($this->cacheMaxAge);
        $response->setSharedMaxAge($this->cacheSharedMaxAge);

        return $response;
    }

    /**
     * @param array  $events
     *
     * @return string
     */
    private function slimDownEventsForMap($events)
    {
        foreach ($events as $key => $value) {
            $events[$key]['startDate'] = $events[$key]['startDate']->format(\DateTime::RFC2822);

            $events[$key]['country'] = Intl::getRegionBundle()->getCountryName($events[$key]['country'], 'de');

            if (!is_null($events[$key]['startTime'])) {
                $events[$key]['startTime'] = $events[$key]['startTime']->format(\DateTime::RFC2822);
            }

            $eventId = $events[$key]['id'];
            $eventSlug = Transliterator::transliterate($events[$key]['title']);

            $events[$key]['url'] = $this->routing->generate(
                'sulu_events.detail',
                array(
                    'id' => $eventId,
                    'slug' => $eventSlug,
                )
            );

            unset($events[$key]['id']);
        }

        return json_encode($events);
    }
}
