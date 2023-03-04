<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        echo "zz"; exit;
        return view('welcome_message');
    }

    public function ddd()
    {
        echo "zz"; exit;
        return view('welcome_message');
    }
}
