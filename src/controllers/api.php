<?php

namespace Helium\Controller;

use Helium\Core\Controller;

class apiController extends Controller
{
    public function index(){
        return json_encode($_SERVER);
    }

    public function toggle(){
        return json_encode($_SERVER);
    }
}