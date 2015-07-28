<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Event;

use Doctrine\ORM\EntityManagerInterface;

use Sulu\Bundle\CategoryBundle\Entity\Category as Category;
use Sulu\Bundle\EventBundle\Entity\Event;
use Sulu\Bundle\CategoryBundle\Category\CategoryRepositoryInterface;
use Sulu\Bundle\EventBundle\Entity\EventEntryFeeRepositoryInterface;
use Sulu\Bundle\EventBundle\Entity\EventOrganizerRepositoryInterface;
use Sulu\Bundle\EventBundle\Entity\EventRepositoryInterface;
use Sulu\Bundle\EventBundle\Event\Exception\EventDependencyNotFoundException;
use Sulu\Bundle\EventBundle\Event\Exception\EventNotFoundException;
use Sulu\Bundle\MediaBundle\Api\Media;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManager;
use Sulu\Component\Persistence\RelationTrait;
use Sulu\Component\Rest\Exception\EntityIdAlreadySetException;
use Sulu\Component\Rest\Exception\EntityNotFoundException;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor;
use Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineJoinDescriptor;
use Symfony\Component\Intl\Intl;

/**
 * EventManager
 *
 * @package    Sulu\Bundle\EventBundle\Event
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventManager implements EventManagerInterface
{
    use RelationTrait;

    public static $eventEntityName = 'SuluEventBundle:Event';
    public static $eventCategoryEntityName = 'SuluEventBundle:EventCategory';
    public static $eventEntryFeeEntityName = 'SuluEventBundle:EventEntryFee';
    public static $eventOrganizerEntityName = 'SuluEventBundle:EventOrganizer';


    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $eventCategoryRepository;

    /**
     * @var EventEntryFeeRepositoryInterface
     */
    private $eventEntryFeeRepository;

    /**
     * @var EventOrganizerRepositoryInterface
     */
    private $eventOrganizerRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DoctrineFieldDescriptor[]
     */
    private $fieldDescriptors = array();

    /**
     * @var DoctrineFieldDescriptor[]
     */
    private $entryFeeFieldDescriptors = array();

    /**
     * @var string
     */
    protected $eventApiEntity;

    /**
     * @var string
     */
    private $eventEntity;

    /**
     * @var MediaManager
     */
    protected $mediaManager;

    /**
     * @param EventRepositoryInterface               $eventRepository
     * @param CategoryRepositoryInterface            $eventCategoryRepository
     * @param EventEntryFeeRepositoryInterface       $eventEntryFeeRepository
     * @param EventOrganizerRepositoryInterface      $eventOrganizerRepository
     * @param MediaManager                           $mediaManager
     * @param EntityManagerInterface                 $entityManager
     * @param string                                 $eventEntity
     * @param string                                 $eventApiEntity
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        CategoryRepositoryInterface $eventCategoryRepository,
        EventEntryFeeRepositoryInterface $eventEntryFeeRepository,
        EventOrganizerRepositoryInterface $eventOrganizerRepository,
        MediaManager $mediaManager,
        EntityManagerInterface $entityManager,
        $eventEntity,
        $eventApiEntity
    )
    {
        $this->eventRepository = $eventRepository;
        $this->eventCategoryRepository = $eventCategoryRepository;
        $this->eventEntryFeeRepository = $eventEntryFeeRepository;
        $this->eventOrganizerRepository = $eventOrganizerRepository;
        $this->mediaManager = $mediaManager;
        $this->entityManager = $entityManager;
        $this->eventEntity = $eventEntity;
        $this->eventApiEntity = $eventApiEntity;
    }

    /**
     * @param array $filter
     * @return array
     */
    public function findEventsForMap($filter)
    {
        $filter['eventIds'] = null;

        if ($filter['dateFrom']) {
            $filter['dateFrom'] = new \DateTime($filter['dateFrom']);
        }

        if ($filter['dateTo']) {
            $filter['dateTo'] = new \DateTime($filter['dateTo']);
        }

        $filter['searchString'] = $this->prepareSearchString($filter['searchString']);

        foreach ($filter as $key => $value) {
            if (is_null($value)) {
                unset($filter[$key]);
            }
        }

        return $this->eventRepository->findEventsForMap($filter);
    }

    /**
     * Prepares the incoming search string
     *
     * This method cleans up the incoming search string, by
     * - removing undesired characters
     *
     * @param string $searchString
     * @return string
     */
    private function prepareSearchString($searchString)
    {
        $searchString = preg_replace('/[^a-zA-Z0-9\-\.\(\)\pL\s]/u', '', $searchString);
        $searchString = trim($searchString);

        return $searchString;
    }

    /**
     * @param string $page
     * @param array  $filter
     * @return array
     * @throws \Sulu\Bundle\MediaBundle\Media\Exception\MediaNotFoundException
     */
    public function findFilteredEvents($page, $filter)
    {
        $filter['eventIds'] = null;

        if ($filter['dateFrom']) {
            $filter['dateFrom'] = new \DateTime($filter['dateFrom']);
        }

        if ($filter['dateTo']) {
            $filter['dateTo'] = new \DateTime($filter['dateTo']);
        }

        $filter['searchString'] = $this->prepareSearchString($filter['searchString']);

        foreach ($filter as $key => $value) {
            if (is_null($value)) {
                unset($filter[$key]);
            }
        }

        $results = $this->eventRepository->findFilteredEvents($page, $filter);

        $apiEvents = array();

        foreach ($results['events'] as $event) {
            $media = array();
            $foundEvent = is_array($event) ? $event[0] : $event;
            $apiEvent = new $this->eventApiEntity($foundEvent, 'de');


            foreach ($apiEvent->getEntity()->getMedia() as $medium) {
                $media[] = $this->mediaManager->getbyId($medium->getId(), 'de');
            }

            $apiEvent->setMedia($media);

            $apiEvents[] = $apiEvent;
        }

        $results['events'] = $apiEvents;

        return $results;
    }

    /**
     * @return array
     */
    public function getFieldDescriptors()
    {
        if (empty($this->fieldDescriptors)) {
            $this->initializeFieldDescriptors();
        }

        return $this->fieldDescriptors;
    }

    /**
     * @param int $maxResults
     * @return mixed
     */
    public function findRunningEvents($maxResults = 20)
    {
        $events = $this->eventRepository->findRunningEvents($maxResults);

        return $events;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findAll()
    {
        return $this->eventRepository->findAll();
    }

    /**
     * @param int    $eventId
     * @param string $locale
     * @param bool   $useDoctrine
     * @return Event
     * @throws \Sulu\Bundle\MediaBundle\Media\Exception\MediaNotFoundException
     */
    public function findByIdAndLocale($eventId, $locale, $useDoctrine = false)
    {
        $event = $this->eventRepository->findByIdAndLocale($eventId, $locale);

        if ($event) {
            $event = new $this->eventApiEntity($event, $locale);
            $media = [];

            foreach ($event->getEntity()->getMedia() as $medium) {
                if ($useDoctrine) {
                    $media[] = $this->mediaManager->getbyId($medium->getId(), $locale);
                } else {
                    $media['ids'][] = $medium->getId();
                }
            }

            $event->setMedia($media);

            return $event;
        }

        return null;
    }

    /**
     * Fetches a event
     *
     * @param int $eventId
     * @param string $locale
     *
     * @return Event
     * @throws EventNotFoundException
     */
    protected function fetchEvent($eventId, $locale)
    {
        $event = $this->eventRepository->findByIdAndLocale($eventId, $locale);

        if (!$event) {
            throw new EventNotFoundException($eventId);
        }

        return new $this->eventApiEntity($event, $locale);
    }

    /**
     * @param array   $data
     * @param int     $locale
     * @param integer $eventId
     * @return Event
     * @throws EventDependencyNotFoundException
     * @throws EventNotFoundException
     */
    public function save(
        array $data,
        $locale,
        $eventId = null
    )
    {
        if ($eventId) {
            $event = $this->fetchEvent($eventId, $locale);
            $oldMediaFiles = $this->findByIdAndLocale($eventId, $locale, true)->getMedia();
        } else {
            $event = new $this->eventApiEntity(new $this->eventEntity, $locale);
            $oldMediaFiles = $event->getMedia();
        }





        $event->setTitle($this->getProperty($data, 'title', $event->getTitle()));

        $this->setDate(
            $data,
            'startDate',
            $event->getStartDate(),
            array($event, 'setStartDate')
        );

        $event->setlongitude(str_replace(',', '.', $this->getProperty($data, 'longitude')));
        $event->setlatitude(str_replace(',', '.', $this->getProperty($data, 'latitude')));

        $event->setZip($this->getProperty($data, 'zip', $event->getZip()));
        $event->setCity($this->getProperty($data, 'city', $event->getCity()));
        $event->setCountry($this->getProperty($data, 'country', $event->getCountry()));

        $this->setDate(
            $data,
            'startTime',
            null,
            array($event, 'setStartTime')
        );

        $get = function ($category) {
            return $category->getId();
        };

        $add = function ($category) use ($event) {
            return $this->addCategories($event, $category);
        };

        $delete = function ($category) use ($event) {
            return $event->removeCategorie($category->getEntity());
        };

        $this->processSubEntities(
            $event->getCategories(),
            $data['categories'],
            $get,
            $add,
            null,
            $delete
        );

        if (isset($data['media']['ids'])) {
            $get = function (Media $media) {
                return $media->getId();
            };

            $add = function ($mediaData) use ($event, $locale) {
                return $this->addMedia($event->getEntity(), $mediaData, $locale);
            };

            $delete = function (Media $media) use ($event) {
                $event->removeMedia($media);

                return true;
            };

            $this->processSubEntities(
                $oldMediaFiles,
                $data['media']['ids'],
                $get,
                $add,
                null,
                $delete
            );
        }

        $this->setDate(
            $data,
            'endDate',
            null,
            array($event, 'setEndDate')
        );

        if (isset($data['organizer'])) {
            $event->getOrganizer()->setTitle($this->getProperty($data['organizer'], 'title'));
            $event->getOrganizer()->setLastName($this->getProperty($data['organizer'], 'lastName'));
            $event->getOrganizer()->setFirstName($this->getProperty($data['organizer'], 'firstName'));
            $event->getOrganizer()->setStreet($this->getProperty($data['organizer'], 'street'));
            $event->getOrganizer()->setZip($this->getProperty($data['organizer'], 'zip'));
            $event->getOrganizer()->setCity($this->getProperty($data['organizer'], 'city'));
            $event->getOrganizer()->setPhone($this->getProperty($data['organizer'], 'phone'));
            $event->getOrganizer()->setFax($this->getProperty($data['organizer'], 'fax'));
            $event->getOrganizer()->setEmail($this->getProperty($data['organizer'], 'email'));
        }

        $event->setDescription($this->getProperty($data, 'description', null));
        $event->setDescriptionVenue($this->getProperty($data, 'descriptionVenue', null));
        $event->setWebsite($this->getProperty($data, 'website', null));



        $event->setIsTopEvent($this->getProperty($data, 'isTopEvent', $event->getIsTopEvent()));

        if ($event->getId() == null) {
            $this->entityManager->persist($event->getEntity());
        }

        $this->entityManager->flush();

        return $event;
    }

    /**
     * @param integer $eventId
     * @throws EventNotFoundException
     */
    public function delete($eventId)
    {
        $event = $this->eventRepository->find($eventId);

        if (!$event) {
            throw new EventNotFoundException($eventId);
        }

        $this->entityManager->remove($event);
        $this->entityManager->flush();
    }

    private function initializeFieldDescriptors()
    {
        $this->fieldDescriptors['id'] = new DoctrineFieldDescriptor(
            'id',
            'id',
            self::$eventEntityName,
            'ID'
        );

        $this->fieldDescriptors['title'] = new DoctrineFieldDescriptor(
            'title',
            'title',
            self::$eventEntityName,
            'Titel'
        );


        $this->fieldDescriptors['country'] = new DoctrineFieldDescriptor(
            'country',
            'country',
            self::$eventEntityName,
            'Land'
        );

        $this->fieldDescriptors['startDate'] = new DoctrineFieldDescriptor(
            'startDate',
            'startDate',
            self::$eventEntityName,
            'Startdatum',
            array(),
            false,
            false,
            'date'
        );

        $this->fieldDescriptors['zip'] = new DoctrineFieldDescriptor(
            'zip',
            'zip',
            self::$eventEntityName,
            'PLZ',
            array(),
            false,
            false,
            'string',
            '',
            '',
            true,
            false
        );

        $this->fieldDescriptors['city'] = new DoctrineFieldDescriptor(
            'city',
            'city',
            self::$eventEntityName,
            'Ort',
            array(),
            false,
            false,
            'string',
            '',
            '',
            true,
            false
        );

        $this->fieldDescriptors['description'] = new DoctrineFieldDescriptor(
            'description',
            'description',
            self::$eventEntityName,
            'Beschreibung',
            array(),
            true,
            false,
            'string'
        );
    }

    /**
     * Returns the entry from the data with the given key, or the given default value, if the key does not exist
     *
     * @param array $data
     * @param string $key
     * @param string $default
     * @return mixed
     */
    protected function getProperty(array $data, $key, $default = null)
    {
        return array_key_exists($key, $data) ? $data[$key] : $default;
    }

    /**
     * sets a date if it's set in data
     * @param $data
     * @param $key
     * @param $currentDate
     * @param callable $setCallback
     */
    private function setDate($data, $key, $currentDate, callable $setCallback)
    {
        if (($date = $this->getProperty($data, $key, $currentDate)) !== null) {
            if (is_string($date)) {
                $date = new \DateTime($data[$key]);
            }
        } else {
            $date = null;
        }

        call_user_func($setCallback, $date);
    }

    /**
     * Adds a new category to the given contact
     * @param $event
     * @param $data
     * @return bool
     * @throws EntityNotFoundException
     * @throws EntityIdAlreadySetException
     */
    protected function addCategories($event, $data)
    {
        $success = true;
        $categoryEntity = 'SuluCategoryBundle:Category';

        $category = $this->entityManager
            ->getRepository($categoryEntity)
            ->find($data['id']);

        if (!$category) {
            throw new EntityNotFoundException($categoryEntity, $data);
        } else {
            $event->addCategorie($category);
        }

        return $success;
    }

    /**
     * @param Event $event
     * @param $mediaData
     * @param $locale
     * @return bool
     * @throws EventDependencyNotFoundException
     * @throws \Sulu\Bundle\MediaBundle\Media\Exception\MediaNotFoundException
     */
    protected function addMedia(Event $event, $mediaData, $locale)
    {
        $media = $this->mediaManager->getById($mediaData, $locale);

        if (!$media) {
            throw new EventDependencyNotFoundException(
                MediaManager::ENTITY_NAME_MEDIA,
                $mediaData
            );
        }

        $media = $media->getEntity();

        $event->addMedia($media);

        $this->entityManager->persist($media);

        return true;
    }

    /**
     * @return \Sulu\Component\Rest\ListBuilder\Doctrine\FieldDescriptor\DoctrineFieldDescriptor[]
     */
    public function getEntryFeeFieldDescriptors()
    {
        $this->entryFeeFieldDescriptors['id'] = new DoctrineFieldDescriptor(
            'id',
            'id',
            self::$eventEntryFeeEntityName,
            'ID'
        );

        $this->entryFeeFieldDescriptors['validUntilDate'] = new DoctrineFieldDescriptor(
            'validUntilDate',
            'validUntilDate',
            self::$eventEntryFeeEntityName,
            'Datum (bis Angabe)',
            array(),
            false,
            false,
            'date'
        );

        $this->entryFeeFieldDescriptors['price'] = new DoctrineFieldDescriptor(
            'price',
            'price',
            self::$eventEntryFeeEntityName,
            'Preis (in €)'
        );

        return $this->entryFeeFieldDescriptors;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->eventCategoryRepository->findAll();
    }

    /**
     * @return array
     */
    public function getCountries()
    {
        $availableCountries = $this->eventRepository->findAvailableCountries();

        $countries = array();

        foreach ($availableCountries as $alpha2) {
            $countries[] = array(
                'id' => $alpha2['country'],
                'name' => Intl::getRegionBundle()->getCountryName($alpha2['country'])
            );
        }

        return $countries;
    }
}
