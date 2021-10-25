<!DOCTYPE html>
<html>
	<body>

		<?php
			include_once ('../../includes/header.php');
			$_SESSION['page'] = "INDEXAD";
			include '../../fonction/verificationback.php';
		?>

		<?php
			include_once(get_path('outils/connexpdo.inc.php'));

			if (isset($_POST['insert'])) {
				$cnx=connexpdo('bdpopcorn');
				$sql = file_get_contents(get_path('../BDD/insert_bdpopcorn.sql'));
				$requests = explode(';', $sql);
				$go = false;
				foreach ($requests as $request) {
					try {
						if (!empty($request)) {
							$remy = $cnx->exec($request);
						}
					} catch(PDOException $e) {
							// echo $e->getMessage();
					}
					if ($remy != 0) {
						$go = true;
					}
				}
				if ($go) {
					echo "<h4>Pré-configuration ajoutée.</h4>";
				}
				else {
					echo "<h4>Pré-configuration déjà installée.</h4>";
				}
				// compter nombre ligne modif pour changer ce msg
				$cnx=null;
			}

			if (!isset($_POST['spotify'])) {
				$client_id = '37f040251306463aa43d272d61d68526';
				$client_secret = 'd0df61c03b394843939d462426bcbc6a';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,            'https://accounts.spotify.com/api/token' );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST,           1 );
				curl_setopt($ch, CURLOPT_POSTFIELDS,     'grant_type=client_credentials' );
				curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));
				$result=curl_exec($ch);
				if(curl_errno($ch))
				    echo 'Curl error: '.curl_error($ch);
				curl_close ($ch);
				$result = explode('",', explode('access_token":"', $result)[1])[0];
				echo "<script>setVariables('" . $result . "')</script>";
				echo "<button id='actualiserBDD' onclick='fillDataBase()'>Actualiser BDD</button>";
				echo "<h3 id=spotifymsg></h3>";
				$cnx=connexpdo('bdpopcorn');
				$req = "SELECT DISTINCT * from tag";
				$req = $cnx->query($req);
				$elems = $req->fetchAll();
				echo "<div class='tags'>";
				echo '<h3>URL optionnel</h3>';
				echo '<input type="url" name="url" id="optionalurl"
       placeholder="https://open.spotify.com/playlist/5sTHqyG2DAwmTCopHXHRdz"
       pattern="https://.*" size="30">';
				echo '<h3>Tags de bases optionnels:</h3>';
				if (!empty($elems)) {
					foreach ($elems as $value) {
						echo '<input class="tagelement" type="checkbox" name='.$value['nomTag'].' id='.$value['numTag'].'>';
						echo '<label class="tagnames" for='.$value['nomTag'].'>'.$value['nomTag'].'</label>';
						echo '<br>';
					}
				}
				else {
					echo 'Pas de tag. <a href='.get_path('pages/back/gestionTag.php').'>Ajoutez en!</a>';
				}
				echo "</div>";
				echo "<form class='remy' action='".$_SERVER['PHP_SELF']."' method='post'>";
				echo "<button type='submit' name='insert' value='Ajouter à la playlist' id='actualiserBDDInsert'>Ajouter automatiquement pré-configuration</button>";
				echo "</form>";
				$cnx=null;
			}
			else {
				$cnx=connexpdo('bdpopcorn');
				// $all_insert = explode(');', $_POST['spotify']);
				// foreach ($all_insert as $insert) {
				// 	$full_insert = $insert + ');';
				// 	$req = $cnx->exec($full_insert);
				// };
				$req = $cnx->exec($_POST['spotify']);
				echo "<h3>Base de donnée mise à jour</h3>";
				$cnx=null;
			}
		?>

		<?php
			include_once ('../../includes/footer.php');
		?>

	</body>
</html>
