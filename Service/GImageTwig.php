<?php

namespace Artgris\Bundle\MediaBundle\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * ImageTwig extension.
 */
class GImageTwig extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('gImage', [$this, 'image'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function image($path)
    {
        if ($path !== null && $path[0] === '/') {
            $path = urldecode(mb_substr($path, 1));
        }

        return $path;
    }
}
