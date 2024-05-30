<?php

namespace App\Controller;

use App\Model\EnseignantModel;
use App\Model\QcmModel;
use App\Model\QuestionModel;
use App\Model\ReponseModel;
use App\Entity\Qcm;
use App\Entity\Enseignant;
use App\Entity\Question;
use App\Entity\Reponse;



class EnseignantController extends Controller

{
    public function EnseiHttps()
    {
        $enseignantMdl = new EnseignantModel;
        $qcmMdl = new QcmModel;
        $questionMdl = new QuestionModel;
        $reponseMdl = new ReponseModel;

        if(isset($_GET['action']))
        {
            $action = $_GET['action'];

            switch($action)
            {
                case "enseignant":

                    if(isset($_SESSION['enseignant']))
                    {
                        $idEns = unserialize($_SESSION['enseignant'])->getId();
                        $qcms = $qcmMdl->getQcmByEns($idEns);
                        $questions = $questionMdl->questions($idEns);
                        $reponses = $reponseMdl->reponses();
                        
                        
                    }
                    // var_dump($idEns);
                    $this->render("enseignant/index", ["qcms" => $qcms, "questions" => $questions, "reponses" => $reponses]);
                    break;

                case "login_ens":
                    if(isset($_POST['login_admin']))
                    {
                        extract($_POST);
                        $enseignantMdl->login($mail, $mdpEns);
                        if(isset($_SESSION['enseignant']))
                        {
                            header("location: ?action=enseignant");
                            exit;

                        }


                    }
                    $this->render("enseignant/login");
                    break;


                case "add_admin":
                    if(isset($_POST['new_admin']))
                    {
                        extract($_POST);

                        $enseignant = new Enseignant(0,$nom,$mdp,$mail);
                        $enseignantMdl->create($enseignant);
                        if(isset($_SESSION['enseignant']))
                        {
                            header("location: ?action=enseignant");
                            exit;
                        }


                    }
             
                    $this->render("enseignant/new");
                    break;
                
                case "logout":
                    session_destroy();
                    header("location: ?action=login_ens");
                    exit;






            }
        }

    }
}