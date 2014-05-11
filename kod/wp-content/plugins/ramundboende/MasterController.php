<?php
/*
Plugin Name: Ramundboende
Plugin URI: http://www.ramundboende.se
Description: Bokningsplugin till sidan ramundboende.se
Version: 1.0
Author: Marike Grinde
Author URI: http://www.marike.se
*/
function renderBooking(){
	$masterController = new MasterController();
	$plugin = $masterController->viewUnbookedList();
	return $plugin;
}
add_shortcode( 'booking', 'renderBooking' );


require_once ("Booking.php");
class MasterController {
	
	public function viewUnbookedList(){
		global $wpdb;
		$stugor = $wpdb->get_results( "SELECT * FROM wp_ramundboende_property" );
		$bokningar= array();
		foreach ($stugor as $stuga) {
			$bokningar[] = $wpdb->get_results( "SELECT * FROM wp_ramundboende_booking WHERE CustomerId=1 AND PropertyId=".$stuga->PropertyId." ORDER BY Year, Week" );	
		}
		$output ="";
		for ($i=0; $i < count($stugor); $i++) { 
			$output.= $this->renderTable($stugor[$i], $bokningar[$i]);
		}
		$output.=$this->newCustomerForm();
		return $output;
	}
	
	public function renderTable($stuga, $bokning){
		$table = "";	
		$table.= '<div class="six columns alpha">';
		$table.= '<h3>Stuga '.$stuga->PropertyId.' - '.$stuga->PropertyName.'</h3>';
		$table.= '<table class="unbooked_list">';
		$table.= '<tr class="table_headers">';
		$table.= '<td class="table_info">År</td>';
		$table.= '<td class="table_info">Vecka</td>';
		$table.= '<td class="table_info">Pris</td>';
		$table.= '<td></td>';
		$table.= '</tr>';
		foreach ($bokning as $bokningsrad) {
			$table.= '<tr>';
			$table.= '<td class="table_info">'.$bokningsrad->Year.'</td>';
			$table.= '<td class="table_info">'.$bokningsrad->Week.'</td>';
			$table.= '<td class="table_info">'.$bokningsrad->Price.'</td>';
			$table.= '<td><input type="hidden" value="'.$bokningsrad->BookingId.'"><input id="bookingButton" name="bookingButton" type="submit" value="Boka"></td>';
			$table.= '</tr>';
		}
		$table.= '</table>';
		$table.='</div>';
		return $table;
	}
	
	public function newCustomerForm(){
		return'<div id="overlay">
    	This is where you will put your inline HTML for the content inside of the overlay
		</div>
		<div id="fade"></div>
		';
	}
}

