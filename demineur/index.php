<?php
require_once('resources/Grille.php');
session_start();
ini_set('xdebug.max_nesting_level', 500); // permet d'agrandir la stack allouée par défaut

if (isset($_POST) && isset($_POST['recommencer'])) {
  unset($_SESSION['partie']); // on supprime la partie
}
elseif (isset($_POST) && isset($_POST['lancer'])) {
  // utilisé pour stocker les préférences de l'utilisateur pour les prochaines parties
  $_SESSION['preferences_user'] = array($_POST['largeur'], $_POST['mines'], $_POST['coups'], isset($_POST['mode'])?true:false);
	
  // largeur de la grille, nb de mines et nb de coups autorisés
  $_SESSION['partie'] = new Grille($_POST['largeur'],$_POST['coups'],$_POST['mines'],isset($_POST['mode'])?true:false);
  $taille = round((($_POST['size']) / $_POST['largeur']), 0, PHP_ROUND_HALF_DOWN);
  $_SESSION['size_img'] = $taille>100?100:$taille;
}
elseif (isset($_SESSION['partie']) && !empty($_SESSION['partie']) && isset($_GET) && isset($_GET['x']) && isset($_GET['y'])) {
	$xx = (int) $_GET['x'];
	$yy = (int) $_GET['y'];
  if (isset($_GET['f'])) {
    $_SESSION['partie']->setFlag($xx,$yy,$_GET['f']);
  }
  else $_SESSION['partie']->decouvrir($xx,$yy);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" media="all" href="resources/style.css" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Démineur</title>
        <link rel="shortcut icon" href="favicon.ico" />
    </head>
    <body>
      <h1>DÉMINEUR</h1>
      <?php
      // FORMULAIRE
      if (!isset($_SESSION['partie'])) {
        include_once('resources/formulaire.php');
      }
      // JEU
      elseif (!empty($_SESSION['partie'])) {
      ?>
      <style>
        img {
          width: <?php echo $_SESSION['size_img']; ?>px !important;
          height: <?php echo $_SESSION['size_img']; ?>px !important;
        }
      </style>
      <?php
        $time = $_SESSION['partie']->getInterval(); // on récupère le temps écoulé
        echo '<table id="grille">';
        foreach($_SESSION['partie']->getJeu() as $x => $ligne) {
          echo "<tr>";
          foreach($ligne as $y => $case) {
            echo	'<td>';
            if ($case==1) {
              echo '<img src="resources/images/';
              $ter = $_SESSION['partie']->getTerrain();
              if ($ter[$x][$y]) echo 'bombe';					
              else echo $_SESSION['partie']->chercherNbMinesAutour($x,$y);
              echo '.png" />';
            }
            elseif ($case == -1) {
              echo '<a oncontextmenu="javascript:window.location.href=\'index.php?x='.$x.'&y='.$y.'&f=0\';return false;" href="index.php?x='.$x.'&y='.$y.'"><img src="resources/images/flag.png" /></a>';
            }
            else echo '<a title="Clique-droit pour mettre un drapeau" oncontextmenu="javascript:window.location.href=\'index.php?x='.$x.'&y='.$y.'&f=1\';return false;" href="index.php?x='.$x.'&y='.$y.'"><img src="resources/images/interrogation.png" /></a>';
            echo '</td>';
          }
          echo "</tr>";
        }
        echo '</table>';
        echo '<div>';
        echo "<h2>".$_SESSION['partie']->getCoupsRestants()." coup(s) restants</h2>";
        echo '<h3 id="time">'.$time.'</h3>';
        if ($_SESSION['partie']->hasLost()) {
          echo '<h3>PERDU: en '.$_SESSION['partie']->getPoints().' coup(s)</h3>';
          unset($_SESSION['partie']); // on supprime la partie
        }
        elseif ($_SESSION['partie']->getCoupsRestants() == 0) {
          echo '<h3>GAGNÉ: en '.$_SESSION['partie']->getPoints().' coup(s)</h3>';
          unset($_SESSION['partie']); // on supprime la partie
        }
        else {
        ?>
        <script type="text/javascript">
          var time = "<?php echo $time; ?>";
        </script>
        <script type="text/javascript" src="resources/stopwatch.js"></script>
        <?php
        }
        echo '<form action="index.php" method="POST"><input type="submit" name="recommencer" value="Recommencer une partie" /></form>';
        echo '</div>';
      }
      ?>	
    </body>
</html>