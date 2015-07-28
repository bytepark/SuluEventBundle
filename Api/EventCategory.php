<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Api;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Sulu\Component\Rest\ApiWrapper;
use Sulu\Bundle\CategoryBundle\Entity\Category as Entity;

/**
 * EventCategory
 *
 * @package Sulu\Bundle\EventBundle\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventCategory extends ApiWrapper
{
    /**
     * @param Entity $eventCategory The event category to wrap
     * @param string $locale        The locale of this event
     */
    public function __construct(Entity $eventCategory, $locale)
    {
        $this->entity = $eventCategory;
        $this->locale = $locale;
    }

    /**
     * Returns the id of the category
     *
     * @return int
     * @VirtualProperty
     * @SerializedName("id")
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * Return the name of the category
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("name")
     */
    public function getName()
    {
        return $this->entity->getName();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->entity->setName($name);
    }
}
