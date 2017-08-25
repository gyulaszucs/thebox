<html>
	<head>
	<link rel="stylesheet" href="style.css">	
	<script src="jquery.js" type="text/javascript"></script>



	</head>
	<body>
		
		<div id="wrapper">
			<form id="pi_form" method="POST" action="checkpin.php">
				
				<input id="input_1" maxlength="1" type="text">
				<input id="input_2" maxlength="1" type="text">
				<input id="input_3" maxlength="1" type="text">
				<input id="input_4" maxlength="1" type="text">
				<input id="input_5" maxlength="1" type="text">
				<input id="input_6" maxlength="1" type="text">
				<input id="pi_hidden" type="hidden" name="pincode">

				<input type="submit" value="OK" name="pi_submit" id="pi_submit">
				<input type="reset" value="RESET" name="" id="">
			</form>
		</div>
		
	</body>
	
</html>

<script>

(function(){

$(document).ready(function() {

	$('#input_1').focus();
   
});


$('#pi_form > input[type="reset"]').on('click', function(){

	$('#input_1').focus();

});


$('#pi_form > input[type="text"]').on('input', function(){


var letters = $(this).val().length;
console.log(letters);

if (letters == 1){
	$(this).next().focus();

}
else{

	$(this).focus();
}
console.log($(this).val());

$('#pi_hidden').val($('#input_1').val()+$('#input_2').val()+$('#input_3').val()+$('#input_4').val()+$('#input_5').val()+$('#input_6').val());

console.log($('#pi_hidden').val());

});



})();

	
</script>