<?php
if (!isset($_SESSION['eleve']) || !is_object($_SESSION['eleve'])) {
    header("Location: index.php?action=login_eleve");
    exit;
}

$eleve = $_SESSION['eleve'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace élève</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Bienvenue <?= htmlspecialchars($eleve->getNom()) ?></h2>
        <div>
            <a href="index.php?action=mes_resultats" class="btn btn-info">Mes résultats</a>
            <a href="index.php?action=logout_eleve" class="btn btn-danger">Se déconnecter</a>
        </div>
    </div>

    <?php if (isset($_SESSION['message_qcm'])) : ?>
        <div class="alert alert-warning">
            <?= htmlspecialchars($_SESSION['message_qcm']) ?>
        </div>
        <?php unset($_SESSION['message_qcm']); ?>
    <?php endif; ?>

    <div class="mb-4">
        <h4>Mes informations</h4>
        <p><strong>Nom :</strong> <?= htmlspecialchars($eleve->getNom()) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($eleve->getEmail()) ?></p>
    </div>

    <div class="mb-4">
        <h4>Liste des QCM disponibles</h4>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Thème</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($qcms)) : ?>
                    <?php foreach ($qcms as $qcm) : ?>
                        <tr>
                            <td><?= $qcm->getId() ?></td>
                            <td><?= htmlspecialchars($qcm->getTheme()) ?></td>
                            <td>
                                <?php if ($resultatMdl->resultatExiste($eleve->getId(), $qcm->getId())) : ?>
                                    <span class="btn btn-secondary btn-sm disabled">Déjà soumis</span>
                                <?php else : ?>
                                    <a href="index.php?action=questions_qcm&id=<?= $qcm->getId() ?>" class="btn btn-primary btn-sm">
                                        Commencer
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">Aucun QCM disponible.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>