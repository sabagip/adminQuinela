(function(){
	var PolePosition = "";
	var VueltaRapida = "";
	var TopTen = [];
	function Pantallas(pantalla){
		if (pantalla === 1) {
			$("#polePosition,#vueltaRapida,#TopTen,#VistaRapida").removeClass("activo");
			$("#polePosition").addClass("activo");
			$("header h1 em").text("POSICIÓN DE PRIVILEGIO")
		}
		else if (pantalla === 2) {
			$("#polePosition,#vueltaRapida,#TopTen,#VistaRapida").removeClass("activo");
			$("#vueltaRapida").addClass("activo");
			$("header h1 em").text("VUELTA RÁPIDA")
		}
		else if (pantalla === 3) {
			$("#polePosition,#vueltaRapida,#TopTen,#VistaRapida").removeClass("activo");
			$("#TopTen").addClass("activo");
			$("header h1 em").text("10 PRIMEROS")
		}
		else if (pantalla === 4) {
			$("#TopTen").removeClass("activo");
			$("#VistaRapida").addClass("activo");
			$("header h1 em").text("VISTA RÁPIDA")
		} 
	}

	function guardar(){
		var pole = PolePosition.idpiloto;
		var vueltarapida = VueltaRapida.idpiloto;
		var topTen = [];
                var jornada = $("#idJornada").val();
                for(i=0;i<TopTen.length;i++){
			topTen.push(TopTen[i].idpiloto);
		}
                
                
		var respuesta = {"PolePosition":pole,"VueltaRapida":vueltarapida, "TopTen":topTen, "jornada":jornada};
                $.ajax({
                               type: "POST",
                               url: "http://localhost/adminQuinela/index.php/admin/prediccion/saveApuesta",
                               data: respuesta, // Adjuntar los campos del formulario enviado.
                               dataType: "json",
                               success: function(request){
                                    console.log("success ", request);
                                    alertify
                                        .alert("Tu pronóstico a sido guardado.", function(){
                                        alertify.message('OK');
                                    });
                                   self.location="http://localhost/adminQuinela/index.php/admin/tramposos";
                               },
                               error : function(request){
                                   console.log("fail ",  request);
                                   
                                    if(request.responseText == "vacio"){
                                         alertify
                                            .alert("Verifica que hayas seleccionado los pilotos correspondientes.", function(){
                                            alertify.message('Error');
                                         });
                                    }
                                    if(request.readyState == 4){
                                         alertify
                                         .alert("Verifica que hayas seleccionado los pilotos correspondientes.", function(){
                                           alertify.message('Error');
                                         });
                                    }
                                   
                               }
                                });
	};
        
        function actualizar(){
		var pole = PolePosition.idpiloto;
		var vueltarapida = VueltaRapida.idpiloto;
		var topTen = [];
                var jornada = $("#idJornada").val();
                for(i=0;i<TopTen.length;i++){
			topTen.push(TopTen[i].idpiloto);
		}
                
                
		var respuesta = {"PolePosition":pole,"VueltaRapida":vueltarapida, "TopTen":topTen, "jornada":jornada};
                $.ajax({
                               type: "POST",
                               url: "http://localhost/adminQuinela/index.php/admin/prediccion/saveApuesta",
                               data: respuesta, // Adjuntar los campos del formulario enviado.
                               dataType: "json",
                               success: function(request){
                                    console.log("success ", request);
                                    alertify
                                        .alert("Tu pronóstico a sido actualizado.", function(){
                                        alertify.message('OK');
                                    });
                                   self.location="http://localhost/adminQuinela/index.php/admin/bienvenido";
                               },
                               error : function(request){
                                   console.log("fail ",  request);
                                   
                                    if(request.responseText == "vacio"){
                                         alertify
                                            .alert("Verifica que hayas seleccionado los pilotos correspondientes.", function(){
                                            alertify.message('Error');
                                         });
                                    }
                                    if(request.readyState == 4){
                                         alertify
                                         .alert("Verifica que hayas seleccionado los pilotos correspondientes.", function(){
                                           alertify.message('Error');
                                         });
                                    }
                                   
                               }
                                });
	};

	function prediccion(){
		var Pantalla = 1;
		Pantallas(Pantalla)
		$(".Navegador .siguiente").click(function(){
			var x = $(this).hasClass("Disable");
			if (!x){
				Pantalla++;
				Pantallas(Pantalla);
			}
		});
		$(".Navegador .anterior").click(function(){
			Pantalla--;
			Pantallas(Pantalla);
		});

		$("#topTenGuardar").click(function(){
			TopTen = [];
			$(".listaPilotosTopTenResultado li").each(function(){
				var dato_idPiloto = $(this).data("idpiloto");
				var dato_escuderia = $(this).children(".pilotoInfoCondensada").children(".info").children("p").text();
				var dato_nombre = $(this).children(".pilotoInfoCondensada").children(".info").children("h3").text();
				var dato_imagenpiloto =  dato_imagenpiloto = $(this).children(".pilotoInfoCondensada").children("img").attr("src");
				TopTen.push({"idpiloto":dato_idPiloto,"nombre":dato_nombre, "imagenpiloto":dato_imagenpiloto, "escuderia":dato_escuderia });
			});
			//console.log(TopTen);

			// Parseo Intenso
			//PolePosition
			var PoleResultado = '<li data-idpiloto="'+PolePosition.idpiloto+'"><img src="'+PolePosition.imagenpiloto+'" alt=""><span class="info"> <h3>'+PolePosition.nombre+'</h3> <p>'+PolePosition.escuderia+'</p></span></li>'; 
			//VueltaRápida
			var VueltaResultado = '<li data-idpiloto="'+VueltaRapida.idpiloto+'"><img src="'+VueltaRapida.imagenpiloto+'" alt=""><span class="info"> <h3>'+VueltaRapida.nombre+'</h3> <p>'+VueltaRapida.escuderia+'</p></span></li>';
			//TopTen
			var TopTenResultado = "";
			for(i=0;i<TopTen.length;i++){
				var cadena = '<li data-idpiloto="'+TopTen[i].idpiloto+'"><span class="pilotoInfoCondensada"><img src="'+TopTen[i].imagenpiloto+'" alt=""><span class="info"> <h3>'+TopTen[i].nombre+'</h3> <p>'+TopTen[i].escuderia+'</p></span></span></li>'
				TopTenResultado = TopTenResultado + cadena;
			}
			//console.log(TopTenResultado);
			//Pintado
			$("#poleResultado .listaPilotos").html(PoleResultado);
			$("#vueltaResultado .listaPilotos").html(VueltaResultado);
			$("#toptenResultado .listaPilotos").html(TopTenResultado);
                        

			//debugger;
		});

$(".listaPilotosPole li").click(function(){
	$(".listaPilotosPole li").each(function(){
		$(this).removeClass("pilotoActivo");
	});
	var dato_idPiloto = $(this).data("idpiloto");
	var dato_nombre = $(this).children(".info").children("h3").text();
	var dato_escuderia = $(this).children(".info").children("p").text();
	var dato_imagenpiloto =  dato_imagenpiloto = $(this).children("img").attr("src");
	PolePosition = {
		"idpiloto":dato_idPiloto,
		"nombre":dato_nombre,
		"imagenpiloto":dato_imagenpiloto,
		"escuderia":dato_escuderia
	};
	$(this).addClass("pilotoActivo");
	$("#polePosition .Navegador .siguiente").removeClass("Disable").addClass("boton--secundario");
	//console.log("PolePosition: "+PolePosition+", VueltaRapida: "+VueltaRapida)
});
$(".listaPilotosVueltaRapida li").click(function(){
	$(".listaPilotosVueltaRapida li").each(function(){
		$(this).removeClass("pilotoActivo");
	});
	var dato_idPiloto = $(this).data("idpiloto");
	var dato_nombre = $(this).children(".info").children("h3").text();
	var dato_escuderia = $(this).children(".info").children("p").text();
	var dato_imagenpiloto =  dato_imagenpiloto = $(this).children("img").attr("src");
	VueltaRapida = {
		"idpiloto":dato_idPiloto,
		"nombre":dato_nombre,
		"imagenpiloto":dato_imagenpiloto,
		"escuderia":dato_escuderia
	};
	$(this).addClass("pilotoActivo");
	$("#vueltaRapida .Navegador .siguiente").removeClass("Disable").addClass("boton--secundario");
	//console.log("PolePosition: "+PolePosition+", VueltaRapida: "+VueltaRapida)
});

}

function lightboxCerrar(){
	$(".lightbox").removeClass("lightbox--activo");
}

function inicio(){
	//AQUÍ ESTA LAS FECHAS DEL CUMPLE
	$( "#userAge" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1910:2015",
		maxDate: "+1D",
		minDate:"-100Y",
		dayNames: ["Domingo", "Lunes", "Martes","Miercoles", "Jueves", "Viernes", "Sábado"],
		dayNamesMin: ["Dom","Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
		monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
	});
	$(".lightbox__fondo").click(lightboxCerrar);
	$(".expertos").click(function(){
		$("#ExpertosLight").addClass("lightbox--activo");
	});
	$("#principal .menuActivador").click(function(){
		$("#menuItems").toggleClass("menuItemsActivo");
		//console.log("OLA KE ASE");
	});

	prediccion();
	//$(".GuardarUsuario").click(guardar("url.php"));
	$(".GuardarPrediccion").click(guardar);
        $(".ActualizarPrediccion").click(actualizar);
        
}

$(document).on("ready", inicio);
}());
