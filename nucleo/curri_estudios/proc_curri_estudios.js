
	

function verAdjEst(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
	
			previewAdjunto("adjuntos/docDocumentos/"+table.rows('.selected').data()[0]["CURRI_RUTA"]);
	}
	else {
		alert ("Debe seleccionar un Mapa Curricular");
		return 0;

		}
	
}

