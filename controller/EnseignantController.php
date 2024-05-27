<?php

namespace App\Controller;

use App\Model\EnseignantModel;
use App\Entity\Enseignant;

class EnseignantController extends Controller

{
    public function EnseiHttps()
    {
        $enseignantMdl = new EnseignantModel;

        if(isset($_GET['action']))
        {
            $action = $_GET['action'];

            switch($action)
            {
                case "enseignant":
                    $this->render("enseignant/index");
                    break;

                case "login_ens":
                    if(isset($_POST['login_admin']))
                    {
                        extract($_POST);
                        $enseignantMdl->login($login, $mdp);
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