<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion enseignant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <h2>Connexion</h2>

    <?php if (isset($erreur)) : ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label">Mail</label>
            <input type="text" name="mail" class="form-control" placeholder="Entrez votre mail">
        </div>

        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="mdpEns" class="form-control" placeholder="Entrez votre mot de passe">
        </div>

        <input type="submit" name="login_admin" class="btn btn-primary" value="Envoyer">
    </form>

</body>
</html>