<?php
if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
    header("Location: index.php?action=login_eleve");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du QCM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Résultat - <?= htmlspecialchars($qcm->getTheme()) ?></h2>
        <a href="index.php?action=eleve" class="btn btn-secondary">Retour à l'accueil</a>
    </div>

    <div class="alert alert-info">
        <strong>Score :</strong> <?= $score ?> / <?= $total ?>
    </div>

    <?php foreach ($details as $detail) : ?>
        <div class="card mb-3">
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

                <?php if ($detail['estBonne']) : ?>
                    <span class="badge bg-success">Correct</span>
                <?php else : ?>
                    <span class="badge bg-danger">Incorrect</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

</body>
</html>