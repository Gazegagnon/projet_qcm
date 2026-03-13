<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription élève</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2>Inscription élève</h2>

    <?php if (isset($erreur)) : ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" placeholder="Entrez votre nom">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="mail" class="form-control" placeholder="Entrez votre email">
        </div>

        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="mdp" class="form-control" placeholder="Entrez votre mot de passe">
        </div>

        <input type="submit" name="new_eleve" class="btn btn-success" value="S'inscrire">
        <a href="index.php?action=login_eleve" class="btn btn-secondary">Se connecter</a>
    </form>

</body>
</html>