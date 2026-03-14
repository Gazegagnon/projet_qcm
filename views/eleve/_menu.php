<?php
$action = $_GET['action'] ?? 'eleve_dashboard';

function actif($page, $action)
{
    return $page == $action ? "active bg-primary text-white" : "";
}
?>

<div class="col-md-3 mb-4">

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="bg-primary text-white p-3 text-center">
                <h5 class="mb-0">Espace Élève</h5>
            </div>

            <div class="list-group list-group-flush">

                <a href="index.php?action=eleve_dashboard"
                   class="list-group-item list-group-item-action <?= actif('eleve_dashboard',$action) ?>">
                    📊 Tableau de bord
                </a>

                <a href="index.php?action=profil_eleve"
                   class="list-group-item list-group-item-action <?= actif('profil_eleve',$action) ?>">
                    👤 Mon profil
                </a>

                <a href="index.php?action=qcm_a_passer"
                   class="list-group-item list-group-item-action <?= actif('qcm_a_passer',$action) ?>">
                    📝 QCM à passer
                </a>

                <a href="index.php?action=qcm_effectues"
                   class="list-group-item list-group-item-action <?= actif('qcm_effectues',$action) ?>">
                    📚 QCM effectués
                </a>

                <a href="index.php?action=mes_resultats"
                   class="list-group-item list-group-item-action <?= actif('mes_resultats',$action) ?>">
                    🏆 Mes résultats
                </a>

                <a href="index.php?action=logout_eleve"
                   class="list-group-item list-group-item-action text-danger">
                    🚪 Déconnexion
                </a>

            </div>

        </div>
    </div>

</div>