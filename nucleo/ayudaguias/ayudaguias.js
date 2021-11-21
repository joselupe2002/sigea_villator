var id_unico="";
var estaseriando=false;
var matser="";
contAlum=1;
contMat=1;


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 
		cargarInformacion();
	});
	
	
		

/*===========================================================POR MATERIAS ==============================================*/
function cargarInformacion(){
	mostrarEspera("esperaInf","grid_ayudaguias","Cargando Guías...");
	elsql2="select * from guiasrap where PRED='' ORDER BY ORDEN";
				
	$("#contenido").empty();	
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}				
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				
				cargaMenus(JSON.parse(data2));  	
				ocultarEspera("esperaInf");  													
					
			}
		});					  		
}

function cargaMenus(grid_data){	
	$("#contenido").html("<div class=\"container\"><ul class=\"ca-menu\" id=\"menu1\"></ul></div>");


jQuery.each(grid_data, function(clave, valor) { 

		$("#menu1").append("<li class=\"ayudaPadre\" id=\"PADRE_"+valor.ID+"\">"+
						"	<a style=\"cursor:pointer;\" onclick=\"CargarSub('"+valor.ID+"');\">"+
						"		<span class=\"ca-icon\"><i class=\"fa "+valor.ICONO+" bigger-150\"></i></span>"+
						"		<div class=\"ca-content\"> "+
						"			<h2 class=\"layuda ca-main fontRobotoB\"  mipadre=\"PADRE_"+valor.ID+"\">"+valor.TITULO+"</h2>"+
						"			<h3 class=\"layuda ca-sub fontRoboto\"  mipadre=\"PADRE_"+valor.ID+"\">"+valor.SUBTITULO+"</h3>"+
						"		</div>"+
						"	</a>"+
						"</li>");

});	

} 




function CargarSub(elpadre){

	mostrarEspera("esperaInf","grid_ayudaguias","Cargando Guías...");
	elsql2="select * from guiasrap where PRED='"+elpadre+"' ORDER BY ORDEN";
				
	$("#contenido").empty();	
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}				
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  				
				cargaSubMenus(JSON.parse(data2),elpadre);  	
				ocultarEspera("esperaInf");  																	
			}
		});

				  					  		
}

function cargaSubMenus(grid_data,elpadre){	
	$("#contenido").html("<div class=\"container\"><ul class=\"ca-menu\" id=\"menu1\"></ul></div>");

	//La opción de Regresar
	$("#menu1").append("<li class=\"ayudaPadre\">"+
	"	<a style=\"cursor:pointer;\" onclick=\"cargarInformacion();\">"+
	"		<span class=\"ca-icon\"><i class=\"fa fa-arrow-left bigger-200\"></i></span>"+
	"		<div class=\"ca-content\"> "+
	"			<h2 class=\"layuda ca-main fontRobotoB\">REGRESAR</h2>"+
	"			<h3 class=\"layuda ca-sub fontRoboto\">Regresar al menú anterior</h3>"+
	"		</div>"+
	"	</a>"+
	"</li>");

jQuery.each(grid_data, function(clave, valor) { 
		$("#menu1").append("<li class=\"ayudaPadre\"  id=\"PADRE_"+valor.ID+"\">"+
						"	<a style=\"cursor:pointer;\" onclick=\"cargalaGuia('"+valor.ID+"','"+elpadre+"','"+valor.TITULO+"','"+valor.TIPO+"','"+valor.COLORES+"');\">"+
						"		<span class=\"ca-icon\"><i class=\"fa "+valor.ICONO+" bigger-150\"></i></span>"+
						"		<div class=\"ca-content\"> "+
						"			<h2 class=\"layuda ca-main fontRobotoB\"  mipadre=\"PADRE_"+valor.ID+"\">"+valor.TITULO+"</h2>"+
						"			<h3 class=\"layuda ca-sub fontRoboto\"  mipadre=\"PADRE_"+valor.ID+"\">"+valor.SUBTITULO+"</h3>"+
						"		</div>"+
						"	</a>"+
						"</li>");

});	

} 



/*====================================*/
function cargalaGuia(elid,elpadre,titulo,tipo,colores){
	enlace="nucleo/ayudaguias/guia.php?id="+elid+"&pad="+elpadre+"&tit="+titulo+"&tipo="+tipo+"&colores="+colores;
	abrirPesta(enlace, "GUÍA")

}


function filtrarMenu() {

	var input = $('#filtrar').val();
	var filter = input.toUpperCase();
	var contenidoMenu="";
	
	if (filter.length == 0) { // show all if filter is empty	
			$(".ayudaPadre").removeClass("hide");
		return;
	} else {														

		$(".ayudaPadre").addClass("hide");
		$('.layuda:contains("' + filter + '")').each(function() {				
		
		   $("#"+$(this).attr("mipadre")).removeClass("hide");
		});
		
	}
}
