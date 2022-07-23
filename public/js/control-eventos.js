$(document).ready(function(){
	
	/*Cerrar caja de notificaciones*/
	$("#cerrar-mensaje").click(function(event){
		event.preventDefault();
		$("#mensaje").hide('slow');		
	});	 		
});