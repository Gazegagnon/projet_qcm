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
    <title>QCM effectués</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <h2 class="mb-4 fw-bold">QCM effectués</h2>

    <div class="row">
        <?php include "views/eleve/_menu.php"; ?>

        <div class="col-md-9">
            <?php if (isset($_SESSION['message_qcm'])) : ?>
                <div class="alert alert-warning">
                    <?= htmlspecialchars($_SESSION['message_qcm']) ?>
                </div>
                <?php unset($_SESSION['message_qcm']); ?>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php if (!empty($resultats)) : ?>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>QCM</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Total</th>
                                    <th>Pourcentage</th>
                                    <th>Statut</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultats as $resultat) : ?>
                                    <?php
                                        $theme = isset($qcmParId[$resultat->getIdQcm()]) ? $qcmParId[$resultat->getIdQcm()]->getTheme() : 'QCM inconnu';
                                        $pourcentage = $resultat->getTotal() > 0 ? round(($resultat->getScore() / $resultat->getTotal()) * 100, 2) : 0;

                                        if ($pourcentage >= 70) {
                                            $statut = "Réussi";
                                            $badge = "bg-success";
                                        } elseif ($pourcentage >= 50) {
                                            $statut = "Moyen";
                                            $badge = "bg-warning text-dark";
                                        } else {
                                            $statut = "Faible";
                                            $badge = "bg-danger";
                                        }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($theme) ?></td>
                                        <td><?= htmlspecialchars($resultat->getDateResultat()) ?></td>
                                        <td><?= $resultat->getScore() ?></td>
                                        <td><?= $resultat->getTotal() ?></td>
                                        <td><?= $pourcentage ?>%</td>
                                        <td><span class="badge <?= $badge ?>"><?= $statut ?></span></td>
                                        <td>
                                            <a href="index.php?action=voir_detail_qcm_effectue&id=<?= $resultat->getIdQcm() ?>" class="btn btn-outline-primary btn-sm">
                                                Voir détail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">
                            Aucun QCM effectué pour le moment.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>