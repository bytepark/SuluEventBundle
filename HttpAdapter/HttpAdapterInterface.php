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

/**
 * HttpAdapterInterface
 *
 * @package    Bytepark\HttpAdapter
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
interface HttpAdapterInterface
{
    /**
     * @param string $query
     * @return mixed
     */
    public function get($query);

    /**
     * @param string $query
     * @return int
     */
    public function getStatus($query);
}
