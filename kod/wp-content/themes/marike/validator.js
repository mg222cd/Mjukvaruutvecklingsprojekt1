/**
 * Author: Marike Grinde
 */

	var Validation = {
		
		//Godkända inputs:
		//TODO lägg in maxlength på dem som inte finns med i listan nedan.  
		passedZipcode: /^[0-9]{3}[-]{1}[0-9]{2}$/,
		passedPhone: /^[0-9]+$/,
		passedEmail: /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
				
				
		init: function(){
			
			//skapar element för fält:
			var form = document.getElementById("bookingForm");
			var name = document.getElementById("nameInput");
			var address = document.getElementById("addressInput");
			var postal = document.getElementById("postalInput");
			var city = document.getElementById("cityInput");
			var phone = document.getElementById("phoneInput");
			var email = document.getElementById("emailInput");
			
			//rensar formulär vid reload:
			form.reset();
			
			/*
			//tar bort skicka-knappen:
			var sendButton = document.getElementById("sendbutton");
			sendButton.parentNode.removeChild(sendButton);
			//...och lägger till länk:
			var sendspace = document.getElementById("sendspace")
			var aSendLink = document.createElement("a");
			aSendLink.href = "#";
			var textSendLink = document.createTextNode("Skicka");
			sendspace.appendChild(aSendLink);
			aSendLink.appendChild(textSendLink);
			*/
			
			//knapparna:
			var sendButton = document.getElementById("confirmBookingbutton");
			var breakButton = document.getElementById("breakBookingbutton");
			
			//event för skicka-länken:
			sendButton.onclick = function(){ //HÄR ÄR JAG!
				//om allt är rätt:
				if (firstName.value.match(Validation.passedFirstname) 
				&& lastName.value.match(Validation.passedLastname) 
				&& zipCode.value.match(Validation.passedZipcode) 
				&& phoneNumber.value.match(Validation.passedPhone) 
				&& eMail.value.match(Validation.passedEmail)) {
					//gå till funktion
					Validation.popup(firstName.value, lastName.value, zipCode.value, phoneNumber.value, eMail.value);
				}
				//om fel:
				else {
					return false;
				}
			}//function
			
			//variabler för tooltips:
			var firstNameToolTip = "Enbart bokstäver";
			var lastNameToolTip = "Enbart bokstäver";
			var zipCodeToolTip = "Ange postnummer på formen XXX-XX";
			var phoneNumberToolTip = "Enbart siffror";
			var eMailToolTip = "Ange en korrekt e-postadress";
			
			//när det skrivits i ett fält anropas funktion för kontroll:
			firstName.onblur = function(){
				Validation.verification(firstName.value, Validation.passedFirstname, firstName.parentNode);
				Validation.removeTooltips();
			}
			lastName.onblur = function(){
				Validation.verification(lastName.value, Validation.passedLastname, lastName.parentNode);
				Validation.removeTooltips();
			}
			zipCode.onblur = function(){
				Validation.verification(zipCode.value, Validation.passedZipcode, zipCode.parentNode);
				Validation.removeTooltips();
			}
			phoneNumber.onblur = function(){
				Validation.verification(phoneNumber.value, Validation.passedPhone, phoneNumber.parentNode);
				Validation.removeTooltips();
			}
			eMail.onblur = function(){
				Validation.verification(eMail.value, Validation.passedEmail, eMail.parentNode);
				Validation.removeTooltips();
			}
			
			//när ett fält fokuseras visas tooltips:
			firstName.onfocus = function(){
				Validation.toolTips(Validation.findPos(firstName), firstNameToolTip);
			}
			lastName.onfocus = function(){
				Validation.toolTips(Validation.findPos(lastName), lastNameToolTip);
			}
			zipCode.onfocus = function(){
				Validation.toolTips(Validation.findPos(zipCode), zipCodeToolTip);
			}
			phoneNumber.onfocus = function(){
				Validation.toolTips(Validation.findPos(phoneNumber), phoneNumberToolTip);
			}
			eMail.onfocus = function(){
				Validation.toolTips(Validation.findPos(eMail), eMailToolTip);
			}
			
								
		}, //init.
		
		//funktion som kontrollerar inmatad text:
		verification: function(input, passedInput, roomForStar){
			var star = document.createElement("span");
			var visibleStar = document.createTextNode("*");
			
			//vid godkänd input:
			if(input.match(passedInput)){
				return function(){
					//kontrollerar om stjärna redan finns
					if (roomForStar.lastChild.className === "star"){
						//tar bort:
						roomForStar.removeChild(roomForStar.lastChild);
					}
				}(); //function
			} //if
			
			//vid felaktig input:
			else{
				return function(){
					//kontrollerar om stjärna finns:
					if(roomForStar.lastChild.className === "star"){
						return Validation.init;
					}
					//annars läggs stjärna till här:
					else {
						star.className = "star";
						star.appendChild(visibleStar);
						roomForStar.appendChild(star);
					} //else
				}(); //function
			} //else
		}, //verification.
		
		toolTips: function(pos, currentToolTip){
			//raderar gamla tooltips:
			var body = document.body;
			if (body.lastChild.className === "tooltips"){
				body.removeChild(body.lastChild);
			}
			//visar nytt tooltip för aktuell rad:
			//skapar och applicerar element:
			var div = document.createElement("div");
			var span = document.createElement("span");
			var shownToolTip = document.createTextNode(currentToolTip);
			div.className = "tooltips";
			body.appendChild(div);
			div.appendChild(span);
			span.appendChild(shownToolTip);
			//positionen:
			div.style.left = pos[0] + "px";
			div.style.top = pos[1]+25 + "px";
			
			//tar bort tooltip:
			this.onblur = function () {	
	        body.removeChild(div);
			};			
		}, //toolTips
		
		removeTooltips: function(){
			var body = document.body;
			if (body.lastChild.className === "tooltips") {
				body.removeChild(body.lastChild);
			}
		},//removeTooltips
		
		//hittar positionen på objektet:
		findPos: function (obj) {
			var curleft = 0;
			var curtop = 0;
			if (obj.offsetParent){
				do {
					curleft += obj.offsetLeft;
					curtop += obj.offsetTop;
			 	} while (obj = obj.offsetParent);
				return [curleft,(curtop + 20)];
			}
		},
		
		//funktion för att visa bekräftelserutan:
		popup: function(firstNameInput, lastNameInput, zipCodeInput, phoneNumberInput, eMailInput){
			//(fName, lName, zip, phone, mail)
			
			//nödvändiga element:
			var form = document.getElementById("form");
			var body = document.body;
			var grayscale = document.createElement("div");
			grayscale.className = "grayscale";
			var popup = document.createElement("div");
			popup.className = "popup";
			//p-taggar för fälten:
			var pFirstName = document.createElement("p");
			var pLastName = document.createElement("p");
			var pZipCode = document.createElement("p");
			var pPhoneNumber = document.createElement("p");
			var pEmail = document.createElement("p");
			//text i fälten:
			var textFirstName = document.createTextNode("Förnamn: " + firstNameInput);
			var textLastName = document.createTextNode("Efternamn: " + lastNameInput);
			var textZipCode = document.createTextNode("Postnummer: " + zipCodeInput);
			var textPhoneNumber = document.createTextNode("Telefonnummer: " + phoneNumberInput);
			var textEmail = document.createTextNode("Epost: " + eMailInput);
			//knapparna:
			var buttonChange = document.createElement("input");
			buttonChange.type = "button";
			buttonChange.value = "Ändra uppgifter";
			var buttonProceed = document.createElement("input");
			buttonProceed.type = "button";
			buttonProceed.value = "Gå vidare";
			//lägger till skugga och popup:
			body.appendChild(grayscale);
			body.appendChild(popup);
			//lägger till p-taggarna:
			popup.appendChild(pFirstName);
			popup.appendChild(pLastName);
			popup.appendChild(pZipCode);
			popup.appendChild(pPhoneNumber);
			popup.appendChild(pEmail);
			//lägger till text i dem:
			pFirstName.appendChild(textFirstName);
			pLastName.appendChild(textLastName);
			pZipCode.appendChild(textZipCode);
			pPhoneNumber.appendChild(textPhoneNumber);
			pEmail.appendChild(textEmail);
			//lägger till knappar:
			popup.appendChild(buttonChange);
			popup.appendChild(buttonProceed);
			//event för knapparna:
			buttonProceed.onclick = function(){
				form.submit();
			}
			buttonChange.onclick = function(){
				document.body.removeChild(popup);
				document.body.removeChild(grayscale);
				
			}
		}//popup
				
		}; //Validation-obj

	//gör så att denna funktion startas direkt sidan laddats:
	window.onload = Validation.init;
	