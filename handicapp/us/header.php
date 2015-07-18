<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Product" lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="description" content="HandicApp is an Android app that allows you to interact very easily with anyone via a voice system." />
		<meta name="viewport" content="initial-scale=1.0">
		<meta name="robots" content="all">
		<title><?php echo $title;?> - HandicApp</title>
		<link rel="stylesheet" media="all" href="../res/design.min.css" />
		<link rel="shortcut icon" href="../favicon.ico" />
	</head>
	<body>
		<header class="center" role="banner">
			<a href="../us" title="Home"><img itemprop="image" src="../res/images/logo.png" alt="logo" /><h1 itemprop="name">HandicApp</h1></a>
			<a id="lang" href="../fr/<?php if ($title=="The application") {echo 'application';} elseif ($title=="Documentation") {echo 'documentation';} elseif ($title=="Contact us") {echo 'contact';}?>" title="Version française"><img src="../res/images/fr.png" alt="FR" /></a>
			<a id="playstorelink" title="Play Store" target="_blank" href="https://play.google.com/store/apps/details?id=eu.romainpellerin.handicapp">
			  <img alt="Google Play" src="https://developer.android.com/images/brand/en_generic_rgb_wo_60.png" />
			</a>
		</header>
		<div id="wrapper">
			<nav id="top" role="navigation" class="center">
				<div id="nav_hidden">
					<a href="#top" class="nav-open" aria-hidden="true">Menu ▼</a>
					<a href="#nav_hidden" class="nav-close" aria-hidden="true">Menu ▲</a>
				</div>
				<ul>
					<li <?php if ($title=="Home") {echo 'class="current"';}?>><a href="../us">THE PROJECT</a></li><!--
				 --><li <?php if ($title=="The application") {echo 'class="current"';}?>><a href="application">THE APP</a></li><!--
				 --><li <?php if ($title=="Documentation") {echo 'class="current"';}?>><a href="documentation">DOCUMENTATION</a></li><!--
				 --><li <?php if ($title=="Contact us") {echo 'class="current"';}?>><a href="contact">CONTACT</a></li>
				</ul>
			</nav>
			<div id="main" role="main">