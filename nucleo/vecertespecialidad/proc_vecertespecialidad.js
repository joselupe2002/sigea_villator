
function imprimeDiploma(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {		
		elid=table.rows('.selected').data()[0][0];
		enlace="nucleo/vecertespecialidad/certespecialidad.php?matricula="+table.rows('.selected').data()[0]["MATRICULA"]+
		"&id="+table.rows('.selected').data()[0]["ID"];

		abrirPesta(enlace,"Certificado");
	    return false;
	}
	else {
		alert ("Debe seleccionar un registro");
		return 0;
		}
   
}




