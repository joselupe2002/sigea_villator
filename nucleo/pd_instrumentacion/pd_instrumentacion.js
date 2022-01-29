var todasColumnas;
var haycorte;
var idcorte=0;
var tipocorte="";
var inicia_corte="";
var termina_corte="";  

$(document).ready(function($) { var Body = $('body'); Body.addClass('preloader-site');});
$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});

$(document).ready(function($) { 
    cargarAct();
});




function cargarAct(){
    $('#dlgproceso').modal({show:true, backdrop: 'static'});
    elsql="SELECT ID, MATERIA, MATERIAD, SIE, SEM, CICLO, BASE, "+  
          "     (select (ROUND((SELECT count(*) FROM pgrupo h where h.MATCVE=MATERIA "+
          "                     AND PDOCVE=CICLO AND GPOCVE=SIE AND h.TIPOCIERRE IN ('T','R'))/"+
          "     (SELECT COUNT(*) FROM eunidades where UNID_MATERIA=MATERIA AND UNID_PRED<>'')*100,0) "+
          "      ) from dual) as AVANCE_PROG  "+             
          " FROM vcargasprof a where PROFESOR='"+usuario+"'"+
                   //" and CICLO=getciclo() "+
          " and CERRADOCAL='N' order by CICLO DESC,MATERIA";

    parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
           type: "POST",
           data:parametros,
           url:  "../base/getdatossqlSeg.php",
           success: function(data){
                 generaTabla(JSON.parse(data));	 
                 $('#dlgproceso').modal("hide");        	     
                 },
           error: function(data) {	  
                      $('#dlgproceso').modal("hide");                 
                      alert('ERROR: '+data);	                      
                  }
          });
}


function generaTabla(grid_data){
c=0;
$("#cuerpo").empty();
$("#tabHorarios").append("<tbody id=\"cuerpo\">");
jQuery.each(grid_data, function(clave, valor) { 	
    
    $("#cuerpo").append("<tr id=\"row"+valor.ID+"\">");
    $("#row"+valor.ID).append("<td>"+valor.ID+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.CICLO+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.SEM+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.SIE+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.MATERIA+"</td>");
    $("#row"+valor.ID).append("<td>"+valor.MATERIAD+"</td>");
    
    $("#row"+valor.ID).append("<td style=\"text-align: center;\"><button title=\"Copiar Instrumentación de Otro Ciclo Escolar\" onclick=\"copiar('"+valor.ID+"','"+usuario+"','"+
    valor.MATERIA+"','"+valor.MATERIAD+"','"+valor.SIE+"','"+valor.CICLO+"','"+valor.BASE+"');\""+
           " class=\"btn btn-xs btn-white btn-primary btn-round\"><i class=\"ace-icon fa fa-copy green  bigger-120\"></i>Copiar</button></td>");


    $("#row"+valor.ID).append("<td style=\"text-align: center;\"><button title=\"Análisis por competencias específicas\" onclick=\"analisis('"+valor.ID+"','"+usuario+"','"+
                               valor.MATERIA+"','"+valor.MATERIAD+"','"+valor.SIE+"','"+valor.CICLO+"','"+valor.BASE+"');\""+
                                      " class=\"btn btn-xs btn-white btn-primary btn-round\"><i class=\"ace-icon fa fa-magic blue  bigger-120\"></i></button></td>");
    
    //Boton de Indicadores de Alcance
    $("#row"+valor.ID).append("<td style=\"text-align: center;\"><button title=\"Indicadores de alcance\" onclick=\"indicadores('"+valor.ID+"','"+usuario+"','"+
                               valor.MATERIA+"','"+valor.MATERIAD+"','"+valor.SIE+"','"+valor.CICLO+"','pd_captcal');\""+
                                      " class=\"btn btn-xs btn-white btn-success btn-round\"><i class=\"ace-icon fa fa-level-up green fa-wrench bigger-140\"></i></button></td>");								  
                                    
    
    //Boton de Matriz de Evaluación 
    $("#row"+valor.ID).append("<td style=\"text-align: center;\"><button title=\"Matriz de evaluación\" onclick=\"matrizEval('"+valor.ID+"','"+usuario+"','"+
                               valor.MATERIA+"','"+valor.MATERIAD+"','"+valor.SIE+"','"+valor.CICLO+"','"+valor.BASE+"','"+valor.SEM+"');\""+
                                      " class=\"btn btn-xs btn-white btn-warning btn-round\"><i class=\"ace-icon blue fa fa-table bigger-140\"></i></button></td>");								  
    
 //Boton de Matriz de Calendarización 
    $("#row"+valor.ID).append("<td style=\"text-align: center;\"><button title=\"Calendarización de evaluación en semanas\" onclick=\"calendarizacion('"+valor.ID+"','"+usuario+"','"+
    valor.MATERIA+"','"+valor.MATERIAD+"','"+valor.SIE+"','"+valor.CICLO+"','"+valor.BASE+"','"+valor.SEM+"');\""+
           " class=\"btn btn-xs btn-white btn-warning btn-round\"><i class=\"ace-icon purple fa fa-calendar bigger-140\"></i></button></td>");								  


    //Boton de Reporte por COrte
    $("#row"+valor.ID).append("<td style=\"text-align: center;\"><button title=\"Reporte de Instrumentación\" onclick=\"imprimirInstrum('"+usuario+"','"+
                               valor.CICLO+"','"+valor.ID+"','"+valor.MATERIA+"');\""+
                             " class=\"btn btn-xs btn-white btn-primary btn-round\"><i class=\"ace-icon green fa fa-file  bigger-140\"></i></button></td>");								  
      
    

});
}		

/*=================================================================*/
function copiar(id,profesor,materia,materiad,grupo,ciclo, base){
  
	dameVentana("ventCopia", "grid_pd_instrumentacion","Copiar Información","sm","bg-successs","fa fa-copy blue bigger-180","370");
	$("#body_ventCopia").append("<div class=\"row fontRoboto bigger-120\">"+
							"     <div class=\"col-sm-12\">"+								
							"	    	<label class=\"label label-primary fontRobotoB bigger-80\">Copiar Instrumentación de Otros Ciclos</label><br>"+
							"			<select id=\"selOtro\" class=\"form-controls\"></select>"+						
							"	  </div><br>"+	
							"     <div class=\"col-sm-12\" style=\"text-align:center;\">"+
							"			<br><button title=\"Sacar copia\" onclick=\"sacarCopia('"+id+"','"+profesor+"','"+ciclo+"');\""+
                                                 " 			class=\"btn btn-white btn-warning btn-round\"><i class=\"ace-icon pink fa fa-lightbulb-o bigger-140\"></i>Copiar Información</button>"+    													
							"	  </div>");	
       actualizaSelect("selOtro", "SELECT IDDETALLE, CONCAT(MATERIAD,'(',CICLO,'-',SIE,')') FROM vedgrupos WHERE PROFESOR='"+profesor+"'  AND MATERIA='"+materia+"' order by CICLO DESC", "","");			
							
}


function sacarCopia(migrupo,profesor,ciclo){
       migrupoc=$("#selOtro").val();
       fecha=dameFecha("FECHAHORA");
	elsql= " DELETE FROM ins_analisis where IDGRUPO='"+migrupo+"'; "+
              " DELETE FROM ins_matriz where IDGRUPO='"+migrupo+"'; "+
              " DELETE FROM ins_indicadores where IDGRUPO='"+migrupo+"'; "+
              " DELETE FROM ins_niveles where IDGRUPO='"+migrupo+"';"+
              " DELETE FROM ins_calendario where IDGRUPO='"+migrupo+"'; ";

  	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
  	$.ajax({
		 type: "POST",
		 data:parametros,
		 url:  "../base/ejecutasql.php",
		 success: function(data){  
			console.log(data);			
			elsql="insert into ins_indicadores (IDGRUPO,UNIDAD,INDICADOR,VALOR,LETRA,USUARIO,FECHAUS) "+
			" select '"+migrupo+"',UNIDAD,INDICADOR,VALOR,LETRA,USUARIO,'"+fecha+"'  FROM ins_indicadores "+
			" where IDGRUPO='"+migrupoc+"';"+
			"insert into ins_niveles (IDGRUPO,UNIDAD,NEX,NNO,NBU,NSU,NIN,USUARIO,FECHAUS) "+
			" select '"+migrupo+"',UNIDAD,NEX,NNO,NBU,NSU,NIN,USUARIO,'"+fecha+"'  FROM ins_niveles "+
			" where IDGRUPO='"+migrupoc+"';"+
                     "insert into ins_analisis (IDGRUPO,UNIDAD,ACTENSENANZA,DC_INS,DC_INT,DC_SIS,HORAST,HORASP,_INSTITUCION,_CAMPUS,USUARIO,FECHAUS) "+
			" select '"+migrupo+"',UNIDAD,ACTENSENANZA,DC_INS,DC_INT,DC_SIS,HORAST,HORASP,_INSTITUCION,_CAMPUS,USUARIO,FECHAUS  FROM ins_analisis "+
			" where IDGRUPO='"+migrupoc+"';"+
                     "insert into ins_matriz (IDGRUPO,UNIDAD,EVAPR,PORC,EVALFOR,A,B,C,D,E,F,G,H,I,J,USUARIO,FECHAUS) "+
			" select '"+migrupo+"',UNIDAD,EVAPR,PORC,EVALFOR,A,B,C,D,E,F,G,H,I,J,USUARIO,FECHAUS  FROM ins_matriz "+
			" where IDGRUPO='"+migrupoc+"';"+
                     "insert into ins_calendario (IDGRUPO,SEM,TIPO,USUARIO,FECHAUS) "+
			" select '"+migrupo+"',SEM,TIPO,USUARIO,FECHAUS  FROM ins_calendario "+
			" where IDGRUPO='"+migrupoc+"';";
                    
                     console.log(elsql);
              
			parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
				type: "POST",
				data:parametros,
				url:  "../base/ejecutasql.php",
				success: function(data){  
					console.log(data);	
					$("#ventCopia").modal("hide");
						
				}	
				
			});				
		 }	
		 
	});			 
}





/*============================================================================================*/




function analisis(id,profesor,materia,materiad,grupo,ciclo, base){
	window.location="analisis.php?base="+base+"&termina_corte="+termina_corte+"&inicia_corte="+inicia_corte+"&idcorte="+
								  idcorte+"&tipocorte="+tipocorte+"&id="+id+"&ciclo="+ciclo+"&profesor="+profesor+"&grupo="+
								  grupo+"&materia="+materia+"&materiad="+materiad+"&modulo=pd_instrumentacion";
}



function indicadores(id,profesor,materia,materiad,grupo,ciclo, base){
	window.location="indicadores.php?base="+base+"&termina_corte="+termina_corte+"&inicia_corte="+inicia_corte+"&idcorte="+
								  idcorte+"&tipocorte="+tipocorte+"&id="+id+"&ciclo="+ciclo+"&profesor="+profesor+"&grupo="+
								  grupo+"&materia="+materia+"&materiad="+materiad+"&modulo=pd_instrumentacion";
}



function matrizEval(id,profesor,materia,materiad,grupo,ciclo, base){
	window.location="matrizEval.php?base="+base+"&termina_corte="+termina_corte+"&inicia_corte="+inicia_corte+"&idcorte="+
								  idcorte+"&tipocorte="+tipocorte+"&id="+id+"&ciclo="+ciclo+"&profesor="+profesor+"&grupo="+
								  grupo+"&materia="+materia+"&materiad="+materiad+"&modulo=pd_instrumentacion";
}


function calendarizacion(id,profesor,materia,materiad,grupo,ciclo, base){
	window.location="calendarizacion.php?base="+base+"&termina_corte="+termina_corte+"&inicia_corte="+inicia_corte+"&idcorte="+
								  idcorte+"&tipocorte="+tipocorte+"&id="+id+"&ciclo="+ciclo+"&profesor="+profesor+"&grupo="+
								  grupo+"&materia="+materia+"&materiad="+materiad+"&modulo=pd_instrumentacion";
}


function imprimirInstrum(usuario,ciclo,id,materia){
enlace="nucleo/pd_instrumentacion/reporteIns.php?id="+id+"&materia="+materia+"&prof="+usuario+"&ciclo="+ciclo;
abrirPesta(enlace,"Instrum.");

}
