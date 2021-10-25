<!DOCTYPE html>
<html>
	<body>

		<?php
			include_once ('../../includes/header.php');
			$_SESSION['page'] = "INDEXAD";
			include '../../fonction/verificationback.php';
			include_once(get_path('outils/connexpdo.inc.php'));
			$cnx=connexpdo('bdpopcorn');
			$req="	SELECT * FROM tag";
			$req = $cnx->query($req);
			$donnee = $req->fetchall();
			$nblignes = count($donnee);
			if ($nblignes == 0) {
				echo "<h4>Cette liste de tag est vide!</h4>";
			}
			else {
				echo '<div class="bodyelement">';
				echo '<table class="table table-dark">
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">ID</th>
										<th scope="col">Nom</th>
									</tr>
								</thead>
								<tbody>';

				foreach ($donnee as $donnees)
				{
					echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
					echo '<tr>';
					echo "<th scope='row'><input type='submit' name='Modifier' value='Modifier' class='btnopt btn btn-secondary btn-sm'></input>
									<input type='submit' name='Supprimer' value='Supprimer' class='btnopt btn btn-secondary btn-sm'></input></th>";
					echo "<td><input type='text' name='numTag' value='".$donnees['numTag']."' readonly></td>";
					echo "  ";
					echo "<td><input type='text' name='nomTag' value='".$donnees['nomTag']."' readonly></td>";
					echo "</form>";
				}
				echo '  </tbody>
							</table>';
				echo "</div>";
			}
			if (empty($_POST['Ajouter']))
			{
				echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
				echo "	<input type='submit' class='spacebottom btnopt btn btn-secondary btn-sm btn-block' name='Ajouter' value='Ajouter'></input>";
				echo "<input type='hidden' name='numTag' value='#' readonly>";
				echo "</form>";
			}
			else
			{
				echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
				echo "	<input type='submit' class='spacebottom btnopt btn btn-secondary btn-sm' name='Confirmer' value='Confirmer'></input>";
				echo "<input type='text' name='nomTag' value='' autocomplete='off' required minlength='2' maxlength='30'>";
				echo "</form>";
					echo "<a href=".get_path('pages/back/gestionTag.php').">Retour arrière</a>";
			}
			if (!empty($_POST['Confirmer']))
			{
				$nomTag = "";
				$nomTag = $cnx->quote($_POST['nomTag']);
				$req = "SELECT * from tag where nomTag = ".$nomTag;
				$req=$cnx->query($req);
				$lignes=$req->fetchall();
				$nblignes = count($lignes);
				if($nblignes==1)
				{
					echo "Ce nom existe déjà";
				}
				else
				{
					include_once(get_path('outils/connexpdo.inc.php'));
					$cnx=connexpdo('bdpopcorn');
					$req2="	insert into Tag (nomTag) values (".$nomTag.")";
					$cnx->exec($req2);
					echo "<script>
					location.assign(location.href);</script>";
					$cnx=null;
				}
			}
			if (!empty($_POST['Modifier']))
			{
				echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
				echo "	<input type='submit' class='btnopt btn btn-secondary btn-sm' name='Valider' value='Valider'></input>";
				echo "<input type='text' name='numTag' value='".$_POST['numTag']."' readonly>";
				echo "  ";
				echo "<input type='text' name='nomTag' value='".$_POST['nomTag']."' autocomplete='off' required minlength='2' maxlength='30'>";
				echo "</form>";
				  echo "<a href=".get_path('pages/back/gestionTag.php').">Retour arrière</a>";
			}
			if (!empty($_POST['Valider']))
			{
				$numTag = "";
				$nomTag = "";
				$numTag = $cnx->quote($_POST['numTag']);
				$nomTag = $cnx->quote($_POST['nomTag']);
				$req = "SELECT * from tag where nomTag = ".$nomTag;
				$req=$cnx->query($req);
				$lignes=$req->fetchall();
				$nblignes = count($lignes);
				if($nblignes==1)
				{
					echo "Ce nom existe déjà";
				}
				else
				{
					include_once(get_path('outils/connexpdo.inc.php'));
					$cnx=connexpdo('bdpopcorn');
					$req2="	UPDATE tag
							SET nomTag = ".$nomTag."
							WHERE numTag =".$numTag;
					$cnx->exec($req2);
					echo "<script>
					location.assign(location.href);</script>";
					$cnx=null;
				}
			}
			if (!empty($_POST['Supprimer']))
			{
				$numtag = "";
				$numTag = $cnx->quote($_POST['numTag']);
				$req="	Delete from Tag where numTag = ".$numTag;
				$cnx->exec($req);
				echo "<script>
				location.assign(location.href);</script>";
				$cnx = null;
			}
			echo '</div>';
			include_once ('../../includes/footer.php');
		?>

	</body>
</html>
