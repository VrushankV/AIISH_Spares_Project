function passValidation(){
	var pass = document.getElementById("pass").value;
	var confpass = document.getElementById("confpass").value;

	if(pass!= confpass){
		document.getElementById("pass").style.borderColor = "#E34234";
		document.getElementById("confpass").style.borderColor = "#E34234";
		document.getElementById("submit").disabled = true;
		console.log(confpass);
	}
	else{
		document.getElementById("submit").disabled = false;
	}
}