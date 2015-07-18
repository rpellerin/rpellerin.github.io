<?php
class Grille {
  private $taille; // largeur du terrain
  private $nb_de_coups_restants; // nombre de cases à découvrir pour gagner
  private $points; // compteur de clics sur les cases
  private $perdu; // True quand on tombe sur une mine, False sinon
  private $modedecouverteauto; // True quand on découvert les cases autour d'une case valant 0
  private $stopwatch; // heure à laquelle le jeu a commencé
  
  private $terrain; // tableau de booléens <=> la grille de jeu. True si une mine, False sinon
  private $jeu; // tableau de int. Une case vaut 0 pour non découverte, -1 pour drapeau positionné, 1 pour découverte

  function __construct($taille,$nb_coups,$nbmines,$mode) {
    $nbmines = $nbmines>=($taille*$taille)?($taille*$taille)-1:$nbmines; // Il faut toujours au moins une case qui n'est pas une mine
    
    $this->taille = $taille;
    $this->nb_de_coups_restants = ((($nb_coups+$nbmines)>$taille*$taille))?($taille*$taille)-$nbmines:$nb_coups; // Autant de coups que de cases qui ne sont pas des mines, sauf si choix perso
    $this->points = 0;
    $this->perdu = false;
    $this->modedecouverteauto = $mode;
    
    $date = new DateTime();
    $this->stopwatch = $date->getTimestamp();

    // Generation de la grille de jeu
    for ($i = 0; $i < $taille; $i++) {
      for ($j = 0; $j < $taille; $j++) {
        $this->terrain[$i][$j] = false;
        $this->jeu[$i][$j] = 0; // Aucune case dévoilée au début
      }
    }
    // Génération des mines positionnées aléatoirement
    for ($i = 1; $i <= $nbmines; $i++) {
      do {
        $x = rand(0,$taille-1);
        $y = rand(0,$taille-1);
      } while($this->terrain[$x][$y]);
      $this->terrain[$x][$y] = true;
    }


    if ($mode && $nbmines+1<($taille*$taille)) { // si mode découverte auto, on dévoile une case au hasard que si y'a plus d'une case a cliquer
      $x = -1;
      $y = -1;

      $offset_x = rand(0,$taille-1);
      $offset_y = rand(0,$taille-1);

      for ($i = 0; $i < $taille; $i++) {
        for ($j = 0; $j < $taille; $j++) {
          $new_i = ($i + $offset_x)%$taille;
          $new_j = ($j + $offset_y)%$taille;


          if (!$this->terrain[$new_i][$new_j]) { // On cherche une case sans mine
            if ($x == -1 && $y == -1) { // Si pas déjà trouvée, on la save
              $x = $new_i;
              $y = $new_j;
            }
            elseif ($this->chercherNbMinesAutour($new_i,$new_j) == 0) { // Si en plus elle a 0 mine autour c'est mieux
              $x = $new_i;
              $y = $new_j;
              break 2;
            }
          }
        }
      }
      $this->decouvrir($x,$y);
    }
  }

  public function getTerrain() {
    return $this->terrain;
  }

  public function getJeu() {
    return $this->jeu;
  }

  public function getCoupsRestants() {
    return $this->nb_de_coups_restants;	
  }

  public function getPoints() {
    return $this->points;
  }
  
  public function hasLost() {
    return $this->perdu;
  }
  
  public function setFlag($x,$y,$setOrUnset) { // $setOrUnset vaut 1 ou 0
    if ($this->nb_de_coups_restants <= 0 || !($this->isThisPointInTheGame($x,$y)) || ($this->jeu[$x][$y]==1) && ($setOrUnset==0 || $setOrUnset==1)) { // si partie finie, ou case incorrecte, ou case deja dévoilée
      return; // on ne fait rien
    }
    else {
      $this->jeu[$x][$y] = 0-$setOrUnset; // -1 = drapeau placé ; 0 = drapeau retiré
    }
  }
  
  // Retourne le temps écoulé entre le début de la partie et maintenant, au format hh:mm:ss
  public function getInterval() {
    $date = new DateTime();
    $interval = $date->getTimestamp() - $this->stopwatch;
    
    $s = $interval % 60;
    $s = str_pad($s,2,'0',STR_PAD_LEFT); // complète avec un zéro à gauche pour que tous les nombres < 10 soient de la forme : 01, 02, etc
    
    $m = (round($interval, 0, PHP_ROUND_HALF_DOWN) / 60) % 60; // round arrondit à l'entier inférieur
    $m = str_pad($m,2,'0',STR_PAD_LEFT);
    
    $h = round(($interval / (60*60)),0 , PHP_ROUND_HALF_DOWN);
    $h = str_pad($h,2,'0',STR_PAD_LEFT);
    
    return $h.':'.$m.':'.$s;
  }
  
  // Dit si une case donnée est dans les limites de la carte de jeu
  private function isThisPointInTheGame($x,$y) {
    return ($x >= 0 && $x < $this->taille && $y >= 0 && $y < $this->taille);
  }
  
  // Donne le numéro de la case <=> nombre de mines se trouvant autour
  public function chercherNbMinesAutour($x,$y) {
    $nb = 0;
    for($i=$x-1;$i<=$x+1;$i++) {
      for($j=$y-1;$j<=$y+1;$j++) {
        if (($i!=$x || $j!=$y) && $this->isThisPointInTheGame($i,$j) && $this->terrain[$i][$j]) $nb++;
      }
    }
    return $nb;
  }
  
  // Dévoile tout le jeu en fin de partie
  private function finDePartie() {
    for ($i = 0; $i < $this->taille; $i++) {
      for ($j = 0; $j < $this->taille; $j++) {
        $this->jeu[$i][$j] = 1; // on dévoile la case
      }
    }
  }
  
  // Utilisé dans le mode découverte automatique, permet de réveler les cases autour d'une case valant 0
  private function decouvrirAutour($x,$y) {
    for($i=$x-1;$i<=$x+1;$i++) {
      for($j=$y-1;$j<=$y+1;$j++) {
        if ($this->nb_de_coups_restants==0) {
          return; // on annule si on n'a plus de coups restants
        }
      
        if (($i!=$x || $j!=$y) && $this->isThisPointInTheGame($i,$j) && $this->jeu[$i][$j]!=1) {
          $this->jeu[$i][$j] = 1; // on dévoile la case
          $this->nb_de_coups_restants--;
          if ($this->chercherNbMinesAutour($i,$j)==0) $this->decouvrirAutour($i,$j);
        }
      }
    }
  }
  
  // Révèle une case cliquée
  public function decouvrir($x,$y) {
    if ($this->nb_de_coups_restants <= 0 || !($this->isThisPointInTheGame($x,$y)) || ($this->jeu[$x][$y]==1)) { // si partie finie, ou case incorrecte, ou case deja dévoilée
      return; // on ne fait rien
    }
    else {
      $this->jeu[$x][$y] = 1; // on dévoile la case
      $this->points++; // on augmente le compteur de clic
      if ($this->terrain[$x][$y]) { // Si on a perdu
        $this->nb_de_coups_restants = 0;
        $this->perdu = true;
        $this->finDePartie(); // On dévoile le jeu
      }
      else {
        $this->nb_de_coups_restants--;
        if ($this->chercherNbMinesAutour($x,$y)==0 && $this->modedecouverteauto) $this->decouvrirAutour($x,$y);
        if ($this->nb_de_coups_restants == 0) $this->finDePartie();
      }
    }
  }
}
?>