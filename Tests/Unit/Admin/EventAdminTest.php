<?php
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 09:27
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Admin;


use Sulu\Bundle\EventBundle\Admin\EventAdmin;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;

class EventAdminTest extends \PHPUnit_Framework_TestCase
{
	/* @var \Sulu\Component\Security\Authorization\SecurityCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
	protected $securityChecker;

	/**
	 * setUp
	 */
	public function setUp(){
		$this->securityChecker = $this->getMock(SecurityCheckerInterface::class);
	}

	/**
	 * testItChecksPermissionOnConstruction
	 */
	public function testItChecksPermissionOnConstruction(){
		$this->securityChecker->expects($this->once())->method('hasPermission')->with('sulu.event.events', 'view');
		$eventAdmin = new EventAdmin($this->securityChecker,'test');
	}

	/**
	 * testItChangeNavigationItemsDependentOfPermissions
	 *
	 * @dataProvider permissionsDataProvider
	 *
	 * @var bool $hasPermissionReturnValue Flag if permission is set
	 * @var int $numberOfNavigationChildren Number of expected child items in navigation
	 */
	public function testItChangeNavigationItemsDependentOfPermissions($hasPermissionReturnValue, $numberOfNavigationChildren){
		$this->securityChecker->expects($this->once())->method('hasPermission')->with('sulu.event.events', 'view')->willReturn($hasPermissionReturnValue);
		$eventAdmin = new EventAdmin($this->securityChecker,'test');
		$this->assertCount($numberOfNavigationChildren, $eventAdmin->getNavigation()->getRoot()->getChildren());
	}

	/**
	 * permissionsDataProvider
	 *
	 * @return array
	 */
	public function permissionsDataProvider(){
		return
			array(
				'hasPermission' => array(true, 1),
				'hasNoPermission' => array(false,0)
			);
	}

	/**
	 * testItNavigationChildHasTheRightIcon
	 */
	public function testItNavigationChildHasCalendarIcon(){
		$this->securityChecker->expects($this->once())->method('hasPermission')->with('sulu.event.events', 'view')->willReturn(true);
		$eventAdmin = new EventAdmin($this->securityChecker,'test');
		$this->assertEquals('calendar',array_values(array_values($eventAdmin->getNavigation()->getRoot()->getChildren())[0]->getChildren())[0]->getIcon());
	}

	/**
	 * testItNavigationChildHasEventsAction
	 */
	public function testItNavigationChildHasEventsAction(){
		$this->securityChecker->expects($this->once())->method('hasPermission')->with('sulu.event.events', 'view')->willReturn(true);
		$eventAdmin = new EventAdmin($this->securityChecker,'test');
		$this->assertEquals('events',array_values(array_values($eventAdmin->getNavigation()->getRoot()->getChildren())[0]->getChildren())[0]->getAction());
	}
}