<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Admin;

use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem;
use Sulu\Bundle\AdminBundle\Navigation\ContentNavigationProviderInterface;

/**
 * EventContentNavigationProvider
 *
 * @package Sulu\Bundle\EventBundle\Admin
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventContentNavigationProvider implements ContentNavigationProviderInterface
{
    /**
     * Returns the navigation items this class provides
     *
     * @param array $options
     *
     * @return ContentNavigationItem[]
     */
    public function getNavigationItems(array $options = array())
    {
        $details = new ContentNavigationItem('sulu.content-navigation.event.details');
        $details->setAction('details');
        $details->setComponent('event/components/details@suluevent');

        $organizer = new ContentNavigationItem('sulu.content-navigation.event.organizer');
        $organizer->setAction('organizer');
        $organizer->setComponent('event/components/organizer@suluevent');

        $entryFee = new ContentNavigationItem('sulu.content-navigation.event.entry_fee');
        $entryFee->setAction('entryFee');
        $entryFee->setComponent('event/components/entry-fee@suluevent');

        return array($details, $organizer, $entryFee);
    }
}
