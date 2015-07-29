<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Admin;

use Sulu\Bundle\EventBundle\Admin\EventContentNavigationProvider;

/**
 * EventContentNavigationProviderTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Unit\Admin
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventContentNavigationProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * testItCreatesNavigationItemsWithExpectedProperties
     *
     * @dataProvider navigationDataProvider
     *
     * @var int $arrayPosition Number of Array
     * @var string $contentNavigationItemName Name of ContentNavigationItem
     * @var string $contentNavigationItemAction Action of ContentNavigationItem
     * @var string $contentNavigationItemComponent Component of ContentNavigationItem
     */
    public function testItCreatesNavigationItemsWithExpectedProperties(
        $arrayPosition,
        $contentNavigationItemName,
        $contentNavigationItemAction,
        $contentNavigationItemComponent
    )
    {
        $eventContentNavigationProvider = new EventContentNavigationProvider();
        /* @var $navigationItem \Sulu\Bundle\AdminBundle\Navigation\ContentNavigationItem */
        $navigationItem = array_values($eventContentNavigationProvider->getNavigationItems())[$arrayPosition];

        $this->assertTrue($navigationItem->getName() === $contentNavigationItemName, 'Content navigation item does not equals expected value!');
        $this->assertTrue($navigationItem->getAction() === $contentNavigationItemAction, 'Content navigation item action does not equals expected value!');
        $this->assertTrue($navigationItem->getComponent() === $contentNavigationItemComponent, 'Content navigation item component does not equals expected value!');
    }

    /**
     * navigationDataProvider
     *
     * @return array
     */
    public function navigationDataProvider()
    {
        return
            array(
                'details' => array(
                    0,
                    'sulu.content-navigation.event.details',
                    'details','event/components/details@suluevent',
                    ''
                ),
                'organizer' => array(
                    1,
                    'sulu.content-navigation.event.organizer',
                    'organizer',
                    'event/components/organizer@suluevent'
                ),
                'entryFee' => array(
                    2,
                    'sulu.content-navigation.event.entry_fee',
                    'entryFee',
                    'event/components/entry-fee@suluevent'
                ),
            );
    }
}
