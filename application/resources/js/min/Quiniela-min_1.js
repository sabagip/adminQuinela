function lightboxCerrar(){$(".lightbox").removeClass("lightbox--activo")}function inicio(){$(".lightbox__fondo").click(lightboxCerrar),$("#callRegistrar").click(function(){$("#registro").addClass("lightbox--activo")}),$("#callIniciarSesion").click(function(){$("#iniciarSesion").addClass("lightbox--activo")})}$(document).on("ready",inicio);