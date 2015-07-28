<?php
<<<<<<< HEAD
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 12:08
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Command;

use Massive\Bundle\SearchBundle\Search\SearchManagerInterface;
use Sulu\Bundle\EventBundle\Command\ReindexCommand;
use Sulu\Bundle\EventBundle\Entity\Event;
use Sulu\Bundle\EventBundle\Event\EventManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReindexCommandTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var InputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $input;

    /**
     * @var OutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $output;

    /**
     * @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $container;

    /**
     * @var EventManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManager;

    /**
     * @var SearchManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $searchManager;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->input = $this->getMock(InputInterface::class);
        $this->output = $this->getMock(OutputInterface::class);
        $this->container = $this->getMock(ContainerInterface::class);
        $this->eventManager = $this->getMock(EventManagerInterface::class);
        $this->searchManager = $this->getMock(SearchManagerInterface::class);
    }

	/**
	 * testItSetsConfiguratoin
	 *
	 */
	public function testItSetsConfiguration(){
		$reIndexCommand = new ReindexCommand('test');
		$reIndexCommand->configure();
		$this->assertEquals('sulu:search:reindex-events',$reIndexCommand->getName());
	}

    /**
     * testItAsksTheEventManagerForAllEvents
     */
    public function testItAsksTheEventManagerForAllEvents()
    {
        $this->container
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('sulu_event.event_manager'))
            ->will($this->returnValue($this->eventManager));

        $this->eventManager
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array()));

        $reindexCommand = new ReindexCommand();
        $reindexCommand->setContainer($this->container);
        $reindexCommand->execute($this->input, $this->output);
    }

    /**
     * testItTellsTheSearchManagerToIndexEachEventReturnedByTheEventManager
     */
    public function testItTellsTheSearchManagerToIndexEachEventReturnedByTheEventManager()
    {
        $this->container
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('sulu_event.event_manager'))
            ->will($this->returnValue($this->eventManager));

        $this->container
            ->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('massive_search.search_manager'))
            ->will($this->returnValue($this->searchManager));

        $eventMock = $this->getMock(Event::class);
        $eventMocks = array(
            $eventMock,
            $eventMock,
            $eventMock,
            $eventMock,
            $eventMock,
        );

        $this->eventManager
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($eventMocks));

        $this->searchManager
            ->expects($this->exactly(5))
            ->method('index')
            ->with($eventMock);

        $reindexCommand = new ReindexCommand();
        $reindexCommand->setContainer($this->container);
        $reindexCommand->execute($this->input, $this->output);
    }

    /**
     * testItLogsToOutputIfAnExceptionIsThrownDuringIndexing
     */
    public function testItLogsToOutputIfAnExceptionIsThrownDuringIndexing()
    {
        $this->container
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('sulu_event.event_manager'))
            ->will($this->returnValue($this->eventManager));

        $this->container
            ->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('massive_search.search_manager'))
            ->will($this->returnValue($this->searchManager));

        $eventMock = $this->getMock(Event::class);
        $eventMock
            ->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue('FooBarEvent'));

        $eventMocks = array(
            $eventMock,
        );

        $this->eventManager
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($eventMocks));

        $this->searchManager
            ->expects($this->exactly(1))
            ->method('index')
            ->with($eventMock)
            ->willThrowException(new \Exception('Something went wrong!'));

        $this->output
            ->expects($this->at(1))
            ->method('writeln')
            ->with($this->stringContains('(path: FooBarEvent: Something went wrong!'));

        $reindexCommand = new ReindexCommand();
        $reindexCommand->setContainer($this->container);
        $reindexCommand->execute($this->input, $this->output);
    }
}
