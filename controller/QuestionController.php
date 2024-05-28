<?php

namespace App\Controller;

use App\Model\QuestionModel;
use App\Model\QcmModel;
use App\Entity\Qcm;
use App\Entity\Question;

class QuestionController extends Controller

{
    public function questionHttps()
    {
        $questionMdl = new QuestionModel;

        if(isset($_GET['action']))
        {
            $action = $_GET['action'];
            
            switch($action)
            {
                case "add_question":
                    if(isset($_POST['add_question']))
                    {
                        extract($_POST);
                        $question = new Question(0,$libelle,$point, $_GET['id']);
                        $questionMdl->create($question);
                        header("location: ?action=enseignant");
                        exit;

                    }
                    $this->render("question/new");
                    break;
            }

        }
    }
}