<?php

namespace Zend\Http;

class StreamObject implements \Stringable
{
    public function __construct(private $tempFile)
    {
    }

    public function __toString(): string
    {
        return (string) $this->tempFile;
    }
}
