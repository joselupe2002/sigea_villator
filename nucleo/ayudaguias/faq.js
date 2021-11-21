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
	elsql2="select * from guiasfaq where GUIA='"+laguia+"' ORDER BY PREGUNTA";
	$("#accordion").empty();	
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}				
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				
				cargaPreg(JSON.parse(data2));  	
				ocultarEspera("esperaInf");  													
					
			}
		});					  		
}

function cargaPreg(grid_data){	

jQuery.each(grid_data, function(clave, valor) { 
	$("#accordion").append(
		"<div class=\"panel panel-default ayudaPadre\" id=\"PADRE_"+valor.ID+"\">"+
		"    <div class=\"panel-heading \"> "+
		"         <h4 class=\"panel-title\">"+
		"             <a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#tab"+valor.ID+"\">"+
		"		          <i class=\"ace-icon fa fa-angle-down bigger-110\" data-icon-hide=\"ace-icon fa fa-angle-down\" data-icon-show=\"ace-icon fa fa-angle-right\"></i>"+
		"                    &nbsp;<span class=\"fontRobotoB layuda\" style=\"font-size:18px;\" mipadre=\"PADRE_"+valor.ID+"\">"+valor.PREGUNTA+"</span>"+
		"              </a>"+
		"         </h4> "+
		"    </div>"+
		"    <div class=\"panel-collapse collapse\" id=\"tab"+valor.ID+"\">"+
		"        <div class=\"panel-body fontRoboto bigger-120\"> "+
		"           <div class=\"row fontRoboto layuda\" style=\"font-size:18px; text-align:justify; padding-left:20px; padding-right:20px;\" mipadre=\"PADRE_"+valor.ID+"\">"+valor.RESPUESTA+
	
		"           </div>"+			
		"	 </div> "+
		"</div>");

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
		jQuery.expr[':'].contains = function(a, i, m) {
			return jQuery(a).text().toUpperCase()
				.indexOf(m[3].toUpperCase()) >= 0;
		  };
		$('.layuda:contains("' + filter + '")').each(function() {				
			
		   $("#"+$(this).attr("mipadre")).removeClass("hide");
		});
		
	}
}
