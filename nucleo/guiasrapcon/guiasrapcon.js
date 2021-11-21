var todasColumnas;
var losCriterios;
var cuentasCal=0;
var eltidepocorte;
var porcRepEst=0;

$(document).ready(function($) { var Body = $('body');   Body.addClass('preloader-site'); });
$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});


jQuery(function($) { 
    $("#encabezado").append("<div class=\"col-sm-1\"></div>"+
                        "<div class=\"col-sm-8\" style=\"text-align:left;\">"+								
                        "	    	<label class=\"fontRobotoB label label-success\">Elija la Guía</label>"+
                        "         <select onchange=\"seleccionaGuia();\" class=\" chosen-select form-control \"  id=\"selGuias\"></select>"+							
                        "</div>"+
                        "<div id=\"btnSave\" class=\"col-sm-2 hide\" style=\"text-align:left; padding-top:25px;\">"+								
                        "     <span class=\"fontRobotoB btn btn-white btn-success\" style=\"cursor:pointer;\" "+
                        "           onclick=\"guardarTodos();\"><i class=\"fa fa-save green\"></i> <span >Guardar Guía</span></span>"+							
                        "</div>"																                           
                                                        
                        );

    actualizaSelect("selGuias", "SELECT ID,TITULO from guiasrap where PRED<>'' order by TITULO", "BUSQUEDA","");
});



function seleccionaGuia(){

    if ($("#selGuias").val()>0) {
        $("#btnSave").removeClass("hide");        
        mostrarEspera("esperaInf","grid_guiasrapcon","Cargando Guías...");
        elsql2="select ID, ifnull(ORDEN,100) as ORDEN, CONTENIDO, TITULO, ICONO, "+
        "ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA='"+$("#selGuias").val()+"' ORDER BY ORDEN";
        $("#conguia").empty();
            
        parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}				
        $.ajax({
                type: "POST",
                data:parametrosw,
                url:  "../base/getdatossqlSeg.php",
                success: function(data2){  
        
                    grid_data=JSON.parse(data2);
                    jQuery.each(grid_data, function(clave, valor) { 

                        btnEliminar="<span title=\"Eliminar Registro\" onclick=\"eliminarRegistro('"+valor.ID+"');\" class=\"btn btn-xs btn-white\"> " +
                        "    <i class=\"ace-icon fa fa-trash red bigger-120\"> </i> <span class=\"fontRoboto\"></span>" +
                        "</span>";

                        btnGuardar="<span title=\"Eliminar Registro\" onclick=\"guardarFila('"+valor.ID+"');\" class=\"btn btn-xs btn-white btnGuardar\"> " +
                        "    <i class=\"ace-icon fa fa-save blue bigger-120\"> </i> <span class=\"fontRoboto\"></span>" +
                        "</span>";

                    $("#conguia").append("<div class=\"row\">"+
                                        "   <div class=\"col-sm-1\" style=\"padding-left:30px;\">"+
                                        "      <span class=\"fontRobotoB Primary\">Orden</label> "+
                                        "      <input class=\"form-control\" value=\""+valor.ORDEN+"\" onchange=\"actualizaCampo('"+valor.ID+"','ORDEN','ORDEN_"+valor.ID+"');\" id=\"ORDEN_"+valor.ID+"\"></input><BR>"+
                                        "<span>"+btnEliminar+" "+btnGuardar+"</span>"+
                                        "   </div>"+                                       
                                        "   <div class=\"col-sm-3\" id=\"ELTITULO_"+valor.ID+"\"></div>"+
                                        "   <div class=\"col-sm-6\" id=\"ELCONTENIDO_"+valor.ID+"\"></div>"+
                                        "   <div class=\"col-sm-2\" id=\"ELICONO_"+valor.ID+"\"></div>"+
                                        "</div>"
                                        );	                                  
                                        getEditor("ELCONTENIDO_"+valor.ID,"CONTENIDO_"+valor.ID,valor.CONTENIDO);
                                        getEditor("ELTITULO_"+valor.ID,"TITULO_"+valor.ID,valor.TITULO);

                                        activaEliminar="N";if (!(valor.RUTA=='')) {activaEliminar="S";}
                                    
                                        dameSubirArchivoLocal("ELICONO_"+valor.ID,"Icono","ICONO_"+valor.ID,'guias','png',
                                        'ID',valor.ID,'ICONO','eadjico','alta',valor.ID,valor.RUTA,activaEliminar,"ICO_"+valor.ID);	
                    
                    });
                
                    $("#conguia").append("<hr><span class=\"fontRoboto btn btn-white btn-success\" style=\"cursor:pointer;\" onclick=\"addPunto();\"><i class=\"fa fa-plus blue\"></i> <span >Nueva Instrucción</span></span>");	              
                    ocultarEspera("esperaInf");

                                            
                }
            });
        }
        else {
            $("#btnSave").addClass("hide");
            $("#conguia").empty();
        }

}




function addPunto(){
        guardarTodos();
	   //Grabamos el log 
		var hoy= new Date();
		lafecha=dameFecha("FECHAHORA");
	
		parametros={tabla:"guiasrapcon",
			bd:"Mysql",
			USUARIO:usuario,
			FECHAUS:lafecha,
			IDGUIA:$("#selGuias").val(),
			_INSTITUCION:inst,
			_CAMPUS:camp
		};
			$.ajax({
				   type: "POST",
				   url:"../../nucleo/base/inserta.php",
				   data: parametros,
				   success: function(data){ 
					   console.log(data);
					   seleccionaGuia();
				   }					
				});                	 
}



function guardarFila(id){
    const regex = /'/gi;
    lafecha=dameFecha("FECHAHORA"); 
    parametros={
            tabla:"guiasrapcon",
            campollave:"ID",
            valorllave:id,
            bd:"Mysql",	
            ORDEN:$("#ORDEN_"+id).val(),
            CONTENIDO:$("#CONTENIDO_"+id).html().replace( regex,"''"),
            TITULO:$("#TITULO_"+id).html().replace( regex,"''")
            };

    $.ajax({
        type: "POST",
        url:"../base/actualiza.php",
        data: parametros,
        success: function(data){		                                	                      
          		console.log(data);	           
        }					     
    });      

}


function guardarTodos() {
    mostrarEspera("esperaSave","grid_guiasrapcon","Guardado Guia...");
	$(".btnGuardar").each(function(){
		$(this).trigger("click");
     });
     ocultarEspera("esperaSave");
    
}


	
function actualizaCampo(id, elcampo, elidcampo){
	lafecha=dameFecha("FECHAHORA");
    valor =$("#"+elidcampo).val();
		
	if (elcampo=="CONTENIDO"){valor =$("#"+elidcampo).html().replace(" ' gi","''");}
	

	parametros={
				tabla:"guiasrapcon",
				campollave:"ID",
				valorllave:id,
				nombreCampo:elcampo,
				valorCampo:valor,
				bd:"Mysql"};
      
			$.ajax({
						type: "POST",
						url:"../base/actualizaDin.php",
						data: parametros,
						success: function(data){
							console.log(data);		
											       					           
						}					     
					}); 				
}



function changeEditor_SELECT(nombre){
    actualizaCampo(nombre.split("_")[1],nombre.split("_")[0],nombre);
}



function eliminarRegistro(elid,idPuntos){

	if (confirm("¿Seguro desea eliminar el registro?")) {
		elsql="DELETE FROM guiasrapcon WHERE ID='"+elid+"'";
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/ejecutasql.php",
		   success: function(data){  
            seleccionaGuia();
		   }
		});
	}
	
}
