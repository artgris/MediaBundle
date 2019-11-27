<?php

namespace Artgris\Bundle\MediaBundle\Controller;

use Gregwar\Image\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax-icon/", name="admin_ajax_icon", defaults={"filename" = null})
     */
    public function ajaxIcon(Request $request)
    {
        $filePath = $request->query->get('path');
        $iconData = $this->get('file_type_service')->fileIcon($filePath);

        return new JsonResponse(['icon' => $iconData]);
    }

    /**
     * @Route("/ajax-crop/", name="admin_ajax_crop")
     */
    public function ajaxCrop(Request $request)
    {
        $post = $request->request;
        $src = $post->get('src');
        $src = strtok($src, '?');
        $x = $post->getInt('x');
        $y = $post->getInt('y');
        $width = $post->getInt('width');
        $height = $post->getInt('height');
        $scaleX = $post->getInt('scaleX', 1);
        $scaleY = $post->getInt('scaleY', 1);
        $rotate = $post->getInt('rotate');
        $conf = $post->get('conf');

        $fileManager = $this->getParameter('artgris_file_manager');

        $destinationFolder = null;
        if ($conf !== null) {
            $artgrisConf = $this->get('artgris_bundle_file_manager.service.filemanager_service')->getBasePath(['conf' => $conf]);
            $destinationFolder = $artgrisConf['dir'];
        }

        $flipX = $scaleX !== 1;
        $flipY = $scaleY !== 1;

        if ($flipX) {
            $rotate = -$rotate;
        }
        if ($flipY) {
            $rotate = -$rotate;
        }

        $pathinfo = pathinfo(parse_url($src, PHP_URL_PATH));
        $extension = $pathinfo['extension'];

        if ($src[0] === '/') {
            $src = urldecode($this->getParameter('kernel.project_dir').'/'.$fileManager['web_dir'].$src);
        }

        if (!file_exists($src)) {
            return new JsonResponse('');
        }

        $image = Image::open($src)
            ->rotate(-$rotate)
            ->flip($flipY, $flipX)
            ->crop($x, $y, $width, $height);

        $savedPath = '/';

        if ($destinationFolder !== null) {

            if (substr($destinationFolder, -1) !== DIRECTORY_SEPARATOR) {
                $destinationFolder .= DIRECTORY_SEPARATOR;
            }
            $rootdir = $this->getParameter('kernel.root_dir');

            $baseUrl = $rootdir.' ../'.$fileManager['web_dir'];
            $cropStrAdd = '_crop_';
            $filename = $pathinfo['filename'];
            $cropPos = mb_strpos($filename, $cropStrAdd);
            if ($cropPos !== false) {
                $filename = mb_substr($filename, 0, $cropPos);
            }
            $croppedPath = $this->getParameter('artgris_media')['cropped_path'];
            $savedPath = $image->save($rootdir.DIRECTORY_SEPARATOR.$destinationFolder.$croppedPath.urldecode($filename).$cropStrAdd.uniqid().'.'.$extension, 'guess', 85);

            $savedPath = mb_substr($savedPath, mb_strlen($baseUrl));
            if ($savedPath[0] !== '/') {
                $savedPath = $src;
            }
        } else {
            if ($extension === 'png') {
                $savedPath .= $image->png();
            } else {
                $savedPath .= $image->jpeg();
            }
        }

        return new JsonResponse($savedPath);
    }
}
