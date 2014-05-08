<?php
	
	register_sidebar(array(
		'name' => __('Right sidebar'),
		'id' => 'news_in_sidebar',
		'description' => 'sidebar with news feed',
		'before_widget' => '<div class="four columns omega" id="sidebar">',
		'after_widget' => '</div>',
	));
	
?>

<?php
plugins_url( 'MasterController.php', __FILE__ );
function renderBooking(){
	$masterController = new MasterController();
	$plugin = $masterController->DoControll();
	return $plugin;
}
add_shortcode( 'booking', 'renderBooking' );
?>