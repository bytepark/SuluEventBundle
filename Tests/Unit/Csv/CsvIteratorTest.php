<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) bytepark GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\EventBundle\Tests\Unit\Csv;

use Exception;
use Sulu\Bundle\EventBundle\Csv\CsvIterator;

/**
 * CsvIteratorTest
 *
 * @package    Sulu\Bundle\EventBundle\Tests\Unit\Csv
 * @author     bytepark GmbH <code@bytepark.de>
 * @link       http://www.bytepark.de
 */
class CsvIteratorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
    }

    /**
     * testExceptionIsThrownIfFileIsNotAvailable
     */
    public function testExceptionIsThrownIfFileIsNotAvailable()
    {
        $this->setExpectedException(Exception::class, 'The file "foo.bar" cannot be read.');

        new CsvIterator('foo.bar');
    }

    /**
     * testIteratingAsLongAsLinesExistsInTheFile
     */
    public function testIteratingAsLongAsLinesExistsInTheFile()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Bytepark/Csv/SemicolonSeparatedWithHeader.csv');

        $lines = 0;
        foreach ($csv as $rowNumber => $columns) {
            $lines++;
        }
        $this->assertEquals(2, $lines);
    }

    /**
     * testReturnCurrentLineOfData
     */
    public function testReturnCurrentLineOfData()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Bytepark/Csv/SemicolonSeparatedWithHeader.csv');

        $data = $csv->current();
        $this->assertEquals('Column A', $data[0]);
    }

    /**
     * testReturnCurrentLineOfDataAfterIteration
     */
    public function testReturnCurrentLineOfDataAfterIteration()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Csv/SemicolonSeparatedWithHeader.csv');

        $data = $csv->current();
        $data = $csv->current();
        $this->assertEquals('Value C', $data[2]);
    }

    /**
     * testReturnCurrentRowNumberIsNullBeforeFirstIteration
     */
    public function testReturnCurrentRowNumberIsNullBeforeFirstIteration()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Csv/SemicolonSeparatedWithHeader.csv');

        $this->assertNull($csv->key());
    }

    /**
     * testReturnCurrentRowNumberAfterIteration
     */
    public function testReturnCurrentRowNumberAfterIteration()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Csv/SemicolonSeparatedWithHeader.csv');
        $csv->current();

        $this->assertSame(1, $csv->key());
    }

    /**
     * testReturnTrueIfAsNotAtEndOfFile
     */
    public function testReturnTrueIfNotAtEndOfFile()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Csv/SemicolonSeparatedWithHeader.csv');
        $csv->current();
        $this->assertTrue($csv->valid());
    }

    /**
     * testReturnFalseIfAtEndOfFile
     */
    public function testReturnFalseIfAtEndOfFile()
    {
        $csv = new CsvIterator(__DIR__ . '/../../Resources/Fixtures/Csv/SemicolonSeparatedWithHeader.csv');
        $csv->current();
        $csv->current();
        $this->assertFalse($csv->valid());
    }
}
