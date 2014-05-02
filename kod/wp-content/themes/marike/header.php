<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<meta name="description" content="   	
		Nybyggt hus i centrala Ramundberget. 50 meter till lift och slalombackar, 
		50 m till längdspår, 50 meter till livsmedelsaffär och 100 meter till Ramundbergets centrum.">
	<meta name="keywords" content="Ramundberget, Ramundberget boende">
	<meta name="author" content="Ramundboende.se">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
	<link rel="stylesheet" href="<?php bloginfo('stylesheets/base.css'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('stylesheets/skeleton.css'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('stylesheets/layout.css'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('style.css'); ?>">
	
	<!-- Favicon TODO:lägg in.
	================================================== -->
	<link rel="shortcut icon" href="">
	<link rel="apple-touch-icon" href="">
<?php wp_head(); ?>
</head>

<body>
	<div class="container">
		<!-- HEADER -->
		<div class="sixteen columns">
			<div class="ten columns alpha">
			<img src="images/logo_ramund_small.png" alt="logga ramundboende" />
			</div>
			<nav>
			<div class="six columns omega">
				<ul>
					<li><a href="<?php echo get_option('Ramundberget'); ?>"Hem</a></li>
					<?php wp_list_pages('title_li=' ); ?>
					<!--
					<li class="not_last"><a href="index.html" class="active">hem</a></li>
					<li class="not_last"><a href="#">nyheter</a></li>
					<li class="not_last"><a href="#">boenden</a></li>
					<li class="not_last"><a href="#">priser</a></li>
					<li><a href="#">bokning</a></li>
					-->
				</ul>
			</div>
			</nav>
		</div><!-- sixteen columns -->