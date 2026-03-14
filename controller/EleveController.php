<?php

namespace App\Controller;

use App\Model\EleveModel;
use App\Model\QcmModel;
use App\Model\QuestionModel;
use App\Model\ReponseModel;
use App\Model\ResultatModel;
use App\Entity\Eleve;
use App\Entity\Resultat;
use App\Model\DetailResultatModel;
use App\Entity\DetailResultat;

class EleveController extends Controller
{
    public function eleveHttps()
    {
        $eleveMdl = new EleveModel();
        $qcmMdl = new QcmModel();
        $questionMdl = new QuestionModel();
        $reponseMdl = new ReponseModel();
        $resultatMdl = new ResultatModel();
        $detailResultatMdl = new DetailResultatModel();

        if (!isset($_GET['action'])) {
            return;
        }

        $action = $_GET['action'];

        switch ($action) {
            case "eleve":
                header("Location: index.php?action=eleve_dashboard");
                exit;

            case "eleve_dashboard":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $eleve = $_SESSION['eleve'];
                $qcms = $qcmMdl->qcms();
                $resultats = $resultatMdl->getResultatByEleve($eleve->getId());

                $totalQcm = count($qcms);
                $totalEffectues = count($resultats);
                $totalRestants = $totalQcm - $totalEffectues;
                $moyenne = $resultatMdl->getMoyenneByEleve($eleve->getId());
                $dernierResultat = $resultatMdl->getDernierResultatByEleve($eleve->getId());

                $meilleurScore = $resultatMdl->getMeilleurScore($eleve->getId());
                $pireScore = $resultatMdl->getPireScore($eleve->getId());
                $qcmReussis = $resultatMdl->countReussis($eleve->getId());
                $qcmEchoues = $resultatMdl->countEchoues($eleve->getId());
                

                $qcmDejaSoumis = [];
                foreach ($resultats as $resultat) {
                    $qcmDejaSoumis[$resultat->getIdQcm()] = true;
                }

                $labelsGraph = [];
                $dataGraph = [];

                foreach (array_reverse($resultats) as $resultat) {
                    $labelsGraph[] = "QCM " . $resultat->getIdQcm();

                    $pourcentage = $resultat->getTotal() > 0
                        ? round(($resultat->getScore() / $resultat->getTotal()) * 100, 2)
                        : 0;

                    $dataGraph[] = $pourcentage;
                }

                $this->render("eleve/dashboard", [
                    "eleve" => $eleve,
                    "totalQcm" => $totalQcm,
                    "totalEffectues" => $totalEffectues,
                    "totalRestants" => $totalRestants,
                    "moyenne" => $moyenne,
                    "dernierResultat" => $dernierResultat,
                    "qcms" => $qcms,
                    "qcmDejaSoumis" => $qcmDejaSoumis,
                    "meilleurScore" => $meilleurScore,
                    "pireScore" => $pireScore,
                    "qcmReussis" => $qcmReussis,
                    "qcmEchoues" => $qcmEchoues,
                    "labelsGraph" => $labelsGraph,
                    "dataGraph" => $dataGraph
                ]);
                break;

            case "profil_eleve":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $eleve = $_SESSION['eleve'];

                $totalEffectues = $resultatMdl->countResultatByEleve($eleve->getId());
                $moyenne = $resultatMdl->getMoyenneByEleve($eleve->getId());
                $meilleurScore = $resultatMdl->getMeilleurScore($eleve->getId());
                $qcmReussis = $resultatMdl->countReussis($eleve->getId());

                $this->render("eleve/profil", [
                    "eleve" => $eleve,
                    "totalEffectues" => $totalEffectues,
                    "moyenne" => $moyenne,
                    "meilleurScore" => $meilleurScore,
                    "qcmReussis" => $qcmReussis
                ]);
                break;

            case "modifier_profil_eleve":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $eleveSession = $_SESSION['eleve'];

                if (isset($_POST['update_eleve'])) {
                    $nom = trim($_POST['nom'] ?? '');
                    $mail = trim($_POST['mail'] ?? '');
                    $mdp = trim($_POST['mdp'] ?? '');

                    if ($nom === '' || $mail === '' || $mdp === '') {
                        $_SESSION['erreur_eleve'] = "Tous les champs sont obligatoires.";
                        header("Location: index.php?action=profil_eleve");
                        exit;
                    }

                    if ($eleveMdl->emailExistePourAutreEleve($mail, $eleveSession->getId())) {
                        $_SESSION['erreur_eleve'] = "Cet email est déjà utilisé par un autre élève.";
                        header("Location: index.php?action=profil_eleve");
                        exit;
                    }

                    $eleve = new Eleve(
                        $eleveSession->getId(),
                        $nom,
                        $mdp,
                        $mail,
                        $eleveSession->getDateInscription(),
                        $eleveSession->getPhoto()
                    );

                    $eleveMaj = $eleveMdl->update($eleve);
                    $_SESSION['eleve'] = $eleveMaj;
                    $_SESSION['success_eleve'] = "Profil mis à jour avec succès.";

                    header("Location: index.php?action=profil_eleve");
                    exit;
                }

                header("Location: index.php?action=profil_eleve");
                exit;

            case "qcm_a_passer":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $eleve = $_SESSION['eleve'];
                $qcms = $qcmMdl->qcms();
                $resultats = $resultatMdl->getResultatByEleve($eleve->getId());

                $searchTheme = trim($_GET['theme'] ?? '');
                $filterEns = trim($_GET['enseignant'] ?? '');
                $sort = trim($_GET['tri'] ?? '');

                $qcmDejaSoumis = [];
                foreach ($resultats as $resultat) {
                    $qcmDejaSoumis[$resultat->getIdQcm()] = true;
                }

                $qcmsRestants = [];
                $infosQcm = [];
                $enseignantsDisponibles = [];

                foreach ($qcms as $qcm) {
                    if (!isset($qcmDejaSoumis[$qcm->getId()])) {
                        $nomEns = $qcmMdl->getNomEnseignantByQcm($qcm->getId());
                        $nbQuestions = $questionMdl->countQuestionsByQcm($qcm->getId());
                        $totalPoints = $questionMdl->totalPointsByQcm($qcm->getId());

                        $enseignantsDisponibles[$nomEns] = $nomEns;

                        if ($searchTheme !== '' && stripos($qcm->getTheme(), $searchTheme) === false) {
                            continue;
                        }

                        if ($filterEns !== '' && $filterEns !== $nomEns) {
                            continue;
                        }

                        $qcmsRestants[] = $qcm;
                        $infosQcm[$qcm->getId()] = [
                            "enseignant" => $nomEns,
                            "questions" => $nbQuestions,
                            "points" => $totalPoints,
                            "nouveau" => true
                        ];
                    }
                }

                if ($sort === 'questions') {
                    usort($qcmsRestants, function ($a, $b) use ($infosQcm) {
                        return $infosQcm[$b->getId()]['questions'] <=> $infosQcm[$a->getId()]['questions'];
                    });
                } elseif ($sort === 'points') {
                    usort($qcmsRestants, function ($a, $b) use ($infosQcm) {
                        return $infosQcm[$b->getId()]['points'] <=> $infosQcm[$a->getId()]['points'];
                    });
                } elseif ($sort === 'theme') {
                    usort($qcmsRestants, function ($a, $b) {
                        return strcmp($a->getTheme(), $b->getTheme());
                    });
                }

                $totalQcm = count($qcmMdl->qcms());
                $totalEffectues = count($resultats);
                $progression = $totalQcm > 0 ? round(($totalEffectues / $totalQcm) * 100, 2) : 0;

                $this->render("eleve/qcm_a_passer", [
                    "eleve" => $eleve,
                    "qcmsRestants" => $qcmsRestants,
                    "infosQcm" => $infosQcm,
                    "enseignantsDisponibles" => $enseignantsDisponibles,
                    "searchTheme" => $searchTheme,
                    "filterEns" => $filterEns,
                    "sort" => $sort,
                    "progression" => $progression
                ]);
                break;

            case "qcm_effectues":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $eleve = $_SESSION['eleve'];
                $resultats = $resultatMdl->getResultatByEleve($eleve->getId());
                $qcms = $qcmMdl->qcms();

                $qcmParId = [];
                foreach ($qcms as $qcm) {
                    $qcmParId[$qcm->getId()] = $qcm;
                }

                $this->render("eleve/qcm_effectues", [
                    "eleve" => $eleve,
                    "resultats" => $resultats,
                    "qcmParId" => $qcmParId
                ]);
                break;

            case "mes_resultats":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $eleve = $_SESSION['eleve'];
                $resultats = $resultatMdl->getResultatByEleve($eleve->getId());
                $qcms = $qcmMdl->qcms();

                $qcmParId = [];
                foreach ($qcms as $qcm) {
                    $qcmParId[$qcm->getId()] = $qcm;
                }

                $moyenne = $resultatMdl->getMoyenneByEleve($eleve->getId());
                $meilleurScore = $resultatMdl->getMeilleurScore($eleve->getId());
                $pireScore = $resultatMdl->getPireScore($eleve->getId());
                $totalCompletes = $resultatMdl->countResultatByEleve($eleve->getId());

                $labelsGraph = [];
                $dataGraph = [];
                $moyenneParTheme = [];

                foreach (array_reverse($resultats) as $resultat) {
                    $theme = isset($qcmParId[$resultat->getIdQcm()]) ? $qcmParId[$resultat->getIdQcm()]->getTheme() : 'QCM inconnu';
                    $pourcentage = $resultat->getTotal() > 0 ? round(($resultat->getScore() / $resultat->getTotal()) * 100, 2) : 0;

                    $labelsGraph[] = $theme;
                    $dataGraph[] = $pourcentage;

                    if (!isset($moyenneParTheme[$theme])) {
                        $moyenneParTheme[$theme] = [];
                    }

                    $moyenneParTheme[$theme][] = $pourcentage;
                }

                foreach ($moyenneParTheme as $theme => $notes) {
                    $moyenneParTheme[$theme] = round(array_sum($notes) / count($notes), 2);
                }

                $this->render("eleve/mes_resultats", [
                    "eleve" => $eleve,
                    "resultats" => $resultats,
                    "qcmParId" => $qcmParId,
                    "moyenne" => $moyenne,
                    "meilleurScore" => $meilleurScore,
                    "pireScore" => $pireScore,
                    "totalCompletes" => $totalCompletes,
                    "labelsGraph" => $labelsGraph,
                    "dataGraph" => $dataGraph,
                    "moyenneParTheme" => $moyenneParTheme
                ]);
                break;

            case "questions_qcm":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $idQcm = $_GET['id'] ?? null;

                if (!$idQcm) {
                    header("Location: index.php?action=qcm_a_passer");
                    exit;
                }

                $eleve = $_SESSION['eleve'];

                if ($resultatMdl->resultatExiste($eleve->getId(), $idQcm)) {
                    $_SESSION['message_qcm'] = "Vous avez déjà soumis ce QCM.";
                    header("Location: index.php?action=qcm_effectues");
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
                    header("Location: index.php?action=qcm_a_passer");
                    exit;
                }

                $eleve = $_SESSION['eleve'];

                if ($resultatMdl->resultatExiste($eleve->getId(), $idQcm)) {
                    $_SESSION['message_qcm'] = "Ce QCM a déjà été soumis.";
                    header("Location: index.php?action=qcm_effectues");
                    exit;
                }

                $questions = $questionMdl->getQuestionByQcm($idQcm);

                $score = 0;
                $total = 0;
                $details = [];
                $detailsAEnregistrer = [];

                foreach ($questions as $question) {
                    $total += $question->getPoints();

                    $idReponseChoisie = $_POST['question_' . $question->getId()] ?? null;
                    $reponses = $reponseMdl->getReponseByQuestion($question->getId());

                    $bonne = false;
                    $bonneReponseTexte = null;
                    $reponseChoisieTexte = null;
                    $pointsObtenus = 0;

                    foreach ($reponses as $reponse) {
                        if ($reponse->getBonneReponse() == 1) {
                            $bonneReponseTexte = $reponse->getReponsePropose();
                        }

                        if ($idReponseChoisie == $reponse->getId()) {
                            $reponseChoisieTexte = $reponse->getReponsePropose();

                            if ($reponse->getBonneReponse() == 1) {
                                $bonne = true;
                                $pointsObtenus = $question->getPoints();
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
                        "estBonne" => $bonne,
                        "pointsObtenus" => $pointsObtenus
                    ];

                    $detailsAEnregistrer[] = [
                        "idQuestion" => $question->getId(),
                        "idReponseChoisie" => $idReponseChoisie,
                        "estCorrecte" => $bonne ? 1 : 0,
                        "pointsObtenus" => $pointsObtenus
                    ];
                }

                $resultat = new Resultat(0, $score, $total, $eleve->getId(), $idQcm);
                $resultatCree = $resultatMdl->create($resultat);

                foreach ($detailsAEnregistrer as $detail) {
                    $detailResultat = new DetailResultat(
                        0,
                        $resultatCree->getId(),
                        $detail['idQuestion'],
                        $detail['idReponseChoisie'],
                        $detail['estCorrecte'],
                        $detail['pointsObtenus']
                    );

                    $detailResultatMdl->create($detailResultat);
                }

                $qcm = $qcmMdl->Qcm($idQcm);

                $this->render("eleve/resultat", [
                    "qcm" => $qcm,
                    "score" => $score,
                    "total" => $total,
                    "details" => $details
                ]);
                break;

            case "voir_detail_qcm_effectue":
                if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
                    header("Location: index.php?action=login_eleve");
                    exit;
                }

                $idQcm = $_GET['id'] ?? null;
                $eleve = $_SESSION['eleve'];

                if (!$idQcm) {
                    header("Location: index.php?action=qcm_effectues");
                    exit;
                }

                $resultat = $resultatMdl->getResultatByEleveAndQcm($eleve->getId(), $idQcm);

                if (!$resultat) {
                    header("Location: index.php?action=qcm_effectues");
                    exit;
                }

                $qcm = $qcmMdl->Qcm($idQcm);
                $questions = $questionMdl->getQuestionByQcm($idQcm);
                $detailsSauvegardes = $detailResultatMdl->getDetailsByResultat($resultat->getId());

                $detailsParQuestion = [];
                foreach ($detailsSauvegardes as $detail) {
                    $detailsParQuestion[$detail->getIdQuestion()] = $detail;
                }

                $details = [];

                foreach ($questions as $question) {
                    $reponses = $reponseMdl->getReponseByQuestion($question->getId());

                    $bonneReponseTexte = null;
                    $reponseChoisieTexte = null;
                    $estCorrecte = false;
                    $pointsObtenus = 0;

                    foreach ($reponses as $reponse) {
                        if ($reponse->getBonneReponse() == 1) {
                            $bonneReponseTexte = $reponse->getReponsePropose();
                        }

                        if (
                            isset($detailsParQuestion[$question->getId()]) &&
                            $detailsParQuestion[$question->getId()]->getIdReponseChoisie() == $reponse->getId()
                        ) {
                            $reponseChoisieTexte = $reponse->getReponsePropose();
                        }
                    }

                    if (isset($detailsParQuestion[$question->getId()])) {
                        $estCorrecte = $detailsParQuestion[$question->getId()]->getEstCorrecte();
                        $pointsObtenus = $detailsParQuestion[$question->getId()]->getPointsObtenus();
                    }

                    $details[] = [
                        "question" => $question,
                        "reponseChoisie" => $reponseChoisieTexte,
                        "bonneReponse" => $bonneReponseTexte,
                        "estCorrecte" => $estCorrecte,
                        "pointsObtenus" => $pointsObtenus
                    ];
                }

                $this->render("eleve/detail_qcm_effectue", [
                    "qcm" => $qcm,
                    "resultat" => $resultat,
                    "details" => $details
                ]);
                break;

            case "login_eleve":
                if (isset($_POST['login_eleve'])) {
                    $mail = $_POST['mail'] ?? '';
                    $mdpEleve = $_POST['mdpEleve'] ?? '';

                    $eleve = $eleveMdl->login($mail, $mdpEleve);

                    if ($eleve) {
                        $_SESSION['eleve'] = $eleve;
                        header("Location: index.php?action=eleve_dashboard");
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
                        header("Location: index.php?action=eleve_dashboard");
                        exit;
                    }
                }

                $this->render("eleve/new");
                break;

            case "logout_eleve":
                unset($_SESSION['eleve']);
                session_destroy();
                header("Location: index.php?action=login_eleve");
                exit;
        }
    }
}