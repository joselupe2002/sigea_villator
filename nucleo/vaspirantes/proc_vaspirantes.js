var nreg=0;
var elReg=0;

function setFinalizado(id,valor){
	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"aspirantes",
		   campollave:"IDASP",
		   bd:"Mysql",
		   valorllave:id,
		   FINALIZADO: valor
	   };
	   $.ajax({
	   type: "POST",
	   url:"actualiza.php",
	   data: parametros,
	   success: function(data){
		   $('#dlgproceso').modal("hide"); 
		   if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}
		   //else {alert ("La actividad: "+table.rows('.selected').data()[0]["ACTIVIDAD"]+" ha sido autorizada")}	
		   window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
	   }					     
	   });    	                
}



function noFinalizada(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
        if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
			if (confirm("Desea Abrir la ficha "+table.rows('.selected').data()[0]["IDASP"])) {
				setFinalizado(table.rows('.selected').data()[0]["IDASP"],"N");
			}
		}
		else {
			if (confirm("La Ficha: "+table.rows('.selected').data()[0]["IDASP"]+" Esta abierta ¿desea finalizarla?")) {
				setFinalizado(table.rows('.selected').data()[0]["IDASP"],"S");
			}
		} 

	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}
	}


function verDocumentos(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
			script="<div class=\"modal fade\" id=\"modalDocumentAsp\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\" > "+
		       "   <div class=\"modal-dialog modal-lg\" role=\"document\" >"+
			   "      <div class=\"modal-content\">"+
			   "          <div class=\"modal-header widget-header  widget-color-green\">"+
			   "             <span class=\"label label-lg label-primary arrowed arrowed-right\"> Documentos Adjuntados </span>"+
			   "             <span class=\"label label-lg label-success arrowed arrowed-right\">"+table.rows('.selected').data()[0]["NOMBRE"]+" "+table.rows('.selected').data()[0]["APEPAT"]+" "+table.rows('.selected').data()[0]["APEMAT"]+"</span>"+			   
			   "             <input type=\"hidden\" id=\"elid\" value=\""+table.rows('.selected').data()[0]["IDASP"]+"\"></input>"+
			   "             <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Cancelar\" style=\"margin: 0 auto; top:0px;\">"+
			   "                  <span aria-hidden=\"true\">&times;</span>"+
			   "             </button>"+
			   "          </div>"+  
			   "          <div id=\"frmdescarga\" class=\"modal-body\" >"+					 
			   "             <div class=\"row\" style=\"overflow-x: auto; overflow-y: auto; height:300px;\"> "+		
		       "                  <table id=\"tabAsp\" class= \"table table-condensed table-bordered table-hover\">"+
		   	   "                         <thead>  "+
			   "                               <tr>"+	
			   "                                   <th>ID</th> "+
			   "                                   <th>Documento</th> "+
			   "                             	   <th>PDF</th> "+ 
			   "                             	   <th>SUBIR PDF</th> "+ 
			   "                               </tr> "+
			   "                         </thead>" +
			   "                   </table>"+	
			   "             </div> "+ //div del row
			   "             <div class=\"space-10\"></div>"+		   
			   "          </div>"+ //div del modal-body		 
		       "          </div>"+ //div del modal content		  
			   "      </div>"+ //div del modal dialog
			   "   </div>"+ //div del modal-fade
			   "</div>";
		 
			
			
	 		 
			 $("#modalDocumentAsp").remove();
		    if (! ( $("#modalDocumentAsp").length )) {
		        $("#grid_"+modulo).append(script);
		    }

		    $('.date-picker').datepicker({autoclose: true,todayHighlight: true}).next().on(ace.click_event, function(){$(this).prev().focus();});
		    
		    $('#modalDocumentAsp').modal({show:true, backdrop: 'static'});

		    
	        sqlAsp="select a.IDDOC, a.CLAVE, a.DOCUMENTO, TIPOADJ,"+
				   "(SELECT ifnull(RUTA,'') AS RUTA FROM adjaspirantes b where "+
				   " b.AUX=CONCAT(a.CLAVE,'_','"+table.rows('.selected').data()[0]["CURP"]+"','_','"+table.rows('.selected').data()[0]["CICLO"]+"')) AS RUTA "+
		           " from documaspirantes a Where ENLINEA='S' and MODULO IN ('REGISTRO','INSCRIPCION') order by IDDOC";

			parametros={sql:sqlAsp,dato:sessionStorage.co,bd:"Mysql"}
		    $.ajax({
				   type: "POST",
				   data:parametros,
		           url:  "../base/getdatossqlSeg.php",
		           success: function(data){  
		        	      losdatos=JSON.parse(data);  
						  generaTablaSubirAsp(JSON.parse(data));						       
		        	        		        	    
		                 },
		           error: function(data) {	                  
		                      alert('ERROR: '+data);
		                  }
		   });
			   
		    
	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}
	
}


function generaTablaSubirAsp(grid_data, op){		
	   ladefault="..\\..\\imagenes\\menu\\pdf.png";
       $("#cuerpoAsp").empty();
	   $("#tabAsp").append("<tbody id=\"cuerpoAsp\">");
       c=0;	
	   globalUni=1; 
	   table = $("#G_vaspirantes").DataTable();
	   
       jQuery.each(grid_data, function(clave, valor) { 	
				stElim="display:none; cursor:pointer;";
				if ((valor.RUTA!='')&&(valor.RUTA!=null)) { stElim="cursor:pointer; display:block; ";}
		
				cadFile="<input class=\"fileSigea\" type=\"file\" id=\"ASPfile_"+valor.CLAVE+"\""+
					"                   onchange=\"subirPDFDriveSaveAsp_local('ASPfile_"+valor.CLAVE+"','docAspira','pdf"+
												  c+"','RUTA_"+valor.CLAVE+"','"+valor.TIPOADJ+"','N','ID','"+valor.CLAVE+
												  "',' DOCUMENTO  "+valor.DOCUMENTO+" ','adjaspirantes','alta','"+valor.CLAVE+"_"+table.rows('.selected').data()[0]["CURP"]+"_"+table.rows('.selected').data()[0]["CICLO"]+"','S');\">"+
					"           <input  type=\"hidden\" value=\"../"+valor.RUTA+"\"  name=\"RUTA_"+valor.CLAVE+"\" id=\"RUTA_"+valor.CLAVE+"\"  placeholder=\"\" />"+
					"        </div>"+
					"        <div class=\"col-sm-1\" style=\"padding-top:5px;\">"+
					"           <i style=\""+stElim+"\"  id=\"btnEli_RUTA_"+valor.CLAVE+"\" title=\"Eliminar el PDF que se ha subido anteriormente\" class=\"ace-icon glyphicon red glyphicon-trash \" "+
					"            onclick=\"eliminarEnlaceCarpeta('ASPfile_"+valor.CLAVE+"','docAspira',"+
					"                      'pdf"+c+"','RUTA_"+valor.CLAVE+"','"+valor.TIPOADJ+"','N','ID','"+valor.CLAVE+"','"+valor.DOCUMENTO+"-DOCUMENTO',"+
					"                      'adjaspirantes','alta','"+valor.CLAVE+"_"+table.rows('.selected').data()[0]["CURP"]+"_"+table.rows('.selected').data()[0]["CICLO"]+"','PDF');\"></i> ";


			 $("#cuerpoAsp").append("<tr id=\"rowAsp"+c+"\"></tr>");
			 $("#rowAsp"+c).append("<td>"+valor.IDDOC+"</td>");
			 $("#rowAsp"+c).append("<td>"+valor.DOCUMENTO+"</td>");				
			
		     cadEnc="<a title=\"Ver Archivo Adjunto\" target=\"_blank\" id=\"enlace_RUTA_"+valor.CLAVE+"\" href=\"../"+valor.RUTA+"\">"+
				                " <img width=\"40px\" height=\"40px\" id=\"pdf"+c+"\" src=\""+ladefault+"\" width=\"50px\" height=\"50px\">"+
								" </a>";		
				 
			$("#rowAsp"+c).append("<td style=\"text-align: center; vertical-align: middle;\">"+cadEnc+"</td>");					
				 
			$("#rowAsp"+c).append("<td>"+cadFile+"</td>");	
			
		     if ((valor.RUTA=='')||(valor.RUTA==null)) { 				    
			        $('#enlace_RUTA_'+valor.CLAVE).attr('disabled', 'disabled');
	                $('#enlace_RUTA_'+valor.CLAVE).attr('href', '..\\..\\imagenes\\menu\\pdfno.png');
					$('#pdf'+c).attr('src', "..\\..\\imagenes\\menu\\pdfno.png");   
				}

			    c++;
		   		globalUni=c;

		   		
		   	});

    $('.fileSigea').ace_file_input({
		no_file:'Sin archivo ...',
		btn_choose:'Buscar',
		btn_change:'Cambiar',
		droppable:false,
		onchange:null,
		thumbnail:false, //| true | large
		whitelist:'pdf',
		blacklist:'exe|php'
		//onchange:''
		//
	});
		
		   }
	

function verPago(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		    
			sqlAsp="SELECT ifnull(RUTA,'') AS RUTA FROM adjaspirantes b where "+
				   " b.AUX=CONCAT('PAGO_','"+table.rows('.selected').data()[0]["CURP"]+"_','"+table.rows('.selected').data()[0]["CICLO"]+"')";		          
			parametros={sql:sqlAsp,dato:sessionStorage.co,bd:"Mysql"}
		    $.ajax({
				   type: "POST",
				   data:parametros,
		           url:  "../base/getdatossqlSeg.php",
		           success: function(data){  
						  losdatos=JSON.parse(data);  
						  entre=false;
						  jQuery.each(losdatos, function(clave, valor) { 	
							   ruta=valor.RUTA;
							   entre=true;
						   });			
						   
						   if (entre) {
							previewAdjunto(ruta);							   
						   }
						   else {alert ("No se adjunto documento de pago");}
		        	        		        	    
		                 },
		           error: function(data) {	                  
		                      alert('ERROR: '+data);
		                  }
		   });
			   
		    
	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}
	
}



function setPagado(id,valor,obs){
	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"aspirantes",
		   campollave:"IDASP",
		   bd:"Mysql",
		   valorllave:id,
		   PAGADO: valor,
		   OBSPAGO:obs
	   };
	   $.ajax({
	   type: "POST",
	   url:"actualiza.php",
	   data: parametros,
	   success: function(data){
		   $('#dlgproceso').modal("hide"); 
		   if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}
		   //else {alert ("La actividad: "+table.rows('.selected').data()[0]["ACTIVIDAD"]+" ha sido autorizada")}	
		   window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
	   }					     
	   });    	                
}


/*============================PAGO DE LA INSCRIPCIÓN====================================================*/

function VerPagoIns(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		    // SE DEBE AGREGAR EL CICLO ESCOLAR MAS ADELANTE PROCESO 2021
			sqlAsp="SELECT ifnull(RUTA,'') AS RUTA FROM adjaspirantes b where "+
				   " b.AUX=CONCAT('PAIN_','"+table.rows('.selected').data()[0]["CURP"]+"_','"+table.rows('.selected').data()[0]["CICLO"]+"')";		          
			parametros={sql:sqlAsp,dato:sessionStorage.co,bd:"Mysql"}
		    $.ajax({
				   type: "POST",
				   data:parametros,
		           url:  "../base/getdatossqlSeg.php",
		           success: function(data){  
						  losdatos=JSON.parse(data);  
						  entre=false;
						  jQuery.each(losdatos, function(clave, valor) { 	
							   ruta=valor.RUTA;
							   entre=true;
						   });			
						   
						   if (entre) {
							previewAdjunto(ruta);				
						   }
						   else {alert ("No se adjunto documento de pago");}
		        	        		        	    
		                 },
		           error: function(data) {	                  
		                      alert('ERROR: '+data);
		                  }
		   });
			   
		    
	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}
	
}



function setPagadoIns(id,valor,obs){
	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"aspirantes",
		   campollave:"IDASP",
		   bd:"Mysql",
		   valorllave:id,
		   PAGADOINS: valor,
		   OBSINS:obs
	   };
	   $.ajax({
	   type: "POST",
	   url:"actualiza.php",
	   data: parametros,
	   success: function(data){
		   $('#dlgproceso').modal("hide"); 
		   if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}
		   //else {alert ("La actividad: "+table.rows('.selected').data()[0]["ACTIVIDAD"]+" ha sido autorizada")}	
		   window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
	   }					     
	   });    	                
}


function marcarPagadoIns(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
		$("#confirmPagado").empty();
		mostrarConfirm("confirmPagado", "grid_vaspirantes",  "Proceso de Pagado",
		"<span class=\"label label-success\">Observaciones</span>"+
		"     <textarea id=\"obsPagado\" style=\"width:100%; height:100%; resize: none;\">"+table.rows('.selected').data()[0]["OBSPAGOINS"]+"</textarea>",
		"¿Marcar como Pagado? "+
		"<SELECT id=\"pagado\"><OPTION value=\"S\">S</OPTION><OPTION value=\"N\">N</OPTION></SELECT>"
		,"Finalizar Proceso", "btnMarcarPagadoIns('"+table.rows('.selected').data()[0]["IDASP"]+"');","modal-sm");
	}
	else {alert ("El registro de este aspirante no esta finalizado");}
}

function btnMarcarPagadoIns(id){
	setPagadoIns(id,$("#pagado").val(),$("#obsPagado").val());
}

/*================================================================================================*/

function setCotejado(id,valor,obs){
	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"aspirantes",
		   campollave:"IDASP",
		   bd:"Mysql",
		   valorllave:id,
		   COTEJADO: valor,
		   OBSCOTEJO:obs
	   };
	   $.ajax({
	   type: "POST",
	   url:"actualiza.php",
	   data: parametros,
	   success: function(data){
		   $('#dlgproceso').modal("hide"); 
		   if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}
		   //else {alert ("La actividad: "+table.rows('.selected').data()[0]["ACTIVIDAD"]+" ha sido autorizada")}	
		   window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
	   }					     
	   });    	                
}



function marcarPagado(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
		$("#confirmPagado").empty();
		mostrarConfirm("confirmPagado", "grid_vaspirantes",  "Proceso de Pagado",
		"<span class=\"label label-success\">Observaciones</span>"+
		"     <textarea id=\"obsPagado\" style=\"width:100%; height:100%; resize: none;\">"+table.rows('.selected').data()[0]["OBSPAGO"]+"</textarea>",
		"¿Marcar como Pagado? "+
		"<SELECT id=\"pagado\"><OPTION value=\"S\">S</OPTION><OPTION value=\"N\">N</OPTION></SELECT>"
		,"Finalizar Proceso", "btnMarcarPagado('"+table.rows('.selected').data()[0]["IDASP"]+"');","modal-sm");
	}
	else {alert ("El registro de este aspirante no esta finalizado");}
}

function btnMarcarPagado(id){
	
	setPagado(id,$("#pagado").val(),$("#obsPagado").val());
}


function marcarCotejado(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
		$("#confirmCotejado").empty();
		mostrarConfirm("confirmCotejado", "grid_vaspirantes",  "Proceso de Cotejo",
		"<span class=\"label label-success\">Observaciones</span>"+
		"     <textarea id=\"obsCotejado\" style=\"width:100%; height:100%; resize: none;\">"+table.rows('.selected').data()[0]["OBSCOTEJO"]+"</textarea>",
		"¿Marcar como Cotejado? "+
		"<SELECT id=\"cotejado\"><OPTION value=\"S\">S</OPTION><OPTION value=\"N\">N</OPTION></SELECT>"
		,"Finalizar Proceso", "btnMarcarCotejado('"+table.rows('.selected').data()[0]["IDASP"]+"');","modal-sm");
	}
	else {alert ("El registro de este aspirante no esta finalizado");}
}

function btnMarcarCotejado(id){
	setCotejado(id,$("#cotejado").val(),$("#obsCotejado").val());
}


function setAprobado(id,valor){
	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"aspirantes",
		   campollave:"IDASP",
		   bd:"Mysql",
		   valorllave:id,
		   ACEPTADO: valor
	   };
	   $.ajax({
	   type: "POST",
	   url:"actualiza.php",
	   data: parametros,
	   success: function(data){
		   $('#dlgproceso').modal("hide"); 
		   if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}
		   //else {alert ("La actividad: "+table.rows('.selected').data()[0]["ACTIVIDAD"]+" ha sido autorizada")}	
		   window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
	   }					     
	   });    	                
}



function AceptarInd(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
		if (table.rows('.selected').data().length>0) {
			if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
				if (table.rows('.selected').data()[0]["ACEPTADO"]=='N') {
					if (confirm("Desea aprobar al alumno ID: "+table.rows('.selected').data()[0]["IDASP"])) {
						setAprobado(table.rows('.selected').data()[0]["IDASP"],"S");
					}
				}
				else {
					if (confirm("La Ficha: "+table.rows('.selected').data()[0]["IDASP"]+" Esta aprobada ¿desea NO ACEPTADO?")) {
						setAprobado(table.rows('.selected').data()[0]["IDASP"],"N");
					}
				} 	
		    }
	         else {alert ("El registro de este aspirante no esta finalizado");}
        }

		else { alert ("Debe seleccionar un Registro"); return 0; }
}
	




function agregarDialog(modulo){
	script="<div id=\"dlg-resultados\" class=\"hide\">"+
               "<textarea id=\"resul\" style=\"width: 100%; height: 100%; font-size: 10px;\"> </textarea>"+
           "</div>";
	if (! ( $("#dlg-resultados").length )) {
	    $("#grid_"+modulo).append(script);
	    }
	var dialog = $( "#dlg-resultados" ).removeClass('hide').dialog({
        modal: true,
        title: "Resultados...",
        width: 400,
        height: 400,
		title_html: true,
        buttons: [
            {
                text: "OK",
                "class" : "btn btn-primary btn-minier",
                click: function() {
					window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
	$('#resul').val("");
}





function aceptarAspirante(lafila,modulo,institucion, campus) {
	res="";
	var table = $("#G_"+modulo).DataTable();	
	parametros={
		tabla:"aspirantes",
		campollave:"IDASP",
		bd:"Mysql",
		valorllave:lafila[0][0],
		ACEPTADO: "S"
	};
    $.ajax({type: "POST",
        	url:"actualiza.php",
        	data: parametros,
        	success: function(data){        			        	
                if (!(data.substring(0,1)=="0"))	{ 					                	 			                   
			        $('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" Se acepto el aspirante "+lafila[0][0]+" correctamente \n"); 
				    }	
			    else {$('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" OCURRIO EL SIGUIENTE ERROR: "+data+"\n");}
        			        	
        		elReg++;
				if (nreg>elReg) {aceptarAspirante(table.rows(elReg).data(),modulo,institucion,campus);}
				if (nreg==elReg) { window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();}
			 }					     
          });    	            
}


function AceptarTodos(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	agregarDialog(modulo);
	nreg=0;
	elReg=0;
	table.rows().iterator('row', function(context, index){
		 nreg++;		    
	});
		aceptarAspirante(table.rows(elReg).data(), modulo,institucion,campus);
}


//=============================================INSCRIPCION ==================================*/


function insTodos(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	agregarDialog(modulo);
	nreg=0;
	elReg=0;
	table.rows().iterator('row', function(context, index){
		 nreg++;		    
	});
	inscribirAspirante(table.rows(elReg).data(), modulo,institucion,campus, );
}

function insInd(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
		if (table.rows('.selected').data().length>0) {
			if (table.rows('.selected').data()[0]["ACEPTADO"]=='S') {
				if (table.rows('.selected').data()[0]["INSCRITO"]=='N') {
					if (confirm("Desea inscribir al aspirante ID: "+table.rows('.selected').data()[0]["IDASP"])) {
						setInscrito(table.rows('.selected').data()[0]["IDASP"],"S",table.rows('.selected').data()[0]["CICLO"],table.rows('.selected').data()[0]["CVE_CARRERA1"]);
					}
				}
				else {
					  if (confirm("El aspirante ya se encuentra inscrito, desea deshacer su inscripción recuerde que el consecutivo no se regresa")){
						  eliminarMatricula(table.rows('.selected').data()[0]["IDASP"],table.rows('.selected').data()[0]["MATRICULA"]);
					  }
					}
				} 	
	         else {alert ("El aspirante no ha sido aceptado");}
        }
		else { alert ("Debe seleccionar un Registro"); return 0; }
}
	

function eliminarMatricula(id,lamatricula){
  
   $('#modalDocument').modal({show:true, backdrop: 'static'});	 
   parametros={ tabla:"aspirantes", campollave:"IDASP",bd:"Mysql",valorllave:id,INSCRITO: 'N',MATRICULA:''};
   $.ajax({
		type: "POST",
		url:"actualiza.php",
		data: parametros,
		success: function(data){
			//eliminamos de dlista
			$('#modalDocument').modal({show:true, backdrop: 'static'});	 
			parametros={ tabla:"dlista", campollave:"ALUCTR",bd:"Mysql",valorllave:lamatricula};
			$.ajax({
				 type: "POST",
				 url:"eliminar.php",
				 data: parametros,
				 success: function(data){
					   //eliminamos de falumnos
					$('#modalDocument').modal({show:true, backdrop: 'static'});	 
					parametros={ tabla:"falumnos", campollave:"ALUM_MATRICULA",bd:"Mysql",valorllave:lamatricula};
					$.ajax({
							type: "POST",
							url:"eliminar.php",
							data: parametros,
							success: function(data){ window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload(); }
						}); 
				 }
			 });
		}
	});
   
}


function setInscrito(id,valor, ciclo, lacar){
	var hoy= new Date();		
	//var elanio=hoy.getFullYear();
	//elaniomat=elanio.toString().substring(2,4)

	elanio="20"+ciclo.substring(1,3);
	elaniomat=elanio.toString().substring(2,4);


	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"aspirantes",
		   campollave:"IDASP",
		   bd:"Mysql",
		   valorllave:id,
		   INSCRITO: valor
	   };
	   $.ajax({
			type: "POST",
			url:"actualiza.php",
			data: parametros,
			success: function(data){
					$.ajax({
						type: "POST",
						url:"../base/getConsecutivo.php?tabla=econsoficial&campok=concat(TIPO,ANIO)&campocons=CONSECUTIVO&valork="+"MATRICULA"+elanio,
						success: function(dataC){
							micons=dataC;							
							mimat=elaniomat+pad(lacar,2,'0')+pad(micons,4,'0');	
							elsqlpas="call inscribeAspirante('"+id+"','"+mimat+"');";						
							if (micons>0) {
								parametros={
									bd:"mysql",
									sql:elsqlpas,
									dato:sessionStorage.co							
								};

								$.ajax({
									type: "POST",
									url:"../base/ejecutasql.php",
									data:parametros,
									success: function(dataC){	
										console.log(dataC);			
										alert ("El aspirante "+id+" Ha quedado inscrito con la matricula "+mimat);
										$('#dlgproceso').modal("hide"); 
										if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}										
										window.parent.document.getElementById('FRvaspirantes').contentWindow.location.reload();
									}					     
								});   //ajax del procedimiento de insercion en falumnos  					   
							}
							else {$('#dlgproceso').modal("hide"); }
						}					     
					});  // ajax del consecutivo   
			} 					     
	   }); //ajax de actualizacion   	                
}


function inscribirAspirante(lafila,modulo,institucion, campus) {
	res="";
	var table = $("#G_"+modulo).DataTable();	
	var hoy= new Date();	
	alert (lafila[0]["CICLO"]);	
	
	elanio="20"+lafila[0]["CICLO"].substring(1,3);
	elaniomat=elanio.toString().substring(2,4);

	if ((lafila[0]["INSCRITO"]=='N') && (lafila[0]["ACEPTADO"]=='S')) {
		parametros={tabla:"aspirantes",campollave:"IDASP",bd:"Mysql",valorllave:lafila[0][0],INSCRITO: "S"};
		$.ajax({type: "POST",
				url:"actualiza.php",
				data: parametros,
				success: function(data){    
					$.ajax({
						type: "POST",
						url:"../base/getConsecutivo.php?tabla=econsoficial&campok=concat(TIPO,ANIO)&campocons=CONSECUTIVO&valork="+"MATRICULA"+elanio,
						success: function(dataC){
							console.log(dataC);
							micons=dataC;				
							mimat=elaniomat+"040"+pad(micons,3,'0');						
							if (micons>0) {
								parametros={
									bd:"mysql",
									sql:"call inscribeAspirante('"+lafila[0][0]+"','"+mimat+"'); ",
									dato:sessionStorage.co				
								};

								$.ajax({
									type: "POST",
									url:"../base/ejecutasql.php",
									data:parametros,
									success: function(dataC){
										
										console.log(dataC);
										
										if (!(data.substring(0,1)=="0"))	{ 					                	 			                   
											$('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" Se inscribio el aspirante "+lafila[0][0]+". Matricula: "+mimat+"\n"); 
											}	
										else {$('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" OCURRIO EL SIGUIENTE ERROR: "+data+"\n");}
														
										elReg++;
										if (nreg>elReg) {inscribirAspirante(table.rows(elReg).data(),modulo,institucion,campus);}										
									 }																								     
								});   //ajax del procedimiento de insercion en falumnos  					   
							}
							else {$('#dlgproceso').modal("hide"); }
						}					     
					});  // ajax del consecutivo   	
				}						     
			  });    	  
	} 
	else {
		$('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" EL ASPIRANTE "+lafila[0][0]+" YA ESTA INSCRITO O NO ESTA ACEPTADO \n");
		elReg++;
		inscribirAspirante(table.rows(elReg).data(),modulo,institucion,campus);	
	}
     
}


/*======================================Envio de Correos =====================================*/



function envioCorreo(modulo,usuario,essuper) {
	getVentanaCorreo("vaspirantes","CORREO");
	
}


