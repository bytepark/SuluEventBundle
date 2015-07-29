<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\SuluEventBundle\Geocoder;

use Sulu\Bundle\SuluEventBundle\Geocoder\Exception\UnsupportedOperation;
use Sulu\Bundle\SuluEventBundle\HttpAdapter\HttpAdapterInterface;

/**
 * GoogleMaps
 *
 * @package    Sulu\Bundle\SuluEventBundle\Geocoder
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class GoogleMaps
{
    /**
     * @var string
     */
    const URL = 'http://maps.googleapis.com/maps/api/geocode/json?address=%s';

    /**
     * @var string
     */
    const URL_SSL = 'https://maps.googleapis.com/maps/api/geocode/json?address=%s';

    /**
     * @var boolean
     */
    private $useSsl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var HttpAdapterInterface
     */
    private $httpAdapter;

    /**
     * @param HttpAdapterInterface $httpAdapter
     * @param boolean              $useSsl
     * @param string               $apiKey
     */
    public function __construct(HttpAdapterInterface $httpAdapter, $useSsl, $apiKey)
    {
        $this->useSsl = $useSsl;
        $this->apiKey = $apiKey;
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * @param string $address
     *
     * @return mixed
     * @throws UnsupportedOperation
     */
    public function geocode($address)
    {
        if (filter_var($address, FILTER_VALIDATE_IP)) {
            throw new UnsupportedOperation('The GoogleMaps provider does not support IP addresses, only street addresses.');
        }
        $query = sprintf(
            $this->useSsl ? self::URL_SSL : self::URL,
            rawurlencode($address)
        );

        return $this->executeQuery($query);
    }

    private function executeQuery($query)
    {
        $query = $this->buildQuery($query);
        $content = $this->httpAdapter->get($query)->getContents();

        $json = json_decode($content);
        $results = $json;

        return $results;
    }

    private function buildQuery($query)
    {
        $query = sprintf('%s&key=%s', $query, $this->apiKey);

        return $query.'&sensor=false';
    }
}
