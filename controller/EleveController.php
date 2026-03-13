<?php

namespace App\Controller;

use App\Model\EleveModel;
use App\Model\QcmModel;
use App\Model\QuestionModel;
use App\Model\ReponseModel;
use App\Model\ResultatModel;
use App\Entity\Eleve;
use App\Entity\Resultat;

class EleveController extends Controller
{
    public function eleveHttps()
    {
        $eleveMdl = new EleveModel();
        $qcmMdl = new QcmModel();
        $questionMdl = new QuestionModel();
        $reponseMdl = new ReponseModel();
        $resultatMdl = new ResultatModel();

        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case "eleve":
                    if (isset($_SESSION['eleve']) && is_object($_SESSION['eleve'])) {
                        $eleve = $_SESSION['eleve'];
                        $qcms = $qcmMdl->qcms();

                        $this->render("eleve/index", [
                            "eleve" => $eleve,
                            "qcms" => $qcms
                        ]);
                        break;
                    } else {
                        header("Location: index.php?action=login_eleve");
                        exit;
                    }

                case "login_eleve":
                    if (isset($_POST['login_eleve'])) {
                        $mail = $_POST['mail'] ?? '';
                        $mdpEleve = $_POST['mdpEleve'] ?? '';

                        $eleve = $eleveMdl->login($mail, $mdpEleve);

                        if ($eleve) {
                            $_SESSION['eleve'] = $eleve;
                            header("Location: index.php?action=eleve");
                            exit;
                        } else {
                            $erreur = "Email ou mot de passe incorrect.";
                            $this->render("eleve/login", ["erreur" => $erreur]);
                            return;
                        }
                    }

                    $this->render("eleve/login");
                    break;

                case "add_eleve":
                    if (isset($_POST['new_eleve'])) {
                        $nom = $_POST['nom'] ?? '';
                        $mail = $_POST['mail'] ?? '';
                        $mdp = $_POST['mdp'] ?? '';

                        if ($eleveMdl->emailExiste($mail)) {
                            $erreur = "Cet email existe déjà.";
                            $this->render("eleve/new", ["erreur" => $erreur]);
                            return;
                        }

                        $eleve = new Eleve(0, $nom, $mdp, $mail);
                        $eleveCree = $eleveMdl->create($eleve);

                        if ($eleveCree) {
                            $_SESSION['eleve'] = $eleveCree;
                            header("Location: index.php?action=eleve");
                            exit;
                        }
                    }

                    $this->render("eleve/new");
                    break;

                case "questions_qcm":
                    if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                        header("Location: index.php?action=login_eleve");
                        exit;
                    }

                    $idQcm = $_GET['id'] ?? null;

                    if (!$idQcm) {
                        header("Location: index.php?action=eleve");
                        exit;
                    }

                    $qcm = $qcmMdl->Qcm($idQcm);
                    $questions = $questionMdl->getQuestionByQcm($idQcm);

                    $reponsesParQuestion = [];

                    foreach ($questions as $question) {
                        $reponsesParQuestion[$question->getId()] = $reponseMdl->getReponseByQuestion($question->getId());
                    }

                    $this->render("eleve/qcm", [
                        "qcm" => $qcm,
                        "questions" => $questions,
                        "reponsesParQuestion" => $reponsesParQuestion
                    ]);
                    break;

                case "corriger_qcm":
                    if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                        header("Location: index.php?action=login_eleve");
                        exit;
                    }

                    $idQcm = $_POST['idQcm'] ?? null;

                    if (!$idQcm) {
                        header("Location: index.php?action=eleve");
                        exit;
                    }

                    $eleve = $_SESSION['eleve'];
                    $questions = $questionMdl->getQuestionByQcm($idQcm);

                    $score = 0;
                    $total = 0;
                    $details = [];

                    foreach ($questions as $question) {
                        $total += $question->getPoints();

                        $reponseChoisie = $_POST['question_' . $question->getId()] ?? null;
                        $reponses = $reponseMdl->getReponseByQuestion($question->getId());

                        $bonne = false;
                        $bonneReponseTexte = null;
                        $reponseChoisieTexte = null;

                        foreach ($reponses as $reponse) {
                            if ($reponse->getBonneReponse() == 1) {
                                $bonneReponseTexte = $reponse->getReponsePropose();
                            }

                            if ($reponseChoisie == $reponse->getId()) {
                                $reponseChoisieTexte = $reponse->getReponsePropose();

                                if ($reponse->getBonneReponse() == 1) {
                                    $bonne = true;
                                }
                            }
                        }

                        if ($bonne) {
                            $score += $question->getPoints();
                        }

                        $details[] = [
                            "question" => $question,
                            "reponseChoisie" => $reponseChoisieTexte,
                            "bonneReponse" => $bonneReponseTexte,
                            "estBonne" => $bonne
                        ];
                    }

                    if (!$resultatMdl->resultatExiste($eleve->getId(), $idQcm)) {
                        $resultat = new Resultat(0, $score, $total, $eleve->getId(), $idQcm);
                        $resultatMdl->create($resultat);
                    }

                    $qcm = $qcmMdl->Qcm($idQcm);

                    $this->render("eleve/resultat", [
                        "qcm" => $qcm,
                        "score" => $score,
                        "total" => $total,
                        "details" => $details
                    ]);
                    break;

                case "mes_resultats":
                    if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                        header("Location: index.php?action=login_eleve");
                        exit;
                    }

                    $eleve = $_SESSION['eleve'];
                    $resultats = $resultatMdl->getResultatByEleve($eleve->getId());

                    $this->render("eleve/mes_resultats", [
                        "eleve" => $eleve,
                        "resultats" => $resultats
                    ]);
                    break;

                case "logout_eleve":
                    unset($_SESSION['eleve']);
                    session_destroy();
                    header("Location: index.php?action=login_eleve");
                    exit;
            }
        }
    }
}