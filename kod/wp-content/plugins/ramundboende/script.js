jQuery(document).ready(function($){
	if($.fn.validator) {
		$('#bookingForm').validator();
	}
	
	//information i rubriken
	$('.bookingButton').click(function(event){
		event.preventDefault();
		//hämta information
		var row = $(this).parent().parent().children();
		var year = row.eq(0).text();
		var week = row.eq(1).text();
		var price = row.eq(2).text();
		var propertyname = $(this).parent().parent().parent().parent().parent().find('h3').text();
		var bookingId = $(this).parent().children().eq(0).val();
		//lägg in infon i koden
		$('.span_year').text(year);
		$('.span_week').text(week);
		$('.span_price').text(price);
		$('.span_propertyname').text(propertyname);
		$('#hiddenId').val(bookingId);
		//visar 
		$('#overlay').toggleClass('hideForm');
	});
	
	//avbryt-knappen
	$('#breakBookingbutton').click(function(event){
		event.preventDefault();
		//gömmer
		$('#overlay').toggleClass('hideForm');
	});
});