<?php
if (!isset($_SESSION['enseignant']) || !is_object($_SESSION['enseignant'])) {
    header("Location: index.php?action=login_ens");
    exit;
}

$errors = $errors ?? [];
$enseignants = $enseignants ?? [$_SESSION['enseignant']];
$csrfToken = $_SESSION['csrf_token'] ?? '';
$statutValue = $_POST['statut'] ?? 'brouillon';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau QCM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body { background: #f5f7fb; }

        .q-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 12px;
        }

        .choice-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .choice-row input[type=text] {
            flex: 1;
        }

        .add-choice-btn {
            font-size: 13px;
            color: #0d6efd;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .btn-add-question {
            border: 1.5px dashed #adb5bd;
            background: #f8f9fa;
            color: #6c757d;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-add-question:hover {
            background: #fff;
            color: #212529;
        }
    </style>
</head>
<body class="container-fluid py-4">
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Nouveau QCM</h2>
            <p class="text-muted mb-0">Remplissez les informations puis ajoutez les questions.</p>
        </div>
        <span id="statusBadge" class="badge <?= $statutValue === 'actif' ? 'bg-success' : 'bg-warning text-dark' ?> fs-6">
            <?= $statutValue === 'actif' ? 'Actif' : 'Brouillon' ?>
        </span>
    </div>

    <div class="row">
        <?php include "views/enseignant/_menu.php"; ?>

        <div class="col-md-9">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=add_qcm" id="qcmForm">
                <?php if ($csrfToken !== ''): ?>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <?php endif; ?>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted fw-semibold mb-3" style="font-size:11px; letter-spacing:.05em;">
                            Informations générales
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Thème / titre *</label>
                                <input
                                    type="text"
                                    name="theme"
                                    class="form-control"
                                    required
                                    value="<?= htmlspecialchars($_POST['theme'] ?? '') ?>"
                                    placeholder="Ex : Algèbre linéaire"
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Enseignant assigné *</label>
                                <select name="enseignant_id" class="form-select" required>
                                    <option value="">Sélectionner…</option>
                                    <?php foreach ($enseignants as $ens): ?>
                                        <option
                                            value="<?= $ens->getId() ?>"
                                            <?= (($_POST['enseignant_id'] ?? $_SESSION['enseignant']->getId()) == $ens->getId()) ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($ens->getNom()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Total de points</label>
                                <input
                                    type="number"
                                    name="total_points"
                                    class="form-control"
                                    min="0"
                                    value="<?= htmlspecialchars($_POST['total_points'] ?? '') ?>"
                                    placeholder="Ex : 20"
                                    id="totalPointsInput"
                                    oninput="updatePointsSummary()"
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date limite</label>
                                <input
                                    type="date"
                                    name="date_limite"
                                    class="form-control"
                                    value="<?= htmlspecialchars($_POST['date_limite'] ?? '') ?>"
                                >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Statut</label>
                                <select name="statut" class="form-select" id="statutSelect" onchange="updateStatusBadge()">
                                    <option value="brouillon" <?= $statutValue === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                                    <option value="actif" <?= $statutValue === 'actif' ? 'selected' : '' ?>>Actif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-uppercase text-muted fw-semibold mb-0" style="font-size:11px; letter-spacing:.05em;">
                                Questions
                            </h6>
                            <small class="text-muted">
                                Points distribués :
                                <strong id="pointsDistrib">0</strong> /
                                <strong id="pointsTotalLabel">—</strong>
                            </small>
                        </div>

                        <div id="questionsContainer"></div>

                        <button type="button" class="btn-add-question mt-2" onclick="addQuestion()">
                            + Ajouter une question
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" name="action_submit" value="brouillon" class="btn btn-secondary">
                        Enregistrer en brouillon
                    </button>
                    <button type="submit" name="action_submit" value="publier" class="btn btn-primary">
                        Publier le QCM
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
let qCount = 0;
let choiceCounts = {};

function addQuestion() {
    qCount++;
    const id = qCount;
    const container = document.getElementById('questionsContainer');
    const div = document.createElement('div');
    div.className = 'q-card';
    div.id = 'q-' + id;

    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-semibold text-muted" style="font-size:13px;">Question ${id}</span>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted mb-0" style="font-size:12px;">Points</label>
                    <input
                        type="number"
                        name="questions[${id}][points]"
                        min="0"
                        value="1"
                        style="width:65px;"
                        class="form-control form-control-sm"
                        onchange="updatePointsSummary()"
                        id="pts-${id}"
                    >
                </div>
                <button type="button" class="btn-close btn-sm" onclick="removeQuestion(${id})"></button>
            </div>
        </div>

        <div class="mb-3">
            <input
                type="text"
                name="questions[${id}][enonce]"
                class="form-control"
                placeholder="Énoncé de la question…"
                required
            >
        </div>

        <div id="choices-${id}">
            ${makeChoice(id, 1)}
            ${makeChoice(id, 2)}
            ${makeChoice(id, 3)}
        </div>

        <button type="button" class="add-choice-btn" onclick="addChoice(${id})">
            + Ajouter un choix
        </button>

        <small class="d-block text-muted mt-2">
            Sélectionnez le bouton radio pour indiquer la bonne réponse.
        </small>
    `;

    container.appendChild(div);
    choiceCounts[id] = 3;
    updatePointsSummary();
}

function makeChoice(qid, idx) {
    return `
        <div class="choice-row" id="choice-${qid}-${idx}">
            <input
                type="radio"
                name="questions[${qid}][correct]"
                value="${idx}"
                title="Bonne réponse"
                required
            >
            <input
                type="text"
                name="questions[${qid}][choix][]"
                class="form-control form-control-sm"
                placeholder="Choix ${idx}…"
                required
            >
            <button type="button" class="btn-close btn-sm" onclick="removeChoice('choice-${qid}-${idx}')"></button>
        </div>
    `;
}

function addChoice(qid) {
    choiceCounts[qid] = (choiceCounts[qid] || 3) + 1;
    const container = document.getElementById('choices-' + qid);
    const wrapper = document.createElement('div');
    wrapper.innerHTML = makeChoice(qid, choiceCounts[qid]);
    container.appendChild(wrapper.firstElementChild);
}

function removeChoice(id) {
    const el = document.getElementById(id);
    if (el) {
        el.remove();
    }
}

function removeQuestion(id) {
    const el = document.getElementById('q-' + id);
    if (el) {
        el.remove();
    }
    updatePointsSummary();
}

function updatePointsSummary() {
    let total = 0;

    document.querySelectorAll('[id^="pts-"]').forEach(el => {
        total += parseInt(el.value) || 0;
    });

    document.getElementById('pointsDistrib').textContent = total;

    const totalPointsInput = document.getElementById('totalPointsInput').value;
    document.getElementById('pointsTotalLabel').textContent = totalPointsInput || '—';
}

function updateStatusBadge() {
    const val = document.getElementById('statutSelect').value;
    const badge = document.getElementById('statusBadge');

    badge.className = val === 'actif'
        ? 'badge bg-success fs-6'
        : 'badge bg-warning text-dark fs-6';

    badge.textContent = val === 'actif' ? 'Actif' : 'Brouillon';
}

// Questions par défaut
addQuestion();
addQuestion();
</script>
</body>
</html>