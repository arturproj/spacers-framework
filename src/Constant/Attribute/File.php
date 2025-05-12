<?php

namespace Spacers\Framework\Constant\Attribute;
use Spacers\Framework\Exception\NotFoundExcetion;

class File
{
    protected string $mimetype;
    protected array $filepath;
    /**
     * Summary of __construct
     * @param string $filename
     * @throws \Spacers\Framework\Exception\NotFoundExcetion
     * @return void
     */
    public function __construct(
        protected string $filename
    ) {
        if (!file_exists($this->filename)) {
            throw new NotFoundExcetion("File source '{$this->filename}' not found");
        }
        $this->mimetype = mime_content_type($this->filename);
        $this->filepath = pathinfo($this->filename);

    }
    /**
     * Summary of getFilename
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
    /**
     * Summary of getMimetype
     * @return string|bool
     */
    public function getMimetype(): string|bool
    {
        return $this->mimetype;
    }
    /**
     * Summary of getContent
     * @return bool|string
     */
    public function getContent(): string|bool
    {
        return file_get_contents($this->filename);
    }
    /**
     * Summary of getPath
     * @return array|string
     */
    public function getPath(): array|string
    {
        return $this->filepath;
    }
}