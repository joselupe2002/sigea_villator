

function impExamen(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	enlace="nucleo/vlinaplicaexamen/examen.php?idaplica="+table.rows('.selected').data()[0][0]+
	"&idexamen="+table.rows('.selected').data()[0][1];

	abrirPesta(enlace, "Examen");
    return false;
	
}


function previewExa(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	
	var url="nucleo/vlinaplicaexamen/examenprev.php?idaplica="+table.rows('.selected').data()[0][0]+"&idexamen="+table.rows('.selected').data()[0][1]   	 
	abrirPesta(url, "Preview");
	
    return true;
	
}


