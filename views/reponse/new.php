<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">

    <title>devoir</title>
</head>

<body>
    <section class="" style="padding: 40px  80px;">
        <h1>Ajouter une nouvelle reponse</h1>

        <form action="" method="POST" enctype="multipart/form-data">

            
            <div class="mb3">
                <label for="prix" class="form-label">Réponse proposé:</label>
                <input type="text" name="reponsePropose" id="prix" required  class="form-control" placeholder="Entre la réponse "  ?>

            </div>

            <div class="mb-3">
                <label for="surface" class="form-label">la bonne réponse:</label>
                <input type="text" name="bonneReponse" id="surface" required class="form-control" placeholder="Entre la valeur de la réponse "  >

            </div>
            
            

            <button type="submit" name="add_reponse" class="btn btn-primary">Ajouter</button>
        </form>
    </section>
</body>
</html>