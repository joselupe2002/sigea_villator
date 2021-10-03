var id_unico="";
var estaseriando=false;
var matser="";
contR=1;
contMat=1;


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 		
		$(".input-mask-hora").mask("99:99");
		$(".input-mask-horario").mask("99:99-99:99");
		$(".input-mask-numero").mask("99");


		$("#losciclos").append("<span class=\"label label-danger\">Ciclo Escolar</span>");
		addSELECT("selCiclos","losciclos","PROPIO", "SELECT CICL_CLAVE, CONCAT(CICL_CLAVE,' ',CICL_DESCRIP) FROM ciclosesc order by cicl_clave desc", "","");  			      
	

		$("#lasmatricula").append("<span class=\"label label-danger\">Matrícula(s)</span>");
		$("#lasmatricula").append("<input class=\"form-control\" id=\"matricula\"></input>");

		$("#losprofesores").append("<span class=\"label label-danger\">Profesores(s)</span>");
		$("#losprofesores").append("<input class=\"form-control\" id=\"profesor\"></input>");

		$("#lasmaterias").append("<span class=\"label label-danger\">Materia(s)</span>");
		$("#lasmaterias").append("<input class=\"form-control\" id=\"materia\"></input>");
	

		
	});
	
	
		 
	function change_SELECT(elemento) {

    }


    function cargarInformacion(){
	
		$("#opcionestabInformacion").addClass("hide");
		$("#botonestabInformacion").empty();
		
		script="<table id=\"tabInformacion\" name=\"tabInformacion\" class= \"table table-condensed table-bordered table-hover\" "+
		        ">"+
	   	   "        <thead >  "+
		   "             <tr id=\"headMaterias\">"+
		   "                <th>No.</th> "+
		   "                <th>Eli</th> "+
		   "                <th>Id</th> "+
		   "                <th>CICLO</th> "+
		   "                <th>MATRICULA</th> "+	
		   "                <th>NOMBRE</th> "+	
		   "                <th>CARRERA</th> "+	
		   "                <th>SEMESTRE</th> "+	
		   "                <th>GRUPO</th> "+	
		   "                <th>TIPOCAL</th> "+	
		   "                <th>CVE_MATERIA</th> "+	
		   "                <th>MATERIA</th> "+	
		   "                <th>CRED</th> "+	
		   "                <th>CVE_PROF</th> "+	
		   "                <th>PROFESOR</th> "+	
		   "                <th>CARRERA</th> "+	
		   "                <th>LISCAL</th> "+	
		   "                <th>LISPA1</th> "+	
		   "                <th>LISPA2</th> "+	
		   "                <th>LISPA3</th> "+	
		   "                <th>LISPA4</th> "+	
		   "                <th>LISPA5</th> "+	
		   "                <th>LISPA6</th> "+	
		   "                <th>LISPA7</th> "+	
		   "                <th>LISPA8</th> "+	
		   "                <th>LISPA9</th> "+	
		   "                <th>LISPA10</th> "+	
		   "              </tr>"+
		   "            </thead>" +
		   "         </table>";
		   $("#informacion").empty();
		   $("#informacion").append(script);


		   tagCiclo="";
		   if ($("#selCiclos").val()>0) {tagCiclo=" AND PDOCVE='"+$("#selCiclos").val()+"'";}

				
		   tagMatricula="";
		   if ($("#matricula").val()!='') {tagMatricula=" AND ALUCTR IN ('"+$("#matricula").val().replace(/,/gi,"','")+"')";}


		   tagProfesor="";
		   if ($("#profesor").val()!='') {tagProfesor=" AND LISTC15 IN ('"+$("#profesor").val().replace(/,/gi,"','")+"')";}

		   tagMateria="";
		   if ($("#materia").val()!='') {tagMateria=" AND MATCVE IN ('"+$("#materia").val().replace(/,/gi,"','")+"')";}


		elsql=" select  ID, ALUCTR, PDOCVE,ALUM_NOMBRE, CICL_CREDITO, ALUM_APEPAT,ALUM_APEMAT,CICL_CUATRIMESTRE, "+
		"GPOCVE, TCACVE, MATCVE, MATE_DESCRIP, LISTC15, EMPL_NOMBRE, EMPL_APEPAT, EMPL_APEMAT, "+
		"(select count(*) from eciclmate where CICL_MATERIA=MATCVE AND CICL_MAPA=ALUM_MAPA) AS ESTA,"+
		"MATE_TIPO AS TIPO"+
		"CARR_CLAVE, CARR_DESCRIP,LISCAL, ifnull(LISPA1,'') as LISPA1, ifnull(LISPA2,'') as LISPA2, "+		
		"ifnull(LISPA3,'') as LISPA3,"+
		"ifnull(LISPA4,'') as LISPA4,"+
		"ifnull(LISPA5,'') as LISPA5,"+
		"ifnull(LISPA6,'') as LISPA6,"+
		"ifnull(LISPA7,'') as LISPA7,"+
		"ifnull(LISPA8,'') as LISPA8,"+
		"ifnull(LISPA9,'') as LISPA9,"+
		"ifnull(LISPA10,'') as LISPA10 from (((((dlista join falumnos) join ccarreras) "+
		" left join cmaterias on (dlista.MATCVE = cmaterias.MATE_CLAVE) ) "+
		" left join pempleados on (dlista.LISTC15 = pempleados.EMPL_NUMERO))"+
		"  left join eciclmate on ((dlista.MATCVE = eciclmate.CICL_MATERIA and eciclmate.CICL_MAPA=falumnos.ALUM_MAPA) "+
		"                       or (dlista.MATCVE = eciclmate.CICL_MATERIA and LISTC13=10)))"+
	   " where falumnos.ALUM_MATRICULA = dlista.ALUCTR and falumnos.ALUM_CARRERAREG = ccarreras.CARR_CLAVE"+
	     tagCiclo+tagMatricula+tagProfesor+tagMateria+
		" ORDER BY PDOCVE, ALUCTR";
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}

	
		mostrarEspera("esperaInf","grid_histalumno","Cargando Datos...Puede tardar");
	    $.ajax({
			   type: "POST",
			   data:parametros,
			   url:  "../base/getdatossqlSeg.php",
	           success: function(data){  				      			      
					generaTablaInformacion(JSON.parse(data));   
					ocultarEspera("esperaInf");  
																																													
			    },
			    error: function(dataMat) {	                  
					alert('ERROR: '+dataMat);
								}
	});	      	      					  					  		
}





function generaTablaInformacion(grid_data){
	totegr=0;totdes=0;
	contR=1;
	$("#cuerpoInformacion").empty();
	$("#tabInformacion").append("<tbody id=\"cuerpoInformacion\">");
	//$("#btnfiltrar").attr("disabled","disabled");
	
	jQuery.each(grid_data, function(clave, valor) { 
  	
		fondo=""; if (valor.ESTA==0) {fondo="style=\"background: #FF5733;\"";}
		if (valor.TIPO=="I") {fondo="style=\"background: #B8895A;\"";}

		$("#cuerpoInformacion").append("<tr id=\"rowM"+contR+"\" "+fondo+">");

		
		$("#rowM"+contR).append("<td>"+contR+"</td>");
		$("#rowM"+contR).append("<td><button onclick=\"eliminar('"+valor.ID+"')\" class=\"btn btn-white btn-info btn-bold\">"+
		                            "<i class=\"ace-icon fa fa-trash bigger-120 red\"></i></button></td>");
		$("#rowM"+contR).append("<td>"+valor.ID+"</td>");
		$("#rowM"+contR).append("<td>"+valor.PDOCVE+"</td>");
		$("#rowM"+contR).append("<td>"+valor.ALUCTR+"</td>");
		$("#rowM"+contR).append("<td>"+valor.ALUM_NOMBRE+" "+valor.ALUM_APEPAT+" "+valor.ALUM_APEMAT+"</td>");
		$("#rowM"+contR).append("<td>"+valor.CARR_CLAVE+" "+valor.CARR_DESCRIP+"</td>");
		$("#rowM"+contR).append("<td>"+valor.CICL_CUATRIMESTRE+"</td>");
		$("#rowM"+contR).append("<td>"+valor.GPOCVE+"</td>");
		$("#rowM"+contR).append("<td>"+valor.TCACVE+"</td>");
		$("#rowM"+contR).append("<td>"+valor.MATCVE+"|"+valor.ESTA+"</td>");
		$("#rowM"+contR).append("<td>"+valor.MATE_DESCRIP+"</td>");
		$("#rowM"+contR).append("<td>"+valor.CICL_CREDITO+"</td>");
		
		$("#rowM"+contR).append("<td>"+valor.LISTC15+"</td>");
		$("#rowM"+contR).append("<td>"+valor.EMPL_NOMBRE+" "+valor.EMPL_APEPAT+" "+valor.EMPL_APEMAT+"</td>");
		$("#rowM"+contR).append("<td>"+valor.CARR_CLAVE+" "+valor.CARR_DESCRIP+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISCAL+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA1+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA2+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA3+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA4+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA5+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA6+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA7+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA8+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA9+"</td>");
		$("#rowM"+contR).append("<td>"+valor.LISPA10+"</td>");
		contR++;
	});


} 


function eliminar(elid) {
	if(confirm("Seguro que desea eliminar el registro: "+elid) ){
		    		
			
	  var parametros = {
				"tabla" :"dlista",
				"campollave" : "ID",
				"bd":"Mysql",
				"valorllave" : elid
		};
		
	  $.ajax({
			  data:  parametros,
			  url:   '../base/eliminar.php',
			  type:  'post',          
			  success:  function (response) {
				  console.log(response);
				  cargarInformacion();
				  
			}		
		 }); 
  }    
}