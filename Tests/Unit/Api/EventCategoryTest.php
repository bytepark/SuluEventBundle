<?php
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 11:20
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Api;

use Sulu\Bundle\CategoryBundle\Entity\Category;
use Sulu\Bundle\EventBundle\Api\EventCategory;

class EventCategoryTest extends \PHPUnit_Framework_TestCase
{
	/* @var Category|\PHPUnit_Framework_MockObject_MockObject */
	protected $category;

	/**
	 * setUp
	 */
	public function setUp(){
		$this->category = $this->getMock(Category::class);
	}

	/**
	 * testItCreatesEventCategoryWithLocale
	 */
	public function testItCreatesEventCategoryWithLocale(){
		$eventCategory = new EventCategory($this->category,'en');
		$this->assertEquals('en', $this->getObjectAttribute($eventCategory,'locale'));
	}
}
