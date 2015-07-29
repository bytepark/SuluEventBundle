<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\DependencyInjection;

use Sulu\Bundle\EventBundle\DependencyInjection\Configuration;

/**
 * ConfigurationTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Unit\DependencyInjection
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testItSetsTreeConfig()
    {
        $configuration = new Configuration();
        $treeBuilder = $configuration->getConfigTreeBuilder();
        $children = $this->getObjectAttribute($this->getObjectAttribute($treeBuilder, 'root'), 'children');
        $this->assertTrue(array_key_exists('google_maps_api_key', $children), 'google maps api key ist not editable in config');
        $this->assertTrue(array_key_exists('csv_import_file', $children), 'csv import file ist not editable in config');
    }
}
