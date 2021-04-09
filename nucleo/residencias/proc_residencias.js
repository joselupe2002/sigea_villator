

function boletaResidencia(modulo,usuario,institucion, campus,essuper){
 	 table = $("#G_"+modulo).DataTable();
	 	  
	  if (table.rows('.selected').data().length>0) {
		  enlace="nucleo/residencias/boletaRes.php?ID="+table.rows('.selected').data()[0]["IDPROY"]+"&concal=N";
		abrirPesta(enlace, "BoletaRes");
		$('#modalDocument').modal("hide");  

	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;

		}

      return false;
}


function boletaResidenciaCal(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
		 
	if (table.rows('.selected').data().length>0) {
		enlace="nucleo/residencias/boletaRes.php?ID="+table.rows('.selected').data()[0]["IDPROY"]+"&concal=S";
		abrirPesta(enlace, "BoletaRes");	  
	  	$('#modalDocument').modal("hide");  

  }
  else {
	  alert ("Debe seleccionar un registro");
	  return 0;

	  }

	return false;
}


function liberacionRes(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	elanio = new Date().getFullYear();	 

	if (table.rows('.selected').data().length>0) {
		$.ajax({
			type: "POST",
			url:"../base/getConsecutivo.php?tabla=econsoficial&campok=concat(TIPO,ANIO)&campocons=CONSECUTIVO&valork=LIBRESIDENCIA"+elanio,
			success: function(data){
				micons=data;
				enlace="nucleo/residencias/liberacion.php?ID="+table.rows('.selected').data()[0]["IDRES"]+"&consec="+micons+"&anio="+elanio;
				abrirPesta(enlace, "Liberación");
				$('#modalDocument').modal("hide");  
			}					     
		});

	

  }
  else {
	  alert ("Debe seleccionar un registro");
	  return 0;

	  }

	return false;
}







function verAdjRes  (modulo,usuario,institucion, campus,essuper){

	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
	
		ss_mostrarAdjuntos(modulo,usuario,institucion, campus,essuper,
			table.rows('.selected').data()[0]["CICLO"],
			table.rows('.selected').data()[0]["MATRICULA"],
			table.rows('.selected').data()[0]["NOMBRE"],
			table.rows('.selected').data()[0]["ID"],
			"modAdjuntos","eadjresidencia","residenciasProf","'RESIDEN_REQ','RESIDEN_ANT','RESIDEN_SEG','RESIDEN_FIN'");
	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}

}






function verLibTit  (modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
		 
	if (table.rows('.selected').data().length>0) {
		enlace="nucleo/residencias/libTitulacion.php?ID="+table.rows('.selected').data()[0]["IDRES"]+"&producto=RESIDENCIA PROFESIONAL";;
		abrirPesta(enlace, "Titulación");
  }
  else {
	  alert ("Debe seleccionar un registro");
	  return 0;
	  }
	return false;
}

