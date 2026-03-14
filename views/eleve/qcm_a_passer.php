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
    <title>QCM à passer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <h2 class="mb-4 fw-bold">QCM à passer</h2>

    <div class="row">
        <?php include "views/eleve/_menu.php"; ?>

        <div class="col-md-9">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Progression globale</h5>
                    <div class="progress" style="height: 24px;">
                        <div class="progress-bar bg-success" style="width: <?= $progression ?>%;">
                            <?= $progression ?>%
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Recherche et filtres</h5>

                    <form method="get" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="qcm_a_passer">

                        <div class="col-md-4">
                            <label class="form-label">Recherche par thème</label>
                            <input type="text" name="theme" class="form-control" value="<?= htmlspecialchars($searchTheme) ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Filtre par enseignant</label>
                            <select name="enseignant" class="form-select">
                                <option value="">Tous</option>
                                <?php foreach ($enseignantsDisponibles as $ens) : ?>
                                    <option value="<?= htmlspecialchars($ens) ?>" <?= $filterEns === $ens ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ens) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tri</label>
                            <select name="tri" class="form-select">
                                <option value="">Aucun</option>
                                <option value="theme" <?= $sort === 'theme' ? 'selected' : '' ?>>Par thème</option>
                                <option value="questions" <?= $sort === 'questions' ? 'selected' : '' ?>>Par nombre de questions</option>
                                <option value="points" <?= $sort === 'points' ? 'selected' : '' ?>>Par total de points</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="index.php?action=qcm_a_passer" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <?php if (!empty($qcmsRestants)) : ?>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thème</th>
                                    <th>Enseignant</th>
                                    <th>Questions</th>
                                    <th>Points</th>
                                    <th>Badge</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($qcmsRestants as $qcm) : ?>
                                    <tr>
                                        <td><?= $qcm->getId() ?></td>
                                        <td><?= htmlspecialchars($qcm->getTheme()) ?></td>
                                        <td><?= htmlspecialchars($infosQcm[$qcm->getId()]['enseignant']) ?></td>
                                        <td><?= $infosQcm[$qcm->getId()]['questions'] ?></td>
                                        <td><?= $infosQcm[$qcm->getId()]['points'] ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark">Nouveau</span>
                                        </td>
                                        <td>
                                            <a href="index.php?action=questions_qcm&id=<?= $qcm->getId() ?>" class="btn btn-primary btn-sm">
                                                Commencer
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-success mb-0">
                            Aucun QCM restant avec ces filtres, ou tous les QCM ont déjà été passés.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>