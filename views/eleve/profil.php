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
    <title>Mon profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb;" class="container-fluid py-4">

    <div class="container">
        <h2 class="mb-4 fw-bold">Mon profil</h2>

        <div class="row">
            <?php include "views/eleve/_menu.php"; ?>

            <div class="col-md-9">

                <?php if (isset($_SESSION['success_eleve'])) : ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success_eleve']) ?>
                    </div>
                    <?php unset($_SESSION['success_eleve']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['erreur_eleve'])) : ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['erreur_eleve']) ?>
                    </div>
                    <?php unset($_SESSION['erreur_eleve']); ?>
                <?php endif; ?>

                <div class="row g-3 mb-4">
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
                                <p class="text-muted mb-1">Moyenne</p>
                                <h3 class="fw-bold text-primary"><?= $moyenne ?>%</h3>
                            </div>
                        </div>
                    </div>

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
                                <p class="text-muted mb-1">QCM réussis</p>
                                <h3 class="fw-bold text-success"><?= $qcmReussis ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="mb-3">Modifier mes informations</h5>

                                <form method="post" action="index.php?action=modifier_profil_eleve">
                                    <div class="mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($eleve->getNom()) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="mail" class="form-control" value="<?= htmlspecialchars($eleve->getEmail()) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Mot de passe</label>
                                        <input type="password" name="mdp" class="form-control" value="<?= htmlspecialchars($eleve->getMotDePasse()) ?>">
                                    </div>

                                    <button type="submit" name="update_eleve" class="btn btn-primary">
                                        Mettre à jour
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="mb-3">Informations du compte</h5>

                                <p><strong>Nom :</strong><br><?= htmlspecialchars($eleve->getNom()) ?></p>
                                <p><strong>Email :</strong><br><?= htmlspecialchars($eleve->getEmail()) ?></p>

                                <p>
                                    <strong>Date d'inscription :</strong><br>
                                    <?= $eleve->getDateInscription() ? htmlspecialchars($eleve->getDateInscription()) : 'Non disponible' ?>
                                </p>

                                <p>
                                    <strong>Photo de profil :</strong><br>
                                    Fonctionnalité à venir
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>