<?php
if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
    header("Location: index.php?action=login_ens");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes réponses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <h2 class="mb-4 fw-bold">Mes réponses</h2>

    <div class="row">
        <?php include "views/enseignant/_menu.php"; ?>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <?php if (!empty($reponsesEns)) : ?>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Réponse proposée</th>
                                    <th>Bonne réponse</th>
                                    <th>ID Question</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reponsesEns as $reponse) : ?>
                                    <tr>
                                        <td><?= $reponse->getId() ?></td>
                                        <td><?= htmlspecialchars($reponse->getReponsePropose()) ?></td>
                                        <td>
                                            <?php if ($reponse->getBonneReponse() == 1) : ?>
                                                <span class="badge bg-success">Oui</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $reponse->getIdQuestion() ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info mb-0">
                            Aucune réponse disponible.
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>