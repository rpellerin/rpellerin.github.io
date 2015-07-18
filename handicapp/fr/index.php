<?php $title= "Accueil";
include("header.php");?>
				<section id="slides">
					<img src="../res/images/1.png" alt="Slide" />
					<img src="../res/images/2.png" alt="Slide" />
				</section>
				<section>
					<ul id="home"><li><strong>RECONNAISSANCE VOCALE</strong> : parlez et voyez le texte s'afficher sur votre écran</li><li><strong>SYNTHESE VOCALE</strong> : entrez du texte et votre téléphone le prononcera</li></ul>
				</section>
				<section>
					<h3>HandicApp, c'est quoi précisément ?</h3>
					<p itemprop="description">C'est une application issue d'un <strong>projet d'étudiants</strong> en 1ère année de DUT informatique à Nantes (Romain Pellerin, Tom Georgin et Thomas Senez). Cet projet s'est étalé de novembre 2012 à mai 2013. Nous avons élaboré chaque étape du processus de développement de notre application, en respectant des consignes imposées.</p>
					<p>L'objectif principal était de proposer aux handicapés auditifs une application leur permettant d'interagir avec un interlocuteur aisément. Cela se fait grâce à la voix.</p>
					<p>Notre acceptons de <strong>partager notre code source</strong> si vous nous le demandez via <a href="contact" title="Contact">le formulaire de contact.</a></p>
                    <h3>Après 7 mois de travail</h3>
                    <p>Ce projet a abouti à une soutenance orale dont le support est <a href="../slideshow.html" title="Diaporama">un diaporama que vous pouvez consulter ici.</a></p>
				</section>
				<script src="http://code.jquery.com/jquery-latest.min.js"></script>
				<script src="../res/js/jquery.slides.min.js"></script>
				<script>
					$(function(){
						$("#slides").slidesjs({
							navigation: false,
							play: {
								auto: true,
								interval: 6000,
								effect: "fade",
								pauseOnHover: true,
								restartDelay: 4000
							},
							pagination: {
								effect: "fade",
								active: true
							}
						});
					});
				</script>
<?php include("../res/footer.php");?>