<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="public/css/app.css">

	<title>AdminHub</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">AdminHub</span>
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
			<a href="#" class="nav-link">Bonjour </a>
			
			
			
			
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
					<span class="text" onclick="window.location.href='personnel.php';">créer un nouveau QCM</span>
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
						<h3>tous les réponse au question de mes QCM</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>id</th>
								<th>Réponse</th>
								<th>Bonne Réponse</th>
								

                                <th>action</th>
							</tr>
						</thead>
						<tbody>
							
							
								
									
								<tr>
									<td>
					
										<p></p>
									</td>
									<td><p></p></td>
									<td>
										<p></p>
									</td>
									<td>
										<p></p>
									</td>
									<td>
										<a href="delete_personne.php?id=">sup</a>
										<a href="up_personnel.php?id=">Mod</a>

									</td>
								</tr>
								
							

							
							
							


							
						</tbody>
					</table>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Tous les QCM</h3>
						<i class='bx bx-plus' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table style="width: 100%; border-collapse: collapse;">
						<thead >
							<tr>
                                <th style="padding-bottom: 12px; text-align: left; font-size: 13px; border-bottom: 1px solid var(--grey) ">id</th>
								<th style="padding-bottom: 12px; text-align: left; font-size: 13px; border-bottom: 1px solid var(--grey) ">theme</th>
								
								<th style="padding-bottom: 12px; text-align: left; font-size: 13px; border-bottom: 1px solid var(--grey) ">action</th>

							</tr>
						</thead>
						<tbody>
							
								
								<tr>
									<td style="padding: 16px 0;">
										
										<p></p>
									</td>

                                    <td style="padding: 16px 0;">
										
										<p></p>
									</td>
									
									<td style="padding: 16px 0;">
										<a href="delete_equipe.php?id=">sup</a>
										<a href="up_equipe.php?id=">Mod</a>
                                        <a href="up_personnel.php?id=">add_reponse</a>
									</td>
								</tr>
							
							


							
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




