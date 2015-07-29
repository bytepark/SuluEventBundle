<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Tests\Functional\Controller;

use Sulu\Bundle\EventBundle\Entity\Event;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

/**
 * EventControllerTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Functional\Controller
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventControllerTest extends SuluTestCase
{

    public function setUp()
    {
        $this->em = $this->db('ORM')->getOm();
        $this->initOrm();
    }

    private function initOrm()
    {
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
