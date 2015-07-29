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

use Hateoas\Configuration\Annotation\Relation;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Sulu\Component\Rest\ApiWrapper;
use Sulu\Bundle\EventBundle\Entity\EventEntryFee as Entity;
use Sulu\Bundle\EventBundle\Entity\Event as EventEntity;

/**
 * EventEntryFee
 *
 * @Relation("self", href="expr('/admin/api/entryfee/' ~ object.getId())")
 * @ExclusionPolicy("all")
 *
 * @package    Sulu\Bundle\EventBundle\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventEntryFee extends ApiWrapper
{
    /**
     * @param Entity $entryFee The event entry fee to wrap
     * @param string $locale   The locale of this event
     */
    public function __construct(Entity $entryFee, $locale)
    {
        $this->entity = $entryFee;
        $this->locale = $locale;
    }

    /**
     * Returns the id of the entry fee
     *
     * @return int
     * @VirtualProperty
     * @SerializedName("id")
     * @Groups({"fullEvent","select"})
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * Return the valid until date of the entry fee
     *
     * @return \DateTime
     * @VirtualProperty
     * @SerializedName("validUntilDate")
     * @Groups({"fullEvent"})
     */
    public function getValidUntilDate()
    {
        return $this->entity->getValidUntilDate();
    }

    /**
     * @param \DateTime $validUntilDate
     */
    public function setValidUntilDate($validUntilDate)
    {
        $this->entity->setValidUntilDate($validUntilDate);
    }

    /**
     * Return the valid until date of the entry fee
     *
     * @return EventEntity
     * @VirtualProperty
     * @SerializedName("event")
     */
    public function getEvent()
    {
        return $this->entity->getEvent();
    }

    /**
     * @param EventEntity $event
     */
    public function setEvent($event)
    {
        $this->entity->setEvent($event);
    }
}
