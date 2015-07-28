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
 * EventOrganizer
 *
 * @ORM\Table("event_organizer")
 * @ORM\Entity(repositoryClass="Sulu\Bundle\EventBundle\Entity\EventOrganizerRepository")
 *
 * @package    Sulu\Bundle\EventBundle\Entity
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventOrganizer
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=128, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=128, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=128, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=50, nullable=true)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=128, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=128, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=true)
     */
    private $email;


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
     * Set title
     *
     * @param string $title
     * @return EventOrganizer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return EventOrganizer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return EventOrganizer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return EventOrganizer
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return EventOrganizer
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return EventOrganizer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return EventOrganizer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return EventOrganizer
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return EventOrganizer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
