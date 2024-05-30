<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="public/css/app.css">

	<title>Enseignant</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">Enseignant</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Tableau de bord</span>
				</a>
			</li>
			<li>
				<a href="gestion.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">QCM</span>
				</a>
			</li>
			
			
			<li>
				<a href="#">
					<i class='bx bxs-group' ></i>
					<span class="text">Réponse</span>
				</a>
			</li>

            <li>
				<a href="#">
					<i class='bx bxs-group' ></i>
					<span class="text">Résultat</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			
			<li>
				<a href="?action=logout" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">se déconnecter</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Bonjour   <?= unserialize($_SESSION['enseignant'])->getNom() ?></a>
			
			
			
			
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Bienvenue <?= unserialize($_SESSION['enseignant'])->getNom() ?></h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Tableau de bord</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						
					</ul>
				</div>
				<a href="#" class="btn-download">
					<i class='bx bxs-cloud-download' ></i>
					<span class="text" onclick="window.location.href='?action=add_qcm&id=<?= unserialize($_SESSION['enseignant'])->getId() ?>';">créer un nouveau QCM</span>
				</a>
			</div>

			<form action="" method="post">
				<label for="nom">bonne reponse</label>
				<input type="text" id="nom" name="nom">
				
				<button name="serch">serch</button>
			</form>


			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Tous les QCM</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>id</th>
								<th>theme</th>
								<th>question du Qcm</th>
								

                                <th>action</th>
							</tr>
						</thead>
						<tbody>
							
							
								
							<?php foreach($qcms as $qcm){ ?>	
								<tr>
									<td>
					
										<p><?= $qcm->getId()?></p>
									</td>
									<td><p><?= $qcm->getTheme()?></p></td>
									<td>
										<p><a href="?action=add_question&id=<?= $qcm->getId()?>">ajouter une nouvelles questions</a></p>
									</td>

									<td>
										<a href="delete_personne.php?id=">sup</a>
										<a href="up_personnel.php?id=">Mod</a>

									</td>
								</tr>
							<?php }?>
								
							

							
							
							


							
						</tbody>
					</table>
				</div>
				
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Tous les questions</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>id</th>
								<th>libelle</th>
								<th>points</th>
								<th>idQcm</th>
								<th>reponse</th>
								

                                <th>action</th>
							</tr>
						</thead>
						<tbody>
							
							
								
							<?php foreach($questions as $question){ ?>	
								<tr>
									<td><p><?= $question->getId()?></p></td>
									<td>
					
										<p><?= $question->getLibelle() ?></p>
									</td>
									<td><p><?= $question->getPoints() ?></p></td>
									<td >
										
										<p><?= $question->getIdQcm() ?></p>
									</td>
									<td>
										<p><a href="?action=add_Reponse&id=<?= $question->getId()?>">ajouter une nouvelle reponse</a></p>
									</td>

									<td>
										<a href="delete_personne.php?id=">sup</a>
										<a href="up_personnel.php?id=">Mod</a>

									</td>
								</tr>
							<?php }?>
								
							

							
							
							


							
						</tbody>
					</table>
				</div>
				
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Tous les Reponses</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>reponsePropose</th>
								<th>bonneReponse</th>
								<th>idQuestion</th>
                                <th>action</th>
							</tr>
						</thead>
						<tbody>
							
							
								
							<?php foreach($reponses as $reponse){ ?>	
								<tr>
									<td>
					
										<p><?= $reponse->getReponsePropose()?></p>
									</td>
									<td><p><?= $reponse->getBonneReponse() ?></p></td>
									<td>
										<p><?= $reponse->getIdQuestion() ?></p>
									</td>

									<td>
										<a href="delete_personne.php?id=">sup</a>
										<a href="up_personnel.php?id=">Mod</a>

									</td>
								</tr>
							<?php }?>
								
							

							
							
							


							
						</tbody>
					</table>
				</div>
				
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="public/js/app.js"></script>
</body>
</html>




