<?php
namespace App\Controller;

abstract class Controller 
{
    public function render($page, array $data = [])
    {
        extract($data);
        include_once "views/". $page . ".php";
    }
    
}

