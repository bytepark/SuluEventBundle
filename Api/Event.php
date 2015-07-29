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
use Sulu\Bundle\EventBundle\Entity\Event as Entity;
use Sulu\Bundle\EventBundle\Entity\EventOrganizer;
use Sulu\Bundle\CategoryBundle\Api\Category;
use Sulu\Bundle\MediaBundle\Api\Media;
use Sulu\Component\Rest\ApiWrapper;
use Sulu\Bundle\CategoryBundle\Entity\Category as CategoryEntity;

/**
 * Event
 *
 * @Relation("self", href="expr('/admin/api/events/' ~ object.getId())")
 * @ExclusionPolicy("all")
 *
 * @package    Sulu\Bundle\EventBundle\Api
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class Event extends ApiWrapper
{
    /**
     * @var Array
     */
    private $media;

    /**
     * @param Entity $event  The event to wrap
     * @param string $locale The locale of this event
     */
    public function __construct(Entity $event, $locale)
    {
        $this->entity = $event;
        $this->locale = $locale;
    }

    /**
     * Returns the id of the event
     *
     * @return int
     * @VirtualProperty
     * @SerializedName("id")
     * @Groups({"fullEvent","partialEvent","select"})
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * Return the organizer of the event
     *
     * @return EventOrganizer
     * @VirtualProperty
     * @SerializedName("organizer")
     * @Groups({"fullEvent"})
     */
    public function getOrganizer()
    {
        $organizer = $this->entity->getOrganizer();
        return new \Sulu\Bundle\EventBundle\Api\EventOrganizer($organizer, $this->locale);
    }

    /**
     * @param EventOrganizer $organizer
     */
    public function setOrganizer($organizer)
    {
        $this->entity->setOrganizer($organizer);
    }

    /**
     * Return the is top event of the event
     *
     * @return boolean
     * @VirtualProperty
     * @SerializedName("isTopEvent")
     * @Groups({"fullEvent","partialEvent"})
     */
    public function getIsTopEvent()
    {
        return $this->entity->getIsTopEvent();
    }

    /**
     * @param boolean $isTopEvent
     */
    public function setIsTopEvent($isTopEvent)
    {
        $this->entity->setIsTopEvent($isTopEvent);
    }

    /**
     * Return the title of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("title")
     * @Groups({"fullEvent","partialEvent"})
     */
    public function getTitle()
    {
        return $this->entity->getTitle();
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->entity->setTitle($title);
    }

    /**
     * Return the categories of the event
     *
     * @return Category[]
     * @VirtualProperty
     * @SerializedName("categories")
     * @Groups({"fullEvent"})
     */
    public function getCategories()
    {
        $entities = array();
        if ($this->entity->getCategories()) {
            foreach ($this->entity->getCategories() as $category) {
                $entities[] = new Category($category, $this->locale);
            }
        }

        return $entities;
    }

    /**
     * Return the start date of the event
     *
     * @return \Datetime
     * @VirtualProperty
     * @SerializedName("startDate")
     * @Groups({"fullEvent","partialEvent"})
     */
    public function getStartDate()
    {
        return $this->entity->getStartDate();
    }

    /**
     * @param \Datetime $startDate
     */
    public function setStartDate(\Datetime $startDate)
    {
        $this->entity->setStartDate($startDate);
    }

    /**
     * Return the start time of the event
     *
     * @return \Datetime
     * @VirtualProperty
     * @SerializedName("startTime")
     * @Groups({"fullEvent"})
     */
    public function getStartTime()
    {
        return $this->entity->getStartTime();
    }

    /**
     * @param \Datetime $startTime
     */
    public function setStartTime($startTime)
    {
        $this->entity->setStartTime($startTime);
    }

    /**
     * Return the end date of the event
     *
     * @return \Datetime
     * @VirtualProperty
     * @SerializedName("endDate")
     * @Groups({"fullEvent"})
     */
    public function getEndDate()
    {
        return $this->entity->getEndDate();
    }

    /**
     * @param \Datetime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->entity->setEndDate($endDate);
    }

    /**
     * Return the zip of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("zip")
     * @Groups({"fullEvent"})
     */
    public function getZip()
    {
        return $this->entity->getZip();
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->entity->setZip($zip);
    }

    /**
     * Return the description of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("description")
     * @Groups({"fullEvent"})
     */
    public function getDescription()
    {
        return $this->entity->getDescription();
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->entity->setDescription($description);
    }

    /**
     * Return the description venue of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("descriptionVenue")
     * @Groups({"fullEvent"})
     */
    public function getDescriptionVenue()
    {
        return $this->entity->getDescriptionVenue();
    }

    /**
     * @param string $descriptionVenue
     */
    public function setDescriptionVenue($descriptionVenue)
    {
        $this->entity->setDescriptionVenue($descriptionVenue);
    }

    /**
     * Return the city of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("city")
     * @Groups({"fullEvent"})
     */
    public function getCity()
    {
        return $this->entity->getCity();
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->entity->setCity($city);
    }

    /**
     * Return the country of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("country")
     * @Groups({"fullEvent"})
     */
    public function getCountry()
    {
        return $this->entity->getCountry();
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->entity->setCountry($country);
    }

    /**
     * Return the latitude of the event
     *
     * @return double
     * @VirtualProperty
     * @SerializedName("latitude")
     * @Groups({"fullEvent"})
     */
    public function getLatitude()
    {
        return $this->entity->getLatitude();
    }

    /**
     * @param double $latitude
     */
    public function setLatitude($latitude)
    {
        $this->entity->setLatitude($latitude);
    }

    /**
     * Return the longitude of the event
     *
     * @return double
     * @VirtualProperty
     * @SerializedName("longitude")
     * @Groups({"fullEvent"})
     */
    public function getLongitude()
    {
        return $this->entity->getLongitude();
    }

    /**
     * @param double $longitude
     */
    public function setLongitude($longitude)
    {
        $this->entity->setLongitude($longitude);
    }

    /**
     * Return the website of the event
     *
     * @return string
     * @VirtualProperty
     * @SerializedName("website")
     * @Groups({"fullEvent"})
     */
    public function getWebsite()
    {
        return $this->entity->getWebsite();
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->entity->setWebsite($website);
    }

    /**
     * Add categories
     *
     * @param CategoryEntity $categories
     * @return Event
     */
    public function addCategorie(CategoryEntity $categories)
    {
        $this->entity->addCategorie($categories);

        return $this;
    }

    /**
     * Remove categories
     *
     * @param CategoryEntity $categories
     */
    public function removeCategorie(CategoryEntity $categories)
    {
        $this->entity->removeCategorie($categories);
    }



    /**
     * Adds a media to the event
     *
     * @param Media $media
     */
    public function addMedia(Media $media)
    {
        $this->entity->addMedia($media->getEntity());
    }

    /**
     * Removes a media from the event
     *
     * @param Media $media
     */
    public function removeMedia(Media $media)
    {
        $this->entity->removeMedia($media->getEntity());
    }

    /**
     * @param Media[] $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Returns the media for the event
     *
     * @return Media[]
     * @VirtualProperty
     * @SerializedName("media")
     * @Groups({"fullEvent"})
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Returns true when collection of media contains media with specific id
     *
     * @param Media $media
     * @return bool
     */
    public function containsMedia(Media $media)
    {
        return $this->entity->getMedia()->contains($media->getEntity());
    }

    /**
     * Returns the regular entry fees for the event
     *
     * @return EventEntryFee[]
     * @VirtualProperty
     * @SerializedName("regularEntryFees")
     * @Groups({"fullEvent"})
     */
    public function getRegularEntryFees()
    {
        return $this->entity->getRegularEntryFees();
    }

    /**
     * Returns the slug of the Event
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->entity->getSlug();
    }
}
