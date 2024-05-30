<?php

namespace App\Controller;

use App\Model\ReponseModel;
use App\Entity\Reponse;

class ReponseController extends Controller
{
    public function reponseHttps()
    {
        $reponseMdl = new ReponseModel;

        if(isset($_GET['action']))
        {
            $action = $_GET['action'];
            
            switch($action)
            {
                case "add_Reponse":
                    if(isset($_POST['add_reponse']))
                    {
                        extract($_POST);

                        $reponse = new Reponse(0,$reponsePropose, $bonneReponse, $_GET['id']);
                        $reponseMdl->create($reponse);
                        header("location: ?action=enseignant");
                        exit;

                    }
                    $this->render("reponse/new");
                    break;
            }
        }

    }
}