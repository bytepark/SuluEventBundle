<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventEntryFee
 *
 * @ORM\Table("event_entry_fee")
 * @ORM\Entity(repositoryClass="Sulu\Bundle\EventBundle\Entity\EventEntryFeeRepository")
 *
 * @package    Sulu\Bundle\EventBundle\DataFixtures\Events
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventEntryFee
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validUntilDate", type="date")
     */
    private $validUntilDate;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="regularEntryFees")
     * @ORM\JoinColumn(name="eventId", referencedColumnName="id")
     */
    private $event;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set validUntilDate
     *
     * @param \DateTime $validUntilDate
     * @return EventEntryFee
     */
    public function setValidUntilDate($validUntilDate)
    {
        $this->validUntilDate = $validUntilDate;

        return $this;
    }

    /**
     * Get validUntilDate
     *
     * @return \DateTime
     */
    public function getValidUntilDate()
    {
        return $this->validUntilDate;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return EventEntryFee
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set event
     *
     * @param Event $event
     * @return EventEntryFee
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
