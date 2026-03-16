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
    <title>Mes QCM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>

<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <h2 class="mb-4 fw-bold">Mes QCM</h2>

    <div class="row">
        <?php include "views/enseignant/_menu.php"; ?>

        <div class="col-md-9">

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Liste de mes QCM</h5>

                    <a href="index.php?action=add_qcm" class="btn btn-primary mb-3">
                        + Créer un nouveau QCM
                    </a>

                    <?php if (!empty($qcms)) : ?>
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thème</th>
                                    <th>Questions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($qcms as $qcm) : ?>
                                    <tr>
                                        <td><?= $qcm->getId() ?></td>
                                        <td><?= htmlspecialchars($qcm->getTheme()) ?></td>
                                        <td><?= $questionMdl->countQuestionsByQcm($qcm->getId()) ?></td>
                                        <td>
                                            <a href="index.php?action=add_question&id=<?= $qcm->getId() ?>" class="btn btn-success btn-sm">
                                                Ajouter question
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-info">
                            Aucun QCM créé pour le moment.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>