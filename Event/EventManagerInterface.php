<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Event;

use Doctrine\Common\Collections\Collection;
use Sulu\Bundle\EventBundle\Entity\Event;

/**
 * EventManagerInterface
 *
 * @package    Sulu\Bundle\EventBundle\Event
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
interface EventManagerInterface
{
    /**
     * @param array $filter
     * @return array
     */
    public function findEventsForMap($filter);

    /**
     * @param string $page
     * @param array  $filter
     * @return array
     */
    public function findFilteredEvents($page, $filter);

    /**
     * @param int    $eventId
     * @param string $locale
     * @return Event
     */
    public function findByIdAndLocale($eventId, $locale);

    /**
     * @param array  $data
     * @param string $locale
     * @param int    $eventId
     * @return Event
     */
    public function save(array $data, $locale, $eventId = null);

    /**
     * @return Collection
     */
    public function findAll();

    /**
     * @return array
     */
    public function getCategories();

    /**
     * @return array
     */
    public function getFieldDescriptors();

    /**
     * @return mixed
     */
    public function getCountries();
}
