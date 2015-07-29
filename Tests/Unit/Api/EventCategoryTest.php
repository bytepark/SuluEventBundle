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

use Sulu\Bundle\CategoryBundle\Entity\Category;
use Sulu\Bundle\EventBundle\Api\EventCategory;

/**
 * EventCategoryTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Unit\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventCategoryTest extends \PHPUnit_Framework_TestCase
{

    /* @var Category|\PHPUnit_Framework_MockObject_MockObject */
    protected $category;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->category = $this->getMock(Category::class);
    }

    /**
     * testItCreatesEventCategoryWithLocale
     */
    public function testItCreatesEventCategoryWithLocale()
    {
        $eventCategory = new EventCategory($this->category, 'en');
        $this->assertEquals('en', $this->getObjectAttribute($eventCategory, 'locale'));
    }
}
