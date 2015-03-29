function lightboxCerrar(){
	$(".lightbox").removeClass("lightbox--activo");
}
function inicio(){
	$(".lightbox__fondo").click(lightboxCerrar);
	$("#callRegistrar").click(function(){
		$("#registro").addClass("lightbox--activo");
	});
	$("#callIniciarSesion").click(function(){
		$("#iniciarSesion").addClass("lightbox--activo");
	});
	$('select').material_select();
	$(".datepicker").pickadate({
		monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Deciembre'],
		monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'],
		weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
		today: 'Hoy',
		clear: 'Limpiar',
		close: 'Clerrar',
		showMonthsShort: true,
		selectYears: true,
  		selectMonths: true,
});
}

$(document).on("ready", inicio);