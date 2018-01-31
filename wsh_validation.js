//wsh_validation.js

/**
	Checks to see if fields have been filled. Assigns and removes "invalid" depending on input.
*/

function checkPassword(element) {
	
	var legalPass = /^.{6,20}$/;		//any char, b/w 6-20 chars
	if(!legalPass.test(element.value)) {
		document.getElementById("passwordMsg").innerHTML = " Password must be between 6-20 characters long."
		element.focus();
		element.select();
	}
	else {
		document.getElementById("passwordMsg").innerHTML = "";
	}
}

//checks to see if confirmed password matches password

function confirmPassword(element) {
	var pass = document.getElementById('password');
	if(element.value != pass.value) {
		document.getElementById("confirmMsg").innerHTML = " Passwords do not match.";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("confirmMsg").innerHTML = "";
	}
}

//check if a given name is valid
function checkGivenName(element) {
	var legalName = /^[a-zA-Z\-\'\s]{1,20}$/;
	if(!legalName.test(element.value)) {
		document.getElementById("gnameMsg").innerHTML = " Given name must be no longer than 20 characters " +
			"and contain only alphabetical letters, hyphen, apostraphe, and space.";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("gnameMsg").innerHTML = "";
	}
}

//check if a surname is valid
function checkSurname(element) {
	var legalName = /^[a-zA-Z\-\'\s]{1,20}$/;
	if(!legalName.test(element.value)) {
		document.getElementById("snameMsg").innerHTML = " Family name must be no longer than 20 characters " +
			"and contain only alphabetical letters, hyphen, apostraphe, and space.";
		element.focus();
		element.select();
	}	
	else {
		document.getElementById("snameMsg").innerHTML = "";
	}
}


function checkAddress(element) {
	var legalAddress = /^.{0,40}$/;
	if(!legalAddress.test(element.value)) {
		document.getElementById("addressMsg").innerHTML = "Address must be no longer than 40 characters";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("addressMsg").innerHTML = "";
	}
}

function checkState(element) {
	if(element.selectedIndex == "") {
		document.getElementById("stateMsg").innerHTML = "A State must be chosen.";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("stateMsg").innerHTML = "";
	}
}


//check postcode validation
function checkPostcode(element) {
	var postcode = element.value;
	var ausPostcodeCheck = /^[0-9]{4}$/;
	if (!ausPostcodeCheck.test(postcode)) {
		document.getElementById("postcodeMsg").innerHTML = "Postcode must be 4 digits only.";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("postcodeMsg").innerHTML = "";
	}
}


function checkMobile(element) {
	var mobile = element.value;
	var mobileCheck = /^04[0-9]{8}$/;
	if (!mobileCheck.test(mobile)) {
		document.getElementById("mobileMsg").innerHTML = "Mobile number must with 10-digits and start with \'04\'";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("mobileMsg").innerHTML = "";
	}
}

function checkEmail(element) {	
	
	/*		https://regex101.com/r/kS6iR7/11
		word then single '@', then repeat characters that end with a '.', then end 2+ letters only
	*/
	var emailCheck = /^[\w]+@(?:[a-zA-Z0-9]+\.)+[a-zA-Z]{2,40}/;
	if (!emailCheck.test(element.value)) {
		document.getElementById("emailMsg").innerHTML = "Email must be no longer than 40 characters and may only contain one \'@\'.";
		element.focus();
		element.select();
	}
	else {
		document.getElementById("emailMsg").innerHTML = "";
	}
}

function validateForm(form) {
	var validForm = true;		//validForm returns false is any mand. fields are empty
	
	if (!checkSubmitText(document.getElementById('username'))) {
		document.getElementById("usernameMsg").innerHTML = "Username can not be empty.";
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('password'))) {
		document.getElementById("passwordMsg").innerHTML = " Password must be between 6-20 characters long."
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('confirm'))) {
		document.getElementById("confirmMsg").innerHTML = " Passwords do not match.";
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('gname'))) {
		document.getElementById("gnameMsg").innerHTML = " Given name must be no longer than 20 characters " +
			"and contain only alphabetical letters, hyphen, apostraphe, and space.";
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('sname'))) {
		document.getElementById("snameMsg").innerHTML = " Family name must be no longer than 20 characters " +
			"and contain only alphabetical letters, hyphen, apostraphe, and space.";
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('address'))) {
		document.getElementById("addressMsg").innerHTML = " Address must be no longer than 40 characters";
		validForm = false;
	}
	
	if (!checkSubmitDropdown(document.getElementById('state'))){
		document.getElementById("stateMsg").innerHTML = " A state must be chosen.";
		validForm = false;
	}
	
	// method to test postcode
	if (!checkSubmitText(document.getElementById('postcode'))) {
		document.getElementById("postcodeMsg").innerHTML = "Postcode must be 4 digits only.";
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('mobile'))) {
		document.getElementById("mobileMsg").innerHTML = "Mobile number must with 10-digits and start with \'04\'";
		validForm = false;
	}
	
	if (!checkSubmitText(document.getElementById('email'))) {
		document.getElementById("emailMsg").innerHTML = "Email must be no longer than 40 characters and may only contain one \'@\'.";
		validForm = false;
	}
	
	//if form is not valid to submit
	if (validForm == false){
		document.getElementById("errorDisplay").innerHTML = "This form has invalid fields. " +
		"Please refer to error messages beside invalid fields to fix this form.";
		return false;		//stops form from submitting
	}
	
	document.getElementById("errorDisplay").innerHTML = "";
	//return true if form is valid
	return true;
}

/**
	checks if element is filled
*/

function checkSubmitText(element) {
	if (element.value == "") 
		return false;		//field invalid
	else 
		return true;		//field valid
	
}

function checkSubmitDropdown(element) {
	if (element.selectedIndex == "")
		return false;		//field invalid
	else 
		return true;		//field valid
}
