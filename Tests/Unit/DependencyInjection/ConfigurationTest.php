<?php
/**
 * Created by PhpStorm.
 * User: stm
 * Date: 24.07.2015
 * Time: 14:18
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\DependencyInjection;

use Sulu\Bundle\EventBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
	public function testItSetsTreeConfig(){
		$configuration = new Configuration();
		$treeBuilder = $configuration->getConfigTreeBuilder();
		$children = $this->getObjectAttribute($this->getObjectAttribute($treeBuilder,'root'),'children');
		$this->assertTrue(array_key_exists('google_maps_api_key',$children),'google maps api key ist not editable in config');
		$this->assertTrue(array_key_exists('csv_import_file',$children),'csv import file ist not editable in config');
	}
}
