<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Intl\Intl;

/**
 * TemplateController
 *
 * @package    Sulu\Bundle\EventBundle\Controller
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class TemplateController extends Controller
{
    /**
     * Returns template for event list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventListAction()
    {
        return $this->render('SuluEventBundle:Event:list.html.twig');
    }

    /**
     * Returns template for event detail
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventDetailAction()
    {
        $countries = array();

        foreach (Intl::getRegionBundle()->getCountryNames() as $alpha2 => $country) {
            $countries[] = array(
                'id' => $alpha2,
                'name' => $country
            );
        }

        return $this->render(
            'SuluEventBundle:Event:detail.html.twig',
            array(
                'countries' => $countries
            )
        );
    }

    /**
     * Returns template for event entry fee
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventEntryFeeListAction()
    {
        return $this->render('SuluEventBundle:Event:entry-fee.list.html.twig');
    }

    /**
     * Returns template for event organizer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function eventOrganizerAction()
    {
        return $this->render('SuluEventBundle:Event:organizer.html.twig');
    }
}
