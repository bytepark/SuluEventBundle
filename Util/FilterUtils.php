<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Util;

use Symfony\Component\HttpFoundation\Request;

/**
 * FilterUtils
 *
 * @package    Sulu\Bundle\EventBundle\Util
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class FilterUtils
{
    /**
     * @param Request $request
     * @param array   $filter
     *
     * @return array
     */
    public static function locationFilter($request, $filter)
    {
        $area = $request->get('area', null);
        $filter['area'] = is_null($area) ? $area : (0.621371192*$area);
        $filter['lat'] = $request->get('lat', null);
        $filter['long'] = $request->get('long', null);
        $filter['country'] = $request->get('country', null);

        return $filter;
    }
}
