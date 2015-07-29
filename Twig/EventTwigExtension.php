<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Twig;

use Sulu\Bundle\WebsiteBundle\Navigation\NavigationMapper;
use Sulu\Bundle\WebsiteBundle\Resolver\StructureResolverInterface;
use Sulu\Component\Content\Mapper\ContentMapperInterface;
use Sulu\Component\PHPCR\SessionManager\SessionManagerInterface;
use Sulu\Component\Webspace\Analyzer\RequestAnalyzerInterface;

/**
 * EventTwigExtension
 *
 * @package    Sulu\Bundle\EventBundle\Twig
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventTwigExtension extends \Twig_Extension
{
    /**
     * @var NavigationMapper
     */
    protected $navigationMapper;

    private $rootPath;

    /**
     * @var ContentMapperInterface
     */
    private $contentMapper;

    /**
     * @var StructureResolverInterface
     */
    private $structureResolver;

    /**
     * @var RequestAnalyzerInterface
     */
    private $requestAnalyzer;

    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    protected $blockSidebar;

    /**
     * @param NavigationMapper           $navigationMapper
     * @param ContentMapperInterface     $contentMapper
     * @param StructureResolverInterface $structureResolver
     * @param SessionManagerInterface    $sessionManager
     * @param RequestAnalyzerInterface   $requestAnalyzer
     * @param string                     $rootPath
     */
    public function __construct(
        NavigationMapper $navigationMapper,
        ContentMapperInterface $contentMapper,
        StructureResolverInterface $structureResolver,
        SessionManagerInterface $sessionManager,
        RequestAnalyzerInterface $requestAnalyzer,
        $rootPath
    )
    {
        $this->navigationMapper = $navigationMapper;
        $this->contentMapper = $contentMapper;
        $this->structureResolver = $structureResolver;
        $this->sessionManager = $sessionManager;
        $this->requestAnalyzer = $requestAnalyzer;
        $this->rootPath = $rootPath;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            'preg_replace' => new \Twig_Filter_Method($this, 'preg_replace'),
            'propertySort' => new \Twig_Filter_Method($this, 'propertySort'),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'betterSort' => new \Twig_Function_Method($this, 'betterSort'),
        );
    }

    /**
     * @param array  $array
     * @param string $method
     * @param string $sortFlag
     * @return mixed
     */
    public function betterSort($array, $method = 'asort', $sortFlag = 'SORT_REGULAR')
    {
        settype($sortFlag, 'integer');

        switch ($method) {
            case 'asort':
                asort($array, $sortFlag);
                break;
            case 'arsort':
                arsort($array, $sortFlag);
                break;
            case 'krsort':
                krsort($array, $sortFlag);
                break;
            case 'ksort':
                ksort($array, $sortFlag);
                break;
            case 'natcasesort':
                natcasesort($array);
                break;
            case 'natsort':
                natsort($array);
                break;
            case 'rsort':
                rsort($array, $sortFlag);
                break;
            case 'sort':
                sort($array, $sortFlag);
                break;
        }

        return $array;
    }

    /**
     * @param array  $input
     * @param string $propertyName
     * @return array
     */
    public function propertySort(array $input, $propertyName)
    {
        usort($input, function($firstArray, $secondArray) use ($propertyName) {
            if (!isset($firstArray[$propertyName]) || !isset($secondArray[$propertyName])) {
                return 0;
            }

            if ($firstArray[$propertyName] == $secondArray[$propertyName]) {
                return 0;
            }

            return ($firstArray[$propertyName] < $secondArray[$propertyName]) ? -1 : 1;
        });

        return $input;
    }



    /**
     * @param string $subject
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     * @return mixed|null
     */
    public function preg_replace($subject, $pattern, $replacement='', $limit=-1)
    {
        if (!isset($subject)) {
            return null;
        } else {
            return preg_replace($pattern, $replacement, $subject, $limit);
        }
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function load($uuid)
    {
        $contentStructure = $this->contentMapper->load(
            $uuid,
            $this->requestAnalyzer->getWebspace()->getKey(),
            $this->requestAnalyzer->getCurrentLocalization()->getLocalization()
        );

        return $this->structureResolver->resolve($contentStructure);
    }



    /**
     * @return string
     */
    public function getName()
    {
        return 'EventTwigExtension';
    }
}
