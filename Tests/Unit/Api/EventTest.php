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

use Sulu\Bundle\EventBundle\Api\Event as ApiEvent;
use Sulu\Bundle\EventBundle\Entity\Event as EntityEvent;

/**
 * EventTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Unit\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Sulu\Bundle\EventBundle\Api\Event|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $event;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->event = $this->getMock(EntityEvent::class);
    }

    /**
     * testItCreatesEventWithLocale
     */
    public function testItCreatesEventWithLocale()
    {
        $event = new ApiEvent($this->event, 'en');
        $this->assertEquals('en', $this->getObjectAttribute($event, 'locale'));
    }
}
