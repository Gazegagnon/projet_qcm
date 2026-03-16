<?php
if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
    header("Location: index.php?action=login_ens");
    exit;
}

$totalQcm = $totalQcm ?? 0;
$totalEleves = $totalEleves ?? 0;
$moyenneClasse = $moyenneClasse ?? 0;
$totalPassages = $totalPassages ?? 0;
$totalEnAttente = $totalEnAttente ?? 0;
$totalReussis = $totalReussis ?? 0;
$totalQcmActifs = $totalQcmActifs ?? 0;
$totalQcmBrouillons = $totalQcmBrouillons ?? 0;

$qcms = $qcms ?? [];
$infosQcm = $infosQcm ?? [];
$elevesEnDifficulte = $elevesEnDifficulte ?? [];
$alertes = $alertes ?? [];
$labelsGraph = $labelsGraph ?? [];
$dataGraph = $dataGraph ?? [];

$tauxCompletion = ($totalQcm > 0 && $totalEleves > 0)
    ? round(($totalPassages / ($totalQcm * $totalEleves)) * 100, 2)
    : 0;

$tauxReussite = $totalPassages > 0
    ? round(($totalReussis / $totalPassages) * 100, 2)
    : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard enseignant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body { background: #f5f7fb; }

        .metric-card {
            background: #eef1f7;
            border-radius: 8px;
            padding: 14px 16px;
        }
        .metric-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .metric-value {
            font-size: 22px;
            font-weight: 500;
            color: #212529;
        }

        .dash-card {
            background: #fff;
            border: 0.5px solid rgba(0,0,0,0.08);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .section-title {
            font-size: 11px;
            font-weight: 500;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        .prog-wrap {
            height: 7px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
        }
        .prog-fill {
            height: 100%;
            border-radius: 4px;
        }

        .dash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .dash-table th {
            font-size: 11px;
            font-weight: 500;
            color: #6c757d;
            padding: 7px 8px;
            border-bottom: 0.5px solid rgba(0,0,0,0.08);
            text-align: left;
        }
        .dash-table td {
            padding: 9px 8px;
            border-bottom: 0.5px solid rgba(0,0,0,0.06);
            vertical-align: middle;
        }
        .dash-table tr:last-child td { border-bottom: none; }
        .dash-table tbody tr:hover td { background: #f8f9fa; }

        .avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1e40af;
            font-size: 11px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .tab-row {
            display: flex;
            gap: 4px;
            margin-bottom: 12px;
        }
        .tab-btn {
            font-size: 12px;
            padding: 4px 14px;
            border-radius: 20px;
            border: 0.5px solid rgba(0,0,0,0.12);
            background: transparent;
            color: #6c757d;
            cursor: pointer;
        }
        .tab-btn.active {
            background: #fff;
            color: #212529;
            border-color: rgba(0,0,0,0.25);
            font-weight: 500;
        }

        .alert-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="container-fluid py-4">
<div class="container">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-500 mb-1" style="font-weight:500;">
                Bonjour, <?= htmlspecialchars($enseignant->getNom()) ?>
            </h2>
            <p class="text-muted mb-0" style="font-size:13px;">
                Aperçu de vos classes et QCM.
            </p>
        </div>
        <a href="index.php?action=add_qcm" class="btn btn-outline-secondary btn-sm">
            + Nouveau QCM
        </a>
    </div>

    <div class="row">
        <?php include "views/enseignant/_menu.php"; ?>

        <div class="col-md-9">

            <div class="row g-2 mb-2">
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">QCM créés</div>
                        <div class="metric-value"><?= $totalQcm ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Élèves suivis</div>
                        <div class="metric-value"><?= $totalEleves ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Moyenne classe</div>
                        <div class="metric-value text-primary"><?= $moyenneClasse ?>%</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Taux de complétion</div>
                        <div class="metric-value text-success"><?= $tauxCompletion ?>%</div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Copies corrigées</div>
                        <div class="metric-value"><?= $totalPassages ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Résultats en attente</div>
                        <div class="metric-value" style="color:#b45309;"><?= $totalEnAttente ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Taux de réussite</div>
                        <div class="metric-value text-success"><?= $tauxReussite ?>%</div>
                    </div>
                </div>
            </div>

            <div class="dash-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="section-title mb-0">Complétion globale des QCM</div>
                    <span style="font-size:13px; font-weight:500; color:#15803d;"><?= $tauxCompletion ?>%</span>
                </div>
                <div class="prog-wrap">
                    <div class="prog-fill bg-success" style="width:<?= $tauxCompletion ?>%;"></div>
                </div>
            </div>

            <div class="dash-card mb-3">
                <div class="section-title">Évolution de la moyenne classe</div>
                <?php if (!empty($dataGraph)) : ?>
                    <div style="position:relative; width:100%; height:190px;">
                        <canvas id="moyenneChart"></canvas>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info mb-0">Aucune donnée disponible.</div>
                <?php endif; ?>
            </div>

            <div class="row g-3 mb-3">

                <div class="col-md-7">
                    <div class="dash-card h-100">
                        <div class="section-title">Mes QCM</div>

                        <div class="tab-row">
                            <button class="tab-btn active" onclick="switchTab('all', this)">Tous</button>
                            <button class="tab-btn" onclick="switchTab('actif', this)">Actifs</button>
                            <button class="tab-btn" onclick="switchTab('brouillon', this)">Brouillons</button>
                        </div>

                        <?php if (!empty($qcms)) : ?>
                            <table class="dash-table" id="qcmTable">
                                <thead>
                                    <tr>
                                        <th>Thème</th>
                                        <th>Questions</th>
                                        <th>Statut</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($qcms as $qcm) : ?>
                                        <?php
                                        $statut = method_exists($qcm, 'getStatut') ? $qcm->getStatut() : 'actif';
                                        $nbQuestions = $infosQcm[$qcm->getId()]['questions'] ?? 0;
                                        ?>
                                        <tr data-statut="<?= htmlspecialchars($statut) ?>">
                                            <td style="font-weight:500;">
                                                <?= htmlspecialchars($qcm->getTheme()) ?>
                                            </td>
                                            <td style="color:#6c757d;">
                                                <?= $nbQuestions ?>
                                            </td>
                                            <td>
                                                <?php if ($statut === 'actif') : ?>
                                                    <span class="badge bg-success-subtle text-success" style="font-size:11px;">Actif</span>
                                                <?php else : ?>
                                                    <span class="badge bg-warning-subtle text-warning" style="font-size:11px;">Brouillon</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="index.php?action=resultats_qcm&id=<?= $qcm->getId() ?>"
                                                   style="font-size:11px; color:#185FA5; text-decoration:none;">
                                                    Résultats →
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p class="text-muted" style="font-size:13px;">Aucun QCM créé.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="dash-card h-100">
                        <div class="section-title">Élèves en difficulté</div>

                        <?php if (!empty($elevesEnDifficulte)) : ?>
                            <table class="dash-table">
                                <tbody>
                                    <?php foreach ($elevesEnDifficulte as $el) : ?>
                                        <?php
                                        $nomEleve = method_exists($el, 'getNom') ? $el->getNom() : 'Élève';
                                        $prenomEleve = method_exists($el, 'getPrenom') ? $el->getPrenom() : '';
                                        $moyenneEleve = method_exists($el, 'getMoyenne') ? $el->getMoyenne() : 0;
                                        $initiales = strtoupper(substr($nomEleve, 0, 1) . substr($prenomEleve !== '' ? $prenomEleve : $nomEleve, 0, 1));
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar"><?= $initiales ?></div>
                                                    <span style="font-size:13px;">
                                                        <?= htmlspecialchars(trim($nomEleve . ' ' . $prenomEleve)) ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge <?= $moyenneEleve < 50 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' ?>"
                                                      style="font-size:11px;">
                                                    <?= $moyenneEleve ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?action=profil_eleve&id=<?= method_exists($el, 'getId') ? $el->getId() : 0 ?>"
                                                   style="font-size:11px; color:#185FA5; text-decoration:none;">
                                                    Voir →
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p class="text-muted" style="font-size:13px;">
                                Aucun élève en difficulté détecté.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row g-3">

                <div class="col-md-7">
                    <div class="dash-card">
                        <div class="section-title">Alertes récentes</div>

                        <?php if (!empty($alertes)) : ?>
                            <?php foreach ($alertes as $alerte) : ?>
                                <div class="d-flex align-items-start gap-2 py-2"
                                     style="border-bottom:0.5px solid rgba(0,0,0,0.06);">
                                    <div class="alert-dot mt-1"
                                         style="background:<?= htmlspecialchars($alerte['couleur'] ?? '#6c757d') ?>;"></div>
                                    <div>
                                        <div style="font-size:13px;">
                                            <?= htmlspecialchars($alerte['message'] ?? '') ?>
                                        </div>
                                        <div style="font-size:11px; color:#6c757d;">
                                            <?= htmlspecialchars($alerte['detail'] ?? '') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="text-muted" style="font-size:13px;">Aucune alerte.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="dash-card">
                        <div class="section-title">Résumé rapide</div>
                        <ul class="list-unstyled mb-0" style="font-size:13px;">
                            <li class="d-flex justify-content-between py-2"
                                style="border-bottom:0.5px solid rgba(0,0,0,0.06);">
                                <span class="text-muted">Nom</span>
                                <strong><?= htmlspecialchars($enseignant->getNom()) ?></strong>
                            </li>
                            <li class="d-flex justify-content-between py-2"
                                style="border-bottom:0.5px solid rgba(0,0,0,0.06);">
                                <span class="text-muted">Email</span>
                                <span style="color:#6c757d;">
                                    <?= htmlspecialchars($enseignant->getEmail()) ?>
                                </span>
                            </li>
                            <li class="d-flex justify-content-between py-2"
                                style="border-bottom:0.5px solid rgba(0,0,0,0.06);">
                                <span class="text-muted">Complétion</span>
                                <strong style="color:#15803d;"><?= $tauxCompletion ?>%</strong>
                            </li>
                            <li class="d-flex justify-content-between py-2"
                                style="border-bottom:0.5px solid rgba(0,0,0,0.06);">
                                <span class="text-muted">QCM actifs</span>
                                <strong><?= $totalQcmActifs ?></strong>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted">QCM brouillons</span>
                                <strong><?= $totalQcmBrouillons ?></strong>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if (!empty($dataGraph)) : ?>
<script>
    const ctx = document.getElementById('moyenneChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labelsGraph) ?>,
            datasets: [{
                label: 'Moyenne classe (%)',
                data: <?= json_encode($dataGraph) ?>,
                borderColor: '#185FA5',
                backgroundColor: 'rgba(24,95,165,0.07)',
                borderWidth: 2,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#185FA5',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 100,
                    ticks: {
                        font: { size: 11 },
                        color: '#aaa'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.04)'
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 },
                        color: '#aaa',
                        autoSkip: false
                    },
                    grid: { display: false }
                }
            }
        }
    });
</script>
<?php endif; ?>

<script>
    function switchTab(tab, el) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        el.classList.add('active');

        document.querySelectorAll('#qcmTable tbody tr').forEach(tr => {
            const statut = tr.dataset.statut;
            tr.style.display = (tab === 'all' || statut === tab) ? '' : 'none';
        });
    }
</script>
</body>
</html>