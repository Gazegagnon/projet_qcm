<?php
session_start();

include_once "vendor/autoload.php";

use  App\Controller\EnseignantController;

$enseignantCtn = new EnseignantController;

$enseignantCtn->EnseiHttps();

