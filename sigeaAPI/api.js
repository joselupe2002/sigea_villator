var elciclo="";

    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});



    jQuery(function($) { 

		if (t=='C1') {getCredencial();}

	
	});

	function getCredencial(){
		elsql="select a.*, b.*, c.*, d.*, getPeriodos(ALUM_MATRICULA,getCiclo()) as PERIODO, count(*) HAY from falumnos a, vdlista_cred b, ciclosesc c, ccarreras d where "+
		" MATRICULA='"+atob(i)+"' and CICLO=getciclo() and CICLO=CICL_CLAVE and ALUM_MATRICULA=MATRICULA and ALUM_CARRERAREG=CARR_CLAVE";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../nucleo/base/getdatossqlSeg.php",
			success: function(data){	
		 			cont=JSON.parse(data);
					if (cont[0]["HAY"]>0) {
						$("#container").html("<div class=\"fontRobotoB row bigger-200\">"+
						                    "    <div class=\"col-sm-2\" style=\" background-color:#0C0D66; color:white\">Matricula: </div>"+
											"    <div class=\"col-sm-8\"  style=\"background-color:#064E4D; color:white\">"+cont[0]["ALUM_MATRICULA"]+"</div>"+								
						                    "</div>"+
											"<div class=\"fontRobotoB row bigger-200\">"+
						                    "    <div class=\"col-sm-2\" style=\"border-top:1px solid white; background-color:#0C0D66; color:white\">Nombre: </div>"+
											"    <div class=\"col-sm-8\"  style=\"border-top:1px solid white; background-color:#064E4D; color:white\">"+cont[0]["ALUM_NOMBRE"]+" "+cont[0]["ALUM_APEPAT"]+" "+cont[0]["ALUM_APEMAT"]+"</div>"+								
						                    "</div>"+
											"<div class=\"fontRobotoB row bigger-200\">"+
						                    "    <div class=\"col-sm-2\" style=\"border-top:1px solid white; background-color:#0C0D66; color:white\">Carrera: </div>"+
											"    <div class=\"col-sm-8\"  style=\"border-top:1px solid white; background-color:#064E4D; color:white\">"+cont[0]["CARR_DESCRIP"]+"</div>"+								
						                    "</div>"+
											"<div class=\"fontRobotoB row bigger-200\">"+
						                    "    <div class=\"col-sm-2\" style=\"border-top:1px solid white; background-color:#0C0D66; color:white\">Semestre: </div>"+
											"    <div class=\"col-sm-8\"  style=\"border-top:1px solid white; background-color:#064E4D; color:white\">"+cont[0]["PERIODO"]+"</div>"+								
						                    "</div>");
						
						if (cont[0]["ALUM_FOTO"].indexOf('../')<0) {
							lafoto=cont[0]["ALUM_FOTO"];
						}
						else {
							var re = /\.\.\//gi;					
							lafoto="../"+cont[0]["ALUM_FOTO"].replace(re, "");
						}
						console.log(lafoto);
						$("#add").html("<img style=\"width:130px; height:160px; border: 1px solid #E1E7E7; border-radius:50%;\" src=\""+lafoto+"\">");
					}
					else {
						$("#container").html("<div class=\"alert alert-danger\">LA CREDENCIAL SE ENCUENTRA VENCIDA</div>");
					}
				
				} // fin del ajax de consulta del ciclo escolar
			});

	}


