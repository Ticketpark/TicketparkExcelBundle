<?php

namespace Ticketpark\ExcelBundle\ExcelCreator;

interface ExcelCreatorInterface
{
    /**
     * Set file contents
     *
     * @param string $content
     * @return ExcelCreator
     */
    public function setContent($content);

    /**
     * Set identifier
     *
     * This is used to identify the file, e.q. for caching
     *
     * @param string $identifier
     * @return ExcelCreator
     */
    public function setIdentifier($identifier);

    /**
     * Create excel
     *
     * @return string Path to created excel file
     */
    public function create();
}