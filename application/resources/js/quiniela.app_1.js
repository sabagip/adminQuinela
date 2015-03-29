//(function(){
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
			$("header h1 em").text("PRIMEROS DIEZ")
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

		for(i=0;i<TopTen.length;i++){
			topTen.push(TopTen[i].idpiloto);
		}

		var respuesta = {"PolePosition":pole,"VueltaRapida":vueltarapida, "TopTen":topTen};
		//Respuesta con POST
		//AQUÍ CAMBIA LAS RUTAS SERGIO
		$.post("http://localhost/QUINIELA-FASTmag/index.php/finalUser/prediccion/saveApuesta", respuesta).done(function(){
			console.log("Se envio con éxito");
                        self.location="http://localhost/QUINIELA-FASTmag/index.php/finalUser/informacion";

		});
	};

	function prediccion(){
		var Pantalla = 1;
		Pantallas(Pantalla)
		$(".Navegador .siguiente").click(function(){
			Pantalla++;
			Pantallas(Pantalla);
		});
		$(".Navegador .anterior").click(function(){
			Pantalla--;
			Pantallas(Pantalla);
		});

		$("#topTenGuardar").click(function(){
			
			$(".listaPilotosTopTenResultado li").each(function(){
				var dato_idPiloto = $(this).data("idpiloto");
				var dato_escuderia = $(this).children(".info").children("p").text();
				var dato_nombre = $(this).children(".info").children("h3").text();
				var dato_imagenpiloto =  dato_imagenpiloto = $(this).children("img").attr("src");
				TopTen.push({"idpiloto":dato_idPiloto,"nombre":dato_nombre, "imagenpiloto":dato_imagenpiloto, "escuderia":dato_escuderia });
			});
			console.log(TopTen);

			// Parseo Intenso
			//PolePosition
			var PoleResultado = '<li data-idpiloto="'+PolePosition.idpiloto+'"><img src="'+PolePosition.imagenpiloto+'" alt=""><span class="info"> <h3>'+PolePosition.nombre+'</h3> <p>'+PolePosition.escuderia+'</p></span></li>'; 
			//VueltaRápida
			var VueltaResultado = '<li data-idpiloto="'+VueltaRapida.idpiloto+'"><img src="'+VueltaRapida.imagenpiloto+'" alt=""><span class="info"> <h3>'+VueltaRapida.nombre+'</h3> <p>'+VueltaRapida.escuderia+'</p></span></li>';
			//TopTen
			var TopTenResultado = "";
			for(i=0;i<TopTen.length;i++){
				var cadena = '<li data-idpiloto="'+TopTen[i].idpiloto+'"><img src="'+TopTen[i].imagenpiloto+'" alt=""><span class="info"> <h3>'+TopTen[i].nombre+'</h3> <p>'+TopTen[i].escuderia+'</p></span></li>'
				TopTenResultado = TopTenResultado + cadena;
			}
			console.log(TopTenResultado);
			

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
		console.log("PolePosition: "+PolePosition+", VueltaRapida: "+VueltaRapida)
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
		console.log("PolePosition: "+PolePosition+", VueltaRapida: "+VueltaRapida)
	});

	}

	function inicio(){
		$("#principal .menuActivador").click(function(){
			$("#menuItems").toggleClass("menuItemsActivo");
		//console.log("OLA KE ASE");
	});

		prediccion();
		$(".Guardar").click(guardar);
	}

	$(document).on("ready", inicio);
//}());
