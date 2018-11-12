<?php

namespace Artgris\Bundle\MediaBundle\Service;

use Gregwar\ImageBundle\Services\ImageHandling;

/**
 * ImageTwig extension.
 */
class GImageTwig extends \Twig_Extension
{
    /**
     * @var ImageHandling
     */
    private $imageHandling;

    /**
     * @param ImageHandling $imageHandling
     */
    public function __construct(ImageHandling $imageHandling)
    {
        $this->imageHandling = $imageHandling;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('gImage', [$this, 'image'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $path
     *
     * @return object
     */
    public function image($path)
    {
        if ($path !== null && $path[0] === '/') {
            $path = urldecode(substr($path, 1));
        }

        return $this->imageHandling->open($path);
    }

}
