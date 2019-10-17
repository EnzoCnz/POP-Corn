<!DOCTYPE html>
<html>
	<body>

		<?php
			include_once '../includes/header.php';
			//fonction de deconnexion pour le salarie
			if (isset($_GET['id']))
			{
				$req="	SELECT * FROM musique where numMusique = '".$_GET['id']."'";

				$req = $cnx->query($req);
				$lignes=$req->fetchall();
				$nblignes = count($lignes);

				if($nblignes==0)
				{
					echo "Cette musique n'existe pas";
				}
				else
				{

					$req="	SELECT distinct m.titre, m.duree, au.nom, au.prenom, a.nomAlbum, a.imageAlbum, a.anneeAlbum, m.numMusique  FROM musique m
					inner join album a on m.numAlbum = a.numAlbum
					inner join ecrire e on m.numMusique = e.numMusique
					inner join Auteur au on e.numAuteur = au.numAuteur
					where m.numMusique = '".$_GET['id']."'";

					$req = $cnx->query($req);

					while($donnees = $req->fetch(PDO::FETCH_ASSOC))
					{
							date_default_timezone_set('Europe/Paris');
							$duree = gmdate("i:s", $donnees['duree']/1000);
							$date = date("Y", strtotime($donnees['anneeAlbum']));
							echo $donnees['numMusique'];

						echo "<div class='musictop'><div class='imgmus'>
				        	<img src=".$donnees['imageAlbum']." alt='Image de album'>
				        	<ul>";
						echo "<li><p>Titre : </p><p class = lien> ".$donnees['titre']." </p></li>";
						echo "<li><p>Durée : </p><p class = lien> ".$duree."min </p></li>";

						echo "<li><p>Artiste : </p><p class = lien> ".$donnees['nom']." ";
						if ($donnees['prenom'] != "null") {echo $donnees['prenom']." </p></li>";}

						if ($donnees['titre'] != $donnees['nomAlbum']){echo "<li><p>Album : </p><p class = lien> ".$donnees['nomAlbum']." </p></li>";}
						echo "<li><p>Année : </p><p class = lien> ".$date." </p></li>";
						echo "</ul></div></div>";
					}
				}







			}
		?>


    <div class="des">


    </div>





		<?php
			include_once '../includes/footer.php';
		?>

	</body>
</html>
