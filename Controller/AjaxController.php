<?php

namespace Artgris\Bundle\MediaBundle\Controller;

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

}