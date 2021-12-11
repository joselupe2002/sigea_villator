
<?php session_start(); if ($_SESSION['inicio']==1) { 
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
        <link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/bootstrap-editable.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/chosen.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>css/sigea.css" />
	
        <style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }
               th, td {  vertical-align: top;  }
               
        </style>
	</head>


	<body id="bodyAnalisis" style="background-color: white; width:98%;">
	    
	    
	    
	    
	<div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>
    
    <div class="page-header">
         
         
         
         <div class="row">	               	
		         <div class="col-sm-6">  
                   <h1><?php echo $_GET["materiad"] ?><small><i class="ace-icon fa fa-angle-double-right"></i><?php echo $_GET["materia"] ?></small></h1>
              </div>	           
         </div>
		 <div class="row" id="encabezado">	               		           
         </div>
    
    </div>
    
    
		      
	<div  class="sigeaPrin table-responsive" style="overflow-y: auto; height: 400px; width:100%;" >
		  <table id="tabAnalisis" class= "fontRobotoB display table-condensed table-striped table-sm table-bordered table-hover nowrap" style="width:98%; vertical-align:text-top;">
		  	<thead>  
				<tr>
					<th>Id.</th> 
					<th>Temas y Subtemas</th> 
					<th>Actividades de Aprendizaje</th> 
					<th>Actividades de Enseñanza</th> 
					<th>Desarrollo de Competencias Genéricas</th> 	
					<th>Horas Teórico - Práctico</th> 				                  																	
				</tr> 
			</thead> 
		  </table>
	</div>
	
	<div class="space-10"></div>
	<div style="text-align:center;" class="row">
                
	</div>
		
 
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

 

 
<script src="<?php echo $nivel; ?>js/subirArchivos.js?v=<?php echo date('YmdHis'); ?>"></script>       
<script src="<?php echo $nivel; ?>assets/js/jquery-2.1.4.min.js"></script>
<script type="<?php echo $nivel; ?>text/javascript"> if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");</script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap.min.js"></script>
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
<script src="<?php echo $nivel; ?>assets/js/jquery.jqGrid.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/grid.locale-en.js"></script>
<script src="<?php echo $nivel; ?>assets/js/chosen.jquery.min.js"></script>

<!-- -------------------Medios ----------------------->
<script src="<?php echo $nivel; ?>assets/js/jquery.inputlimiter.min.js"></script>
<script src="<?php echo $nivel; ?>js/mask.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-tag.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.jqGrid.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/grid.locale-en.js"></script>


<!-- -------------------ultimos ----------------------->
<script src="<?php echo $nivel; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace.min.js"></script>
<script type="text/javascript" src="<?php echo $nivel; ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo $nivel; ?>js/sha/sha512.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-editable.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace-editable.min.js"></script>
<script src="<?php echo $nivel; ?>js/utilerias.js?v=<?php echo date('YmdHis'); ?>"></script>




<script type="text/javascript">
        var todasColumnas;
        var losCriterios;
        var cuentasCal=0;
        var eltidepocorte;
		var porcRepEst=0;
		var migrupo="";
		var compGen="";
		var compEsp="";
        
		$(document).ready(function($) { var Body = $('body');  Body.addClass('preloader-site'); });
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});


		jQuery(function($) { 
			$("#encabezado").append(" <div class=\"col-sm-1\"></div>"+
			                    "<div class=\"col-sm-3\">"+								
						    	"	    	<label class=\"fontRobotoB label label-success\">No. de Unidad o Competencia</label><select onchange=\"seleccionaUnidad('<?php echo $_GET["id"]?>');\" class=\"form-control\"  id=\"selUnidad\"></select>"+							
								"</div>"+
								"<div class=\"col-sm-6\" style=\"padding-top:20px;\">"+								
						    	"	<button title=\"Ver Competencias de la Unidad\" onclick=\"verCompetencias();\""+
                                " class=\"btn  btn-white btn-primary btn-round\"><i class=\"ace-icon pink fa fa-lightbulb-o bigger-140\"></i> Competencias</button>"+    						
								"	<button title=\"Copiar información de otra Unidad\" onclick=\"copiarInfo();\""+
                                " class=\"btn  btn-white btn-primary btn-round\"><i class=\"ace-icon green fa fa-copy bigger-140\"></i> Copiar Información</button>"+    						
								"	<button title=\"Copiar información de otra Unidad\" onclick=\"regresar();\""+
                                " class=\"btn  btn-white btn-primary btn-round\"><i class=\"ace-icon red fa fa-arrow-left bigger-140\"></i> Regresar</button>"+    						
								"</div>"																								
								);

			actualizaSelect("selUnidad", "SELECT UNID_NUMERO,concat(UNID_NUMERO,' ',UNID_DESCRIP) FROM eunidades where UNID_PRED='' AND UNID_MATERIA='<?php echo $_GET["materia"]?>' ORDER BY UNID_NUMERO", "","");			
		
		});



function copiarInfo(){
dameVentana("ventCopia", "bodyAnalisis","Copiar Información","sm","bg-successs","fa fa-copy blue bigger-180","370");
$("#body_ventCopia").append("<div class=\"row fontRoboto bigger-120\">"+
							"     <div class=\"col-sm-12\">"+								
							"	    	<label class=\"label label-primary fontRobotoB bigger-80\">Copiar Información de Unidad</label><br>"+
							"			<select id=\"selunidadc\" class=\"form-controls\"></select>"+						
							"	  </div>"+	
							"     <div class=\"col-sm-12\" style=\"text-align:center;\">"+
							"			<br><button title=\"Sacar copia\" onclick=\"sacarCopia();\""+
                                " 			class=\"btn btn-white btn-warning btn-round\"><i class=\"ace-icon pink fa fa-lightbulb-o bigger-140\"></i>Copiar Información</button>"+    													
							"	  </div>");	
							$("#selunidadc").html($("#selUnidad").html())
						
}


function sacarCopia(){

	unidadc=$("#selunidadc").val();
	unidad=$("#selUnidad").val();
	elsql=" DELETE FROM ins_analisis where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidad+"'";

  	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
  	$.ajax({
		 type: "POST",
		 data:parametros,
		 url:  "../base/ejecutasql.php",
		 success: function(data){  
			console.log(data);			
			elsql="insert into ins_analisis (IDGRUPO,UNIDAD,ACTENSENANZA,DC_INS,DC_INT,DC_SIS,HORAST,HORASP,_INSTITUCION,_CAMPUS,USUARIO,FECHAUS) "+
			" select IDGRUPO,'"+unidad+"',ACTENSENANZA,DC_INS,DC_INT,DC_SIS,HORAST,HORASP,_INSTITUCION,_CAMPUS,USUARIO,FECHAUS  FROM ins_analisis "+
			" where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidadc+"'";
			
			parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
				type: "POST",
				data:parametros,
				url:  "../base/ejecutasql.php",
				success: function(data){  
					console.log(data);	
					$("#ventCopia").modal("hide");
					seleccionaUnidad(migrupo);	

				}	
				
			});				
		 }	
		 
	});			 
}


function verCompetencias(){

dameVentana("ventComp", "bodyAnalisis","Competencias","sm","bg-successs","fa fa-book blue bigger-180","370");
$("#body_ventComp").append("<div class=\"row fontRoboto bigger-120\">"+
							"     <div class=\"col-sm-12\">"+								
							"	    	<label class=\"label label-primary fontRobotoB bigger-80\">Competencias Genericas</label><br>"+
							"<p>"+compGen+"</p>"+						
							"	  </div>"+	
							"     <div class=\"col-sm-12\">"+								
							"	    	<label class=\"label label-success  fontRobotoB bigger-80\">Competencas Específicas</label><br>"+
							"<p>"+compEsp+"</p>"+								
							"	  </div>");
			

}


function seleccionaUnidad(id){
	migrupo=id;
	elsql="SELECT UNID_MATERIA,UNID_COMPESP,UNID_COMPGEN, UNID_ACTAPR, UNID_MATERIA, ifnull(ID,'') as ID, "+
	" ifnull(ACTENSENANZA,'') as ACTENSENANZA,ifnull(DC_INS,'') as DC_INS,ifnull(DC_INT,'') as DC_INT,ifnull(DC_SIS,'') as DC_SIS, ifnull(HORAST,'') "+
	" as HORAST, ifnull(HORASP,'') as HORASP  FROM eunidades left outer join ins_analisis on "+
	"                    (IDGRUPO='"+id+"' AND UNIDAD='"+$("#selUnidad").val()+"') "+
	" WHERE UNID_PRED='' AND UNID_NUMERO='"+$("#selUnidad").val()+"' AND UNID_MATERIA='<?php echo $_GET["materia"]?>'";

	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
           success: function(data){

			   datos=JSON.parse(data);
			   grid_data=JSON.parse(data);			
				c=1;
       			$("#cuerpoAnalisis").empty();
	   			$("#tabAnalisis").append("<tbody id=\"cuerpoAnalisis\">");

      			jQuery.each(grid_data, function(clave, valor) { 	
					$("#cuerpoAnalisis").append("<tr id=\"row"+c+"\">");	
					$("#cuerpoAnalisis").append("<tr id=\"row"+valor.ID+"\">");	
					
					$("#row"+c).append("<td>"+valor.ID+"</td>");
					
					$("#row"+c).append("<td id=\""+valor.UNID_MATERIA+"_"+$("#selUnidad").val()+"\"></td>");	
					
					compGen=valor.UNID_COMPGEN;
					compEsp=valor.UNID_COMPESP;
					

				

					$("#row"+c).append("<td>"+valor.UNID_ACTAPR+"</td>");

					//$("#row"+c).append("<td><textarea id=\"actens\" onchange=\"guardar('"+id+"');\" style=\"height:340PX; width:300px;\">"+valor.ACTENSENANZA+"</textarea></td>");
					
					$("#row"+c).append("<td><div style=\"padding:0px; width:350px;\" id=\"las_acten\" class=\"form-control\"></div>"+																				
									"</td>");

					$("#row"+c).append("<td><div style=\"padding:0px; width:350px;\" id=\"las_dc_ins\" class=\"form-control\"></div>"+																				
									"</td>");

					
					$("#row"+c).append("<td><span class=\"label label-success\">Teóricas</span><br>"+
					                         "<input id=\"horteo\" onchange=\"guardar('"+id+"');\" class=\"input-mask-numero form-control\" value=\""+valor.HORAST+"\"></input>"+
											 "<span class=\"label label-success\">Prácticas</span><br>"+
					                         "<input id=\"horpra\" onchange=\"guardar('"+id+"');\" class=\"input-mask-numero form-control\" value=\""+valor.HORASP+"\"></input>"+
									"</td>");

					addSELECTMULT_CONVALOR("sel_dc_ins","las_dc_ins","PROPIO", "select CATA_CLAVE, CATA_DESCRIP "+
						                   "from scatalogos WHERE CATA_TIPO='COMPGENERICAS' order by CATA_CLAVE ", "","BUSQUEDA",valor.DC_INS);  	

					addSELECTMULT_CONVALOR("sel_acten","las_acten","PROPIO", "select CATA_CLAVE, CATA_DESCRIP "+
						                   "from scatalogos WHERE CATA_TIPO='ACTENSENANZA' order by CATA_CLAVE ", "","BUSQUEDA",valor.ACTENSENANZA);  	
														
					c++;			
        		});	
				
			
				elsql="SELECT * FROM eunidades WHERE UNID_PRED<>'' AND UNID_PRED='"+$("#selUnidad").val()+"' AND UNID_MATERIA='<?php echo $_GET["materia"]?>' ORDER BY UNID_NUMERO";

				parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
				$.ajax({
					type: "POST",
					data:parametros,
					url:  "../base/getdatossqlSeg.php",
					success: function(data2){
						grid_data2=JSON.parse(data2);
						jQuery.each(grid_data2, function(clave2, valor2) { 
							$("#"+valor2.UNID_MATERIA+"_"+$("#selUnidad").val()).append(valor2.UNID_NUMERO+" "+valor2.UNID_DESCRIP+"<br>");
						});
					}
				});
				$(".input-mask-numero").mask("999");

		   }
	});
}


function change_SELECT(elemento) {
	guardar();
	}

function guardar(idgrupo){
		campo='';
	
		lafecha=dameFecha("FECHAHORA");
		unidad=$("#selUnidad").val();
	
		var losdatos=[];
		losdatos[0]=migrupo+"|"+unidad+"|"+$("#sel_acten").val()+"|"+$("#sel_dc_ins").val()+"|"+$("#sel_dc_int").val()+"|"+$("#sel_dc_sis").val()+"|"+$("#horteo").val()+"|"+$("#horpra").val()+"|<?php echo $_SESSION["INSTITUCION"];?>|<?php echo $_SESSION["CAMPUS"];?>|<?php echo $_SESSION["usuario"];?>|"+lafecha;
   		var loscampos = ["IDGRUPO","UNIDAD","ACTENSENANZA","DC_INS","DC_INT","DC_SIS","HORAST","HORASP","_INSTITUCION","_CAMPUS","USUARIO","FECHAUS"];

		   parametros={
			tabla:"ins_analisis",
			 campollave:"concat(IDGRUPO,'_',UNIDAD)",
			 bd:"Mysql",
			 valorllave:migrupo+"_"+unidad,
			 eliminar: "S",
			 separador:"|",
			 campos: JSON.stringify(loscampos),
			 datos: JSON.stringify(losdatos)
		   };

		  $.ajax({
			 type: "POST",
			 url:"../base/grabadetalle.php",
			 data: parametros,
			 success: function(data){
					console.log(data);	          
			 }					     
		 });    	                 	 
}

function regresar(){
			window.location="grid.php?modulo=<?php echo $_GET["modulo"];?> ";	
			}

</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
