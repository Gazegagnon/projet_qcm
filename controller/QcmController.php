<?php

namespace App\Controller;

use App\Model\QcmModel;
use App\Entity\Qcm;


class QcmController extends Controller 

{
    public function qcmHttps()
    {
        $qcmMdl = new QcmModel;
        if(isset($_GET['action']))
        {
            $action = $_GET['action'];

            switch($action)
            {
                case "voir_qcm":
                    $qcmMdl->qcms();

                case "add_qcm":
                    if(isset($_POST['new_qcm']))
                    {
                        extract($_POST);

                        $qcm = new Qcm(0,$theme, $_GET['id']);
                        $qcmMdl->create($qcm);

                        header("location: ?action=enseignant");
                        exit;

                        
                    }
                    $this->render("qcm/new");
                    break;
                    
            }
        }

    }
}