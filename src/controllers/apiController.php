<?php

namespace Helium\controllers;

use Helium\Core\Controller;

class apiController extends Controller
{
    public function toggle(){
        return json_encode($_SERVER);
    }
}