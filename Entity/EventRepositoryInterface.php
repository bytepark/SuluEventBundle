<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * EventRepositoryInterface
 *
 * @package    Sulu\Bundle\EventBundle\Entity
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
interface EventRepositoryInterface
{
    /**
     * Finds the event with the given ID in the given language
     *
     * @param int    $eventId The id of the event
     * @param string $locale  The locale of the event to load
     * @return Event
     */
    public function findByIdAndLocale($eventId, $locale);

    /**
     * @param array $filter
     */
    public function findEventsForMap($filter);

    /**
     * @param string $page
     * @param array  $filter
     */
    public function findFilteredEvents($page, $filter);

    /**
     * @return Collection
     */
    public function findAll();

    /**
     * @param int $maxResults
     * @return Collection
     */
    public function findRunningEvents($maxResults);

    /**
     * @return mixed
     */
    public function findAvailableCountries();
}
