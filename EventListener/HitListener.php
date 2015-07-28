<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\EventListener;

use Sulu\Bundle\EventBundle\Event\EventManagerInterface;
use Massive\Bundle\SearchBundle\Search\Event\HitEvent;
use Massive\Bundle\SearchBundle\Search\Field;
use Sulu\Component\Webspace\Analyzer\RequestAnalyzerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * HitListener
 *
 * @package    Sulu\Bundle\EventBundle\EventListener
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class HitListener
{

    /**
     * @var RequestAnalyzerInterface
     */
    private $requestAnalyzer;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RequestAnalyzerInterface $requestAnalyzer
     * @param EventManagerInterface    $eventManager
     * @param RouterInterface          $router
     */
    public function __construct(
        RequestAnalyzerInterface $requestAnalyzer,
        EventManagerInterface $eventManager,
        RouterInterface $router
    )
    {
        $this->requestAnalyzer = $requestAnalyzer;
        $this->eventManager = $eventManager;
        $this->router = $router;
    }

    /**
     * @param HitEvent $event
     */
    public function onHit(HitEvent $event)
    {
        if ($event->getMetadata()->reflection->getName() !== 'Sulu\Bundle\EventBundle\Entity\Event') {
            return;
        }

        $locale = $this->requestAnalyzer->getCurrentLocalization()->getLocalization();
        $document = $event->getHit()->getDocument();

        $eventApiEntity = $this->eventManager->findByIdAndLocale($document->getId(), $locale);

        if (!$eventApiEntity) {
            return;
        }

        $startDate = $eventApiEntity->getStartDate();
        $endDate = $eventApiEntity->getEndDate();

        $categories = $eventApiEntity->getCategories();

        $categoryTitles = array();
        foreach ($categories as $category) {
            $categoryTitles[] = $category->getName();

        }

        $startDateField = new Field('start_date', $startDate->format('c'), Field::TYPE_STRING);
        $document->addField($startDateField);

        if ($endDate) {
            $endDateField = new Field('end_date', $endDate->format('c'), Field::TYPE_STRING);
            $document->addField($endDateField);
        }

        $categoryTitleField = new Field('category_title', implode(', ', $categoryTitles), Field::TYPE_STRING);
        $document->addField($categoryTitleField);

        $url = $this->router->generate('sulu_events.detail', array('id' => $eventApiEntity->getId(), 'slug' => $eventApiEntity->getSlug()));
        $document->setUrl($url);
    }

}
