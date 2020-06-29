<?php

namespace Artgris\Bundle\MediaBundle\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * ImageTwig extension.
 */
class RSlashTwig extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('rSlash', [$this, 'rSlash'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function rSlash($path)
    {
        if ($path !== null && $path[0] === '/') {
            $path = urldecode(mb_substr($path, 1));
        }

        return $path;
    }
}
