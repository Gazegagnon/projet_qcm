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
    <title>Profil enseignant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

<div class="container">
    <h2 class="mb-4 fw-bold">Mon profil</h2>

    <div class="row">
        <?php include "views/enseignant/_menu.php"; ?>

        <div class="col-md-9">

            <?php if (isset($_SESSION['success_enseignant'])) : ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_enseignant']) ?>
                </div>
                <?php unset($_SESSION['success_enseignant']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['erreur_enseignant'])) : ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['erreur_enseignant']) ?>
                </div>
                <?php unset($_SESSION['erreur_enseignant']); ?>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Modifier mes informations</h5>

                    <form method="post" action="index.php?action=modifier_profil_enseignant">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($enseignant->getNom()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="mail" class="form-control" value="<?= htmlspecialchars($enseignant->getEmail()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="text" name="mdp" class="form-control" value="<?= htmlspecialchars($enseignant->getMotDePasse()) ?>">
                        </div>

                        <button type="submit" name="update_enseignant" class="btn btn-primary">
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>