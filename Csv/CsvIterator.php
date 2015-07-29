<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Csv;

use Exception;
use Iterator;

/**
 * CsvIterator
 *
 * @package    Sulu\Bundle\EventBundle\Csv
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class CsvIterator implements Iterator
{
    const ROW_SIZE = 4096;

    /**
     * @var resource
     */
    private $filePointer = null;

    /**
     * @var array
     */
    private $currentElement = null;

    /**
     * The row counter.
     * @var int
     */
    private $rowCounter = null;

    /**
     * The delimiter for the csv file.
     * @var string
     */
    private $delimiter = null;

    /**
     * @param string $file
     * @param string $delimiter
     *
     * @throws Exception
     */
    public function __construct($file, $delimiter=';')
    {
        try {
            $this->filePointer = fopen($file, 'r');
            $this->delimiter = $delimiter;
        } catch (Exception $e) {
            throw new Exception('The file "'.$file.'" cannot be read.');
        }
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }

    /**
     * @return array
     */
    public function current()
    {
        $this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
        $this->rowCounter++;

        return $this->currentElement;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->rowCounter;
    }

    /**
     * @return boolean
     */
    public function next()
    {
        return !feof($this->filePointer);
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        if (!$this->next()) {
            fclose($this->filePointer);

            return false;
        }

        return true;
    }
}
