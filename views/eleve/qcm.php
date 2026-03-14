<?php
if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
    header("Location: index.php?action=login_eleve");
    exit;
}

$totalQuestions = count($questions);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Passer un QCM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">QCM : <?= htmlspecialchars($qcm->getTheme()) ?></h2>
        <a href="index.php?action=qcm_a_passer" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Progression de l'examen</h5>
            <div class="progress" style="height: 24px;">
                <div class="progress-bar bg-primary" style="width: 100%;">
                    <?= $totalQuestions ?> question(s)
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="index.php?action=corriger_qcm">
        <input type="hidden" name="idQcm" value="<?= $qcm->getId() ?>">

        <?php if (!empty($questions)) : ?>
            <?php foreach ($questions as $index => $question) : ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-2">Question <?= $index + 1 ?> / <?= $totalQuestions ?></h5>
                        <p class="fw-bold"><?= htmlspecialchars($question->getLibelle()) ?></p>
                        <p class="text-muted">Points : <?= $question->getPoints() ?></p>

                        <?php if (isset($reponsesParQuestion[$question->getId()])) : ?>
                            <?php foreach ($reponsesParQuestion[$question->getId()] as $reponse) : ?>
                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="question_<?= $question->getId() ?>"
                                        value="<?= $reponse->getId() ?>"
                                        id="rep_<?= $reponse->getId() ?>"
                                    >
                                    <label class="form-check-label" for="rep_<?= $reponse->getId() ?>">
                                        <?= htmlspecialchars($reponse->getReponsePropose()) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-success">Soumettre</button>
        <?php else : ?>
            <div class="alert alert-warning">Aucune question trouvée pour ce QCM.</div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>