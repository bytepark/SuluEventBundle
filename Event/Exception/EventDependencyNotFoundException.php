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
 * EventDependencyNotFoundException
 *
 * @package    Sulu\Bundle\EventBundle\Event\Exception
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class EventDependencyNotFoundException extends EventException
{
    /**
     * The name of the object not found
     *
     * @var string
     */
    private $entityName;

    /**
     * The id of the object not found
     *
     * @var integer
     */
    private $dependencyId;

    /**
     * @param string $entityName
     * @param int    $dependencyId
     */
    public function __construct($entityName, $dependencyId)
    {
        $this->entityName = $entityName;
        $this->dependencyId = $dependencyId;

        parent::__construct('The event dependency "' . $this->entityName . ' with the id "' . $this->dependencyId . '" was not found.', 0);
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
        return $this->dependencyId;
    }
}
