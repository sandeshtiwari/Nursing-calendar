//$('.collapse').collapse();

function process(courseID, roomID, weekID){

	alert ("the course is : "+ courseID + " the room is : " + roomID + " and the week is : " + weekID);

	$.post('modefy_helper.php', { courseID: courseID, 
								 roomID: roomID, 	
								 weekID: weekID
								 	 }, function(data){

		$('#name_feedback').html(data);



		});

	
}


