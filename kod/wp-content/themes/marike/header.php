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
  
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/stylesheets/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/stylesheets/skeleton.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/stylesheets/layout.css" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
	
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
			<div class="six columns alpha">
				<img src="<?php bloginfo('url');?>/wp-content/themes/marike/images/logo_ramund_small.png" />
			</div>
			<nav>
			<div class="ten columns omega">
				<ul class="navigation_links">
					<?php wp_list_pages('title_li=' ); ?>
				</ul>
			</div>
			</nav>
		</div><!-- sixteen columns -->
		
		<!-- CONTENT -->
		<div class="sixteen columns">