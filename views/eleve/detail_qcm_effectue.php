<?php
if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
    header("Location: index.php?action=login_eleve");
    exit;
}

$pourcentage = $resultat->getTotal() > 0 ? round(($resultat->getScore() / $resultat->getTotal()) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du QCM effectué</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Détail - <?= htmlspecialchars($qcm->getTheme()) ?></h2>
        <a href="index.php?action=qcm_effectues" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h4>Score obtenu : <?= $resultat->getScore() ?> / <?= $resultat->getTotal() ?> (<?= $pourcentage ?>%)</h4>
            <p class="text-muted mb-0">Date : <?= htmlspecialchars($resultat->getDateResultat()) ?></p>
        </div>
    </div>

    <?php foreach ($details as $detail) : ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5><?= htmlspecialchars($detail['question']->getLibelle()) ?></h5>

                <p>
                    <strong>Votre réponse :</strong>
                    <?= $detail['reponseChoisie'] ? htmlspecialchars($detail['reponseChoisie']) : 'Aucune réponse' ?>
                </p>

                <p>
                    <strong>Bonne réponse :</strong>
                    <?= htmlspecialchars($detail['bonneReponse']) ?>
                </p>

                <p>
                    <strong>Points obtenus :</strong>
                    <?= $detail['pointsObtenus'] ?> / <?= $detail['question']->getPoints() ?>
                </p>

                <?php if ($detail['estCorrecte']) : ?>
                    <span class="badge bg-success">Correct</span>
                <?php else : ?>
                    <span class="badge bg-danger">Incorrect</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>