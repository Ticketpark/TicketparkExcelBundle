<?php

namespace Ticketpark\ExcelBundle\Tests\ExcelCreator;

use Ticketpark\ExcelBundle\ExcelCreator\ExcelCreator;

class ExcelCreatorTest extends \PHPUnit_Framework_TestCase
{
    protected $pathToTestfiles;

    public function setUp()
    {
        $this->pathToTestfiles = __DIR__.'/../testfiles/';
    }

    public function testCreateNewExcel()
    {
        $excelCreator = new ExcelCreator($this->getFileHandlerMock());

        $file = $excelCreator
            ->setContent('dummy')
            ->setIdentifier('id')
            ->create();

        $this->assertEquals(true, file_exists($file));

        if (is_file($file) && is_writable($file)) {
            unlink($file);
        }

    }

    public function testCreateExcelAlreadyInCache()
    {
        $excelCreator = new ExcelCreator($this->getFileHandlerMock(true));

        $result = $excelCreator
            ->setContent('dummy')
            ->setIdentifier('id')
            ->create();

        $this->assertEquals('oldFileFromCache', $result);
    }

    /**
    * @expectedException Ticketpark\ExcelBundle\Exception\InvalidArgumentException
    */
    public function testNoContent()
    {
        $excelCreator = new ExcelCreator($this->getFileHandlerMock());

        $excelCreator
            ->setIdentifier('id')
            ->create();
    }

    /**
     * @expectedException Ticketpark\ExcelBundle\Exception\InvalidArgumentException
     */
    public function testNoIdentifier()
    {
        $excelCreator = new ExcelCreator($this->getFileHandlerMock());

        $excelCreator
            ->setContent('dummy')
            ->create();
    }

    public function getFileHandlerMock($fileInCache=false)
    {
        $fileHandler = $this->getMockBuilder('Ticketpark\FileBundle\FileHandler\FileHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('fromCache', 'cache'))
            ->getMock();

        $fileHandler->expects($this->any())
            ->method('fromCache')
            ->will($this->returnValue(call_user_func(array($this, 'fromCache'), $fileInCache)));

        $fileHandler->expects($this->any())
            ->method('cache')
            ->will($this->returnCallback(array($this, 'cache')));

        return $fileHandler;
    }

    public function fromCache($fileInCache)
    {
        if ($fileInCache) {
            return 'oldFileFromCache';
        }

        return false;
    }

    public function cache()
    {
        if (!is_writable($this->pathToTestfiles)) {
            $this->markTestSkipped(
                sprintf('The directory %s must be writable for this test to run.', realpath($this->pathToTestfiles))
            );
        }

        return $this->pathToTestfiles.'dummyfile';
    }
}