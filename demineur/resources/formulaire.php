<form action="index.php" method="POST">
  <input type="hidden" name="size" id="hid" />
  <script>
    document.getElementById('hid').value= (window.innerWidth>window.innerHeight)?innerHeight-250:innerWidth-50;
  </script>
  <label for="largeur">Largeur du terrain:</label>
  <select id="largeur" name="largeur">
    <?php for ($i=2;$i<=20;$i++) {
      echo '<option value="'.$i.'"';
      if (isset($_SESSION['preferences_user'][0]) && ($_SESSION['preferences_user'][0] == $i)) {
        echo ' selected';
      }
      echo '>'.$i.' cases de large</option>';
    } ?>
  </select><br />
  <label for="mines">Nombre de mines:</label>
  <select id="mines" name="mines">
    <option value="1"<?php if(isset($_SESSION['preferences_user'][1])&&($_SESSION['preferences_user'][1] == 1)){echo ' selected';}?>>1 mine</option>
    <?php for ($i=2;$i<=399;$i++) {
      echo '<option value="'.$i.'"';
      if (isset($_SESSION['preferences_user'][1]) && ($_SESSION['preferences_user'][1] == $i)) {
        echo ' selected';
      }
      echo '>'.$i.' mine(s)</option>';
    } ?>
  </select><br />
  <label for="coups">Nombre de coups à jouer:</label>
  <select id="coups" name="coups">
    <option value="500"<?php if(isset($_SESSION['preferences_user'][2])&&($_SESSION['preferences_user'][2] == 500)){echo ' selected';}?>>Le plus possible !</option>
    <?php for ($i=3;$i<=399;$i++) {
      echo '<option value="'.$i.'"';
      if (isset($_SESSION['preferences_user'][2]) && ($_SESSION['preferences_user'][2] == $i)) {
        echo ' selected';
      }
      echo '>'.$i.' coups</option>';
    } ?>
  </select><br />
  <label for="check">Mode découverte automatique:</label>
  <input <?php if(isset($_SESSION['preferences_user'][3])) {echo $_SESSION['preferences_user'][3]?"checked":"";} ?> id="check" type="checkbox" name="mode" value="yes" /><br />
  <input type="submit" name="lancer" value="Commencer la partie" />
</form>