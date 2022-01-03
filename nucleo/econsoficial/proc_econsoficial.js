

function copiaConse(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	lafec=dameFecha("FECHAHORA");
	
	if (table.rows('.selected').data().length>0) {
		
		var anio = prompt("Coloque el nuevo año. Se copiará del Año "+table.rows('.selected').data()[0]["ANIO"], "");

		if (anio != null) {

			sqAse="insert into econsoficial (TIPO, ANIO, CONSECUTIVO, USUARIO, FECHAUS,_INSTITUCION,_CAMPUS) "+
			"select TIPO,'"+anio+"','1','"+usuario+"','"+lafec+"',_INSTITUCION,_CAMPUS FROM econsoficial WHERE ANIO='"+table.rows('.selected').data()[0]["ANIO"]+"'";
		
			parametros={
				bd:"mysql",
				sql:sqAse,
				dato:sessionStorage.co				
			};

			$.ajax({
				type: "POST",
				url:"../base/ejecutasql.php",
				data:parametros,
				success: function(dataC){
					console.log(dataC);
				
					window.parent.document.getElementById('FReconsoficial').contentWindow.location.reload();
				}
			});
						
			
		 	
		}
	}
	else {
		alert ("Debe seleccionar un registro, para identificar el año que desea copiar");
		return 0;

		}  
	    
}
