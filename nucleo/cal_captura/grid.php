
<?php session_start(); if (($_SESSION['inicio']==1)  && (strpos($_SESSION['permisos'],$_GET["modulo"])) ){ 
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";  
	$nivel="../../";
?> 
<!DOCTYPE html>
<html lang="es">
	<head>
	    <link rel="icon" type="image/gif" href="imagenes/login/sigea.ico">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="<?php echo $_SESSION['encode'];?>" />
		<title><?php echo $_SESSION["titApli"];?></title>
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/fonts.googleapis.com.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ace-rtl.min.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="<?php echo $nivel; ?>estilos/preloader.css" type="text/css" media="screen">         
        <link href="imagenes/login/sigea.png" rel="image_src" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ui.jqgrid.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/chosen.min.css" />

        <style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }
               th, td {  word-wrap: break-word;        
                         overflow-wrap: break-word;   }
               
        </style>
	</head>


	<body id="grid_<?php echo $_GET['modulo']; ?>" style="background-color: white; width:95%;">
	    
	    
	    
	    
	<div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>
    
    <div class="row" >
		 <div class="col-sm-2" id="contCiclos"> 
		       <span class="label label-info">Ciclo Escolar</span>
		 </div>
		 <div class="col-sm-4" id="contProfesores"> 
		       <span class="label label-success">Profesor</span>
		 </div>

		 <div class="col-sm-4" id="contMaterias"> 
		       <span class="label label-success">Materias</span>
		 </div>

		 <div class="col-sm-2" id="contTipo"> 
		       <span class="label label-success">Tipo</span>
			   <select  id="selTipo" style="width: 100%;">
			     <option value="1">PRIMERA OPORTUNIDAD</option>
				 <option value="2">SEGUNDA OPORTUNIDAD</option>
				
			   </select>
		 </div>
		 
	</div>
	<div class="space-12"></div>
	    <div class="row"><div class="col-sm-5"><span class="label label-success" style="width:100%" id="labol"></span></div></div>  
		<div class="row">
			<div class="col-sm-8"> 
	     		 <table id="latabla" class= "display table-condensed table-striped table-sm table-bordered table-hover nowrap" ></table>
			</div> 
			<div class="hide col-sm-4" id="botones"> 
				<button title="Ver Boleta de Calificaciones" onclick="imprimirBoleta()" class="btn  btn-white btn-primary btn-round" style="width:150px;">
		        	<i class="ace-icon fa green fa-file-text-o bigger-140"></i> Imprimir Boleta
			  	</button>
				<br/>
				<br/>
				<button title="Cerrar boleta de Materias" onclick="cerrarBoleta('S')" class="btn btn-white btn-danger btn-round" style="width:150px;">
		         	<i class="ace-icon  red glyphicon glyphicon-folder-close bigger-140"></i> Cerrar Boleta
			  	</button>
				<br/>
				<br/>
				<button title="Abrir boleta de Materias" onclick="cerrarBoleta('N')" class="btn btn-white btn-warning btn-round" style="width:150px;">
		         	<i class="ace-icon  blue glyphicon glyphicon-folder-open bigger-140"></i> Abrir Boleta
			  	</button>

			</div> 
		 
	</div>

	<select style="display:none;" id="base" style="width: 100%;">
			              <?php for ($x=0;$x<=100;$x++) {?>
			                  echo  <option value="<?php echo $x;?>"><?php echo $x;?></option>			            
			              <?php } ?>		              
			           </select>
				
 
<!-- DIALOGO DE ESPERA -->     
<div id="dlgproceso" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 50%;">
     <div class="modal-content" style="vertical-align: middle;">
         <div class="modal-header" style="text-align: center;"> <p style="font-size: 16px; color: green; font-weight: bold;"> Procesando espere por favor..</p></div>
         <div class="modal-body" style="text-align: center;">
              <img src="../../imagenes/menu/esperar.gif" style="background: transparent; width: 100px; height: 80px"/>	
         </div>     
     </div>
     </div>
</div>

 
        
<!-- -------------------Primero ----------------------->
<script src="<?php echo $nivel; ?>assets/js/jquery-2.1.4.min.js"></script>
<script type="<?php echo $nivel; ?>text/javascript"> if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");</script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap.min.js"></script>

<!-- -------------------Segundo ----------------------->
<script src="<?php echo $nivel; ?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/chosen.jquery.min.js"></script>

<!-- -------------------Medios ----------------------->
<script src="<?php echo $nivel; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.flash.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.html5.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.print.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.colVis.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/dataTables.select.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/moment.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.knob.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/autosize.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.inputlimiter.min.js"></script>
<script src="<?php echo $nivel; ?>js/mask.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-tag.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-select.js"></script>

<!-- -------------------ultimos ----------------------->
<script src="<?php echo $nivel; ?>assets/js/ace-elements.min.js"></script>
<script type="text/javascript" src="<?php echo $nivel; ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo $nivel; ?>js/subirArchivos.js"></script>
<script src="<?php echo $nivel; ?>js/utilerias.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.jqGrid.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/grid.locale-en.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootbox.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.gritter.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.easypiechart.min.js"></script>
<script src="<?php echo $nivel; ?>js/utilerias.js"></script>


<script type="text/javascript">
        var todasColumnas;
        var haycorte;
		var idcorte=0;
		var tipocorte="";
        var inicia_corte="";
        var termina_corte="";  
        
		$(document).ready(function($) { var Body = $('body'); Body.addClass('preloader-site');});
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});

		$(document).ready(function($) { 
			
			addSELECT("selCiclos","contCiclos","CICLOS", "", "","BUSQUEDA"); 
			addSELECT("selProfesores","contProfesores","PROPIO", "select EMPL_NUMERO, EMPL_NOMBRE FROM pempleados limit 1", "", "BUSQUEDA"); 
			addSELECT("selMaterias","contMaterias","PROPIO", "select 0,'' FROM dual limit 1", "", "BUSQUEDA"); 

			
			
	    });

		function change_SELECT(elemento) {
        if (elemento=='selCiclos') {
		
			actualizaSelect("selProfesores","select distinct PROFESOR, CONCAT( PROFESORD ,' ',PROFESOR) from vcargasprof where CICLO='"+
										$("#selCiclos").val()+"' and CARRERA not in (12) ORDER BY PROFESORD","BUSQUEDA");
										
			
			}
	    if (elemento=='selProfesores') {
			elsql="SELECT MATERIA, concat(MATERIA,' ',MATERIAD,'|',SEM,'|',SIE,'|',ID) "+                
			  " FROM vcargasprof a where  PROFESOR='"+$("#selProfesores").val()+"' and CICLO='"+$("#selCiclos").val()+"' AND CARRERA NOT IN (12)";
			actualizaSelect("selMaterias",elsql,"BUSQUEDA");											
		}

		if (elemento=='selMaterias') {
			cargarAlumnos();
			$("#botones").removeClass("hide");
		}


    }



	function cargarAlumnos() {


			 grupo=$('#selMaterias option:selected').text().split("|")[2];
			 idgr=$('#selMaterias option:selected').text().split("|")[3];
			 
			 elsql="select DGRU_CERRADOCAL as C FROM edgrupos where DGRU_ID='"+idgr+"'";
			 parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			 $.ajax({
				 type: "POST",
				 data:parametros,
		         url:  "../base/getdatossqlSeg.php",
		         success: function(data){    
					laclass="label-success"; cadc="BOLETA ABIERTA"; if (JSON.parse(data)[0]["C"]=='S' ) {cadc="BOLETA CERRADA"; laclass="label-danger";}
					$("#labol").html(cadc);
					$("#labol").removeClass("label-success");
					$("#labol").removeClass("label-danger");
					$("#labol").addClass(laclass);
				 }
				});

			 elsql="select a.ID, ALUM_MATRICULA,  CONCAT(ALUM_APEPAT,' ',ALUM_APEMAT,' ',ALUM_NOMBRE) AS NOMBRE, IFNULL(LISCAL,'0') as CAL"+
				          " from dlista a, falumnos b where a.ALUCTR=b.ALUM_MATRICULA and a.GPOCVE='"+grupo+"'"+
				          " and PDOCVE='"+$("#selCiclos").val()+"' and LISTC15='"+$("#selProfesores").val()+"'"+
						  " and MATCVE='"+$("#selMaterias").val()+"' and a.BAJA='N' order by ALUM_APEPAT,ALUM_APEMAT,ALUM_NOMBRE";

			
	         parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			 $.ajax({
				 type: "POST",
				 data:parametros,
		         url:  "../base/getdatossqlSeg.php",
		         success: function(data){    
		        	 $("#latabla").empty();
		        	 $("#latabla").append("<thead><tr id=\"titulo\"><th style=\"text-align: center;\">No. Control</th>"+ 
                                                      "<th style=\"text-align: center;\">Nombre</th><th colspan=\"2\" style=\"text-align: center;\">Calif.</th></tr></thead>"); 
			     
		        	 $("#latabla").append("<tbody id=\"cuerpo\">");		        	 
		        	 jQuery.each(JSON.parse(data), function(clave, valor) { 
		        		    $("#cuerpo").append("<tr id=\"row"+valor.ID+"\">");				
				    	    $("#row"+valor.ID).append("<td id=\"matricula_"+valor.ID+"\">"+valor.ALUM_MATRICULA+"</td>");
				    	    $("#row"+valor.ID).append("<td id=\"nombre_"+valor.ID+"\">"+utf8Decode(valor.NOMBRE)+"</td>");

				    	
				    	    	//Para capturar calificaciones
				    	    	$("#row"+valor.ID).append("<td id=\"nombre_"+valor.ID+"\">"+"<select class=\"text-primary\" style=\"width:60px; font-size:11px;\" id=\"SEL_"+valor.ID+"\" "+
						    	    	" onchange=\"guardar("+valor.ID+",'LISCAL','"+$("#selMaterias").val()+"','"+$("#selProfesores").val()+"','"+$("#selCiclos").val()+"','"+
						    	    	valor.ALUM_MATRICULA+"','"+grupo+"','CAL');\"></select>"+"</td>");

				    	    	//Para el indicador 
				    	    	laruta="..\\..\\imagenes\\menu\\mal.png";
		                        if (valor.CAL>=70) {laruta="..\\..\\imagenes\\menu\\bien.png"; }  			    	                                 
		                        $("#row"+valor.ID).append("<td><img id=\"SELIMG_"+valor.ID+"\" width=\"20px\" height=\"20px\" src=\""+laruta+"\"></td>");

		                         
				    	    	$("#SELF_"+valor.ID).html($("#base").html());
				    	    	$("#SEL_"+valor.ID).html($("#base").html());
                                $("#SEL_"+valor.ID).val(valor.CAL);  
                                $("#SELF_"+valor.ID).val(valor.FALTA);                              				    	   

				    	            		        	     
		               });
		             },
		         error: function(data) {	                  
		                    alert('ERROR: '+data);
		                }
		        });
        
	}



	function guardar(id,numeroUni, materia, profesor, ciclo, matricula, grupo, tipo){

		campo='';
		if (tipo=="FALTA"){campo='F';}

		eltidepocorte="CCO3";
		if ($("#selTipo").val()=='1'){eltidepocorte="CCO3";}
		if ($("#selTipo").val()=='2'){eltidepocorte="CCC1";}
		if ($("#selTipo").val()=='3'){eltidepocorte="CCC2";}
		

		tipocal=$("#selTipo").val();

		$("#SELIMG_"+id).attr("src","..\\..\\imagenes\\menu\\esperar.gif");
				$.ajax({
					type: "POST",
					url:"actualizaCal.php?valorllave="+id+"&numeroUni="+numeroUni+"&c="+$("#SEL"+campo+"_"+id).val()+"&materia="+materia+
						"&tipocal="+tipocal+"&materia="+materia+"&profesor="+profesor+"&ciclo="+ciclo+"&matricula="+matricula+"&grupo="+grupo+"&tipo="+tipo+
						"&idcorte=999&tipocorte="+eltidepocorte,		    
					success: function(data){	
													
						if (data.substring(0,2)=='0:') {alert ("Ocurrio un error: "+data);}	 

						laruta="..\\..\\imagenes\\menu\\mal.png";
						if ($("#SEL_"+id).val()>=70) {laruta="..\\..\\imagenes\\menu\\bien.png"; }

						$("#SELIMG_"+id).attr("src",laruta);                                 	                                        					          
					}					     
				});    	                	 
}




function cerrarBoleta(valor){
	materia=$('#selMaterias').val();
	materiad=$('#selMaterias option:selected').text().split("|")[0];
	grupo=$('#selMaterias option:selected').text().split("|")[2];
	id=$('#selMaterias option:selected').text().split("|")[3];

	mensaje="La boleta se abrio nuevamente"; if (valor=='S') {mensaje="La boleta ha sido cerrada";}
	if(confirm("Seguro que desea Cerrar la Boleta de : "+materiad+" Grupo: "+grupo)) {
		var f = new Date();
		fechacap=pad(f.getDate(),2) + "/" + pad((f.getMonth() +1),2) + "/" + f.getFullYear()+" "+ f.getHours()+":"+ f.getMinutes()+":"+ f.getSeconds();
		res="";
	 
	    parametros={tabla:"edgrupos",bd:"Mysql",campollave:"DGRU_ID",valorllave:id,DGRU_CERRADOCAL:valor,
			FECHACIERRECAL:fechacap,USERCIERRECAL:"<?php echo $_SESSION["usuario"];?>"};
        $.ajax({
            type: "POST",
            url:"../base/actualiza.php",
            data: parametros,
            success: function(data){       

        	if (!(data.substring(0,1)=="0"))	{ 
				parametros2={tabla:"dlista",bd:"Mysql",campollave:"IDGRUPO",valorllave:id,CERRADO:valor};
				$.ajax({
						type: "POST",
						url:"../base/actualiza.php",
						data: parametros2,
						success: function(data){       
						if (!(data.substring(0,1)=="0"))	{cargarAlumnos();}	
						else {alert (" OCURRIO EL SIGUIENTE ERROR: "+data+"\n");}																															
						}					     
				}); 
			}
            else {alert (" OCURRIO EL SIGUIENTE ERROR: "+data+"\n");}
                                           	                                        					         
            }					     
	   });  
	
	}
}


function imprimirBoleta(id,profesor,materia,materiad,grupo,ciclo, base,semestre){
	tit='Boleta';
	ciclo=$('#selCiclos').val();
	materia=$('#selMaterias').val();
	materiad=$('#selMaterias option:selected').text().split("|")[0];
	semestre=$('#selMaterias option:selected').text().split("|")[1];
	grupo=$('#selMaterias option:selected').text().split("|")[2];
	id=$('#selMaterias option:selected').text().split("|")[3];

	abrirPesta("nucleo/cierreCal/boleta.php?tipo=0&grupo="+grupo+"&ciclo="+ciclo+"&profesor="+$("#selProfesores").val()+"&materia="+
								  materia+"&materiad="+materiad+"&id="+id+"&semestre="+semestre,tit);
}

function enviarBoleta(id,profesor,materia,materiad,grupo,ciclo, base,semestre){
	tit='Enviando ...';
	tit='Boleta';
	ciclo=$('#selCiclos').val();
	materia=$('#selMaterias').val();
	materiad=$('#selMaterias option:selected').text().split("|")[0];
	semestre=$('#selMaterias option:selected').text().split("|")[1];
	grupo=$('#selMaterias option:selected').text().split("|")[2];
	id=$('#selMaterias option:selected').text().split("|")[3];

	abrirPesta("nucleo/cierreCal/boleta.php?tipo=2&grupo="+grupo+"&ciclo="+ciclo+"&profesor="+$("#selProfesores").val()+"&materia="+
								  materia+"&materiad="+materiad+"&id="+id+"&semestre="+semestre,tit);
}




		</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
