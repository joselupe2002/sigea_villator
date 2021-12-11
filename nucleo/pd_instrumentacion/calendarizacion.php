
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


	<body id="grid_indicadores" style="background-color: white; width:95%;">
	    
	    
	    
	    
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
		var lasletras=[];
		var letras =["A","B","C","D","E","F","G","H","I","J"]
		var numletras=0;
        
		$(document).ready(function($) { var Body = $('body');  Body.addClass('preloader-site'); });
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});


		jQuery(function($) { 
			$("#encabezado").append("<div class=\"row\"> "+									
								"	<div class=\"row\">"+
								"		<div class=\"col-sm-1\"></div>"+
								"		<div class=\"col-sm-10\" id=\"mateval\"></div>"+							
								"	</div>"+
								"<select class=\"hidden form-control\" id=\"seltipos\"></select>"
																										
								);

								
			actualizaSelect("selUnidad", "SELECT UNID_NUMERO,concat(UNID_NUMERO,' ',UNID_DESCRIP) FROM eunidades where UNID_PRED='' AND UNID_MATERIA='<?php echo $_GET["materia"]?>' ORDER BY UNID_NUMERO", "","");			
			actualizaSelect("seltipos", "SELECT CATA_CLAVE,CATA_CLAVE FROM scatalogos where CATA_TIPO='TIPEVALCOM'", "","");			
		
			migrupo="<?php echo $_GET["id"];?>";

			semhtml="";
			for (i=1; i<=16;i++){
				semhtml+="<th>"+i+"</th>";
			}

			$("#mateval").empty();
			$("#mateval").append(
								"	<div class=\"row\" style=\"padding:0px;\" >"+
								" 		<table id=\"tabInd\" class= \"fontRobotoB display table-condensed table-striped table-sm table-bordered table-hover nowrap\" style=\"width:100%; vertical-align:text-top;\">"+
								"			<thead><tr><td>Semana</td>"+semhtml+"</tr></thead> "+
								" 		</table>"+
								"   </div>"								
								);
					
			cargaDatos();
			
		});



function cargaDatos (){

	elsql="SELECT * FROM ins_calendario  where IDGRUPO='"+migrupo+"' order by ID";
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
       			$("#cuerpoMat").empty();
	   			$("#tabInd").append("<tbody id=\"cuerpoMat\">");

				$("#cuerpoMat").append("<tr id=\"rowTP\"></tr>");
				$("#rowTP").append("<td>Tiempo Planeado</td>");
				for (i=1; i<=16;i++){
					$("#rowTP").append("<td ><select id=\"TP"+i+"\" style=\"width:60px;\" onchange=\"guardar('"+i+"','TP"+i+"')\"></select></td>");
					$("#TP"+i).html($("#seltipos").html());
				}
      				

				//================
				$("#cuerpoMat").append("<tr id=\"rowTR\"></tr>");
				$("#rowTR").append("<td>Tiempo Real</td>");
				for (i=1; i<=16;i++){
					$("#rowTR").append("<td></td>");
					$("#TP"+i).html($("#seltipos").html());				
				}

				jQuery.each(grid_data, function(clave, valor) { 	
					
					$("#TP"+valor.SEM+" option[value=\""+valor.TIPO+"\"]").attr("selected",true);
					$("#TP"+valor.SEM).css("border","solid 1px #D9DADA");
					if (valor.TIPO=='ED') { $("#TP"+valor.SEM).css("border","solid 2px #1E1BA3");}
					if (valor.TIPO=='ES') { $("#TP"+valor.SEM).css("border","solid 2px #8B4114");}
					if (valor.TIPO=='EF') { $("#TP"+valor.SEM).css("border","solid 2px #0F3920");}
				
        		});
							
		   }
	});
}


	
	function guardar(nsem,eltiposel){
		campo='';
		
		lafecha=dameFecha("FECHAHORA");	
	    eltipo=$("#"+eltiposel).val();

		$("#"+eltiposel).css("border","solid 1px #D9DADA");
		if (eltipo=='ED') { $("#"+eltiposel).css("border","solid 2px #1E1BA3");}
		if (eltipo=='ES') { $("#"+eltiposel).css("border","solid 2px #8B4114");}
		if (eltipo=='EF') { $("#"+eltiposel).css("border","solid 2px #0F3920");}
		var losdatos=[];
		losdatos[0]=migrupo+"|"+nsem+"|"+eltipo+"|<?php echo $_SESSION["usuario"];?>|"+lafecha;
   		var loscampos = ["IDGRUPO","SEM","TIPO","USUARIO","FECHAUS"];

		   parametros={
			tabla:"ins_calendario",
			 campollave:"concat(IDGRUPO,'_',SEM)",
			 bd:"Mysql",
			 valorllave:migrupo+"_"+nsem,
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
