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

use Behat\Transliterator\Transliterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Bundle\MediaBundle\Entity\Media;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="event",indexes={@ORM\Index(name="startDate", columns={"startDate"})})
 * @ORM\Entity(repositoryClass="Sulu\Bundle\EventBundle\Entity\EventRepository")
 *
 * @package    Sulu\Bundle\EventBundle\DataFixtures\Events
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class Event
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
     * @var boolean
     *
     * @ORM\Column(name="isTopEvent", type="boolean", options={"default"= 0})
     */
    private $isTopEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Sulu\Bundle\MediaBundle\Entity\Media")
     * @ORM\JoinTable(name="event_media",
     *      joinColumns={@ORM\JoinColumn(name="idEvent", referencedColumnName="id", nullable=false, onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="idMedia", referencedColumnName="id", nullable=false, onDelete="cascade")}
     * )
     */
    private $media;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date", nullable=false)
     *
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Sulu\Bundle\CategoryBundle\Entity\Category")
     * @ORM\JoinTable(name="event_event_categories",
     *      joinColumns={@ORM\JoinColumn(name="idEvents", referencedColumnName="id", nullable=false)},
     *      inverseJoinColumns={@ORM\JoinColumn(name="idCategories", referencedColumnName="id", nullable=false)}
     *      )
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionVenue", type="text", nullable=true)
     */
    private $descriptionVenue;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=50, nullable=false)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=3, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="decimal", precision=9, scale=7, nullable=false)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="decimal", precision=9, scale=7, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="text", nullable=true)
     */
    private $website;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EventEntryFee", mappedBy="event", cascade={"all"})
     */
    private $regularEntryFees;

    /**
     * @var EventOrganizer
     *
     * @ORM\OneToOne(targetEntity="EventOrganizer", cascade={"all"})
     * @ORM\JoinColumn(name="idOrganizer", referencedColumnName="id")
     */
    private $organizer;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->isTopEvent = 0;
        $this->regularEntryFees = new ArrayCollection();
        $this->organizer = new EventOrganizer();
    }

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
     * Set isTopEvent
     *
     * @param boolean $isTopEvent
     * @return Event
     */
    public function setIsTopEvent($isTopEvent)
    {
        $this->isTopEvent = $isTopEvent;

        return $this;
    }

    /**
     * Get isTopEvent
     *
     * @return boolean
     */
    public function getIsTopEvent()
    {
        return $this->isTopEvent;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
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
     * Add media
     *
     * @param Media $media
     * @return Event
     */
    public function addMedia(Media $media)
    {
        $this->media[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param Media $media
     */
    public function removeMedia(Media $media)
    {
        $this->media->removeElement($media);
    }

    /**
     * Get media
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Event
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        if (is_object($this->startTime)) {
            return $this->startTime->format('H:i:s');
        }

        return $this->startTime;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set categories
     *
     * @param Collection $categories
     * @return Event
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Add category
     *
     * @param \Sulu\Bundle\CategoryBundle\Entity\Category $categories
     * @return Event
     */
    public function addCategory(\Sulu\Bundle\CategoryBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Add categories
     *
     * @param \Sulu\Bundle\CategoryBundle\Entity\Category $categories
     * @return Event
     *
     * FIXME someone needs this ugly methods .... (restcontroller)
     */
    public function addCategorie(\Sulu\Bundle\CategoryBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Sulu\Bundle\CategoryBundle\Entity\Category $categories
     */
    public function removeCategory(\Sulu\Bundle\CategoryBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Remove categories
     *
     * @param \Sulu\Bundle\CategoryBundle\Entity\Category $categories
     *
     * FIXME someone needs this ugly methods .... (restcontroller)
     */
    public function removeCategorie(\Sulu\Bundle\CategoryBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set descriptionVenue
     *
     * @param string $descriptionVenue
     * @return Event
     */
    public function setDescriptionVenue($descriptionVenue)
    {
        $this->descriptionVenue = $descriptionVenue;

        return $this;
    }

    /**
     * Get descriptionVenue
     *
     * @return string
     */
    public function getDescriptionVenue()
    {
        return $this->descriptionVenue;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Event
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
     * @return Event
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
     * Set country
     *
     * @param string $country
     * @return Event
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Event
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set regularEntryFees
     *
     * @param \stdClass $regularEntryFees
     * @return Event
     */
    public function setRegularEntryFees($regularEntryFees)
    {
        $this->regularEntryFees = $regularEntryFees;

        return $this;
    }

    /**
     * Get regularEntryFees
     *
     * @return \stdClass
     */
    public function getRegularEntryFees()
    {
        return $this->regularEntryFees;
    }

    /**
     * Set organizer
     *
     * @param EventOrganizer $organizer
     * @return Event
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * Get organizer
     *
     * @return EventOrganizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return Transliterator::transliterate($this->getTitle());
    }
}
