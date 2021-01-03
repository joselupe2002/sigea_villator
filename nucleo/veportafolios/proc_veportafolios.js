




function verPortafolio(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {		
		
		if (table.rows('.selected').data()[0]["RUTA"]) {
			previewAdjunto(table.rows('.selected').data()[0]["RUTA"]);
		}
		else {
			alert ("No se ha adjuntando Portafolio de Evidencia")
			}
		}
	else {
		alert ("Debe seleccionar a un alumno");
		return 0;

		}
	
}


