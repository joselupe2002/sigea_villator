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

	mostrarEspera("esperaInf","grid_ayudaTec","Cargando Datos...");
	elsql="SELECT ifnull(usua_usuader,'') as usua_usuader,  usua_super FROM CUSUARIOS WHERE usua_usuario='"+usuario+"'";
	elsql2="";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"SQLite"}
	$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){  
				losroles=JSON.parse(data)[0][0].split(",");
				if (JSON.parse(data)[0][1]!='S') {
					losroles.forEach(function callback(currentValue, index, array) {
						elsql2+="select * from edocgen LEFT OUTER JOIN fures ON (DEPARTAMENTO=URES_URES) where   USUARIOS LIKE '%"+currentValue+"%' UNION "						
					});
					elsql2=elsql2.substring(0,elsql2.length-7);   
				}
				else {
				   elsql2="select * from edocgen LEFT OUTER JOIN fures ON (DEPARTAMENTO=URES_URES)  ORDER BY URES_DESCRIP";
				}

		
				$("#informacion").empty();
				$("#informacion").append(script);
				parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}				
				$.ajax({
						type: "POST",
						data:parametrosw,
						url:  "../base/getdatossqlSeg.php",
						success: function(data2){  
							generaTabla(JSON.parse(data2));   													
							ocultarEspera("esperaInf");  	

						}
					});
			}
		});
				  					  		
}

function generaTabla(grid_data){	
contAlum=1;
$("#cuerpoMaterias").empty();
$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");

cont=1;
$("#contenido").append("<div id=\"linea"+cont+"\" class=\"row\"></div>"); 


jQuery.each(grid_data, function(clave, valor) { 

	img1="<a onclick=\"previewAdjunto('"+valor.DOCGEN_RUTA+"');\"  > <img  id=\"img1\"  src=\"../../imagenes/menu/ayuda1.png\"  style=\"cursor:pointer; width:50px; height:50px;\" /></a>";
	if (valor.DOCGEN_RUTA=="../../imagenes/menu/default.png") {img1="";}
	img2="<a href=\""+valor.ENLACEEXT+"\" target=\"_blank\" > <img  id=\"img1\" src=\"../../imagenes/menu/ayuda2.png\"  style=\"width:50px; height:50px;\" /></a>";
	if ((valor.ENLACEEXT=="") || (valor.ENLACEEXT==null)) {img2="";}
	if ((valor.DOCGEN_RUTA=="") || (valor.DOCGEN_RUTA==null)) {img1="";}

    $("#linea"+cont).append("<div id=\"ventAyuda"+valor.CLAVE+"\" class=\" ayudaPadre fontRoboto col-md-3\">"+
	"<div class=\"thumbnail search-thumbnail\">"+
	"	<div class=\"row\" style=\"text-align:center;\">"+
	"	<div class=\"col-sm-2\"></div>"+
	"	<div class=\"col-sm-3\">"+img1+"</div>"+
	"	<div class=\"col-sm-3\">"+img2+"</div>"+
	"   </div>"+
	"	<div class=\"caption\">"+
	"		<span class=\"text-success fontRobotoB layuda\" mipadre=\"ventAyuda"+valor.CLAVE+"\"  >"+valor.URES_DESCRIPLAR+"</span>"+
	"		<h3 class=\"fontRobotoB bigger-110 layuda \">"+
	"			<span class=\"blue layuda\" mipadre=\"ventAyuda"+valor.CLAVE+"\" >"+valor.NOMBRE+"</span>"+
	"		</h3>"+
	"		<p style=\"text-align:justify;\" class=\"layuda\" mipadre=\"ventAyuda"+valor.CLAVE+"\" >"+valor.OBS+"</p>"+
	"	</div>"+
	"</div>");
	if ((contAlum % 4)==0) { cont++; $("#contenido").append("<div id=\"linea"+cont+"\" class=\"row\"></div>"); }	
	contAlum++;     

});	
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
		$(' .layuda:contains("' + filter + '")').each(function() {				
		
		   $("#"+$(this).attr("mipadre")).removeClass("hide");
		});
		
	}
}
