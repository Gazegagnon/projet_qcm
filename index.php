<?php
session_start();

include_once "vendor/autoload.php";

use  App\Controller\EnseignantController;
use  App\Controller\QcmController;
use  App\Controller\QuestionController;

$enseignantCtn = new EnseignantController;
$enseignantCtn->EnseiHttps();

$QcmCtn = new QcmController;
$QcmCtn->qcmHttps();

$questionCtn = new QuestionController;
$questionCtn->questionHttps();





