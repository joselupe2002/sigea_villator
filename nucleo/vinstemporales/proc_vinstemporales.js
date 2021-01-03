var nreg=0;
var elReg=0;


function verPagoTem(modulo,usuario,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {
		abrirPesta(table.rows('.selected').data()[0]["RUTA"],"Recibo");
    
	}
	else {
		alert ("Debe seleccionar un Registro");
		return 0;

		}
}











