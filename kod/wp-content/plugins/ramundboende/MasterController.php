<?php
/*
Plugin Name: Ramundboende
Plugin URI: http://www.ramundboende.se
Description: Bokningsplugin till sidan ramundboende.se
Version: 1.0
Author: Marike Grinde
Author URI: http://www.marike.se
*/
require_once ("Booking.php");
require_once(ABSPATH . '/wp-load.php');
class MasterController {
	
	public function DoControll(){
		$xhtml = 'testar skicka tillbaka grejer med shortcode';
		return $xhtml;
	}
	
}

