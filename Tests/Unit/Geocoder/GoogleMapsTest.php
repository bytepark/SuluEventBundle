<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\SuluEventBundle\Tests\Unit\Geocoder;

use GuzzleHttp\Stream\StreamInterface;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Sulu\Bundle\SuluEventBundle\Geocoder\GoogleMaps;
use Sulu\Bundle\SuluEventBundle\HttpAdapter\HttpAdapterInterface;

/**
 * GoogleMapsTest
 *
 * @package    Sulu\Bundle\SuluEventBundle\Tests\Unit\Geocoder
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class GoogleMapsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var HttpAdapterInterface|ObjectProphecy
     */
    protected $httpAdapterMock;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->httpAdapterMock = $this->prophesize(HttpAdapterInterface::class);
    }

    /**
     * testGeocodingAnIpAddressWillThrowAnUnsupportedOperationException
     *
     * @dataProvider ipAddressProvider
     *
     * @param string $ipAddress
     */
    public function testGeocodingAnIpAddressWillThrowAnUnsupportedOperationException($ipAddress)
    {
        $this->setExpectedException(UnsupportedOperation::class, 'The GoogleMaps provider does not support IP addresses, only street addresses.');

        $googleMapsGeoCoder = new GoogleMaps($this->httpAdapterMock->reveal(), false, 'xoxo');
        $googleMapsGeoCoder->geocode($ipAddress);
    }

    /**
     * ipAddressProvider
     *
     * @return array
     */
    public function ipAddressProvider()
    {
        return array(
            array('127.0.0.1'), // loopback
            array('192.168.0.1'), // private network
            array('172.16.254.1'), // private network
            array('93.180.154.69'), // bytepark.de
            array('216.58.213.35'), // google.de
            array('255.255.255.255'), // broadcast
            array('2001:0db8:85a3:0000:0000:8a2e:0370:7334'), // ipv6
        );
    }

    /**
     * testSwitchingSslUsage
     *
     * @dataProvider sslOptionsProvider
     *
     * @param boolean $useSsl
     * @param string  $expectedScheme
     */
    public function testSwitchingSslUsage($useSsl, $expectedScheme)
    {
        $responseMock = $this->prophesize(StreamInterface::class);
        $responseMock->getContents()->willReturn('[ { "foo": "bar" }, { "123": 456 } ]');

        $this->httpAdapterMock->get(Argument::containingString($expectedScheme))->willReturn($responseMock->reveal());

        $googleMapsGeoCoder = new GoogleMaps($this->httpAdapterMock->reveal(), $useSsl, 'xoxo');
        $googleMapsGeoCoder->geocode('Schützenstraße 8, 10117 Berlin, Deutschland');
    }

    /**
     * sslOptionsProvider
     *
     * @return array
     */
    public function sslOptionsProvider()
    {
        return array(
            array(true, 'https://'),
            array(false, 'http://')
        );
    }

    /**
     * testProvidingApiKeyUsage
     *
     * @dataProvider apiKeyOptionsProvider
     *
     * @param boolean $useSsl
     * @param string  $apiKey
     */
    public function testProvidingApiKeyUsage($useSsl, $apiKey)
    {
        $responseMock = $this->prophesize(StreamInterface::class);
        $responseMock->getContents()->willReturn('[ { "foo": "bar" }, { "123": 456 } ]');

        $this->httpAdapterMock->get(Argument::containingString('key=' . $apiKey))->willReturn($responseMock->reveal());

        $googleMapsGeoCoder = new GoogleMaps($this->httpAdapterMock->reveal(), $useSsl, $apiKey);
        $googleMapsGeoCoder->geocode('Schützenstraße 8, 10117 Berlin, Deutschland');
    }

    /**
     * apiKeyOptionsProvider
     *
     * @return array
     */
    public function apiKeyOptionsProvider()
    {
        return array(
            array(true, 'xoxo'),
            array(false, 'foobar')
        );
    }

    /**
     * testGeocodingWillReturnAnArray
     */
    public function testGeocodingWillReturnAnArray()
    {
        $responseMock = $this->prophesize(StreamInterface::class);
        $responseMock->getContents()->willReturn('[ { "foo": "bar" }, { "123": 456 } ]');

        $this->httpAdapterMock->get(Argument::type('string'))->willReturn($responseMock->reveal());

        $googleMapsGeoCoder = new GoogleMaps($this->httpAdapterMock->reveal(), false, 'xoxo');
        $results = $googleMapsGeoCoder->geocode('Schützenstraße 8, 10117 Berlin, Deutschland');

        $this->assertInternalType('array', $results);
    }
}
