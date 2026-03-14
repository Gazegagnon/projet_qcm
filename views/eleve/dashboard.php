<?php
if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
    header("Location: index.php?action=login_eleve");
    exit;
}

$progression = $totalQcm > 0 ? round(($totalEffectues / $totalQcm) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard élève</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

    <div class="container">
        <div class="mb-4">
            <h2 class="fw-bold">Bienvenue <?= htmlspecialchars($eleve->getNom()) ?></h2>
            <p class="text-muted mb-0">Voici un aperçu de votre progression et de vos activités.</p>
        </div>

        <div class="row">
            <?php include "views/eleve/_menu.php"; ?>

            <div class="col-md-9">

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">QCM disponibles</p>
                                <h3 class="fw-bold"><?= $totalQcm ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">QCM effectués</p>
                                <h3 class="fw-bold"><?= $totalEffectues ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">QCM restants</p>
                                <h3 class="fw-bold"><?= $totalRestants ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">Moyenne</p>
                                <h3 class="fw-bold text-primary"><?= $moyenne ?>%</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">Meilleur score</p>
                                <h3 class="fw-bold text-success"><?= $meilleurScore ?>%</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">Pire score</p>
                                <h3 class="fw-bold text-danger"><?= $pireScore ?>%</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">QCM réussis</p>
                                <h3 class="fw-bold text-success"><?= $qcmReussis ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <p class="text-muted mb-1">QCM échoués</p>
                                <h3 class="fw-bold text-danger"><?= $qcmEchoues ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Progression globale</h5>
                        <div class="progress" style="height: 25px;">
                            <div
                                class="progress-bar bg-success"
                                role="progressbar"
                                style="width: <?= $progression ?>%;"
                                aria-valuenow="<?= $progression ?>"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            >
                                <?= $progression ?>%
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($dernierResultat) : ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="mb-2">Dernier résultat</h5>
                            <p class="mb-1">
                                Vous avez obtenu
                                <strong><?= $dernierResultat->getScore() ?>/<?= $dernierResultat->getTotal() ?></strong>
                            </p>
                            <p class="text-muted mb-0">
                                Date : <?= htmlspecialchars($dernierResultat->getDateResultat()) ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Évolution de vos résultats</h5>

                        <?php if (!empty($dataGraph)) : ?>
                            <canvas id="resultatsChart" height="100"></canvas>
                        <?php else : ?>
                            <div class="alert alert-info mb-0">
                                Aucun résultat disponible pour afficher un graphique.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="mb-3">Suggestions</h5>

                                <?php if ($totalRestants > 0) : ?>
                                    <p>
                                        Vous avez encore des QCM à passer. Continuez votre progression
                                        pour améliorer votre moyenne générale.
                                    </p>
                                    <a href="index.php?action=qcm_a_passer" class="btn btn-primary">
                                        Voir les QCM à passer
                                    </a>
                                <?php else : ?>
                                    <p>
                                        Bravo, vous avez terminé tous les QCM actuellement disponibles.
                                    </p>
                                    <a href="index.php?action=mes_resultats" class="btn btn-success">
                                        Voir mes résultats
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="mb-3">Résumé rapide</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0">
                                        Nom : <strong><?= htmlspecialchars($eleve->getNom()) ?></strong>
                                    </li>
                                    <li class="list-group-item px-0">
                                        Email : <strong><?= htmlspecialchars($eleve->getEmail()) ?></strong>
                                    </li>
                                    <li class="list-group-item px-0">
                                        Progression : <strong><?= $progression ?>%</strong>
                                    </li>
                                    <li class="list-group-item px-0">
                                        QCM réussis : <strong><?= $qcmReussis ?></strong>
                                    </li>
                                    <li class="list-group-item px-0">
                                        QCM échoués : <strong><?= $qcmEchoues ?></strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($qcms)) : ?>
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="mb-3">Vue rapide des QCM</h5>

                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Thème</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($qcms as $qcm) : ?>
                                        <tr>
                                            <td><?= $qcm->getId() ?></td>
                                            <td><?= htmlspecialchars($qcm->getTheme()) ?></td>
                                            <td>
                                                <?php if (isset($qcmDejaSoumis[$qcm->getId()])) : ?>
                                                    <span class="badge bg-success">Effectué</span>
                                                <?php else : ?>
                                                    <span class="badge bg-warning text-dark">À passer</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php if (!empty($dataGraph)) : ?>
    <script>
        const ctx = document.getElementById('resultatsChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labelsGraph) ?>,
                datasets: [{
                    label: 'Résultats en %',
                    data: <?= json_encode($dataGraph) ?>,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
    <?php endif; ?>

</body>
</html>