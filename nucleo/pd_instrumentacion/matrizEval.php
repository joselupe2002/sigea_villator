
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


	<body id="grid_matriz" style="background-color: white; width:98%;">
	    
	    
	    
	    
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
		var laletra="";
		var lasletras=[];
		var letras =["A","B","C","D","E","F","G","H","I","J"]
		var numletras=0;
		var global=0;
        
		$(document).ready(function($) { var Body = $('body');  Body.addClass('preloader-site'); });
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});


		jQuery(function($) { 
			$("#encabezado").append("<div class=\"row\"> "+		
								"		<div class=\"col-sm-1\"></div>"+				
			                    "		<div class=\"col-sm-3\">"+								
						    	"	    	<label class=\"fontRobotoB label label-success\">No. de Unidad o Competencia</label><select onchange=\"seleccionaUnidad('<?php echo $_GET["id"]?>');\" class=\"form-control\"  id=\"selUnidad\"></select>"+							
								"		</div>"+
								"		<div class=\"col-sm-6\" style=\"padding-top:20px;\">"+														   		
								"			<button title=\"Copiar información de otra Unidad\" onclick=\"copiarInfo();\""+
                                " 			class=\"btn  btn-white btn-primary btn-round\"><i class=\"ace-icon green fa fa-copy bigger-140\"></i> Copiar Información</button>"+    						
								"			<button title=\"Regresar a la lista de asignaturas\" onclick=\"regresar();\""+
                                " 			class=\"btn  btn-white btn-primary btn-round\"><i class=\"ace-icon red fa fa-arrow-left bigger-140\"></i> Regresar</button>"+    														
								"		</div>"+
								"	 </div><br>"+
								"	<div class=\"row\">"+
								"		<div class=\"col-sm-1\"></div>"+
								"		<div class=\"col-sm-10\" id=\"mateval\"></div>"+							
								"	</div>"
																										
								);

								
			actualizaSelect("selUnidad", "SELECT UNID_NUMERO,concat(UNID_NUMERO,' ',UNID_DESCRIP) FROM eunidades where UNID_PRED='' AND UNID_MATERIA='<?php echo $_GET["materia"]?>' ORDER BY UNID_NUMERO", "","");			
			
		
			migrupo="<?php echo $_GET["id"];?>";
		});



function copiarInfo(){
	dameVentana("ventCopia", "grid_matriz","Copiar Información","sm","bg-successs","fa fa-copy blue bigger-180","370");
	$("#body_ventCopia").append("<div class=\"row fontRoboto bigger-120\">"+
							"     <div class=\"col-sm-12\">"+								
							"	    	<label class=\"label label-primary fontRobotoB bigger-80\">Copiar Información de Unidad</label><br>"+
							"			<select id=\"selunidadc\" class=\"form-controls\"></select>"+						
							"	  </div><br>"+	
							"     <div class=\"col-sm-12\" style=\"text-align:center;\">"+
							"			<br><button title=\"Sacar copia\" onclick=\"sacarCopia();\""+
                                " 			class=\"btn btn-white btn-warning btn-round\"><i class=\"ace-icon pink fa fa-lightbulb-o bigger-140\"></i>Copiar Información</button>"+    													
							"	  </div>");	
							$("#selunidadc").html($("#selUnidad").html())
						
}


function sacarCopia(){

	unidadc=$("#selunidadc").val();
	unidad=$("#selUnidad").val();
	elsql=" DELETE FROM ins_matriz where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidad+"';";

  	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
  	$.ajax({
		 type: "POST",
		 data:parametros,
		 url:  "../base/ejecutasql.php",
		 success: function(data){  
			console.log(data);			
			elsql="insert into ins_matriz (IDGRUPO,TIPO,UNIDAD,EVAPR,PORC,EVALFOR,A,B,C,D,E,F,G,H,I,J,USUARIO,FECHAUS) "+
			" select IDGRUPO,TIPO,'"+unidad+"',EVAPR,PORC,EVALFOR,A,B,C,D,E,F,G,H,I,J,USUARIO,FECHAUS  FROM ins_matriz "+
			" where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidadc+"';";

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





function seleccionaUnidad(id){
	
	elsql="SELECT DISTINCT(LETRA),INDICADORD, VALOR FROM vins_indicadores  where IDGRUPO='"+migrupo+"' AND UNIDAD='"+$("#selUnidad").val()+"' ORDER BY LETRA";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
           success: function(data){	
				lasletras=JSON.parse(data);
				numletras=lasletras.length;
				letrashtml=""; letrashtmlpie="";
				for (i=0; i<lasletras.length;i++){
					letrashtml+="<th title=\""+lasletras[i]["INDICADORD"]+"\" style=\"text-align:center;\"><span class=\"badge badge-primary\">"+letras[i]+"</span><br>"+
					"<span id=\"s_"+letras[i]+"\"  class=\"badge badge-success\">"+lasletras[i]["VALOR"]+"</span></th>";

					/*
					letrashtmlpie+="<th style=\"text-align:center;\">"+
					"<span id=\"spie_"+letras[i]+"\"><i class=\"fa fa-refresh green\"></i></span></th>";
					*/
				}

				$("#mateval").empty();
				$("#mateval").append("<div class=\"alert alert-success\" style=\"padding:2px;\">"+
									"	<div class=\"row\" style=\"padding:0px;\">"+
									"  		 <div class=\"col-sm-3\">"+
									"			<label class=\"fontRobotoB\">Evidencia de Aprendizaje <badge class=\"badge badge-danger\" style=\"cursor:pointer;\"  onclick=\"getDescrip();\"> <i class=\"fa fa-question white\"></i></badge></label><select class=\" form-control chosen-select \"  id=\"evapr\"></select>"+									
									" 		</div>"+
									"   	<div class=\"col-sm-2\">"+
									"			<label class=\"fontRobotoB\">Tipo</label><select class=\" form-control \"  id=\"selTipo\"></select>"+							
									" 		</div>"+
									"  		 <div class=\"col-sm-2\">"+
									"			<label class=\"fontRobotoB\">Porcentaje</label><input  class=\"form-control input-mask-numero captProy\"  id=\"porc\"></input>"+							
									" 		</div>"+
									"   	<div class=\"col-sm-3\">"+
									"			<label class=\"fontRobotoB\">Eval. Formativa Competencia</label><select class=\" form-control \"  id=\"evalfor\"></select>"+							
									" 		</div>"+								
									"   	<div class=\"col-sm-2\" style=\"padding-top:25px;\">"+
									"			<button title=\"Insertar Indicador\" onclick=\"addIndicador();\""+
									" 				class=\"btn btn-xs btn-white btn-primary btn-round\"><i class=\"ace-icon pink fa fa-plus bigger-140\"></i> Insertar</button>"+    						
									" 		</div>"+
									"	</div>"+					
									"</div>"+
									"	<div class=\"row\" style=\"padding:0px;\" >"+
									" 		<table id=\"tabInd\" class= \"fontRobotoB display table-condensed table-striped table-sm table-bordered table-hover nowrap\" style=\"width:100%; vertical-align:text-top;\">"+
									"			<thead><tr><th rowspan=\"2\">Id.</th><th rowspan=\"2\">Op</th><th rowspan=\"2\">Evidencia de Aprendizaje</th><th rowspan=\"2\">Tipo</th><th rowspan=\"2\">%</th><th rowspan=\"2\"></th><th colspan=\""+numletras+"\">Indicador Alcance</th><th rowspan=\"2\">Evaluación Formativa Competencia</th></tr>"+
									"                  <tr>"+letrashtml+"</tr></thead> "+
									"			<tfoot><tr><th></th><th></th><th></th><th></th><th></th>"+letrashtmlpie+"<th></th></tr>"+
									" 		</table>"+
									"   </div>"+			
									"	<div class=\"row\" style=\"margin:0px; padding:2px; text-align:right;\">"+			
									"  		<span class=\"fontRobotoB\"> Suma de Ponderación: <span class=\"badge badge-success\" id=\"suma\">0</span>"+						
									"	</div>"						
									);
				$(".input-mask-numero").mask("99");

				
			
				actualizaSelect("selTipo", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='TIPOSEVIDENCIAS'", "","");			
				actualizaSelect("evapr", "SELECT ID, NOMBRE FROM ins_eviapr ORDER BY NOMBRE", "BUSQUEDA","");
				actualizaSelect("evalfor", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='TIPOINSEVAL'", "","");					

				$('.chosen-select').chosen({allow_single_deselect:true}); 			
			    $(window).off('resize.chosen').on('resize.chosen', function() {$('.chosen-select').each(function() {var $this = $(this); $this.next().css({'width': "100%"});})}).trigger('resize.chosen');
			    $(document).on('settings.ace.chosen', function(e, event_name, event_val) { if(event_name != 'sidebar_collapsed') return; $('.chosen-select').each(function() {  var $this = $(this); $this.next().css({'width': "100%"});})});	     		    
		
				cargaDatos();
				
			}
		});

}


function verificaSumas(){
	for (i=1; i<global; i++) {
		elid=$("#lin"+i).html();
		por=$("#por"+i).html();
		suma=0;
		for (j=0; j<numletras; j++) {
			if ($("#c_"+elid+letras[j]).val()!="") {			
				suma+=parseInt($("#c_"+elid+letras[j]).val());
			}
		}

		if (suma==por) {		
			$("#et"+i).html("<i class=\"fa fa-check blue\"></i>");
		}
		if (suma>por) {		
			$("#et"+i).html("<i class=\"fa fa-times red\"></i>");
		}
	}

	for (i=0; i<numletras; i++) {		
		por=$("#s_"+letras[i]).html();
		suma=0;
		for (j=1; j<global; j++) {
			elid=$("#lin"+j).html();
			if ($("#c_"+elid+letras[i]).val()!="") {			
				suma+=parseInt($("#c_"+elid+letras[i]).val());
			}
		}

		if (suma==por) {		
			$("#spie_"+letras[i]).html("<i class=\"fa fa-check blue\"></i>");
		}
		if (suma>por) {		
			$("#spie_"+letras[i]).html("<i class=\"fa fa-times red\"></i>");
		}
	}

}

function cargaDatos (){

	elsql="SELECT * FROM vins_matriz  where IDGRUPO='"+migrupo+"' AND UNIDAD='"+$("#selUnidad").val()+"' order by ID";

	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
           success: function(data){

		
			   datos=JSON.parse(data);
			   grid_data=JSON.parse(data);			
				c=1;
				global=1;
				suma=0;
       			$("#cuerpoMat").empty();
	   			$("#tabInd").append("<tbody id=\"cuerpoMat\">");

      			jQuery.each(grid_data, function(clave, valor) { 	
					$("#cuerpoMat").append("<tr id=\"row"+c+"\">");						
					$("#row"+c).append("<td id=\"lin"+c+"\">"+valor.ID+"</td>");					
					$("#row"+c).append("<td><span onclick=\"elimar('"+valor.ID+"')\" style=\"cursor:pointer;\"><i class=\"fa fa-trash red bigger-160\"></i></span></td>");					
					$("#row"+c).append("<td>"+valor.EVAPR+" "+valor.EVAPRD+" <badge class=\"badge badge-danger\" style=\"cursor:pointer;\"  onclick=\"verDescripcion('"+valor.EVAPRD+"','"+valor.EVAPR_D+"');\"> <i class=\"fa fa-question white\"></i></badge></td>");
					$("#row"+c).append("<td>"+valor.TIPO+"</td>");
					$("#row"+c).append("<td id=\"por"+c+"\">"+valor.PORC+"</td>");
					$("#row"+c).append("<td id=\"et"+c+"\"><i class=\"fa fa-refresh warning\"></i></td>");
					

					for (i=0; i<numletras; i++) {
						
							$("#row"+c).append("<td style=\"text-align:center;\"><input id=\"c_"+valor.ID+letras[i]+"\" style=\"width:25px;\" onchange=\"guardarLetra('"+valor.ID+"','"+letras[i]+"')\" value=\""+datos[clave][letras[i]]+"\"></input></td>");
											
					}

					$("#row"+c).append("<td>"+valor.EVALFOR+" "+valor.EVALFORD+"</td>");
					suma+=parseInt(valor.PORC);
					$("#suma").html(suma);

					$("#suma").removeClass("badge-primary");
					$("#suma").removeClass("badge-success");
					$("#suma").removeClass("badge-danger");

					if (suma>100) {						
						$("#suma").addClass("badge-danger");
					} 
					if (suma==100) {
						$("#suma").addClass("badge-primary");
					} 
					if (suma<100) {
						$("#suma").addClass("badge-success");
					} 
					c++;
					global++;			
        		});	
				verificaSumas();			
		   }
	});
}


function change_SELECT(elemento) {
	guardar();

	}

function addIndicador(){
		//Grabamos el log 
			var hoy= new Date();
			lafecha=dameFecha("FECHAHORA");
			laletra=$("#letra").val();

    		parametros={tabla:"ins_matriz",
			    bd:"Mysql",
			    USUARIO:"<?php echo $_SESSION["CAMPUS"];?>",
				FECHAUS:lafecha,
				IDGRUPO:migrupo,
				TIPO:$("#selTipo").val(),
				UNIDAD:$("#selUnidad").val(),
				EVAPR:$("#evapr").val(),
				PORC:$("#porc").val(),
				EVALFOR:$("#evalfor").val()
			};
			    $.ajax({
			 		  type: "POST",
			 		  url:"../../nucleo/base/inserta.php",
			 	      data: parametros,
			 	      success: function(data){ 
						   console.log(data);
						cargaDatos();
					   }					
					});                	 
}


function elimar(id){			
                if (confirm("Seguro que desea eliminar este Registro")) {
		        parametros={
		    			    tabla:"ins_matriz",						    		    	      
		    			    bd:"Mysql",
		    			    campollave:"ID",
		    			    valorllave:id};

		    	    $.ajax({
		    			   type: "POST",
		    			   url:"../base/eliminar.php",
		    			   data: parametros,
		    			   success: function(data){
							console.log(data);
							cargaDatos();                             	                                        					          
		    			    }					     
		    	      });  
                    }
                                   	 
    			}


				
function guardarLetra(id,letra){
	lafecha=dameFecha("FECHAHORA");
	unidad=$("#selUnidad").val();
	valorlet=$("#c_"+id+letra).val(); 


	console.log(valorlet);
	parametros={
				tabla:"ins_matriz",
				campollave:"ID",
				valorllave:id,
				nombreCampo:letra,
				valorCampo:valorlet,
				bd:"Mysql"};
      
			$.ajax({
						type: "POST",
						url:"../base/actualizaDin.php",
						data: parametros,
						success: function(data){
							console.log(data);			
							cargaDatos();						
											       					           
						}					     
					}); 				
}


function getDescrip(){
	desc=$("#evapr option:selected").text();
	elsql="SELECT DESCRIPCION FROM ins_eviapr  where ID='"+$("#evapr").val()+"'";


	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
           success: function(data){
				texto=JSON.parse(data)[0][0];
				verDescripcion(desc,texto);
		   }
		});


}

function verDescripcion(titulo,texto){
dameVentana("ventMatriz", "grid_matriz","<span class=\"fontRobotoB\">"+titulo+"</span>","sm","bg-successs","","370");
$("#body_ventMatriz").append("<div class=\"row fontRoboto bigger-120\">"+
							"     <div class=\"col-sm-12\">"+														
							"<p>"+texto+"</p>"	+					
							"	  </div>");			

}



function regresar(){
			window.location="grid.php?modulo=<?php echo $_GET["modulo"];?> ";	
			}

</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
