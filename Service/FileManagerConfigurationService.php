<?php

namespace Artgris\Bundle\MediaBundle\Service;

use Artgris\Bundle\FileManagerBundle\Service\FilemanagerService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileManagerConfigurationService extends AbstractExtension
{
    /**
     * @var FilemanagerService
     */
    private $filemanagerService;

    /**
     * @var array
     */
    private $artgrisFileManagerConfig;

    public function __construct(FilemanagerService $filemanagerService, ParameterBagInterface $parameterBag)
    {
        $this->filemanagerService = $filemanagerService;
        $this->artgrisFileManagerConfig = $parameterBag->get('artgris_file_manager');
    }

    public function getWebPath(string $conf)
    {
        $dirPath = $this->filemanagerService->getBasePath(['conf' => $conf]);

        if (!isset($dirPath)) {
            throw new \InvalidArgumentException("The conf \"$conf\" was not found in artgris_file_manager.");
        }

        $confPath = $dirPath['dir'];
        $publicDir = '../'.$this->artgrisFileManagerConfig['web_dir'];

        if (mb_strpos($confPath, $publicDir) !== 0) {
            return true;
        }

        return mb_substr($confPath, mb_strlen($publicDir));
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_web_path', [$this, 'getWebPath']),
        ];
    }
}
