

function impCarta(modulo,usuario,institucion, campus,essuper){
 	 table = $("#G_"+modulo).DataTable();
	 	  
	if (table.rows('.selected').data().length>0) {

		enlace="nucleo/vss_alumnos/carta.php?id="+table.rows('.selected').data()[0]["ID"]+"&tipo=0";
		abrirPesta(enlace,'Carta Presentación');


	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

      return false;
}

function impCartaSellada(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
  if (table.rows('.selected').data().length>0) {
	  enlace="nucleo/vss_alumnos/carta.php?id="+table.rows('.selected').data()[0]["ID"]+"&tipo=1";
	  abrirPesta(enlace,'Carta Presentación');
  }
  else {
	  alert ("Debe seleccionar un registro");
	  return 0;
	  }

	return false;
}



function setFinalizado(id,valor){
	$('#modalDocument').modal({show:true, backdrop: 'static'});	 
	   parametros={
		   tabla:"ss_alumnos",
		   campollave:"ID",
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
		   window.parent.document.getElementById('FRvss_alumnos').contentWindow.location.reload();
	   }					     
	   });    	                
}



function finaliza(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
        if (table.rows('.selected').data()[0]["FINALIZADO"]=='N') {
			if (confirm("Desea Finalizar el proceso de Servicio Social de  "+table.rows('.selected').data()[0]["MATRICULA"]+" "+table.rows('.selected').data()[0]["NOMBRE"])) {
				setFinalizado(table.rows('.selected').data()[0]["ID"],"S");
			}
		}
		else {
			if (confirm("El proceso de : "+table.rows('.selected').data()[0]["MATRICULA"]+" "+table.rows('.selected').data()[0]["NOMBRE"]+" esta Finalizados ¿desea cambiarlo a No Finalizado?")) {
				setFinalizado(table.rows('.selected').data()[0]["ID"],"N");
			}
		} 

	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}
	}



	function setValidado(id,valor,obs,user){
		$('#modalDocument').modal({show:true, backdrop: 'static'});	 
		   if (valor=='S') {parametros={tabla:"ss_alumnos",campollave:"ID",bd:"Mysql",valorllave:id,VALIDADO: valor,OBS:obs};}
		   else {parametros={tabla:"ss_alumnos",campollave:"ID",bd:"Mysql",valorllave:id,VALIDADO: valor,OBS:obs,ENVIADA:'N'};}
		   $.ajax({
		   type: "POST",
		   url:"actualiza.php",
		   data: parametros,
		   success: function(data){
			   $('#dlgproceso').modal("hide"); 
			   if (data.substring(0,1)=='0') {alert ("Ocurrio un error: "+data);}
			   //else {alert ("La actividad: "+table.rows('.selected').data()[0]["ACTIVIDAD"]+" ha sido autorizada")}	
			   window.parent.document.getElementById('FRvss_alumnos').contentWindow.location.reload();
		   }					     
		   });    	                
	}


	

	
function validarSol (modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	$("#confirmCotejado").empty();
	mostrarConfirm("confirmCotejado", "grid_vss_alumnos",  "Proceso de Cotejo",
	"<span class=\"label label-success\">Observaciones</span>"+
	"     <textarea id=\"obsCotejado\" style=\"width:100%; height:100%; resize: none;\">"+table.rows('.selected').data()[0]["OBS"]+"</textarea>",
	"¿Marcar como Validado? "+
	"<SELECT id=\"cotejado\"><OPTION value=\"S\">SI</OPTION><OPTION value=\"N\">NO</OPTION></SELECT>"
	,"Finalizar Proceso", "btnMarcarValidado('"+table.rows('.selected').data()[0]["MATRICULA"]+"','"+table.rows('.selected').data()[0]["ID"]+"','"+modulo+"','"+usuario+"');","modal-sm");
}


function btnMarcarValidado(alumno,id,modulo,eluser){
	setValidado(id,$("#cotejado").val(),$("#obsCotejado").val(),eluser);

	status="<span style=\"color:red\"><b>NO VALIDADO</b></span>"; 
	cadObs="<b>Favor de Revisar la siguiente Observación:<b><br>"+$("#obsCotejado").val();
	if ($("#cotejado").val()=='S') {status="<span style=\"color:green\"><b> VALIDADO</b></span>"; cadObs="";}

	correoalAlum(alumno, "<html>Tu solicitud de Servicio Social ha sido "+status+
							"</b></span>."+cadObs
							,"STATUS DE SOLICITUD SERVICIO SOCIAL "+alumno);			

}



	


function impLib(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
			enlace="nucleo/vss_alumnos/oficioLib.php?id="+table.rows('.selected').data()[0]["ID"]+"&tipo=0";
			abrirPesta(enlace,'Oficio Lib.');}
		else {
			alert ("El registro de  "+table.rows('.selected').data()[0]["MATRICULA"]+" "+table.rows('.selected').data()[0]["NOMBRE"]+" No esta  Finalizado");
		}


	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

}

function impLibSellado(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		if (table.rows('.selected').data()[0]["FINALIZADO"]=='S') {
			enlace="nucleo/vss_alumnos/oficioLib.php?id="+table.rows('.selected').data()[0]["ID"]+"&tipo=1";
			abrirPesta(enlace,'Oficio Lib.');}
		else {
			alert ("El registro de  "+table.rows('.selected').data()[0]["MATRICULA"]+" "+table.rows('.selected').data()[0]["NOMBRE"]+" No esta  Finalizado");
		}


	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

}

function impSolSS(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		enlace="nucleo/pa_servsoc/solicitud.php?id="+table.rows('.selected').data()[0]["ID"];
		abrirPesta(enlace, "Solic.");

	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

}


function impCartaSS(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		enlace="nucleo/pa_servsoc/cartaCom.php?id="+table.rows('.selected').data()[0]["ID"];
		abrirPesta(enlace, "Solic.");

	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

}







	
function veradjss  (modulo,usuario,institucion, campus,essuper){

	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
	
		ss_mostrarAdjuntos(modulo,usuario,institucion, campus,essuper,
			table.rows('.selected').data()[0]["CICLO"],
			table.rows('.selected').data()[0]["MATRICULA"],
			table.rows('.selected').data()[0]["NOMBRE"],
			table.rows('.selected').data()[0]["ID"],
			"modAdjuntos","eadjresidencia","servicioSocial","'SERVSOC_INI','SERVSOC_SEG','SERVSOC_FIN'");
	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}

}


/*====================================================*/




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
					window.parent.document.getElementById('FRvss_alumnos').contentWindow.location.reload();
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
	$('#resul').val("");
}


function promediarSS(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	agregarDialog(modulo);
	nreg=0;
	elReg=0;
	table.rows().iterator('row', function(context, index){
		 nreg++;		    
	});
	promediarAlumno(table.rows(elReg).data(), modulo,institucion,campus);
}





function promediarAlumno(lafila,modulo,institucion, campus) {
	res="";
	var table = $("#G_"+modulo).DataTable();	

	var prom=60;

    var alumno=((parseFloat(lafila[0]["REP1EVAL"]))+
              (parseFloat(lafila[0]["REP2EVAL"]))+
              (parseFloat(lafila[0]["REP3EVAL"]))+
              (parseFloat(lafila[0]["REP1AUTO"]))+
              (parseFloat(lafila[0]["REP1AUTO"]))+
              (parseFloat(lafila[0]["REP1AUTO"]))
              )/6;

    var empresa=((parseFloat(lafila[0]["REP1EVALACT"]))+
                 (parseFloat(lafila[0]["REP2EVALACT"]))+
                 (parseFloat(lafila[0]["REP3EVALACT"]))          
                )/3;

 	p90=(empresa*0.9);
	p10=(alumno*0.10);
	prom=(p90+p10).toFixed(2);
	prom2=(p90+p10).toFixed(0);
	if (isNaN(prom)) {prom=0;prom2=0;}


    elsql="SELECT CATA_DESCRIP FROM scatalogos where CATA_TIPO='CALIF_SS' AND  CATA_CLAVE='"+prom2+"'"+
          "ORDER BY CATA_DESCRIP ";
      
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	    $.ajax({
			   type: "POST",
			   data:parametros,
			   url:  "../base/getdatossqlSeg.php",
	           success: function(data){  
				
					parametros={tabla:"ss_alumnos",
								campollave:"ID",
								bd:"Mysql",
								valorllave:lafila[0][0],
								CALIFICACIONL: JSON.parse(data)[0][0],
								CALIFICACION2: prom2,
								CALIFICACION: prom,
							
							};
					$.ajax({type: "POST",
							url:"actualiza.php",
							data: parametros,
							success: function(data){    
									
									if (!(data.substring(0,1)=="0"))	{ 					                	 			                   
										$('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" Se Promedio al alumno "+lafila[0]["MATRICULA"]+". Promedio: "+prom+"\n"); 
										}	
									else {$('#resul').val($('#resul').val()+(elReg+1)+" de "+(nreg)+" OCURRIO EL SIGUIENTE ERROR: "+data+"\n");}
													
									elReg++;
									if (nreg>elReg) {promediarAlumno(table.rows(elReg).data(),modulo,institucion,campus);}	
									else {  window.parent.document.getElementById('FRvss_alumnos').contentWindow.location.reload(); }									
							}
						});
			    }
	});	      	      			
     
}


/*===========================================*/
function impBoletass(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {

		enlace="nucleo/vss_alumnos/boletass.php?carrera="+table.rows('.selected').data()[0]["CARRERA"]+
		"&ciclo="+table.rows('.selected').data()[0]["CICLO"];
		abrirPesta(enlace, "Boleta");

	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

}