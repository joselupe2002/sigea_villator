



function addAlumnos(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	dameVentana("ventaulas", "grid_vin_visitas",table.rows('.selected').data()[0]["EMPRESA"],"lg","bg-successs","fa fa-book blue bigger-180","370");
	$("#body_ventaulas").empty();

	$("#body_ventaulas").append(
	"<div class=\"row\">"+	
	"	<div  class=\"col-sm-4\" style=\"padding-top:10px;\">"+
	"    		 <button  onclick=\"cargarAlumnosGrupo('"+table.rows('.selected').data()[0]["IDGRUPO"]+"','"+table.rows('.selected').data()[0]["ID"]+"');\" class=\"fontRobotoB btn btn-white btn-info btn-bold bigger-80\">"+
	"  			 <i class= \"ace-icon fa fa-user bigger-100 blue\"></i>"+
	"   		 <span class=\"text-success\">Insertar Alumnos del Grupo</span>"+
	"    		 </button>"+
	"	</div>"+
	"	<div  class=\"col-sm-5\" id=\"losalumnos\"></div>"+
	"	<div class=\"col-sm-1\" id=\"elbtn\" style=\"padding-top:13px;\"></div>"+
	"	<div class=\"col-sm-1\"></div>"+
	"</div><BR>");

	$("#body_ventaulas").append("<table id=tabAlum class=\"fontRobotoB display table-condensed table-striped table-sm table-bordered "+
	"table-hover nowrap\" style=\"overflow-y: auto;\"></table>");

	$("#losalumnos").append("<span class=\"label label-warning\">Alumnos de otros grupos</span>");
	addSELECT("selAlumnos","losalumnos","PROPIO", "SELECT ALUM_MATRICULA, CONCAT(ALUM_MATRICULA,' ',ALUM_NOMBRE,' ',ALUM_APEPAT,' ',ALUM_APEMAT) from falumnos WHERE ALUM_ACTIVO IN (1)", "","BUSQUEDA");  			      

	$("#elbtn").append(" <button  onclick=\"insertarAlum('"+table.rows('.selected').data()[0]["ID"]+"');\" class=\"fontRobotoB btn btn-white btn-info btn-bold bigger-80\">"+
	"  			 <i class= \"ace-icon fa fa-plus bigger-100 blue\"></i>"+
	"   		 <span class=\"text-success\">Insertar</span>"+
	"    		 </button>");

	cargarListaAlum(table.rows('.selected').data()[0]["ID"]);
	
}



function cargarAlumnosGrupo(id,idvisita){

 	elsql="DELETE FROM vin_visalum WHERE IDVISITA='"+idvisita+"'";
  	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
  	$.ajax({
		 type: "POST",
		 data:parametros,
		 url:  "../base/ejecutasql.php",
		 success: function(data){  
			console.log(data);
			elsql="insert into vin_visalum (MATRICULA,IDVISITA) SELECT ALUCTR, "+idvisita+" FROM dlista WHERE IDGRUPO='"+id+"'";
			parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
				type: "POST",
				data:parametros,
				url:  "../base/ejecutasql.php",
				success: function(data){  
					console.log(data);
					cargarListaAlum(idvisita);	
				}	
			});	
			
		 }	
		 
	});			 

}

function insertarAlum(idvisita){
	if ($("#selAlumnos").val()!="0") {
		elsql="insert into vin_visalum (MATRICULA,IDVISITA) values ('"+$("#selAlumnos").val()+"', '"+idvisita+"')";
				parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
				$.ajax({
					type: "POST",
					data:parametros,
					url:  "../base/ejecutasql.php",
					success: function(data){  
						console.log(data);
						cargarListaAlum(idvisita);	
					}	
				});	
		}
	else {alert ("Debe elegir un alumno a insertar")}
}



function cargarListaAlum(idvisita){	
	elsql="SELECT * FROM vvin_visalum WHERE IDVISITA='"+idvisita+"'";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
 
 	$.ajax({
		type: "POST",
		data:parametros,
        url:  "../base/getdatossqlSeg.php",
        success: function(data){  
     	    losdatos=JSON.parse(data);  
			$("#tabAlum").empty();
			$("#tabAlum").append("<thead><th>OP</th><th>MATRICULA</th><th>NOMBRE</th><th>CARRERA</th><th>SEMESTRE</th><thead>")
			$("#cuerpoAlum").empty();			   
			$("#tabAlum").append("<tbody id=\"cuerpoAlum\">");
			c=1;	
			global=1; 
			jQuery.each(losdatos, function(clave, valor) { 	
				  
				btnEliminar="<button title=\"Eliminar Registro\" onclick=\"eliminarRegistro('"+valor.ID+"','"+idvisita+"');\" class=\"btn btn-xs btn-white\"> " +
							 "    <i class=\"ace-icon fa fa-trash red bigger-120\"></i>" +
							  "</button>";
				
				$("#cuerpoAlum").append("<tr id=\"row"+valor.ID+"\">");
				$("#row"+valor.ID).append("<td>"+btnEliminar+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.MATRICULA+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.NOMBRE+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.CARRERA+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.PERIODO+"</td>");
				
				c++;
				global=c;
			  });
		}
	});
 

}


function eliminarRegistro(elid,idvisita){
	elsql="DELETE FROM vin_visalum WHERE ID='"+elid+"'";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	$.ajax({
	   type: "POST",
	   data:parametros,
	   url:  "../base/ejecutasql.php",
	   success: function(data){  
			cargarListaAlum(idvisita);
	   }
	});
}


function repSolicitud(modulo,usuario,institucion, campus,essuper){	
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
		enlace="nucleo/vin_visitas/reporteVis.php?id="+table.rows('.selected').data()[0]["ID"];
		abrirPesta(enlace, "Solicitud");
	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;
		}
	}



	function repLista(modulo,usuario,institucion, campus,essuper){	
		table = $("#G_"+modulo).DataTable();
		if (table.rows('.selected').data().length>0) {
			enlace="nucleo/vin_visitas/listaVis.php?id="+table.rows('.selected').data()[0]["ID"];
			abrirPesta(enlace, "Lista");
		}
		else {
			alert ("Debe seleccionar un registro");
			return 0;
			}
		}



		



		function marcaAsis(modulo,usuario,institucion, campus,essuper){
			table = $("#G_"+modulo).DataTable();
			dameVentana("ventaulas", "grid_vin_visitas",table.rows('.selected').data()[0]["EMPRESA"],"lg","bg-successs","fa fa-book blue bigger-180","370");
			$("#body_ventaulas").empty();
		
			$("#body_ventaulas").append("<table id=tabAlum class=\"fontRobotoB display table-condensed table-striped table-sm table-bordered "+
			"table-hover nowrap\" style=\"overflow-y: auto;\"></table>");
		
			cargarListaAlumAsis(table.rows('.selected').data()[0]["ID"]);
			
		}
		
		
		
function cargarListaAlumAsis(idvisita){	
	elsql="SELECT * FROM vvin_visalum WHERE IDVISITA='"+idvisita+"'";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
 
 	$.ajax({
		type: "POST",
		data:parametros,
        url:  "../base/getdatossqlSeg.php",
        success: function(data){  
     	    losdatos=JSON.parse(data);  
			$("#tabAlum").empty();
			$("#tabAlum").append("<thead><th>OP</th><th>MATRICULA</th><th>NOMBRE</th><th>CARRERA</th><th>SEMESTRE</th><thead>")
			$("#cuerpoAlum").empty();			   
			$("#tabAlum").append("<tbody id=\"cuerpoAlum\">");
			c=1;	
			global=1; 
			jQuery.each(losdatos, function(clave, valor) { 	
				  
				elvalor2='S';  elico="fa-thumbs-up green";
				if (valor.ASISTIO=='S') {elvalor2='N'; elico="fa-thumbs-down red";}
				btn="<button title=\"Marcar Asistencia / Inasistencia\" onclick=\"marcarAsistencia('"+valor.ID+"');\" class=\"btn btn-xs btn-white\"> " +
							 "    <i id=\"ico"+valor.ID+"\" valor=\""+elvalor2+"\" class=\"ace-icon fa "+elico+" bigger-120\"></i>" +
							  "</button>";
				
				$("#cuerpoAlum").append("<tr id=\"row"+valor.ID+"\">");
				$("#row"+valor.ID).append("<td>"+btn+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.MATRICULA+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.NOMBRE+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.CARRERA+"</td>");
				$("#row"+valor.ID).append("<td>"+valor.PERIODO+"</td>");
				
				c++;
				global=c;
			  });
		}
	});
 

}


function marcarAsistencia(id){
		elvalor=$("#ico"+id).attr("valor");
		elsql="update vin_visalum set ASISTIO='"+elvalor+"' where ID="+id;
				parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
				$.ajax({
					type: "POST",
					data:parametros,
					url:  "../base/ejecutasql.php",
					success: function(data){  
						console.log(data);
						if (elvalor=='S') {
							$("#ico"+id).attr("valor","N"); 
							$("#ico"+id).removeClass("fa-thumbs-up green");
							$("#ico"+id).addClass("fa-thumbs-down red");							
						}
						else {
							
							$("#ico"+id).removeClass("fa-thumbs-down red");
							$("#ico"+id).addClass("fa-thumbs-up green");
							$("#ico"+id).attr("valor","S");
						}
					}	
				});	

}


function repResultado(modulo,usuario,institucion, campus,essuper){	
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
		enlace="nucleo/vin_visitas/reporteRes.php?id="+table.rows('.selected').data()[0]["ID"];
		abrirPesta(enlace, "Evidencia");
	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;
		}
	}





		function verEvidenciaVis(modulo,usuario,institucion, campus,essuper){
			table = $("#G_"+modulo).DataTable();
			if (table.rows('.selected').data().length>0) {		
				
				if (table.rows('.selected').data()[0]["RUTA"]) {
					previewAdjunto(table.rows('.selected').data()[0]["RUTA"]);			
				}
				else {
					alert ("No se ha adjuntando PDF")
					}
				}
			else {
				alert ("Debe seleccionar un Registro");
				return 0;
		
				}
			
		}



		function adjVisitas(modulo,usuario,institucion, campus,essuper){
			table = $("#G_"+modulo).DataTable();
			ladefault="..\\..\\imagenes\\menu\\pdf.png";
			if (table.rows('.selected').data().length>0) {
				
				 
				stElim="display:none; cursor:pointer;";
				if (table.rows('.selected').data()[0]["RUTA"].length>0) {stElim="cursor:pointer; display:block; ";}
				btnEliminar="<i style=\""+stElim+"\"  id=\"btnEli_RUTA\"  title=\"Eliminar el PDF que se ha subido anteriormente\" class=\"ace-icon glyphicon red glyphicon-trash \" "+        	                            
				"onclick=\"eliminarEnlaceCarpeta('file_RUTA','visitas','pdf_RUTA','RUTA','pdf','N','ID','"+
												table.rows('.selected').data()[0]["ID"]+"','"+table.rows('.selected').data()[0]["EMPRESA"]+
												"','vin_visitas','edita','','PDF');\"></i> "; 
				
				 
				script="<div class=\"modal fade\" id=\"modalDocument\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\"> "+
				   "   <div class=\"modal-dialog modal-sm \" role=\"document\">"+
				   "      <div class=\"modal-content\">"+
				   "          <div class=\"modal-header bg-info\" >"+
				   "             <span><i class=\"menu-icon green fa-2x fa fa-cloud-upload\"></i><span class=\"text-success lead \"> <strong>Subir Documento</strong></span></span>"+
				   "             <button type=\"button\" class=\"close\" onclick=\"cierraModal('"+modulo+"');\"  aria-label=\"Cancelar\">"+
				   "                  <span aria-hidden=\"true\">&times;</span>"+
				   "             </button>"+
				   "          </div>"+
				   "          <div id=\"frmdocumentos\" class=\"modal-body\" style=\"overflow-x: auto; overflow-y: auto; height:300px;\">"+	
				   "                  <div class=\"row\"> "+
				   "                     <div class=\"col-sm-12\">"+
				   "                         <span class=\"label label-sm label-success arrowed-in\">"+table.rows('.selected').data()[0]["SOLICITAD"]+"</span>"+
				   "                     </div>"+
				   "                  </div> "+
				   "                  <div class=\"row\"> "+
				   "                     <div class=\"col-sm-12\">"+
				   "                         <span class=\"label label-sm label-primary arrowed-in\">"+table.rows('.selected').data()[0]["EMPRESA"]+"</span>"+
				   "                     </div>"+
				   "                  </div> "+
				   "                  <div class=\"space-12\"></div> "+
				   "                  <div class=\"row\"> "+
				   "                     <div class=\"col-sm-3\"></div>"+
				   "                     <div class=\"col-sm-5\">"+
				   "                          <a target=\"_blank\" id=\"enlace_RUTA\" href=\""+table.rows('.selected').data()[0]["RUTA"]+"\">"+
				   "                               <img id=\"pdf_RUTA\" name=\"pdf_RUTA\" src=\""+ladefault+"\" width=\"50px\" height=\"50px\">"+
				   "                          </a>"+
				   "                     </div>"+
				   "                     <div class=\"col-sm-1\">"+btnEliminar+"</div>"+
				   "                     <div class=\"col-sm-3\"></div>"+
				   "                  </div>"+
				   "                  <div class=\"space-12\"></div> "+
				   "                  <div class=\"row\"> "+
				   "                    <div class=\"col-sm-12\">"+
				   "                      <input type=\"file\" id=\"file_RUTA\" name=\"file_RUTA\""+
				   "                          onchange=\"subirPDFDriveSaveAsp_local('file_RUTA','visitas','pdf_RUTA','RUTA','pdf','N','ID','"+table.rows('.selected').data()[0]["ID"]+"','"+table.rows('.selected').data()[0]["EMPRESA"]+"-"+table.rows('.selected').data()[0]["SOLICITAD"]+"','vin_visitas','edita','','"+table.rows('.selected').data()[0]["ID"]+"');\"/>"+
				   "                      <input  type=\"hidden\" value=\""+table.rows('.selected').data()[0]["RUTA"]+"\"  name=\"RUTA\" id=\"RUTA\"  placeholder=\"\" />\n"+
				   "                  </div> "+
				   "                 </div>"+		  
				   "          </div>"+
				   "      </div>"+
				   "   </div>"+
				   " <select id=\"aulas\" style=\"visibility:hidden\"></select> "
				   "</div>";
			 
				$("#modalDocument").remove();
				if (! ( $("#modalDocument").length )) {
					$("#grid_"+modulo).append(script);
				}
				
					 if (table.rows('.selected').data()[0]["RUTA"]=='') { 
							$('#enlace_RUTA').attr('disabled', 'disabled');
							$('#enlace_RUTA').attr('href', '#');
							$('#pdf_RUTA').attr('src', "..\\..\\imagenes\\menu\\pdfno.png");
						   }
				
				$('#modalDocument').modal({show:true, backdrop: 'static'});
			
				$('#file_RUTA').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false, //| true | large
					whitelist:'pdf',
					blacklist:'exe|php'
					//onchange:''
					//
				});
				
		
			}
			else {
				alert ("Debe seleccionar un registro");
				return 0;
		
				}
		}
		

		
function cierraModal(modulo){
	table = $("#G_"+modulo).DataTable();
	$('#modalDocument').modal("hide");  
	window.parent.document.getElementById('FRvin_visitas').contentWindow.location.reload();
}


function captResultados(modulo){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {	
		
		elid=table.rows('.selected').data()[0]["ID"];
		elsql="select * from vin_visitas a where ID='"+elid+"'";
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
				type: "POST",
				data:parametros,
				url:  "../base/getdatossqlSeg.php",
				success: function(data){
					misdatos=JSON.parse(data);
						dameVentana("ventRes", "grid_vin_visitas","<span class=\"fontRobotoB\">Resultados de Visita</span>","sm","bg-successs","fa fa-book blue bigger-180","370");
						$("#body_ventRes").append("<div id=\"info\">"+							
												  "</div><br>");	

						$("#info").append("<div class=\"row\"><div id=\"i1\" class=\"col-sm-12\"></div></div>");
						addElementPes("PARRAFO","i1","vin_visitas","ID",elid,"CUMPLIOOBJ","¿Se cumplieron con los objetivos de la visita? Explique:","","","",misdatos);
											  
						$("#info").append("<div class=\"row\"><div id=\"i1\" class=\"col-sm-12\"></div></div>");
						addElementPes("PARRAFO","i1","vin_visitas","ID",elid,"INCIDENCIAS","Incidencias de la Visita:","","","",misdatos);
							
						$("#info").append("<div class=\"row\"><div id=\"i1\" class=\"col-sm-12\"></div></div>");
						addElementPes("FECHA","i1","vin_visitas","ID",elid,"FECHARES","Incidencias de la Visita:","","","",misdatos);
						
						$('.date-picker').datepicker({autoclose: true,todayHighlight: true}).next().on(ace.click_event, function(){$(this).prev().focus();});

				}
			});
		}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}
	
}
