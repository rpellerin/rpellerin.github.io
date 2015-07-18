<?php $title= "Contact";
include("header.php");?>
	<h2>Contact par email direct</h2>
	<section>
        <div id="message" class="center">
          <?php
          if (isset($_POST['name'])){ // Si la variable existe c'est qu'on a cliqué sur envoyer
          $vartest = 1; // Donc on crée la variable pour compter

          if (isset($_POST['name']) && $_POST['name'] != ""){
          $_POST['name'] = htmlspecialchars($_POST['name']);
          $vartest++;} // 2
          if (isset($_POST['email']) && $_POST['email'] != ""){
          $_POST['email'] = htmlspecialchars($_POST['email']);
          $vartest++;} // 3
          if (isset($_POST['subject']) && $_POST['subject'] != ""){
          $_POST['subject'] = htmlspecialchars($_POST['subject']);
          $vartest++;} // 4
          if (isset($_POST['contents']) && $_POST['contents'] != ""){
          $_POST['contents'] = nl2br(htmlspecialchars($_POST['contents']));
          $vartest++;} // 5

          if ($vartest == 5) {
          $chaine = $_POST['email'];
          if(!preg_match('`^[[:alnum:]]([-_.]?[[:alnum:]])+_?@[[:alnum:]]([-.]?[[:alnum:]])+\.[a-z]{2,6}$`',$chaine)) { // On vérifie si l'adresse mail est bonne
          echo "Entrez une adresse email valide.";
          }
          else {
          $mail = 'romain.pellerin85@gmail.com'; // Déclaration de l'adresse de destination.
          if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
          {
          $passage_ligne = "\r\n";
          }
          else
          {
          $passage_ligne = "\n";
          }
          //=====Déclaration des messages au format texte et au format HTML.
          $message_txt = 'Message de : '.$_POST['email'].'<br />'.$_POST['contents'];
          $message_html = '<html><head></head><body>'.$message_txt.'</body></html>';
          //==========

          //=====Création de la boundary
          $boundary = "-----=".md5(rand());
          //==========

          //=====Définition du subject.
          $subject = $_POST['subject'].' (via romainpellerin.eu)';
          //=========

          //=====Création du header de l'e-mail.
          $header = 'From: "'.$_POST['name'].'" <'.$_POST['email'].'>'.$passage_ligne;
          $header.= 'Reply-to: "'.$_POST['name'].'" <'.$_POST['email'].'>'.$passage_ligne;
          $header.= "MIME-Version: 1.0".$passage_ligne;
          $header .= "X-Priority: 5".$passage_ligne;
          $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
          //==========

          //=====Création du message.
          $message = $passage_ligne."--".$boundary.$passage_ligne;
          //=====Ajout du message au format texte.
          $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
          $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
          $message.= $passage_ligne.$message_txt.$passage_ligne;
          //==========
          $message.= $passage_ligne."--".$boundary.$passage_ligne;
          //=====Ajout du message au format HTML
          $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
          $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
          $message.= $passage_ligne.$message_html.$passage_ligne;
          //==========
          $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
          $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
          //==========

          //=====Envoi de l'e-mail.
          mail($mail,$subject,$message,$header);
          //==========
          echo "Serveur non configuré. Envoyez un email à contact@romainpellerin.eu";
          }
          }
          else {
          echo "Merci de compléter tous les champs.";
          }
          }

          ?>
        </div> <!-- DIV message envoie mail -->

        <form method="post" action="contact" >
          <em>Tous les champs sont requis.</em><br /><br />
          <label for="name">Prénom et nom</label><br />
          <input aria-required="true" required type="text" name="name" id="name" /><br /><br />
          <label for="email">Adresse email</label><br />
          <input aria-required="true" required type="email" name="email" id="email" /><br /><br />
          <label for="subject">Sujet</label><br />
          <input aria-required="true" required type="text" name="subject" id="subject" /><br /><br />
          <label for="area">Message :</label><br />
          <textarea aria-required="true" required rows="17" name="contents" id="area"></textarea><br /><br />
          <input type="submit" value="Envoyer"> -
          <input type="reset" value="Annuler">
        </form>
	</section>
<?php include("../res/footer.php");?>