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

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Sulu\Bundle\EventBundle\Entity\EventOrganizer as EventOrganizerEntity;
use Sulu\Component\Rest\ApiWrapper;

/**
 * EventOrganizer
 *
 * @ExclusionPolicy("all")
 *
 * @package Sulu\Bundle\EventBundle\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventOrganizer extends ApiWrapper
{

    /**
     * Constructor
     *
     * @param EventOrganizerEntity $eventOrganizer The event organizer to wrap
     * @param string               $locale         The locale of the event organizer
     */
    public function __construct(EventOrganizerEntity $eventOrganizer, $locale)
    {
        $this->entity = $eventOrganizer;
        $this->locale = $locale;
    }

    /**
     * @VirtualProperty
     * @SerializedName("id")
     * @Groups({"fullEvent"})
     *
     * @return int
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * @VirtualProperty
     * @SerializedName("title")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->entity->getTitle();
    }

    /**
     * @param string $title The title
     * @return void
     */
    public function setTitle($title)
    {
        $this->entity->setTitle($title);
    }

    /**
     * @VirtualProperty
     * @SerializedName("firstName")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->entity->getFirstName();
    }

    /**
     * @param string $firstName The firstname
     */
    public function setFirstName($firstName)
    {
        $this->entity->setFirstName($firstName);
    }

    /**
     * @VirtualProperty
     * @SerializedName("lastName")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->entity->getLastName();
    }

    /**
     * @param string $lastName The lastname
     */
    public function setLastName($lastName)
    {
        $this->entity->setLastName($lastName);
    }

    /**
     * @VirtualProperty
     * @SerializedName("street")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->entity->getStreet();
    }

    /**
     * @param string $street The street + number
     */
    public function setStreet($street)
    {
        $this->entity->setStreet($street);
    }

    /**
     * @VirtualProperty
     * @SerializedName("zip")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getZip()
    {
        return $this->entity->getZip();
    }

    /**
     * @param string $zip The zip code
     */
    public function setZip($zip)
    {
        $this->entity->setZip($zip);
    }

    /**
     * @VirtualProperty
     * @SerializedName("city")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getCity()
    {
        return $this->entity->getCity();
    }

    /**
     * @param string $city The city
     */
    public function setCity($city)
    {
        $this->entity->setCity($city);
    }

    /**
     * @VirtualProperty
     * @SerializedName("phone")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->entity->getPhone();
    }

    /**
     * @param string $phone The phone
     */
    public function setPhone($phone)
    {
        $this->entity->setPhone($phone);
    }

    /**
     * @VirtualProperty
     * @SerializedName("fax")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getFax()
    {
        return $this->entity->getFax();
    }

    /**
     * @param string $fax The fax
     */
    public function setFax($fax)
    {
        $this->entity->setFax($fax);
    }

    /**
     * @VirtualProperty
     * @SerializedName("email")
     * @Groups({"fullEvent"})
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->entity->getEmail();
    }

    /**
     * @param string $email The email
     */
    public function setEmail($email)
    {
        $this->entity->setEmail($email);
    }
}
