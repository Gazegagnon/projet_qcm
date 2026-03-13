<?php
session_start();

require_once "vendor/autoload.php";

use App\Controller\EnseignantController;
use App\Controller\QcmController;
use App\Controller\QuestionController;
use App\Controller\ReponseController;

$enseignantCtn = new EnseignantController();
$enseignantCtn->EnseiHttps();

$qcmCtn = new QcmController();
$qcmCtn->qcmHttps();

$questionCtn = new QuestionController();
$questionCtn->questionHttps();

$reponseCtn = new ReponseController();
$reponseCtn->reponseHttps();