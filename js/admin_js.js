


var startDate = new Date('01/01/2012');
var FromEndDate = new Date();
var ToEndDate = new Date();

ToEndDate.setDate(ToEndDate.getDate()+365);

$('.from_date').datepicker({

    weekStart: 1,
    startDate: '01/01/2012',
    endDate: FromEndDate, 
    autoclose: true
})
    .on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.to_date').datepicker('setStartDate', startDate);
    }); 
$('.to_date')
    .datepicker({

        weekStart: 1,
        startDate: startDate,
        endDate: ToEndDate,
        autoclose: true
    })
    .on('changeDate', function(selected){
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('.from_date').datepicker('setEndDate', FromEndDate);
    });


    $("#button").click(function(){
          
        	var fromD = $('.from_date').val();
        	var toD = $('.to_date').val();

        	$("#start").append(fromD);
        	$("#end").append(fromD);

        	
        	
        	$("#conformation").css({"visibility" : "visible", "opacity" : "1.5", "z-index" : "3"});

        	$(this).prop('disabled', true);
        	


        });

    $('#reset').click(function() {
    location.reload();
});

 





	$('#conformationButton').click(function(){

		var fromD = $('.from_date').val();
	    var toD = $('.to_date').val();

	    if ( $('.from_date').val()){
	    	
	    }
	    else{
	    	alert ("please select start date");
	    };

	     if ( $('.to_date').val()){
	    	
	    }
	    else{
	    	alert ("please select end date");
	    };

	    /********************************/

	    	/* Add some jqurey functions */

	    /*********************************/


 	});
		
	/*
	$("#openR").click(function() {

		var reg_stataus = $("#openR").prop('name');

		$.post('setting.php', { status : reg_stataus }, function(data){

			alert(data);



		});
			

	});*/

	function switchReg(){

		//var reg_stataus = $("#closeR").val();  //$("#closeR").prop('name');

		var reg_stataus = "dummy data";

		$.post('setting.php', { reg_status : status  }, function(data){

			//swal("malai baal");
			location.reload(true);
			
		}); 


$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').focus()
})
		

			

	};