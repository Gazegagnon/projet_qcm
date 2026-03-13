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
    <title>Mes résultats</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mes résultats</h2>
        <a href="index.php?action=eleve" class="btn btn-secondary">Retour</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID résultat</th>
                <th>ID QCM</th>
                <th>Score</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($resultats)) : ?>
                <?php foreach ($resultats as $resultat) : ?>
                    <tr>
                        <td><?= $resultat->getId() ?></td>
                        <td><?= $resultat->getIdQcm() ?></td>
                        <td><?= $resultat->getScore() ?></td>
                        <td><?= $resultat->getTotal() ?></td>
                        <td><?= htmlspecialchars($resultat->getDateResultat()) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Aucun résultat disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>