<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\SuluEventBundle\HttpAdapter;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Client;

/**
 * GuzzleHttpAdapter
 *
 * @package    Sulu\Bundle\SuluEventBundle\HttpAdapter
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class GuzzleHttpAdapter implements HttpAdapterInterface
{
    private $client;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $query
     * @return Stream
     */
    public function get($query)
    {
        return $this->client->get($query)->getBody();
    }

    /**
     * @param string $query
     * @return int
     */
    public function getStatus($query)
    {
        return $this->client->get($query)->getStatusCode();
    }
}
