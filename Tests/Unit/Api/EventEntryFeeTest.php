<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Api;

use Sulu\Bundle\EventBundle\Api\EventEntryFee;
use Sulu\Bundle\EventBundle\Entity\EventEntryFee as Entity;

/**
 * EventEntryFeeTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Unit\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventEntryFeeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Entity|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $entryFee;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->entryFee = $this->getMock(Entity::class);
    }

    /**
     * testItCreatesEventEntryFeeWithLocale
     */
    public function testItCreatesEventEntryFeeWithLocale()
    {
        $eventEntryFee = new EventEntryFee($this->entryFee, 'en');
        $this->assertEquals('en', $this->getObjectAttribute($eventEntryFee, 'locale'));
    }
}
