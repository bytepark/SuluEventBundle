<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;

/**
 * EventAdmin
 *
 * @package    Sulu\Bundle\EventBundle\Admin
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventAdmin extends Admin
{
    /**
     * @var SecurityCheckerInterface
     */
    private $securityChecker;

    /**
     * @param SecurityCheckerInterface $securityChecker
     * @param string                   $title
     */
    public function __construct(SecurityCheckerInterface $securityChecker, $title)
    {
        $this->securityChecker = $securityChecker;

        $rootNavigationItem = new NavigationItem($title);
        $section = new NavigationItem('');

        if ($this->securityChecker->hasPermission('sulu.event.events', 'view')) {
            $shop = new NavigationItem('sulu.navigation.events');
            $shop->setIcon('calendar');
            $shop->setAction('events');

            $section->addChild($shop);
            $rootNavigationItem->addChild($section);
        }

        $this->setNavigation(new Navigation($rootNavigationItem));
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getJsBundleName()
    {
        return 'suluevent';
    }

    /**
     * @return array
     */
    public function getSecurityContexts()
    {
        return array(
            'Sulu' => array(
                'Event Add-on' => array(
                    'sulu.event.events'
                )
            ),
        );
    }
}
