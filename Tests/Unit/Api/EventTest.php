<?php
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 11:13
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Api;




use Sulu\Bundle\EventBundle\Api\Event as ApiEvent;
use Sulu\Bundle\EventBundle\Entity\Event as EntityEvent;

class EventTest extends  \PHPUnit_Framework_TestCase
{
	/* @var \Sulu\Bundle\EventBundle\Api\Event|\PHPUnit_Framework_MockObject_MockObject */
	protected $event;

	/**
	 * setUp
	 */
	public function setUp(){
		$this->event = $this->getMock(EntityEvent::class);
	}

	/**
	 * testItCreatesEventWithLocale
	 */
	public function testItCreatesEventWithLocale(){
		$event = new ApiEvent($this->event,'en');
		$this->assertEquals('en', $this->getObjectAttribute($event,'locale'));
	}
}