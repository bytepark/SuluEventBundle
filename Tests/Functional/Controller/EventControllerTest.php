<?php
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 15:58
 */

namespace Sulu\Bundle\EventBundle\Tests\Functional\Controller;


use Sulu\Bundle\EventBundle\Entity\Event;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class EventControllerTest extends SuluTestCase
{
	public function setUp()
	{
		$this->em = $this->db('ORM')->getOm();
		$this->initOrm();
	}

	private function initOrm(){
		$this->purgeDatabase();
		$event = new Event();
		$event->setTitle('test');
	}

	private function createTestClient()
	{
		return $this->createClient(
			array(),
			array(
				'PHP_AUTH_USER' => 'test',
				'PHP_AUTH_PW' => 'test',
			)
		);
	}

	public function testGetById()
	{
		$client = $this->createTestClient();
		$client->request('GET', '/api/events/' . $this->event->getId());

		$response = json_decode($client->getResponse()->getContent());

		$this->assertEquals('test', $response->title);
	}

}
