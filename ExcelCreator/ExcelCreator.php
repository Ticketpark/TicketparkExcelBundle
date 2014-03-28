<?php

namespace Ticketpark\ExcelBundle\ExcelCreator;

use Ticketpark\ExcelBundle\Exception\InvalidArgumentException;
use Ticketpark\FileBundle\FileHandler\FileHandlerInterface;
use Ticketpark\HtmlPhpExcel\HtmlPhpExcel;

class ExcelCreator implements ExcelCreatorInterface
{
    protected $excelClient;
    protected $fileHandler;
    protected $content;
    protected $identifier;
    protected $useCache = true;

    public function __construct(FileHandlerInterface $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create($excelWriterType = 'Excel2007')
    {
        if (null === $this->identifier) {
            throw new InvalidArgumentException('You must define a file identifier with setIdentifier() to create a excel');
        }

        if (null === $this->content) {
            throw new InvalidArgumentException('You must define a content with setContent() to create a excel');
        }

        $contentHash = hash('sha256', $this->content);
        $identifier = 'ticketpark_excel_'.$this->identifier;
        if (!$file = $this->fileHandler->fromCache($identifier, array($contentHash))) {

            //We are doing a dirty trick with the cache here in order to save the file in cache without much hassle
            $file = $this->fileHandler->cache(null, $identifier, array($contentHash));

            $document = new HtmlPhpExcel($this->content);
            $document->process()->save($file, $excelWriterType);
        }

        return $file;
    }
}