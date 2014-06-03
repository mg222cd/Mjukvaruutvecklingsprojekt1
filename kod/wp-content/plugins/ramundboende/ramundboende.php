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
	wp_enqueue_style( 'style', plugins_url('style.css', __FILE__) );
}
add_action( 'wp_enqueue_scripts', 'addScript' );

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
		//var_dump(date('W'));
		foreach ($stugor as $stuga) {
			$bokningar[] = $wpdb->get_results( "SELECT * FROM wp_ramundboende_booking WHERE CustomerId=1 AND PropertyId=".$stuga->PropertyId." AND Week >= ".date('W')." OR CustomerId=1 AND PropertyId=".$stuga->PropertyId." AND Year >= ".date('Y')." ORDER BY Year, Week" );	
		}
		$output ="";
		$countBooking = 0;
		for ($i=0; $i < count($stugor); $i++) {
			$countBooking += count($bokningar[$i]);
			if (count($bokningar[$i]) > 0) {
				$output.= $this->renderTable($stugor[$i], $bokningar[$i]);
			}	 
		}
		if ($countBooking < 1) {
			$output .= '<div class="error">För tillfället är alla veckor fullbokade.</div>';
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
			$table.= '<td class="table_info">'.$bokningsrad->Price.':-</td>';
			$table.= '<td ><input type="hidden" value="'.$bokningsrad->BookingId.'"><input class="bookingButton" name="bookingButton" type="submit" value="Boka"></td>';
			$table.= '</tr>';
		}
		$table.= '</table>';
		$table.='</div>';
		return $table;
	}
	
	public function newCustomerForm($submitForm = null){
		$name = $this->filterInput($_POST['regularInputName']);
		$address = $this->filterInput($_POST['regularInputAddress']);
		$postal = $this->filterInput($_POST['regularInputPostal']);
		$city = $this->filterInput($_POST['regularInputCity']);
		$phone = $this->filterInput($_POST['regularInputPhone']);
		$email = $this->filterInput($_POST['regularInputEmail']);
		$hidden = $_POST['hiddenId'];
		
		$class = "";
		if (is_null($submitForm)) {
			$class="hideForm";
		}
		global $wpdb;
		$stuga = $wpdb->get_results( "SELECT * FROM wp_ramundboende_property WHERE PropertyId =".$submitForm[2][0]->PropertyId );
		$h4 = '<h4>Bokning av <span class="span_propertyname">'.$stuga[0]->PropertyName.'</span> vecka <span class="span_week">'.$submitForm[2][0]->Week.'</span> 
				år <span class="span_year">'.$submitForm[2][0]->Year.'</span>. 
				Pris <span class="span_price">'.$submitForm[2][0]->Price.':-</span></h4>';
		$form = '<p>Bekräfta bokningen genom att ange dina kontaktuppgifter nedan.</p>
				<form id="bookingForm" method="POST" action="'.$this->curPageURL().'">
						<input type="hidden" name="hiddenId" value="'.$hidden.'" id="hiddenId">

					  		<label for="regularInput">Namn</label>
					  		<input type="text" id="regularInputName" maxlength="40" name="regularInputName" value="'.$name.'" data-validator="required|min:2|max:255" />

					  		<label for="regularInput">Adress</label>
					  		<input type="text" id="regularInputAddress" maxlength="40" name="regularInputAddress" value="'.$address.'" data-validator="required|min:2|max:255" />

					  		<label for="regularInput">Postnummer</label>
					  		<input type="text" id="regularInputPostal" maxlength="6" name="regularInputPostal" value="'.$postal.'" data-validator="required|min:2|max:255" />

					  		<label for="regularInput">Ort</label>
					  		<input type="text" id="regularInputCity" maxlength="25" name="regularInputCity" value="'.$city.'" data-validator="required|min:2|max:255" />

					  		<label for="regularInput">Telefonnummer</label>
					  		<input type="text" id="regularInputPhone" maxlength="20" name="regularInputPhone" value="'.$phone.'" data-validator="required|min:2|max:255" />

					  		<label for="regularInput">E-mail</label>
					  		<input type="text" id="regularInputEmail" maxlength="50" name="regularInputEmail" value="'.$email.'" data-validator="required|pattern:email|max:255" />

					  <button type="submit" id="confirmBookingbutton" name="confirmBookingButton">Bekräfta</button>
					  <button type="submit" id="breakBookingbutton" name="breakBookingButton">Avbryt</button>
					</form>';
		if ($submitForm[1]) {
			//lyckad bokning
			$h1 = "<h1>Tack för din bokning!</h1>".$h4;
			//$button = '<button type="submit" id="breakBookingbutton" name="breakBookingButton">Återgå</button>';
			$button = '<a class="button" href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'">Återgå</a>';
			$p = '<p>'.$name.'<br>';
			$p .= $address.'<br>';
			$p .= $postal.' '.$city.'<p>';
			$p .= '<p>Bokningsbekräftelse har skickas till '.$email.'. Får ni ingen bekräftelse, kontrollera även skräpposten.</p>';
			$message=array($h1,$p.$button);
		}
		else {
			if ($message = "Dubbelbokning!") {
				$message = "det här är en dubbelbokning";
			} 
			else {
				$h1 = $h4.'<div class="red_text">'.$submitForm[0].'</div>';
				$message = array($h1,$form);	
			}
		}
		return'<div id="overlay" class="'.$class.'">
					'.$message[0].'
					<div class="modal_container">
						<div class="modal_first_column">
						'.$message[1].'
					</div>
					</div>	
				</div>
		<div id="fade"></div>
		';
	}

	public function submitForm(){
		$name = $this->filterInput($_POST['regularInputName']);
		$address = $this->filterInput($_POST['regularInputAddress']);
		$postal = $this->filterInput($_POST['regularInputPostal']);
		$city = $this->filterInput($_POST['regularInputCity']);
		$phone = $this->filterInput($_POST['regularInputPhone']);
		$email = $this->filterInput($_POST['regularInputEmail']);
		$hidden = $_POST['hiddenId'];
		
		$boolean = false;
		$message = "";
	
		global $wpdb;
		$booking = $wpdb->get_results( "SELECT * FROM wp_ramundboende_booking WHERE BookingId=".$hidden);
		
		//säkerhetskontroll för att undvika dubbelbokning
		if ($booking[0]->CustomerId == 2) {
			if (empty($name) || empty($address) || empty($postal) || empty($city) || empty($phone) || empty($email)) {
			$message = "Inget fält får lämnas tomt.";
			return array($message, $boolean, $booking);
			}
			if ($this->checkEmail($email)) {
				$wpdb->insert( 'wp_ramundboende_customer', array('Name' => $name, 'Address' => $address, 'Postal' => $postal, 
																'City' => $city, 'Phone' => $phone, 'Email' => $email ) );
				$wpdb->update( 'wp_ramundboende_booking', array('CustomerId' => $wpdb->insert_id), array('BookingId' => $hidden) );	
				$stuga = $wpdb->get_results( "SELECT * FROM wp_ramundboende_property WHERE PropertyId=".$booking[0]->PropertyId);
				$sendinfo = array(
					'customerInfo'=>array(
						'Name' => $name, 
						'Address' => $address, 
						'Postal' => $postal, 
						'City' => $city, 
						'Phone' => $phone, 
						'Email' => $email 
					), 
					'stuga'=>$stuga[0]->PropertyName, 
					'stugnr'=>$stuga[0]->PropertyId,
					'week'=>$booking[0]->Week, 
					'year'=>$booking[0]->Year, 
					'price'=>$booking[0]->Price
				);
				$boolean = $this->sendToCustomer($sendinfo) && $this->sendToAdmin($sendinfo);
				$message = "";
			}	
			else {
				
				$message = "Ogiltigt format på E-postadressen.";
			}
		} 
		else {
			$message = "Dubbelbokning!";
			$boolean = false;
		}
		return array($message, $boolean, $booking);
	}
	
	private function checkEmail($email){
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		return isset( $email ) && $email !== '' && preg_match($regex, $email);
	}
	
	private function filterInput($input){
		global $wpdb;
		$input = $wpdb->escape($input);
		$input = strip_tags($input);
		return $input;
	}
	
	private function sendMail($email, $subject, $message){
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/HTML; charset=utf-8\r\n";
		$headers .= 'From: info@ramundboende.se' . "\r\n" .
    	'Reply-To: info@ramundboende.se' . "\r\n";
		return wp_mail($email, $subject, $message, $headers);
	}
	
	private function sendToCustomer($data=array('customerInfo'=>array(), 'stuga'=>'', 'stugnr'=>'', 'week'=>'', 'year'=>'', 'price'=>'')){
		$subject = "Bokningsbekräftelse";
		$message = '<h1>Bokningsbekräftelse</h1>';
		$message .= '<h2>Stuga nr '.$data[stugnr].' - '.$data['stuga'].'. Vecka: '.$data['week'].', år: '.$data['year'].'. Pris: '.$data['price'].':-'. "</h2>";
		$message .= $data['customerInfo']['Name'].'<br>';
		$message .= $data['customerInfo']['Address'].'<br>';
		$message .= $data['customerInfo']['Postal'].' '.$data['customerInfo']['Postal'].'<br><br>';
		$message .= 'Telefon: ' .$data['customerInfo']['Phone'].'<br>';
		$message .= 'Mail: ' .$data['customerInfo']['Email'].'<br><br><br>';
		$message .= '<p>In/utcheckningsdag under vintersäsongen före påskveckan är lördag, efter påskveckan söndag. Övriga veckor lördag.
					Incheckning tidigast kl 15 ankomstdagen och utcheckning senast kl 11 avresedagen. Nyckeln sitter i dörren vid ankomst.
					Adress: Klinkvägen 2, 84097 Bruksvallarna. Vägbeskrivning: 50 m efter Ramundbergets centrum, första till höger efter 
					avfarten till Ramis Livs.</p>';
		$message .= '<p>Bokningsavgift 1500:- betalas inom 14 dagar, resterande summa senast 30 dagar före ankomst. Bankgiro: 5903-8596 Bruksvallarnas Semesterboende AB,
					märk betalningen med namn och veckonummer.</p>';
		$message .= '<p>Sänglinne och handdukar ingår ej. Önskas slutstädning ska detta anmälas i förhand, pris för detta är <br>
					Stuga 1 - Lillstugan: 900:-<br>
					Stuga 2 - Storstugan: 2500:-</p>';
		$message .= '<p>Hör av er vid frågor (kontaktuppgifter nedan).</p>';		
		$message .= '<p>Välkommen till Ramundberget!</p>';
		$message .= '<p>Bruksvallarna '.date('Y-m-d').'<br>';
		$message .= 'Med vänlig hälsning,</p>';
		$message .= '<p>Andreas Hoflin<br>
					Bruksvallarnas Semesterboende AB<br>
					Gruvarbetarvägen 10<br>
					840 97 Bruksvallarna<br><br>
					Mail: info@ramundboende.se<br>
					Telefon: 070-3251974 / 070-6221065
					</p>';		
		$message .= '</HTML>';
		$email = $data['customerInfo']['Email'];
		return $this->sendMail($email, $subject, $message);
	}
	
	private function sendToAdmin($data=array('customerInfo'=>array(), 'stuga'=>'', 'stugnr'=>'', 'week'=>'', 'year'=>'', 'price'=>'')){
		$subject = "Bokningsbekräftelse";
		$message = '<h1>Bokningsbekräftelse</h1>';
		$message .= '<h2>Stuga nr '.$data[stugnr].' - '.$data['stuga'].'. Vecka: '.$data['week'].', år: '.$data['year'].'. Pris: '.$data['price'].':-'. "</h2>";
		$message .= $data['customerInfo']['Name'].'<br>';
		$message .= $data['customerInfo']['Address'].'<br>';
		$message .= $data['customerInfo']['Postal'].' '.$data['customerInfo']['Postal'].'<br><br>';
		$message .= 'Telefon: ' .$data['customerInfo']['Phone'].'<br>';
		$message .= 'Mail: ' .$data['customerInfo']['Email'].'<br><br><br>';
		$message .= '<p>In/utcheckningsdag under vintersäsongen före påskveckan är lördag, efter påskveckan söndag. Övriga veckor lördag.
					Incheckning tidigast kl 15 ankomstdagen och utcheckning senast kl 11 avresedagen. Nyckeln sitter i dörren vid ankomst.
					Adress: Klinkvägen 2, 84097 Bruksvallarna. Vägbeskrivning: 50 m efter Ramundbergets centrum, första till höger efter 
					avfarten till Ramis Livs.</p>';
		$message .= '<p>Bokningsavgift 1500:- betalas inom 14 dagar, resterande summa senast 30 dagar före ankomst. Bankgiro: 5903-8596 Bruksvallarnas Semesterboende AB,
					märk betalningen med namn och veckonummer.</p>';
		$message .= '<p>Sänglinne och handdukar ingår ej. Önskas slutstädning ska detta anmälas i förhand, pris för detta är <br>
					Stuga 1 - Lillstugan: 900:-<br>
					Stuga 2 - Storstugan: 2500:-</p>';
		$message .= '<p>Hör av er vid frågor (kontaktuppgifter nedan).</p>';		
		$message .= '<p>Välkommen till Ramundberget!</p>';
		$message .= '<p>Bruksvallarna '.date('Y-m-d').'<br>';
		$message .= 'Med vänlig hälsning,</p>';
		$message .= '<p>Andreas Hoflin<br>
					Bruksvallarnas Semesterboende AB<br>
					Gruvarbetarvägen 10<br>
					840 97 Bruksvallarna<br><br>
					Mail: info@ramundboende.se<br>
					Telefon: 070-3251974 / 070-6221065
					</p>';		
		$message .= '</HTML>';
		$email = get_option('admin_email');
		return $this->sendMail($email, $subject, $message);
	}
}

