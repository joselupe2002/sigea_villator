
function ALUM_MATRICULAclicadd(DATO, usuario, institucion, campus){

   var hoy= new Date();		
	var elanio=hoy.getFullYear();
	elaniomat=elanio.toString().substring(2,4)
   if (confirm("¿Seguro que desea consumir un consecutivo del año "+elanio+"?")) {
		$.ajax({
					type: "POST",
					url:"../base/getConsecutivo.php?tabla=econsoficial&campok=concat(TIPO,ANIO)&campocons=CONSECUTIVO&valork=MATRICULA"+elanio,
					success: function(dataC){
                     micons=dataC;			
                     mimat=elaniomat+"00"+pad(micons,4,'0');	
                     $("#ALUM_MATRICULA").val(mimat);					
               }
      });
   }
}
