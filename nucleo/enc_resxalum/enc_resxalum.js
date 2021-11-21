var id_unico="";
var estaseriando=false;
var matser="";
var cadFilPer="";
contAlum=1;
contMat=1;
cadaux="";
var losdatosprin = [];

    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 

		
		$(".input-mask-hora").mask("99:99");
		$(".input-mask-horario").mask("99:99-99:99");
		$(".input-mask-numero").mask("99");

	
		$("#lascarreras").append("<span class=\"label label-warning\">Carrera</span>");
		addSELECT("selCarreras","lascarreras","PROPIO", "SELECT CARR_CLAVE, CARR_DESCRIP FROM ccarreras where CARR_CLAVE=0", "","");  			      

		$.ajax({
			type: "GET",
			url:  "../base/getSesion.php?bd=Mysql&campo=carrera",
			success: function(data){  
				actualizaSelect("selCarreras", "(SELECT CARR_CLAVE, CARR_DESCORTA FROM ccarreras where CARR_ACTIVO='S'"+
				" and CARR_CLAVE IN ("+data+")) UNION (SELECT '%', 'TODAS LAS CARRERAS' FROM DUAL)", "",""); 				
				miscarreras=data;
				},
			error: function(data) {	                  
					   alert('ERROR: '+data);
					   $('#dlgproceso').modal("hide");  
				   }
		   });
		
		$("#lasencuestas").append("<span class=\"label label-danger\">Encuesta</span>");
		addSELECT("selEncuestas","lasencuestas","PROPIO", "SELECT ID, DESCRIP FROM encuestas order by ID desc", "","BUSQUEDA");  			      
	
		$("#laspersonas").append("<span class=\"label label-danger\">Alumnos / Personal</span>");
		addSELECT("selPersonas","laspersonas","PROPIO", "SELECT 0 from dual", "","BUSQUEDA");  			      
	

		$("#losfiltros").append("<span class=\"label label-danger\">Filtrar Por</span>");
		addSELECT("selFiltros","losfiltros","PROPIO", "SELECT CATA_CLAVE,CATA_DESCRIP from scatalogos where CATA_TIPO='PERIODICIDAD'", "","NORMAL");

		
	

		$("#losciclos").append("<i class=\" fa white fa-level-down bigger-180\"></i> ");
		$("#losciclos").append("<strong><span id=\"elciclo\" class=\"text-white bigger-40\"></span></strong>");
		colocarCiclo("elciclo","CLAVE");
		
	});
	
	
		 
	function change_SELECT(elemento) {

		if (elemento=='selEncuestas') {	
			addSELECT("selPreguntas","laspreguntas","PROPIO","SELECT CLAVE, concat(CLAVE,' ',PREGUNTA) FROM encpreguntas where IDENC='"+$("#selEncuestas").val()+"' order by CLAVE","","BUSQUEDA");
		}  
		

		if (elemento=='selCarreras') {				
			actualizaSelect("selPersonas","SELECT NUMERO, CONCAT(NUMERO,' ',NOMBRE) FROM vpersonas where CARRERA='"+$("#selCarreras").val()+"' order by NOMBRE","BUSQUEDA");
		}  

		if (elemento=='selFiltros') {	
			valor=$("#selFiltros").val();
			$("#losvalores").empty();
			cadFilPer=""; if ($("#selPersonas").val()!=0) {cadFilPer=" AND IDRESPONDE ='"+$("#selPersonas").val()+"'";}
			
			if (valor=="MENSUAL") {
				$("#losvalores").append("<span class=\"label label-primary\">No. Mes</span>");
				addSELECT("selValores","losvalores","PROPIO", "SELECT distinct CONCAT(ANIO,MES), CONCAT(ANIO,' ',MES) from vencrespuestas WHERE IDENC='"+$("#selEncuestas").val()+"' "+cadFilPer+"  ORDER BY ANIO,MES", "","NORMAL");
				cadaux=" and CONCAT(ANIO,MES)=";
			 }
			
			 if (valor=="DIARIO") {	
				$("#losvalores").append("<span class=\"label label-primary\">Fecha</span>");			 
				addSELECT("selValores","losvalores","PROPIO", "SELECT distinct FECHA, FECHA from vencrespuestas WHERE IDENC='"+$("#selEncuestas").val()+"' "+cadFilPer+"  ORDER BY STR_TO_DATE(FECHA,'%d-%m-%Y') DESC", "","NORMAL");
				cadaux=" and FECHA=";
			 }

			 if (valor=="ANUAL") {	
				$("#losvalores").append("<span class=\"label label-primary\">Año</span>");			
			   addSELECT("selValores","losvalores","PROPIO", "SELECT distinct ANIO, ANIO from vencrespuestas WHERE IDENC='"+$("#selEncuestas").val()+"' "+cadFilPer+"  ORDER BY ANIO DESC", "","NORMAL");
			   cadaux=" and ANIO=";
			}

			if (valor=="SEMANAL") {	
				$("#losvalores").append("<span class=\"label label-primary\">No. Semana</span>");			
			   addSELECT("selValores","losvalores","PROPIO", "SELECT distinct CONCAT(ANIO,NSEMANA), CONCAT(ANIO,' ',NSEMANA) from vencrespuestas WHERE IDENC='"+$("#selEncuestas").val()+"' "+cadFilPer+"  ORDER BY ANIO, NSEMANA DESC", "","NORMAL");
			   cadaux=" and CONCAT(ANIO,NSEMANA)=";
			}

			if (valor=="CICLO") {	
				$("#losvalores").append("<span class=\"label label-primary\">Ciclo</span>");			
			   addSELECT("selValores","losvalores","PROPIO", "SELECT distinct CICLO, CICLO from vencrespuestas WHERE IDENC='"+$("#selEncuestas").val()+"' "+cadFilPer+"  ORDER BY CICLO DESC", "","NORMAL");
			   cadaux=" and CICLO=";
			}

		}  

    }

/*===========================================================POR MATERIAS ==============================================*/
    function cargarInformacion(){

		cadPreg="";
		miscampos="";
		$("#selPreguntas option").each(function(){
			if ($(this).attr('value')!='0') {
				cadPreg+="<td title=\""+$(this).text()+"\">"+$(this).attr('value')+"</td>";
				miscampos+=$(this).val()+',';
			}
		 });

		script="<table id=\"tabMaterias\" name=\"tabMaterias\" class= \"table table-condensed table-bordered table-hover\" "+
		        ">"+
	   	   "        <thead >  "+
		   "             <tr id=\"headMaterias\">"+
		   "                <th>No.</th> "+
		   "                <th>Lis.</th> "+
		   "                <th>Res.</th> "+
		   "                <th>Numero</th> "+
		   "                <th>Nombre</th> "+
		   "                <th>Carrera</th> "+
		   "                <th>Especialiad</th> "+
		   "                <th>Ciclo</th> "+
		   "                <th>Periodo</th> "+
		   "                <th>Mes</th> "+
		   "                <th>Semana</th> "+
		   "                <th>Fecha</th> "+
		   cadPreg+			 
		   "             </tr> "+
		   "            </thead>" +
		   "         </table>";
		   $("#informacion").empty();
		   $("#informacion").append(script);
				
	
		elsql="SELECT a.ID, x.NUMERO AS MATRICULA, IDENC, x.NOMBRE, RESULTADO, PERIODICIDAD,"+
		" x.CARRERAD,"+miscampos+"CLAVEOF, ifnull(e.DESCRIP,'') AS ESPECIALIDADD, CICLO, ANIO, MES, NSEMANA, FECHA  FROM vencrespuestas a "+
		" LEFT OUTER JOIN falumnos b ON (IDRESPONDE=ALUM_MATRICULA) "+
		" LEFT OUTER JOIN ccarreras ON (ALUM_CARRERAREG=CARR_CLAVE)"+
		" LEFT OUTER JOIN especialidad e ON (ALUM_ESPECIALIDAD=e.ID), vpersonas x where  IDENC='"+$("#selEncuestas").val()+"'"+
		" and a.IDRESPONDE=x.NUMERO AND x.CARRERA LIKE '"+$("#selCarreras").val()+"'"+
		cadaux+"'"+$("#selValores").val()+"'"+cadFilPer;

	
		//alert (elsql);
		
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	    $.ajax({
			   type: "POST",
			   data:parametros,
			   url:  "../base/getdatossqlSeg.php",
	           success: function(data){  
				    losdatosprin=JSON.parse(data);				      			      
					generaTabla(losdatosprin);   													
				    ocultarEspera("esperahor");  																											
			},
			error: function(dataMat) {	                  
					alert('ERROR: '+dataMat);
								}
	});	      	      					  					  		
}

function generaTabla(grid_data){	
	contAlum=1;
	$("#cuerpoMaterias").empty();
	$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");
	//$("#btnfiltrar").attr("disabled","disabled");
	jQuery.each(grid_data, function(clave, valor) { 
		//alert ($("#rowM"+contAlum).html()+" "+valor.PROFESOR);   			
		$("#cuerpoMaterias").append("<tr id=\"rowM"+contAlum+"\">");
		$("#rowM"+contAlum).append("<td>"+contAlum+"</td>");
		
		btnListar="<button onclick=\"verListaPreg('"+valor.ID+"','"+clave+"');\""+
		" class=\"btn btn-white btn-success btn-round\">"+
		"<i class=\"/ace-icon blue fa fa-list-alt bigger-140\"></i></button>";
		
		btnRes="";
		if (valor.RESULTADO=="S") {
			btnRes="<button onclick=\"verResultado('"+valor.IDENC+"','"+valor.PERIODICIDAD+"','"+valor.MATRICULA+"','"+valor.ID+"');\""+
			" class=\"btn btn-white btn-success btn-round\">"+
			"<i class=\"/ace-icon green fa fa-check-square-o bigger-140\"></i></button>";
		}

		$("#rowM"+contAlum).append("<td>"+btnListar+"</td>");
		$("#rowM"+contAlum).append("<td>"+btnRes+"</td>");

		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.MATRICULA+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.NOMBRE+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.CARRERAD+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.ESPECIALIDADD+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.CICLO+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.ANIO+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.MES+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.NSEMANA+"</td>");
		$("#rowM"+contAlum).append("<td class=\"fontRoboto\">"+valor.FECHA+"</td>");
		$("#selPreguntas option").each(function(){
			if ($(this).attr('value')!='0') {
				$("#rowM"+contAlum).append("<td class=\"fontRobotoB\">"+grid_data[contAlum-1][$(this).attr('value')]+"</td>");		
			}
		 });
		
	    contAlum++;      			
	});	
} 



function verListaPreg(id,clave) {

	cadPreg="";
	mispreguntas="";
	$("#selPreguntas option").each(function(){
		if ($(this).attr('value')!='0') {
				mispreguntas+="<p class=\"fontRobotoB\" style=\"color:black;\">"+$(this).text()+"</p>";
				mispreguntas+="<p class=\"fontRobotoB\" style=\"color:blue; \">"+losdatosprin[clave][$(this).attr('value')]+"</p>"
			}
		});


	mostrarIfo("infoPreg","grid_enc_resxalum","RESPUESTA DE ENCUESTA",
	           "<div style=\"background-color:white; height:300px; overflow-y: auto; text-align: justify;\" class=\"sigeaPrin\">"+mispreguntas+"</div>","modal-lg"); 	

}

function verResultado(elidenc,periodo,laper,elidres) {


	parametros={dato:sessionStorage.co,bd:"Mysql",idenc:elidenc,periodicidad:periodo,lapersona:laper,idres:elidres}
	$.ajax({
		type: "POST",
		data:parametros,
		url:  "verResultado.php",
		success: function(data){  	
			mostrarIfo("infoPreg","grid_enc_resxalum","RESULTADO DE ENCUESTA",
			"<div style=\"background-color:white; height:200px; overflow-y: auto; text-align: justify;\" class=\"sigeaPrin\">"+data+"</div>","modal-lg"); 	
		}
	});



}




function verTablas(){

	var losdatos=[];
	var loscolores=[];
	var lasetiquetas=[];

	var colores=["#4b77a9","#5f255f","#d21243","#B27200","#B21200","#B23200","#B57200","#B47200"];
	latabla="<table id=\"tabTab\" name=\"tabTab\" class= \"table table-condensed table-bordered table-hover\">"+
                   "        <thead >  "+
                   "             <tr id=\"headTab\">"+
                   "                <th>No.</th> "+
				   "                <th>Respuesta</th> "+
					"                <th>No.</th> "+
					"             </tr> "+
					"            </thead>" +
					"         </table>"+
					"  <div class=\"row\" style=\"border:1px #E4E4E9 solid;\">"+
					"       <div class=\"col-sm-12\" id=\"canvas-holder\">"+
					"            <canvas id=\"chart-area\"></canvas>"+
					"       </div> "+
					" </div> ";
	

	mostrarIfo("infoMat","grid_enc_resultados",$("#selPreguntas option:selected").text(),latabla,"modal-lg"); 
	

	sql="SELECT "+$("#selPreguntas").val()+" as x,count(*) AS y "+
	" FROM encrespuestas s, vpersonas t where s.IDRESPONDE=t.NUMERO "+
	" and IDENC= '"+$("#selEncuestas").val()+"'"+
	" and t.CARRERA like '"+$("#selCarreras").val()+"' GROUP BY "+$("#selPreguntas").val();

	parametros={sql:sql,dato:sessionStorage.co,bd:"Mysql"}
	cont=1;
	$("#cuerpoTab").empty();
	$("#tabTab").append("<tbody id=\"cuerpoTab\">");

	$.ajax({
		type: "POST",
		data:parametros,
		url:  "../base/getdatossqlSeg.php",
		success: function(data){  	


			jQuery.each(JSON.parse(data), function(clave, valor) {
				$("#cuerpoTab").append("<tr id=\"rowT"+cont+"\">");
				$("#rowT"+cont).append("<td>"+cont+"</td>");
				$("#rowT"+cont).append("<td style=\"white-space: normal;\">"+valor.x+"</td>");
				$("#rowT"+cont).append("<td>"+valor.y+"</td>");		
				losdatos[cont-1]=valor.y;	
				loscolores[cont-1]=colores[cont-1];		
				lasetiquetas[cont-1]=valor.x;	
				cont++;
			});	
	
			
			var data = [{data:losdatos,
				backgroundColor: loscolores,
				borderColor: "#fff"
			  }];
  
			var options = {
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							var dataset = data.datasets[tooltipItem.datasetIndex];
						var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
							return parseFloat(previousValue) + parseFloat(currentValue);
						});
						var currentValue = dataset.data[tooltipItem.index];
						var precentage = Math.floor(((currentValue/total) * 100)+0.5);         
						return total+" "+currentValue+" "+precentage + "%";
						}
					}
					}
			};
  
			var ctx = document.getElementById("chart-area").getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'pie',
				data: {
				labels: lasetiquetas,
				datasets: data
				},
				options: options
			});




			
	 },
	 error: function(dataMat) {	                  
			 alert('ERROR: '+dataMat);
						 }
    });	  
   	      				
}


