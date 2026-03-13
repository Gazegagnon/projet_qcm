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
    <title>Passage du QCM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>QCM : <?= htmlspecialchars($qcm->getTheme()) ?></h2>
        <a href="index.php?action=eleve" class="btn btn-secondary">Retour</a>
    </div>

    <form method="post" action="index.php?action=corriger_qcm">
        <input type="hidden" name="idQcm" value="<?= $qcm->getId() ?>">

        <?php if (!empty($questions)) : ?>
            <?php foreach ($questions as $index => $question) : ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Question <?= $index + 1 ?> :</h5>
                        <p><strong><?= htmlspecialchars($question->getLibelle()) ?></strong></p>
                        <p>Points : <?= $question->getPoints() ?></p>

                        <?php if (isset($reponsesParQuestion[$question->getId()])) : ?>
                            <?php foreach ($reponsesParQuestion[$question->getId()] as $reponse) : ?>
                                <div class="form-check">
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

            <button type="submit" class="btn btn-success">Valider le QCM</button>
        <?php else : ?>
            <div class="alert alert-warning">Aucune question trouvée pour ce QCM.</div>
        <?php endif; ?>
    </form>

</body>
</html>