<?php
/*
Plugin Name: Ramundboende
Plugin URI: http://www.ramundboende.se
Description: Bokningsplugin till sidan ramundboende.se
Version: 1.0
Author: Marike Grinde
Author URI: http://www.marike.se
*/



function render( ) {
     return 'testar pluginet här';
}
add_shortcode('booking', 'render');
?>



<?php
/*
global $wpdb;
$customers = $wpdb->get_results("SELECT * FROM customers;");
print_r($customers);
*/