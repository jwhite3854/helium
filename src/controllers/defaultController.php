<?php

namespace Helium\controllers;

use Helium\Core\Controller;
use Helium\Helium;

class defaultController extends Controller
{
    public function index(): string
    {
        $layoutData = $this->generateGenericTitle($_SERVER["REQUEST_URI"]);
    
        return $this->render([], $layoutData, [], []);
    }

    private function generateGenericTitle($request_uri): array
    {
        $uri =  trim($request_uri, '/');
        $parts = array_reverse(explode('/', $uri));
        $title = ucwords(str_replace('-', ' ', $parts[0]));
        $baseUrl = Helium::getConfig('redirect_base');

        if ( $title === trim($baseUrl, '/') ) {
            $title = Helium::getConfig('app_title');;
        }

        return [
            'meta' => [
                'title' => $title
            ]
        ];
    }
}