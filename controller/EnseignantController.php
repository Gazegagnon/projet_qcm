<?php

namespace App\Controller;

use App\Model\EnseignantModel;
use App\Model\QcmModel;
use App\Model\QuestionModel;
use App\Model\ReponseModel;
use App\Model\ResultatModel;
use App\Model\EleveModel;
use App\Entity\Enseignant;
use App\Entity\Qcm;
use App\Entity\Question;
use App\Entity\Reponse;

class EnseignantController extends Controller
{
    public function EnseiHttps()
    {
        $enseignantMdl = new EnseignantModel();
        $qcmMdl = new QcmModel();
        $questionMdl = new QuestionModel();
        $reponseMdl = new ReponseModel();
        $resultatMdl = new ResultatModel();
        $eleveMdl = new EleveModel();

        if (!isset($_GET['action'])) {
            return;
        }

        $action = $_GET['action'];

        switch ($action) {

            case "enseignant":
            case "dashboard_enseignant":
                header("Location: index.php?action=enseignant_dashboard");
                exit;

            case "enseignant_dashboard":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignant = $_SESSION['enseignant'];
                $idEns = $enseignant->getId();

                // ── QCM de l'enseignant ─────────────────────────────
                $qcms = $qcmMdl->getQcmByEns($idEns);
                $totalQcm = count($qcms);

                $totalQcmActifs = 0;
                $totalQcmBrouillons = 0;

                foreach ($qcms as $qcm) {
                    if (method_exists($qcm, 'getStatut')) {
                        if ($qcm->getStatut() === 'actif') {
                            $totalQcmActifs++;
                        } else {
                            $totalQcmBrouillons++;
                        }
                    } else {
                        // Si le statut n'existe pas encore, on considère tout comme actif
                        $totalQcmActifs++;
                    }
                }

                // ── Infos détaillées par QCM ────────────────────────
                $infosQcm = [];

                foreach ($qcms as $qcm) {
                    $questionsQcm = $questionMdl->getQuestionByQcm($qcm->getId());

                    $nbQuestions = count($questionsQcm);
                    $nbPoints = 0;

                    foreach ($questionsQcm as $question) {
                        $nbPoints += $question->getPoints();
                    }

                    $infosQcm[$qcm->getId()] = [
                        'questions' => $nbQuestions,
                        'points' => $nbPoints
                    ];
                }

                // ── Élèves ──────────────────────────────────────────
                $eleves = method_exists($eleveMdl, 'Eleves') ? $eleveMdl->Eleves() : [];
                $totalEleves = count($eleves);

                // ── Résultats des QCM de cet enseignant ────────────
                $resultatsEns = $resultatMdl->getResultatsByEnseignant($idEns);

                $totalPassages = count($resultatsEns);
                $totalReussis = $resultatMdl->countReussisParEnseignant($idEns);
                $totalEnAttente = $resultatMdl->countNonConsultesByEnseignant($idEns);
                $moyenneClasse = $resultatMdl->moyenneClasseByEnseignant($idEns);

                // ── Graphique évolution moyenne ─────────────────────
                $graphData = $resultatMdl->evolutionMoyenneByEnseignant($idEns);
                $labelsGraph = [];
                $dataGraph = [];

                foreach ($graphData as $ligne) {
                    $labelsGraph[] = $ligne['semaine'];
                    $dataGraph[] = $ligne['moyenne'];
                }

                // ── Élèves en difficulté ───────────────────────────
                $elevesEnDifficulte = [];

                if (!empty($eleves) && !empty($resultatsEns)) {
                    $statsEleves = [];

                    foreach ($resultatsEns as $resultat) {
                        $idEleve = $resultat->getIdEleve();
                        $score = $resultat->getScore();
                        $total = $resultat->getTotal();

                        $pourcentage = $total > 0 ? ($score / $total) * 100 : 0;

                        if (!isset($statsEleves[$idEleve])) {
                            $statsEleves[$idEleve] = [];
                        }

                        $statsEleves[$idEleve][] = $pourcentage;
                    }

                    foreach ($eleves as $eleve) {
                        $idEleve = $eleve->getId();

                        if (isset($statsEleves[$idEleve])) {
                            $moyenneEleve = round(array_sum($statsEleves[$idEleve]) / count($statsEleves[$idEleve]), 2);

                            if ($moyenneEleve < 60) {
                                $elevesEnDifficulte[] = new class($eleve, $moyenneEleve) {
                                    private $eleve;
                                    private $moyenne;

                                    public function __construct($eleve, $moyenne)
                                    {
                                        $this->eleve = $eleve;
                                        $this->moyenne = $moyenne;
                                    }

                                    public function getId()
                                    {
                                        return $this->eleve->getId();
                                    }

                                    public function getNom()
                                    {
                                        return method_exists($this->eleve, 'getNom') ? $this->eleve->getNom() : '';
                                    }

                                    public function getPrenom()
                                    {
                                        return method_exists($this->eleve, 'getPrenom') ? $this->eleve->getPrenom() : '';
                                    }

                                    public function getMoyenne()
                                    {
                                        return $this->moyenne;
                                    }
                                };
                            }
                        }
                    }
                }

                // ── Alertes ────────────────────────────────────────
                $alertes = [];

                if ($totalEnAttente > 0) {
                    $alertes[] = [
                        'message' => $totalEnAttente . " résultat(s) non consulté(s)",
                        'detail' => 'À consulter dans vos résultats',
                        'couleur' => '#b45309',
                    ];
                }

                if ($totalQcmBrouillons > 0) {
                    $alertes[] = [
                        'message' => $totalQcmBrouillons . " QCM en brouillon",
                        'detail' => 'Non visibles par les élèves',
                        'couleur' => '#185FA5',
                    ];
                }

                if (!empty($elevesEnDifficulte)) {
                    $alertes[] = [
                        'message' => count($elevesEnDifficulte) . " élève(s) en difficulté détecté(s)",
                        'detail' => 'Moyenne inférieure à 60%',
                        'couleur' => '#dc2626',
                    ];
                }

                if ($moyenneClasse > 0) {
                    $alertes[] = [
                        'message' => "Moyenne classe : " . $moyenneClasse . "%",
                        'detail' => 'Mise à jour en temps réel',
                        'couleur' => '#15803d',
                    ];
                }

                $this->render("enseignant/dashboard", [
                    "enseignant" => $enseignant,
                    "qcms" => $qcms,
                    "infosQcm" => $infosQcm,
                    "totalQcm" => $totalQcm,
                    "totalEleves" => $totalEleves,
                    "moyenneClasse" => $moyenneClasse,
                    "totalPassages" => $totalPassages,
                    "totalReussis" => $totalReussis,
                    "totalEnAttente" => $totalEnAttente,
                    "totalQcmActifs" => $totalQcmActifs,
                    "totalQcmBrouillons" => $totalQcmBrouillons,
                    "labelsGraph" => $labelsGraph,
                    "dataGraph" => $dataGraph,
                    "elevesEnDifficulte" => $elevesEnDifficulte,
                    "alertes" => $alertes
                ]);
                break;

            case "profil_enseignant":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignant = $_SESSION['enseignant'];

                $this->render("enseignant/profil", [
                    "enseignant" => $enseignant
                ]);
                break;

            case "modifier_profil_enseignant":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignantSession = $_SESSION['enseignant'];

                if (isset($_POST['update_enseignant'])) {
                    $nom = trim($_POST['nom'] ?? '');
                    $mail = trim($_POST['mail'] ?? '');
                    $mdp = trim($_POST['mdp'] ?? '');

                    if ($nom === '' || $mail === '' || $mdp === '') {
                        $_SESSION['erreur_enseignant'] = "Tous les champs sont obligatoires.";
                        header("Location: index.php?action=profil_enseignant");
                        exit;
                    }

                    $enseignant = new Enseignant(
                        $enseignantSession->getId(),
                        $nom,
                        $mdp,
                        $mail
                    );

                    $enseignantMaj = $enseignantMdl->update($enseignant);
                    $_SESSION['enseignant'] = $enseignantMaj;
                    $_SESSION['success_enseignant'] = "Profil mis à jour avec succès.";

                    header("Location: index.php?action=profil_enseignant");
                    exit;
                }

                header("Location: index.php?action=profil_enseignant");
                exit;

            case "qcm_enseignant":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignant = $_SESSION['enseignant'];
                $qcms = $qcmMdl->getQcmByEns($enseignant->getId());

                $this->render("enseignant/qcm", [
                    "enseignant" => $enseignant,
                    "qcms" => $qcms,
                    "questionMdl" => $questionMdl
                ]);
                break;

            case "questions_enseignant":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignant = $_SESSION['enseignant'];
                $qcms = $qcmMdl->getQcmByEns($enseignant->getId());
                $questions = $questionMdl->questions();

                $idsQcmEns = [];
                foreach ($qcms as $qcm) {
                    $idsQcmEns[] = $qcm->getId();
                }

                $questionsEns = [];
                foreach ($questions as $question) {
                    if (in_array($question->getIdQcm(), $idsQcmEns)) {
                        $questionsEns[] = $question;
                    }
                }

                $this->render("enseignant/questions", [
                    "enseignant" => $enseignant,
                    "questionsEns" => $questionsEns
                ]);
                break;

            case "reponses_enseignant":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignant = $_SESSION['enseignant'];
                $qcms = $qcmMdl->getQcmByEns($enseignant->getId());
                $questions = $questionMdl->questions();
                $reponses = $reponseMdl->reponses();

                $idsQcmEns = [];
                foreach ($qcms as $qcm) {
                    $idsQcmEns[] = $qcm->getId();
                }

                $idsQuestionEns = [];
                foreach ($questions as $question) {
                    if (in_array($question->getIdQcm(), $idsQcmEns)) {
                        $idsQuestionEns[] = $question->getId();
                    }
                }

                $reponsesEns = [];
                foreach ($reponses as $reponse) {
                    if (in_array($reponse->getIdQuestion(), $idsQuestionEns)) {
                        $reponsesEns[] = $reponse;
                    }
                }

                $this->render("enseignant/reponses", [
                    "enseignant" => $enseignant,
                    "reponsesEns" => $reponsesEns
                ]);
                break;

            case "login_enseignant":
            case "login_ens":
                if (isset($_POST['login_admin'])) {
                    $mail = $_POST['mail'] ?? '';
                    $mdpEns = $_POST['mdpEns'] ?? '';

                    $enseignant = $enseignantMdl->login($mail, $mdpEns);

                    if ($enseignant) {
                        $_SESSION['enseignant'] = $enseignant;
                        header("Location: index.php?action=enseignant_dashboard");
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
                        header("Location: index.php?action=enseignant_dashboard");
                        exit;
                    }
                }

                $this->render("enseignant/new");
                break;

            case "logout":
                unset($_SESSION['enseignant']);
                session_destroy();
                header("Location: index.php?action=login_enseignant");
                exit;
        
            case "add_qcm":
            case "creer_qcm":
                if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
                    header("Location: index.php?action=login_ens");
                    exit;
                }

                $enseignant = $_SESSION['enseignant'];
                $enseignants = [$enseignant];
                $errors = [];

                if (empty($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    if (
                        !isset($_POST['csrf_token']) ||
                        $_POST['csrf_token'] !== $_SESSION['csrf_token']
                    ) {
                        $errors[] = "Jeton CSRF invalide.";
                    }

                    $theme = trim($_POST['theme'] ?? '');
                    $enseignantId = (int)($_POST['enseignant_id'] ?? 0);
                    $questionsForm = $_POST['questions'] ?? [];
                    $actionSubmit = $_POST['action_submit'] ?? 'brouillon';

                    if ($theme === '') {
                        $errors[] = "Le thème est obligatoire.";
                    }

                    if ($enseignantId <= 0) {
                        $errors[] = "L'enseignant est obligatoire.";
                    }

                    if (empty($questionsForm)) {
                        $errors[] = "Ajoutez au moins une question.";
                    }

                    foreach ($questionsForm as $index => $questionData) {
                        $enonce = trim($questionData['enonce'] ?? '');
                        $choix = $questionData['choix'] ?? [];
                        $correct = $questionData['correct'] ?? null;

                        if ($enonce === '') {
                            $errors[] = "L'énoncé de la question {$index} est vide.";
                        }

                        $choixValides = [];
                        foreach ($choix as $choixTexte) {
                            if (trim($choixTexte) !== '') {
                                $choixValides[] = trim($choixTexte);
                            }
                        }

                        if (count($choixValides) < 2) {
                            $errors[] = "La question {$index} doit contenir au moins 2 choix.";
                        }

                        if ($correct === null || $correct === '') {
                            $errors[] = "La question {$index} doit avoir une bonne réponse sélectionnée.";
                        }
                    }

                    if (empty($errors)) {
                        // Création du QCM
                        $qcm = new \App\Entity\Qcm(
                            0,
                            $theme,
                            $enseignantId
                        );

                        $qcmCree = $qcmMdl->create($qcm);

                        // Création des questions + réponses
                        foreach ($questionsForm as $questionData) {
                            $enonce = trim($questionData['enonce'] ?? '');
                            $points = (int)($questionData['points'] ?? 1);
                            $choix = $questionData['choix'] ?? [];
                            $correct = (int)($questionData['correct'] ?? 0);

                            if ($enonce === '') {
                                continue;
                            }

                            $question = new \App\Entity\Question(
                                0,
                                $enonce,
                                $points,
                                $qcmCree->getId()
                            );

                            $questionCree = $questionMdl->create($question);

                            foreach ($choix as $i => $texteChoix) {
                                $texteChoix = trim($texteChoix);

                                if ($texteChoix === '') {
                                    continue;
                                }

                                $bonneReponse = (($i + 1) === $correct) ? 1 : 0;

                                $reponse = new \App\Entity\Reponse(
                                    0,
                                    $texteChoix,
                                    $bonneReponse,
                                    $questionCree->getId()
                                );

                                $reponseMdl->create($reponse);
                            }
                        }

                        $_SESSION['success_qcm'] = $actionSubmit === 'publier'
                            ? "Le QCM a été créé et publié avec succès."
                            : "Le QCM a été enregistré avec succès.";

                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                        header("Location: index.php?action=qcm_enseignant");
                        exit;
                    }
                }

                $this->render("enseignant/qcm_new", [
                    "enseignants" => $enseignants,
                    "errors" => $errors
                ]);
                break;
            }
    }
}