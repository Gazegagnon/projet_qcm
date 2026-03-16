<?php
$action = $_GET['action'] ?? 'enseignant_dashboard';

if (!function_exists('actifEns')) {
    function actifEns($page, $action)
    {
        return $page == $action ? "active bg-primary text-white" : "";
    }
}
?>

<div class="col-md-3 mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="bg-primary text-white p-3 text-center">
                <h5 class="mb-0">Espace Enseignant</h5>
            </div>

            <div class="list-group list-group-flush">
                <a href="index.php?action=enseignant_dashboard" class="list-group-item list-group-item-action <?= actifEns('enseignant_dashboard', $action) ?>">
                    📊 Tableau de bord
                </a>

                <a href="index.php?action=profil_enseignant" class="list-group-item list-group-item-action <?= actifEns('profil_enseignant', $action) ?>">
                    👤 Mon profil
                </a>

                <a href="index.php?action=qcm_enseignant" class="list-group-item list-group-item-action <?= actifEns('qcm_enseignant', $action) ?>">
                    📝 Mes QCM
                </a>

                <a href="index.php?action=questions_enseignant" class="list-group-item list-group-item-action <?= actifEns('questions_enseignant', $action) ?>">
                    ❓ Mes questions
                </a>

                <a href="index.php?action=reponses_enseignant" class="list-group-item list-group-item-action <?= actifEns('reponses_enseignant', $action) ?>">
                    ✅ Mes réponses
                </a>

                <a href="index.php?action=logout" class="list-group-item list-group-item-action text-danger">
                    🚪 Déconnexion
                </a>
            </div>
        </div>
    </div>
</div>