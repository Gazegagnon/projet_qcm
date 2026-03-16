<?php
if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
    header("Location: index.php?action=login_ens");
    exit;
}

$search = trim($_GET['search'] ?? '');
$questionsFiltrees = [];

if (!empty($questionsEns)) {
    foreach ($questionsEns as $question) {
        if ($search === '' || stripos($question->getLibelle(), $search) !== false) {
            $questionsFiltrees[] = $question;
        }
    }
}

$totalQuestions = count($questionsEns);
$totalAffichees = count($questionsFiltrees);
$totalPoints = 0;

foreach ($questionsEns as $question) {
    $totalPoints += (float) $question->getPoints();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes questions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Mes questions</h2>
            <p class="text-muted mb-0">Consultez et gérez toutes les questions rattachées à vos QCM.</p>
        </div>
    </div>

    <div class="row">
        <?php include "views/enseignant/_menu.php"; ?>

        <div class="col-md-9">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Nombre total de questions</p>
                            <h3 class="fw-bold"><?= $totalQuestions ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Total des points</p>
                            <h3 class="fw-bold text-primary"><?= $totalPoints ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Recherche</h5>

                    <form method="get" action="index.php" class="row g-3">
                        <input type="hidden" name="action" value="questions_enseignant">

                        <div class="col-md-9">
                            <label class="form-label">Rechercher par libellé</label>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="<?= htmlspecialchars($search) ?>"
                                placeholder="Ex : Définition, calcul, syntaxe..."
                            >
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <div class="w-100">
                                <button type="submit" class="btn btn-primary w-100 mb-2">Rechercher</button>
                                <a href="index.php?action=questions_enseignant" class="btn btn-secondary w-100">Réinitialiser</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Liste de mes questions</h5>
                        <span class="text-muted" style="font-size: 14px;">
                            <?= $totalAffichees ?> affichée(s) / <?= $totalQuestions ?>
                        </span>
                    </div>

                    <?php if (!empty($questionsFiltrees)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Libellé</th>
                                        <th>Points</th>
                                        <th>ID QCM</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($questionsFiltrees as $question) : ?>
                                        <tr>
                                            <td><?= $question->getId() ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($question->getLibelle()) ?></strong>
                                            </td>
                                            <td><?= $question->getPoints() ?></td>
                                            <td><?= $question->getIdQcm() ?></td>
                                            <td>
                                                <a href="index.php?action=add_Reponse&id=<?= $question->getId() ?>" class="btn btn-success btn-sm">
                                                    Ajouter réponse
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">
                            Aucune question disponible pour cette recherche.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>