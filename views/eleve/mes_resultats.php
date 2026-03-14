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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <h2 class="mb-4 fw-bold">Mes résultats</h2>

    <div class="row">
        <?php include "views/eleve/_menu.php"; ?>

        <div class="col-md-9">

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Moyenne</p>
                            <h4 class="text-primary"><?= $moyenne ?>%</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Meilleur score</p>
                            <h4 class="text-success"><?= $meilleurScore ?>%</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Moins bon score</p>
                            <h4 class="text-danger"><?= $pireScore ?>%</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">QCM complétés</p>
                            <h4><?= $totalCompletes ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Évolution de vos résultats</h5>
                    <?php if (!empty($dataGraph)) : ?>
                        <canvas id="resultatsChart" height="100"></canvas>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">Aucun résultat disponible pour le graphique.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Moyenne par thème</h5>

                    <?php if (!empty($moyenneParTheme)) : ?>
                        <ul class="list-group">
                            <?php foreach ($moyenneParTheme as $theme => $moy) : ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($theme) ?>
                                    <span class="badge bg-primary"><?= $moy ?>%</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">Aucune moyenne par thème disponible.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php if (!empty($resultats)) : ?>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>QCM</th>
                                    <th>Score</th>
                                    <th>Total</th>
                                    <th>Pourcentage</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultats as $resultat) : ?>
                                    <?php
                                        $theme = isset($qcmParId[$resultat->getIdQcm()]) ? $qcmParId[$resultat->getIdQcm()]->getTheme() : 'QCM inconnu';
                                        $pourcentage = $resultat->getTotal() > 0 ? round(($resultat->getScore() / $resultat->getTotal()) * 100, 2) : 0;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($theme) ?></td>
                                        <td><?= $resultat->getScore() ?></td>
                                        <td><?= $resultat->getTotal() ?></td>
                                        <td><?= $pourcentage ?>%</td>
                                        <td><?= htmlspecialchars($resultat->getDateResultat()) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">Aucun résultat disponible.</div>
                    <?php endif; ?>
                </div>
            </div>

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
            label: 'Progression (%)',
            data: <?= json_encode($dataGraph) ?>,
            borderWidth: 2,
            tension: 0.3,
            fill: false
        }]
    },
    options: {
        responsive: true,
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