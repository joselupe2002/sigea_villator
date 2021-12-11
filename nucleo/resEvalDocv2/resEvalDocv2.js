var id_unico="";
var estaseriando=false;
var matser="";
contAlum=1;
contMat=1;


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 

		
		$(".input-mask-hora").mask("99:99");
		$(".input-mask-horario").mask("99:99-99:99");
		$(".input-mask-numero").mask("99");

		$("#lascarreras").append("<span class=\"label label-warning\">Carrera</span>");
		 
		$.ajax({
			type: "GET",
			url:  "../base/getSesion.php?bd=Mysql&campo=carrera",
			success: function(data){  
				addSELECT("selCarreras","lascarreras","PROPIO", "SELECT CARR_CLAVE, CARR_DESCRIP FROM ccarreras where CARR_ACTIVO='S'"+
				" and CARR_CLAVE IN ("+data+")", "",""); 
				},
			error: function(data) {	                  
					   alert('ERROR: '+data);
					   $('#dlgproceso').modal("hide");  
				   }
		   });
		
		$("#losciclossel").append("<span class=\"label label-danger\">Ciclo Escolar</span>");
		addSELECT("selCiclos","losciclossel","PROPIO", "SELECT CICL_CLAVE, concat(CICL_CLAVE,' ',CICL_DESCRIP) as CICL_CLAVE FROM ciclosesc order by cicl_clave desc", "","");  			      
	
		$("#losciclos").append("<i class=\" fa white fa-level-down bigger-180\"></i> ");
		$("#losciclos").append("<strong><span id=\"elciclo\" class=\"text-white bigger-40\"></span></strong>");
		colocarCiclo("elciclo","CLAVE");
		
	});
	
	
		 
	function change_SELECT(elemento) {
		if (elemento=='selCarreras') {	
			$("#loshorarios").empty();	
		}  
    }

/*===========================================================POR MATERIAS ==============================================*/
    function cargarInformacion(){
		script="<table id=\"tabMaterias\" name=\"tabMaterias\" class= \"table table-condensed table-bordered table-hover\" "+
		        ">"+
	   	   "        <thead >  "+
		   "             <tr id=\"headMaterias\">"+
		   "                <th>No.</th> "+
		   "                <th>ID.</th> "+
		   "                <th>Profesor</th> "+	
		   "                <th>Materia</th> "+	
		   "                <th>GPO</th> "+
		   "                <th>Sem</th>"+
		   "                <th>Alum</th> "+
		   "                <th>#Res</th> "+
		   "                <th>%Res</th> "+
		   "             </tr> "+
		   "            </thead>" +
		   "         </table>";
		   $("#informacion").empty();
		   $("#informacion").append(script);
				
		elsql="select a.IDDETALLE, PROFESOR, PROFESORD, MATERIA, MATERIAD, SEMESTRE, SIE AS GRUPO, "+
			  "(SELECT COUNT(DISTINCT(l.MATRICULA)) FROM ed_respuestasv2 l where l.TERMINADA='S' and l.IDGRUPO=a.IDDETALLE) AS RES, "+
			  "(select count(*) from dlista where IDGRUPO=a.IDDETALLE AND BAJA='N') AS ALUM "+
		      " from vedgrupos a, cmaterias b where a.MATERIA=b.MATE_CLAVE AND IFNULL(MATE_TIPO,'') NOT IN ('T','RP','I') and a.CICLO='"+$("#selCiclos").val()+"'"+ 
			  " and a.CARRERA="+$("#selCarreras").val()+"  ORDER BY SEMESTRE,MATERIAD";
	  
		mostrarEspera("esperahor","grid_resEvalDocv2","Cargando Datos...");
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	    $.ajax({
			   type: "POST",
			   data:parametros,
			   url:  "../base/getdatossqlSeg.php",
	           success: function(data){  				      			      
					generaTablaMaterias(JSON.parse(data));   													
				    ocultarEspera("esperahor");  																											
			},
			error: function(dataMat) {	                  
					alert('ERROR: '+dataMat);
								}
	});	      	      					  					  		
}

function generaTablaMaterias(grid_data){	
	contAlum=1;
	$("#cuerpoMaterias").empty();
	$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");
	//$("#btnfiltrar").attr("disabled","disabled");
	jQuery.each(grid_data, function(clave, valor) { 
		//alert ($("#rowM"+contAlum).html()+" "+valor.PROFESOR);   			
		$("#cuerpoMaterias").append("<tr id=\"rowM"+contAlum+"\">");
		$("#rowM"+contAlum).append("<td>"+contAlum+"</td>");
		$("#rowM"+contAlum).append("<td id=\"IDDETALLE_"+contAlum+"\" style=\"font-size:10px;\">"+valor.IDDETALLE+"</td>");
		$("#rowM"+contAlum).append("<td id=\"Nalum_"+contAlum+"\" style=\"font-size:10px;\">"+valor.PROFESORD+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:10px;\">"+valor.MATERIAD+"</td>");						
		$("#rowM"+contAlum).append("<td> <span class=\"badge  badge-info\">"+valor.GRUPO+"</span></td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\">"+valor.SEMESTRE+"</td>");

		evento="onclick=\"previewAdjunto('../pd_captcal/repUni.php?materia="+valor.MATERIA+"&grupo="+valor.GRUPO+
		"&ciclo="+$("#selCiclos").val()+"&profesor="+valor.PROFESOR+"&id="+valor.IDDETALLE+
		"&materiad="+valor.MATERIAD+"&semestre="+valor.SEMESTRE+"');\" ";

		stpor="";
		porc=Math.round((parseInt(valor.RES)/parseInt(valor.ALUM)*100),1);
		if (porc==0) stpor="class=\"badge  badge-danger\"";
		if ((porc>0) && (porc<=50)) stpor="class=\"badge  badge-warning\"";
		if ((porc>50) && (porc<=99)) stpor="class=\"badge  badge-success\"";
		if (porc>=100) stpor="class=\"badge  badge-primary\"";
		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\"><a "+evento+"><span title=\"click para ver reporte de unidades por alumnos\" style=\"cursor:pointer\" class=\"badge  badge-info\">"+valor.ALUM+"</span></a></td>");
		$("#rowM"+contAlum).append("<td><span title=\"Número de alumnos que han respondido la encuesta\" class=\"badge badge-success\"><i class=\"fa fa-male white\"> "+valor.RES+"</i></span></td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\"><span "+stpor+">"+porc+" %</span></td>");
		

	    contAlum++;      			
	});	
} 



/*===========================================================POR PROFESORES==============================================*/
function cargarInformacionP(){
	script="<table id=\"tabMaterias\" name=\"tabMaterias\" class= \"table table-condensed table-bordered table-hover\" "+
			">"+
		  "        <thead >  "+
	   "             <tr id=\"headMaterias\">"+
	   "                <th>No.</th> "+
	   "                <th>Ciclo</th> "+	
	   "                <th>Cve</th> "+	
	   "                <th>Profesor</th> "+	
	   "                <th>Depto</th> "+	
	   "                <th>Departamento</th> "+	 
	   "                <th>Alum</th> "+
	   "                <th>#Res</th> "+
	   "                <th>%Res</th> "+	
	   "                <th>Reporte</th> "+
	   "             </tr> "+
	   "            </thead>" +
	   "         </table>";
	   $("#informacion").empty();
	   $("#informacion").append(script);
			
	elsql="select CICLO,PROFESOR, PROFESORD, z.EMPL_DEPTO as DEPTO, ifnull(URES_DESCRIP,'') AS DEPTOD,"+
		  "SUM((SELECT COUNT(DISTINCT(l.MATRICULA)) FROM ed_respuestasv2 l where l.TERMINADA='S' and l.IDGRUPO=a.IDDETALLE)) AS RES, "+
		  "SUM((select count(*) from dlista where IDGRUPO=a.IDDETALLE AND BAJA='N')) AS ALUM "+
		  " from vedgrupos a, cmaterias b, pempleados z LEFT OUTER JOIN  fures y ON (z.EMPL_DEPTO=y.URES_URES)  where a.CICLO='"+$("#selCiclos").val()+"'"+ 
		  " and a.PROFESOR=z.EMPL_NUMERO and a.MATERIA=b.MATE_CLAVE AND IFNULL(MATE_TIPO,'') NOT IN ('T','RP','I')"+
		  " GROUP BY   CICLO, PROFESOR, PROFESORD, z.EMPL_DEPTO" +
		  "  ORDER BY  CICLO, z.EMPL_DEPTO, PROFESORD";


	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	mostrarEspera("esperahor","grid_resEvalDocv2","Cargando Datos...");
	$.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
		   success: function(data){  				      			      
				generaTablaProfesores(JSON.parse(data));   													
				ocultarEspera("esperahor");  																											
		},
		error: function(dataMat) {	                  
				alert('ERROR: '+dataMat);
							}
});	      	      					  					  		
}

function generaTablaProfesores(grid_data){	
contAlum=1;
$("#cuerpoMaterias").empty();
$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");
//$("#btnfiltrar").attr("disabled","disabled");
jQuery.each(grid_data, function(clave, valor) { 

	if (valor.ALUM>0) {
		$("#cuerpoMaterias").append("<tr id=\"rowM"+contAlum+"\">");
		$("#rowM"+contAlum).append("<td>"+contAlum+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.CICLO+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.PROFESOR+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.PROFESORD+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.DEPTO+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:10px;\">"+valor.DEPTOD+"</td>");

		evento="onclick=\"verMaterias('"+$("#selCiclos").val()+"','"+valor.PROFESOR+"');\"";

		stpor="";
		porc=Math.round((parseInt(valor.RES)/parseInt(valor.ALUM)*100),1);
		if (porc==0) stpor="class=\"badge  badge-danger\"";
		if ((porc>0) && (porc<=50)) stpor="class=\"badge  badge-warning\"";
		if ((porc>50) && (porc<=99)) stpor="class=\"badge  badge-success\"";
		if (porc>=100) stpor="class=\"badge  badge-primary\"";

		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\"><a "+evento+"><span title=\"click para ver asiganturas del profesor\" style=\"cursor:pointer\" class=\"badge  badge-info\">"+valor.ALUM+"</span></a></td>");
		$("#rowM"+contAlum).append("<td><span title=\"Número de alumnos que han respondido la encuensta\" class=\"badge badge-success\"><i class=\"fa fa-male white\"> "+valor.RES+"</i></span></td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\"><span "+stpor+">"+porc+" %</span></td>");
		


		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\">"+
		                                "<button onclick=\"previewAdjunto('nucleo/resEvalDocv2/reporte.php?ciclo="+valor.CICLO+"&profesor="+valor.PROFESOR+"&profesord="+valor.PROFESORD+"&deptod="+valor.DEPTOD+"');\""+
		                                " class=\"btn btn-white btn-success btn-round\">"+
		                                "<i class=\"/ace-icon blue fa fa-tachometer bigger-140\"></i> Reporte</button></td>");
		contAlum++;      			
	}
});	
} 

function verMaterias(ciclo,profesor){
	elsqlMa=elsql="select a.IDDETALLE, PROFESOR, PROFESORD, MATERIA, MATERIAD, SEMESTRE, SIE AS GRUPO, "+
	"(SELECT COUNT(DISTINCT(l.MATRICULA)) FROM ed_respuestasv2 l where l.TERMINADA='S' and l.IDGRUPO=a.IDDETALLE) AS RES, "+
	"(select count(*) from dlista where IDGRUPO=a.IDDETALLE AND BAJA='N') AS ALUM "+
	" from vedgrupos a, cmaterias b where a.MATERIA=b.MATE_CLAVE AND IFNULL(MATE_TIPO,'') NOT IN ('T','RP','I') "+
	"and a.CICLO='"+ciclo+"' and PROFESOR='"+profesor+"'"+ " ORDER BY SEMESTRE,MATERIAD";

	parametros={sql:elsqlMa,dato:sessionStorage.co,bd:"Mysql"}

	$.ajax({
		type: "POST",
		data:parametros,
		url:  "../base/getdatossqlSeg.php",
		success: function(data){  				      			      
			cad="<ol>";
			jQuery.each(JSON.parse(data), function(clave, valor) {
			   cad+="<li style=\"text-align:justify;\">"+valor.MATERIA+" "+valor.MATERIAD+
					"   <span title=\"Número de alumnos en el grupo\" class=\"badge badge-primary\">"+valor.ALUM+"</span>"+
					"   <span title=\"Número de alumnos que han respondido encuesta\" class=\"badge badge-success\">"+valor.RES+"</span>"+					
			        "</li>"; 
			});	
			cad+="</ol>";
			
			mostrarIfo("infoMat","grid_resEvalDocv2","Asignaturas",cad,"modal-lg"); 
	 },
	 error: function(dataMat) {	                  
			 alert('ERROR: '+dataMat);
						 }
    });	  
   	      				
}


/*===========================================================POR ALUMNOS==============================================*/


function cargarInformacionA(){
	script="<table id=\"tabMaterias\" name=\"tabMaterias\" class= \"table table-condensed table-bordered table-hover\" "+
			">"+
		  "        <thead >  "+
	   "             <tr id=\"headMaterias\">"+
	   "                <th>No.</th> "+
	   "                <th>Ciclo</th> "+	
	   "                <th>No. Control</th> "+	
	   "                <th>Nombre Alumno</th> "+	
	   "                <th>#Mat</th> "+
	   "                <th>Res</th> "+
	   "                <th>%Res</th> "+	   
	   "             </tr> "+
	   "            </thead>" +
	   "         </table>";
	   $("#informacion").empty();
	   $("#informacion").append(script);
			
	elsql="select PDOCVE AS CICLO,ALUM_MATRICULA as MATRICULA, concat (ALUM_APEPAT,' ',ALUM_APEMAT,' ', ALUM_NOMBRE) as NOMBRE, "+
	" count(*) as NMAT,"+
	" (SELECT COUNT(DISTINCT(l.IDGRUPO)) FROM ed_respuestasv2 l where l.TERMINADA='S' and l.CICLO='"+$("#selCiclos").val()+"' and l.MATRICULA=b.ALUM_MATRICULA) AS RES"+
	" from dlista a, falumnos b, cmaterias c  where PDOCVE='"+$("#selCiclos").val()+"'"+
	" and a.ALUCTR=b.ALUM_MATRICULA"+
	" and a.MATCVE=c.MATE_CLAVE "+   //AND IFNULL(MATE_TIPO,'') NOT IN ('T','RP','I') "+
	" and b.ALUM_CARRERAREG='"+$("#selCarreras").val()+"'"+
	" group by PDOCVE,ALUCTR";

	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	mostrarEspera("esperahor","grid_resEvalDocv2","Cargando Datos...");
	$.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
		   success: function(data){  				      			      
				generaTablaAlumnos(JSON.parse(data));   													
				ocultarEspera("esperahor");  																											
		},
		error: function(dataMat) {	                  
				alert('ERROR: '+dataMat);
							}
});	      	      					  					  		
}

function generaTablaAlumnos(grid_data){	
contAlum=1;
$("#cuerpoMaterias").empty();
$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");
//$("#btnfiltrar").attr("disabled","disabled");
jQuery.each(grid_data, function(clave, valor) { 

		$("#cuerpoMaterias").append("<tr id=\"rowM"+contAlum+"\">");
		$("#rowM"+contAlum).append("<td>"+contAlum+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.CICLO+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.MATRICULA+"</td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:14px;\">"+valor.NOMBRE+"</td>");
	
		evento="onclick=\"verMateriasA('"+$("#selCiclos").val()+"','"+valor.MATRICULA+"');\"";

		stpor="";
		porc=Math.round((parseInt(valor.RES)/parseInt(valor.NMAT)*100),1);
		if (porc==0) stpor="class=\"badge  badge-danger\"";
		if ((porc>0) && (porc<=50)) stpor="class=\"badge  badge-warning\"";
		if ((porc>50) && (porc<=99)) stpor="class=\"badge  badge-success\"";
		if (porc>=100) stpor="class=\"badge  badge-primary\"";

		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\"><a "+evento+"><span title=\"click para ver asiganturas del profesor\" style=\"cursor:pointer\" class=\"badge  badge-info\">"+valor.NMAT+"</span></a></td>");
		$("#rowM"+contAlum).append("<td><span title=\"Número de materias que ha evaluado\" class=\"badge badge-success\"><i class=\"fa fa-male white\"> "+valor.RES+"</i></span></td>");
		$("#rowM"+contAlum).append("<td style=\"font-size:12px;\"><span "+stpor+">"+porc+" %</span></td>");
		

		contAlum++;      			

});	
} 

function verMateriasA(ciclo,alumno){
	elsqlMa=elsql="select MATCVE AS MATERIA, MATE_DESCRIP AS MATERIAD, getcuatrimatxalum(MATCVE,ALUCTR) AS SEM "+
	" from dlista a, cmaterias c  where  PDOCVE='"+ciclo+"'  and  a.MATCVE=c.MATE_CLAVE AND ALUCTR='"+alumno+"'  ORDER BY 3,1";

	parametros={sql:elsqlMa,dato:sessionStorage.co,bd:"Mysql"}

	$.ajax({
		type: "POST",
		data:parametros,
		url:  "../base/getdatossqlSeg.php",
		success: function(data){  				      			      
			cad="<ol>";
			jQuery.each(JSON.parse(data), function(clave, valor) {
			   cad+="<li style=\"text-align:justify;\">"+valor.MATERIA+" "+valor.MATERIAD+					
			        "</li>"; 
			});	
			cad+="</ol>";
			
			mostrarIfo("infoMat","grid_resEvalDocv2","Asignaturas",cad,"modal-lg"); 
	 },
	 error: function(dataMat) {	                  
			 alert('ERROR: '+dataMat);
						 }
    });	  
   	      				
}



/*===============================ALUMNOS FALTANTES ======================================*/



function cargarFaltantes(){
	script="<table id=\"tabMaterias\" name=\"tabMaterias\" class= \"fontRoboto table table-condensed table-bordered table-hover\" "+
			">"+
		  "        <thead >  "+
	   "             <tr id=\"headMaterias\">"+
	   "                <th>No.</th> "+
	   "                <th>Ciclo</th> "+	
	   "                <th>No. Control</th> "+	
	   "                <th>Nombre Alumno</th> "+	
	   "                <th>Carrera</th> "+
	   "                <th>Materia</th> "+
	   "                <th>Correo</th> "+
	   "                <th>Correo Ins.</th> "+
	   "                <th>Teléfono</th> "+	   
	   "             </tr> "+
	   "            </thead>" +
	   "         </table>";
	   $("#informacion").empty();
	   $("#informacion").append(script);
			
	elsql="SELECT a.PDOCVE AS CICLO,ALUCTR AS MATRICULA, concat(ALUM_NOMBRE,' ',ALUM_APEPAT,' ',ALUM_APEMAT) AS NOMBRE, "+
	"CARR_CLAVE AS CARRREA, CARR_DESCRIP AS CARRERAD, MATE_CLAVE AS MATERIA, MATE_DESCRIP AS MATERIAD, "+
	"ALUM_CORREO AS CORREO, ALUM_CORREOINS AS CORREOINS, ALUM_TELEFONO AS TELEFONO"+
	" from dlista a, falumnos b, ccarreras, cmaterias where a.PDOCVE="+$("#selCiclos").val()+" and "+
	"ALUCTR=ALUM_MATRICULA and CARR_CLAVE=ALUM_CARRERAREG AND "+
	"MATCVE=MATE_CLAVE AND IFNULL(MATE_TIPO,'') NOT IN ('T','RP','I') AND LISTC15<>'9999' AND "+
	" a.ID NOT in (select IFNULL(h.IDDETALLE,0) from ed_respuestasv2 h) order by ALUM_APEPAT, ALUM_APEMAT,ALUM_NOMBRE";

	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	mostrarEspera("esperahor","grid_resEvalDocv2","Cargando Datos...");
	$.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
		   success: function(data){  				      			      
				generaTablaFaltan(JSON.parse(data));   													
				ocultarEspera("esperahor");  																											
		},
		error: function(dataMat) {	                  
				alert('ERROR: '+dataMat);
							}
});	      	      					  					  		
}


function generaTablaFaltan(grid_data){	
	contAlum=1;
	$("#cuerpoMaterias").empty();
	$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");
	//$("#btnfiltrar").attr("disabled","disabled");
	jQuery.each(grid_data, function(clave, valor) { 
	
			$("#cuerpoMaterias").append("<tr id=\"rowMF"+contAlum+"\">");
			$("#rowMF"+contAlum).append("<td>"+contAlum+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.CICLO+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.MATRICULA+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.NOMBRE+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.CARRERAD+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.MATERIA+" "+valor.MATERIAD+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.CORREO+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.CORREOINS+"</td>");
			$("#rowMF"+contAlum).append("<td style=\"font-size:14px;\">"+valor.TELEFONO+"</td>");
	
			contAlum++;      			
	
	});	
	} 



	function repGeneral(){
		enlace="nucleo/resEvalDocv2/reporteGen.php?ciclo="+$("#selCiclos").val()+"&depto="+$("#selCarreras").val()+"&deptod="+$("#selCarreras option:selected").text();
		abrirPesta(enlace,"RepGen")
	}