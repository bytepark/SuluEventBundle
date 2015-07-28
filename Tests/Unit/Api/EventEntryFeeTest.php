<?php
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 12:00
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Api;

use Sulu\Bundle\EventBundle\Api\EventEntryFee;
use Sulu\Bundle\EventBundle\Entity\EventEntryFee as Entity;

class EventEntryFeeTest extends \PHPUnit_Framework_TestCase
{
	/* @var Entity|\PHPUnit_Framework_MockObject_MockObject */
	protected $entryFee;

	/**
	 * setUp
	 */
	public function setUp(){
		$this->entryFee = $this->getMock(Entity::class);
	}

	/**
	 * testItCreatesEventEntryFeeWithLocale
	 */
	public function testItCreatesEventEntryFeeWithLocale(){
		$eventEntryFee = new EventEntryFee($this->entryFee,'en');
		$this->assertEquals('en', $this->getObjectAttribute($eventEntryFee,'locale'));
	}
}
