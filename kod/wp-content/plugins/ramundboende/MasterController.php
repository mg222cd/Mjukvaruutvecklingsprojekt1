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
	if (isset($_POST['confirmBookingButton'])) {
		$output = $masterController->submitForm();
	}
	$plugin = $masterController->viewUnbookedList();
	$plugin .= $masterController->newCustomerForm($output);
	return $plugin;
}
add_shortcode( 'booking', 'renderBooking' );

function addScript(){
	wp_enqueue_script( 'jquery', plugins_url('jquery.js',__FILE__ ), false, '1.11.1');
	wp_enqueue_script( 'validator', plugins_url('validator.js',__FILE__ ), array('jquery'), '1');
	wp_enqueue_script( 'script', plugins_url('script.js',__FILE__ ), array('jquery','validator'), '1');
}
add_action( 'wp_enqueue_scripts', 'addScript' );


require_once ("Booking.php");
class MasterController {
	private function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
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
			$table.= '<td><input type="hidden" value="'.$bokningsrad->BookingId.'"><input class="bookingButton" name="bookingButton" type="submit" value="Boka"></td>';
			$table.= '</tr>';
		}
		$table.= '</table>';
		$table.='</div>';
		return $table;
	}
	
	public function newCustomerForm($submitForm = null){
		return'<div id="overlay" class="hideForm">
					<h4>Bokning av <span class="span_propertyname"></span> vecka <span class="span_week"></span> år <span class="span_year"></span>. 
					Pris <span class="span_price"></span></h4>
					<p>Bekräfta bokningen genom att ange dina kontaktuppgifter nedan.</p>
					<div class="modal_container">
						<div class="modal_first_column">
						<form id="bookingForm" method="POST" action="'.$this->curPageURL().'">
						<input type="hidden" value="" id="hiddenId">
    					<div class="form_div">
    						<p id="nameInput">
					  		<label for="regularInput">Namn</label>
					  		<input type="text" id="regularInputName" name="regularInputName" data-validator="required|min:2|max:255" />
					  		</p>
					  	</div>
					  	<div class="form_div">
					  		<p id="addressInput">
					  		<label for="regularInput">Adress</label>
					  		<input type="text" id="regularInputAddress" name="regularInputAddress" data-validator="required|min:2|max:255" />
					  		</p>
					  	</div>
					  	<div class="form_div">
					  		<p id="postalInput">
					  		<label for="regularInput">Postnummer</label>
					  		<input type="text" id="regularInputPostal" name="regularInputPostal" data-validator="required|min:2|max:255" />
					  		</p>
					  	</div>
							<p id="cityInput">
							<div class="form_div">
					  		<label for="regularInput">Ort</label>
					  		<input type="text" id="regularInputCity" name="regularInputCity" data-validator="required|min:2|max:255" />
					  		</p>
					  	</div>
					  	<div class="form_div">
					  		<p id="phoneInput">
					  		<label for="regularInput">Telefonnummer</label>
					  		<input type="text" id="regularInputPhone" name="regularInputPhone" data-validator="required|min:2|max:255" />
					  		</p>
					  	</div>
					  	<div class="form_div">
					  		<p id="mailInput">
					  		<label for="regularInput">E-mail</label>
					  		<input type="text" id="regularInputEmail" name="regularInputEmail" data-validator="required|pattern:email|max:255" />
					  		</p>
					  	</div>
					  <button type="submit" id="confirmBookingbutton" name="confirmBookingButton">Bekräfta</button>
					  <button type="submit" id="breakBookingbutton" name="breakBookingButton">Avbryt</button>
					</form>
					</div>
					</div>	
				</div>
		<div id="fade"></div>
		';
	}

	public function submitForm(){
		$name = $_POST['regularInputName'];
		$address = $_POST['regularInputAddress'];
		$postal = $_POST['regularInputPostal'];
		$city = $_POST['regularInputCity'];
		$phone = $_POST['regularInputPhone'];
		$email = $_POST['regularInputEmail'];
		
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
	
		if ( isset( $email ) && $email !== '' && preg_match($regex, $email) ) {
			
		}
			
	}
}

