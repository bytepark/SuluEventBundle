<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Event\Exception;

/**
 * EventNotFoundException
 *
 * @package    Sulu\Bundle\EventBundle\Event\Exception
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventNotFoundException extends EventException
{
    /**
     * The name of the object not found
     * @var string
     */
    private $entityName;

    /**
     * The id of the event not found
     * @var integer
     */
    private $eventId;

    /**
     * @param string $eventId
     */
    public function __construct($eventId)
    {
        $this->entityName = 'SuluEventBundle:Event';
        $this->eventId = $eventId;

        parent::__construct('The event with the id "' . $this->eventId . '" was not found.', 0);
    }

    /**
     * Returns the name of the entity name of the dependency not found
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Returns the id of the object not found
     *
     * @return int
     */
    public function getId()
    {
        return $this->eventId;
    }
}
