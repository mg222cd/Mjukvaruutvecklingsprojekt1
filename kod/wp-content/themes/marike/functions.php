<?php
	
	register_sidebar(array(
		'name' => __('Right sidebar'),
		'id' => 'news_in_sidebar',
		'description' => 'sidebar with news feed',
		'before_widget' => '<div class="four columns omega" id="sidebar">',
		'after_widget' => '</div>',
	));
	
?>

