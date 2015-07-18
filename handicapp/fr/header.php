<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Product" lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="HandicApp est une application pour Android qui vous permet d'interagir facilement par la voix avec votre interlocuteur." />
		<meta name="viewport" content="initial-scale=1.0">
		<meta name="robots" content="all">
		<title><?php echo $title;?> - HandicApp</title>
		<link rel="stylesheet" media="all" href="../res/design.min.css" />
		<link rel="shortcut icon" href="../favicon.ico" />
	</head>
	<body>
		<header class="center" role="banner">
			<a href="../fr" title="Accueil"><img itemprop="image" src="../res/images/logo.png" alt="logo" /><h1 itemprop="name">HandicApp</h1></a>
			<a id="lang" href="../us/<?php if ($title=="L'application") {echo 'application';} elseif ($title=="Documentation") {echo 'documentation';} elseif ($title=="Contact") {echo 'contact';}?>" title="English version"><img src="../res/images/us.png" alt="US" /></a>
			<a id="playstorelink" title="Play Store" target="_blank" href="https://play.google.com/store/apps/details?id=eu.romainpellerin.handicapp">
			  <img alt="Google Play" src="https://developer.android.com/images/brand/fr_generic_rgb_wo_60.png" />
			</a>
		</header>
		<div id="wrapper">
			<nav id="top" role="navigation" class="center">
				<div id="nav_hidden">
					<a href="#top" class="nav-open" aria-hidden="true">Menu ▼</a>
					<a href="#nav_hidden" class="nav-close" aria-hidden="true">Menu ▲</a>
				</div>
				<ul>
					<li <?php if ($title=="Accueil") {echo 'class="current"';}?>><a href="../fr">LE PROJET</a></li><!--
				 --><li <?php if ($title=="L'application") {echo 'class="current"';}?>><a href="application">L'APPLICATION</a></li><!--
				 --><li <?php if ($title=="Documentation") {echo 'class="current"';}?>><a href="documentation">DOCUMENTATION</a></li><!--
				 --><li <?php if ($title=="Contact") {echo 'class="current"';}?>><a href="contact">CONTACT</a></li>
				</ul>
			</nav>
			<div id="main" role="main">