<?php

namespace App\Controller;

use App\Model\EnseignantModel;
use App\Model\QcmModel;
use App\Model\QuestionModel;
use App\Model\ReponseModel;
use App\Entity\Enseignant;

class EnseignantController extends Controller
{
    public function EnseiHttps()
    {
        $enseignantMdl = new EnseignantModel();
        $qcmMdl = new QcmModel();
        $questionMdl = new QuestionModel();
        $reponseMdl = new ReponseModel();

        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case "enseignant":
                    if (isset($_SESSION['enseignant']) && is_object($_SESSION['enseignant'])) {
                        $enseignant = $_SESSION['enseignant'];
                        $idEns = $enseignant->getId();

                        $qcms = $qcmMdl->getQcmByEns($idEns);
                        $questions = $questionMdl->questions($idEns);
                        $reponses = $reponseMdl->reponses();

                        $this->render("enseignant/index", [
                            "qcms" => $qcms,
                            "questions" => $questions,
                            "reponses" => $reponses
                        ]);
                        break;
                    } else {
                        header("Location: index.php?action=login_ens");
                        exit;
                    }

                case "login_ens":
                    if (isset($_POST['login_admin'])) {
                        $mail = $_POST['mail'] ?? '';
                        $mdpEns = $_POST['mdpEns'] ?? '';

                        $enseignant = $enseignantMdl->login($mail, $mdpEns);

                        if ($enseignant) {
                            $_SESSION['enseignant'] = $enseignant;
                            header("Location: index.php?action=enseignant");
                            exit;
                        } else {
                            $erreur = "Email ou mot de passe incorrect.";
                            $this->render("enseignant/login", ["erreur" => $erreur]);
                            return;
                        }
                    }

                    $this->render("enseignant/login");
                    break;

                case "add_admin":
                    if (isset($_POST['new_admin'])) {
                        $nom = $_POST['nom'] ?? '';
                        $mdp = $_POST['mdp'] ?? '';
                        $mail = $_POST['mail'] ?? '';

                        $enseignant = new Enseignant(0, $nom, $mdp, $mail);
                        $enseignantCree = $enseignantMdl->create($enseignant);

                        if ($enseignantCree) {
                            $_SESSION['enseignant'] = $enseignantCree;
                            header("Location: index.php?action=enseignant");
                            exit;
                        }
                    }

                    $this->render("enseignant/new");
                    break;

                case "logout":
                    unset($_SESSION['enseignant']);
                    session_destroy();
                    header("Location: index.php?action=login_ens");
                    exit;
            }
        }
    }
}