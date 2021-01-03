

function impJustifica(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	enlace="nucleo/vejustifica/justificante.php?id="+table.rows('.selected').data()[0][0]+"&tipo=0":
	abrirPesta(enlace,"Justificante" );

    return false;
	
}


function correoJustifica(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	enlace="nucleo/vejustifica/justificante.php?id="+table.rows('.selected').data()[0][0]+"&tipo=1";	
	abrirPesta(enlace,"Justificante" );

    return false;
	
}


function selladoJustifica(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	enlace="nucleo/vejustifica/justificante.php?id="+table.rows('.selected').data()[0][0]+"&tipo=2"
	abrirPesta(enlace,"Justificante" );

    return false;
	
}
