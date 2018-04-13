function changeRole() {

	var input = event.target.name;
	var array = input.split(" ");
	var Prefix = array[0];
	var Num = array[1];

	alert ("prefix is : " + Prefix + "and Number is : " + Num +  " was clicked");  

	alert(event.target.name);

	var b = Prefix+Num;


	
	var a = "'#" + b + " option:selected' ";
	var value = $('#CSCI4060');

	alert (b);

	alert ($(a).text());
                 
}

function myFunction(string){

	 //event.preventDefault();


	var input = " #" + string + " option:selected";

	var teachName = $( input ).text();

	var array = teachName.split(" ");
	var Fname = array[0];
	var Lname = array[1];

	alert (teachName);

	$.post('cleanSetting.php', { Fname: Fname, 
								 Lname: Lname, 	
								 class: string	 }, function(data){

		$('#name_feedback').html(data);
		
	});


	
   



	
}