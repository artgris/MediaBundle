<?php

namespace Artgris\Bundle\MediaBundle\Entity;

use JsonSerializable;

class Media implements JsonSerializable
{


    /** @var string */
    private $path;
    
    /** @var string */
    private $alt;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'path' => $this->path,
            'alt' => $this->alt
        ];
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        $path = urldecode($this->path);
        if (!empty($path) && $path[0] === '/') {
            return substr($path, 1);
        }
        return $path;
    }

    public function __toString()
    {
        return $this->path;
    }

}