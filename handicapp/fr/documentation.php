<?php $title= "Documentation";
include("header.php");?>
<h2>Documentation détaillée</h2>
<section>
<ol>
	<li><a href="#sv">Synthèse vocale</a>
	<ol><li><a href="#svaa">Autres actions</a></li></ol>
	</li>
	<li><a href="#rv">Reconnaissance vocale</a>
	<ol><li><a href="#rvc">Reconnaissance en continu</a></li><li><a href="#rvaa">Autres actions</a></li></ol>
	</li>
	<li><a href="#p">Paramètres</a></li>
</ol>
<h3 id="sv">Synthèse vocale</h3>
<a title="Agrandir" href="../res/images/documentation/premiere.png"><img style="width: 50%; float: left;" src="../res/images/documentation/premiere.png" alt="PNG" /></a>
<p>Cette page est la première que vous verrez lors du lancement de l'application. Elle est constituée de deux zones, celle  qui correspond à la synthèse vocale et celle de droite qui correspond à la reconnaissance vocale. Si la synthèse vocale n'est pas activée sur votre appareil, une boîte de dialogue vous invitera à l'activer, sans quoi la synthèse ne pourra fonctionner.</p>
<p>Le champ situé en haut vous permet de saisir du texte afin de le synthétiser vocalement. Un appui sur "Ecouter" et la synthèse démarre. Appuyez de nouveau pour l'interrompre. La synthèse vocale se fait dans la langue de votre appareil.</p>
<h4 id="svaa">Autres actions</h4>
<p>Le gros bouton qui contient un micro vous permet de lancer la reconnaissance vocale (voir plus bas). Enfin, les paramètres de l'application sont accessibles via l'icone tout en haut à droite de l'application (voir plus bas).</p>
<h3 id="rv">Reconnaissance vocale</h3>
<a title="Agrandir" href="../res/images/documentation/deuxieme2.png"><img style="width: 23%; float:right;" src="../res/images/documentation/deuxieme2.png" alt="PNG" /></a><a title="Agrandir" href="../res/images/documentation/deuxieme1.png"><img style="width: 23%; float:right;" src="../res/images/documentation/deuxieme1.png" alt="PNG" /></a>
<p>Lorsque, depuis la première vue, vous lancez la reconnaissance, voici ce qui s'affiche (dans l'ordre d'apparition, si un texte à été reconnu). La première photo montre la phase de reconnaissance vocale. C'est pendant cette phase que vous devez parler. Puis, quand vous avez fini, votre appareil analysera ce que vous avez dit pendant quelques secondes puis l'affichera (deuxième photo). Vous aurez alors la possibilité de modifier directement ce texte, si des fautes s'y sont glissées, ou même pour y ajouter du contenu. Le bouton du bas vous permettra de recommencer la reconnaissance si vous le souhaitez.</p>
<h4 id="rvc">Reconnaissance vocale en continu</h4>
<p>Lorsque cette option est activée dans les paramètres, aucun pop-up ne s'affiche, parlez en continu et l'application affichera ce que vous dîtes en même temps (un temps d'analyse est cependant nécessaire).</p>
<h4 id="rvaa">Autres actions</h4>
<a title="Agrandir" href="../res/images/documentation/menu.png"><img style="width: 30%; float: right;" src="../res/images/documentation/menu.png" alt="PNG" /></a>
<p>Le menu du haut comporte plusieurs actions. L'icône la plus à droite vous permet de traduire le texte en anglais, au moyen d'une connexion internet. Elle vous permet également d'accéder aux paramètres de l'application (voir plus bas). Puis à gauche se trouvent les options de partage. Le texte peut être envoyé vers une application tierce de votre appareil, ou même vers la synthèse vocale de HandicApp.</p>
<h3 id="p">Paramètres</h3>
<a title="Agrandir" href="../res/images/documentation/parametres.png"><img style="width: 25%; float: left;" src="../res/images/documentation/parametres.png" alt="PNG" /></a>
<p>Ici, vous trouverez tous les paramètres de l'application.</p>
<ul>
	<li><strong>Taille du texte :</strong> vous permet d'ajuster la taille du texte à synthétiser ou celui de la reconnaissance vocale selon trois tailles prédéfinies (petite, moyenne, grande).</li>
	<li><strong>Vitesse d'élocution :</strong> permet de choisir le vitesse de la synthèse vocale (lente, normale ou rapide).</li>
	<li><strong>Tonalité :</strong> permet de choisir la tonalité de la voix de la synthèse vocale (basse, moyenne ou haute).</li>
	<li><strong>En continu :</strong> permet d'activer/désactiver la reconnaissance vocale en continu (voir plus haut).</li>
	<li><strong>Aide :</strong> vous permet d'accéder à cette même documentation depuis l'application.</li>
	<li><strong>HandicApp :</strong> vous donne le numéro de version de l'application.</li>
</ul>
<div style="clear: both;"></div>
</section>
<?php include("../res/footer.php");?>