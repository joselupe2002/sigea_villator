
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


	<body id="grid_indicadores" style="background-color: white; width:98%;">
	    
	    
	    
	    
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
         <div class="col-sm-2"> 
              <button class="btn btn-white btn-danger btn-bold" onclick="regresar();"><i class="ace-icon fa fa-reply-all bigger-120 blue"></i>Regresar    </button>
         </div>           
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
		var compGen="";
		var compEsp="";
        
		$(document).ready(function($) { var Body = $('body');  Body.addClass('preloader-site'); });
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});


		jQuery(function($) { 
			$("#encabezado").append("<div class=\"row\"> "+		
								"		<div class=\"col-sm-1\"></div>"+				
			                    "		<div class=\"col-sm-3\">"+								
						    	"	    	<label class=\"fontRobotoB label label-success\">No. de Unidad o Competencia</label><select onchange=\"seleccionaUnidad('<?php echo $_GET["id"]?>');\" class=\"form-control\"  id=\"selUnidad\"></select>"+							
								"		</div>"+
								"		<div class=\"col-sm-3\" style=\"padding-top:20px;\">"+														   		
								"			<button title=\"Copiar información de otra Unidad\" onclick=\"copiarInfo();\""+
                                " 			class=\"btn  btn-white btn-primary btn-round\"><i class=\"ace-icon green fa fa-copy bigger-140\"></i> Copiar Información</button>"+    						
								"		</div>"+
								"	</div><br>"+
								"	<div class=\"row\">"+
								"		<div class=\"col-sm-1\"></div>"+
								"		<div class=\"col-sm-6\" id=\"indalc\"></div>"+
								"		<div class=\"col-sm-5\" id=\"nivdes\"></div>"+
								"	</div>"
																										
								);

								
			actualizaSelect("selUnidad", "SELECT UNID_NUMERO,concat(UNID_NUMERO,' ',UNID_DESCRIP) FROM eunidades where UNID_PRED='' AND UNID_MATERIA='<?php echo $_GET["materia"]?>' ORDER BY UNID_NUMERO", "","");			
		
			migrupo="<?php echo $_GET["id"];?>";
		});


function copiarInfo(){
dameVentana("ventCopia", "grid_indicadores","Copiar Información","sm","bg-successs","fa fa-copy blue bigger-180","370");
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
	elsql=" DELETE FROM ins_indicadores where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidad+"'; DELETE FROM ins_niveles where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidad+"';";

  	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
  	$.ajax({
		 type: "POST",
		 data:parametros,
		 url:  "../base/ejecutasql.php",
		 success: function(data){  
			console.log(data);			
			elsql="insert into ins_indicadores (IDGRUPO,UNIDAD,INDICADOR,VALOR,LETRA,USUARIO,FECHAUS) "+
			" select IDGRUPO,'"+unidad+"',INDICADOR,VALOR,LETRA,USUARIO,FECHAUS  FROM ins_indicadores "+
			" where IDGRUPO='"+migrupo+"' and UNIDAD='"+unidadc+"';"+
			"insert into ins_niveles (IDGRUPO,UNIDAD,NEX,NNO,NBU,NSU,NIN,USUARIO,FECHAUS) "+
			" select IDGRUPO,'"+unidad+"',NEX,NNO,NBU,NSU,NIN,USUARIO,FECHAUS  FROM ins_niveles "+
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
	$("#indalc").empty();
	$("#indalc").append("<div class=\"alert alert-success\" style=\"padding:2px;\">"+
						"	<div class=\"row\" style=\"padding:0px;\">"+
						"  		 <div class=\"col-sm-2\">"+
						"			<label class=\"fontRobotoB\">Letra</label><select  class=\"form-control captProy\"  id=\"letra\">"+
						"				<option value=\"A\">A</option><option value=\"B\">B</option><option value=\"C\">C</option><option value=\"D\">D</option><option value=\"E\">E</option><option value=\"F\">F</option><option value=\"G\">G</option><option value=\"H\">H</option>"+
						"			</select>"+							
						" 		</div>"+
						"  		 <div class=\"col-sm-5\">"+
						"			<label class=\"fontRobotoB\">Indicador de Alcance</label><select  class=\"form-control captProy\"  id=\"indicador\"></select>"+							
						" 		</div>"+
						"   	<div class=\"col-sm-2\">"+
						"			<label class=\"fontRobotoB\">Valor</label><input class=\"input-mask-numero form-control \"  id=\"valor\"></input>"+							
						" 		</div>"+
						"   	<div class=\"col-sm-3\" style=\"padding-top:25px;\">"+
						"			<button title=\"Insertar Indicador\" onclick=\"addIndicador();\""+
                    	" 				class=\"btn btn-xs btn-white btn-primary btn-round\"><i class=\"ace-icon pink fa fa-plus bigger-140\"></i> Insertar</button>"+    						
						" 		</div>"+
						"	</div>"+					
						"</div>"+
						"	<div class=\"row\" style=\"padding:0px;\" >"+
						" 		<table id=\"tabInd\" class= \"fontRobotoB display table-condensed table-striped table-sm table-bordered table-hover nowrap\" style=\"width:100%; vertical-align:text-top;\">"+
		  				"			<thead><tr><th>Id.</th><th>Op</th><th>Letra</th><th>Indicador de Alcance</th><th>Valor</th></tr></thead> "+
						" 		</table>"+
						"   </div>"+			
						"	<div class=\"row\" style=\"margin:0px; padding:2px; text-align:right;\">"+			
						"  		<span class=\"fontRobotoB\"> Suma de Ponderación: <span class=\"badge badge-success\" id=\"suma\">0</span>"+						
						"	</div>"						
						);
	$(".input-mask-numero").mask("999");
	actualizaSelectMarcar("indicador", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='INS_INDICADORES' order by CATA_CLAVE", "","",'0');
	
	cargaDatos();

	// ==========================Nivel de Desempeño  ==============================================
	elsql="SELECT * FROM ins_niveles  where IDGRUPO='"+migrupo+"' AND UNIDAD='"+$("#selUnidad").val()+"'";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
           success: function(data){			
			   gd=JSON.parse(data);			
					nex=""; nbu=""; nno=""; nsu=""; nin=""; 
					if (gd.length) {nex=gd[0]["NEX"];nbu=gd[0]["NBU"]; nno=gd[0]["NNO"]; nsu=gd[0]["NSU"]; nin=gd[0]["NIN"]; }
					$("#nivdes").empty();
					$("#nivdes").append("<div class=\"\" style=\"padding:2px; color:white; background-color:#1F7D46\">"+
										"	<div class=\"row\" style=\"padding:0px; text-align:center;\"><span class=\"fontRobotoB\">NIVELES DE DESEMPEÑO</span></div>"+
										"	<div class=\"row\" style=\"padding:0px;\">"+
										"  		 <div class=\"col-sm-12\">"+
										"			<label class=\"fontRobotoB label-primary\">Excelente (95-100)</label>"+
										"			<textarea  onchange=\"guardarNivel();\" class=\"form-control captProy\"  id=\"ex\">"+nex+"</textarea>"+
										" 		</div>"+
										"    </div>"+
										"	<div class=\"row\" style=\"padding:0px;\">"+
										"  		 <div class=\"col-sm-12\">"+
										"			<label class=\"fontRobotoB label-info\">Notable (85-94)</label>"+
										"			<textarea onchange=\"guardarNivel();\"  class=\"form-control captProy\"  id=\"no\">"+nno+"</textarea>"+
										" 		</div>"+
										"    </div>"+
										"	<div class=\"row\" style=\"padding:0px;\">"+
										"  		 <div class=\"col-sm-12\">"+
										"			<label class=\"fontRobotoB label-success\">Bueno (75-84)</label>"+
										"			<textarea onchange=\"guardarNivel();\"  class=\"form-control captProy\"  id=\"bu\">"+nbu+"</textarea>"+
										" 		</div>"+
										"    </div>"+
										"	<div class=\"row\" style=\"padding:0px;\">"+
										"  		 <div class=\"col-sm-12\">"+
										"			<label class=\"fontRobotoB label-warning\">Suficiente (70-74)</label>"+
										"			<textarea onchange=\"guardarNivel();\"  class=\"form-control captProy\"  id=\"su\">"+nsu+"</textarea>"+
										" 		</div>"+
										"    </div>"+
										"	<div class=\"row\" style=\"padding:0px;\">"+
										"  		 <div class=\"col-sm-12\">"+
										"			<label class=\"fontRobotoB label-danger\">Insuficiente (NA - NO ALCANZADA)</label>"+
										"			<textarea onchange=\"guardarNivel();\"  class=\"form-control captProy\"  id=\"in\">"+nin+"</textarea>"+
										" 		</div>"+
										"    </div>"+
										"</div>");				  
				}
			});
}


function cargaDatos (){

	elsql="SELECT * FROM vins_indicadores  where IDGRUPO='"+migrupo+"' AND UNIDAD='"+$("#selUnidad").val()+"' order by LETRA";
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
    $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
           success: function(data){

			   datos=JSON.parse(data);
			   grid_data=JSON.parse(data);			
				c=1;
				suma=0;
       			$("#cuerpoInd").empty();
	   			$("#tabInd").append("<tbody id=\"cuerpoInd\">");

      			jQuery.each(grid_data, function(clave, valor) { 	
					$("#cuerpoInd").append("<tr id=\"row"+c+"\">");	
					$("#cuerpoInd").append("<tr id=\"row"+valor.ID+"\">");						
					$("#row"+c).append("<td>"+valor.ID+"</td>");
					$("#row"+c).append("<td><span onclick=\"elimar('"+valor.ID+"')\" style=\"cursor:pointer;\"><i class=\"fa fa-trash red bigger-160\"></i></span></td>");					
					$("#row"+c).append("<td>"+valor.LETRA+"</td>");
					$("#row"+c).append("<td>"+valor.INDICADORD+"</td>");
					$("#row"+c).append("<td>"+valor.VALOR+"</td>");
					suma+=parseInt(valor.VALOR);
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
        		});	
							
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

    		parametros={tabla:"ins_indicadores",
			    bd:"Mysql",
			    USUARIO:"<?php echo $_SESSION["CAMPUS"];?>",
				FECHAUS:lafecha,
				IDGRUPO:migrupo,
				UNIDAD:$("#selUnidad").val(),
				INDICADOR:$("#indicador").val(),
				VALOR:$("#valor").val(),
				LETRA:laletra
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
		    			    tabla:"ins_indicadores",						    		    	      
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


				
function guardarNivel(){
		campo='';
	
		lafecha=dameFecha("FECHAHORA");
		unidad=$("#selUnidad").val();
	
		var losdatos=[];
		losdatos[0]=migrupo+"|"+unidad+"|"+$("#ex").val()+"|"+$("#no").val()+"|"+$("#bu").val()+"|"+$("#su").val()+"|"+$("#in").val()+"|<?php echo $_SESSION["usuario"];?>|"+lafecha;
   		var loscampos = ["IDGRUPO","UNIDAD","NEX","NNO","NBU","NSU","NIN","USUARIO","FECHAUS"];

		   parametros={
			tabla:"ins_niveles",
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
