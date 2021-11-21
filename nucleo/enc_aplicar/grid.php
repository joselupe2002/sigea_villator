
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
		<link rel="stylesheet" href="<?php echo $nivel; ?>css/sigea.css" />

        <style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }
               th, td {  word-wrap: break-word;        
                         overflow-wrap: break-word;   }
               
        </style>
	</head>


	<body id="grid_<?php echo $_GET['modulo']; ?>" style="background-color: white; width: 98%;">
	    
	    
	    
	    
	<div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>


      <h3 class="header smaller lighter text-warning"><strong>Encuestas<i class="ace-icon fa fa-angle-double-right"></i> <small id="elciclo"></small> <small id="elciclod"></small></strong></h3>
	     <div  class="table-responsive sigeaPrin">
		     <table id=tabHorarios class= "fontRobotoB sigeaPrin display table-condensed table-striped table-sm table-bordered table-hover nowrap " style="overflow-y: auto;">
				   	<thead>  
					    <tr style="background-color: #9F5906; color: white;">					        
					        <th style="text-align: center;">ID</th> 
					        <th style="text-align: center;">Encuesta</th> 
							<th style="text-align: center;">Periodicidad</th> 					       
					        <th style="text-align: center;">Aplicar</th> 	
							<th style="text-align: center;">Comprobante</th> 	
							<th style="text-align: center;">Resultado</th> 			        
					     </tr> 
					     <?php $misNoti=$miUtil->getEncuestas($_SESSION['usuario'],$_SESSION['super'],true);
				               $noti=0;
				               foreach ($misNoti as $row) {
				               	    echo "<tr>";
				               	    echo "     <td>".$row["ID"]."</td>";
				               	    echo "     <td>".$row["DESCRIP"]."</td>";
									echo "     <td>".$row["PERIODICIDAD"]."</td>";

									$yaesta=$miUtil->getEncuestaContestada($_SESSION['usuario'],$_SESSION['super'],$row["PERIODICIDAD"],$row["ID"]);
								
								
									$elresultado="";
									if (($row["RESULTADO"]=='S') && ($yaesta)) {
										$elresultado=$miUtil->getResultado($_SESSION['usuario'],$_SESSION['super'],$row["PERIODICIDAD"],$row["ID"],0);
									}
																	
								
				               	    if ($yaesta=="0") {
						               	    echo "     <td style= \"text-align: center;\" > ".
		                                                   "<a  onclick=\"verEncuesta('".$row["ID"]."','".$row["DESCRIP"]."','".$row["OBJETIVO"]."','".$row["TEXTOINI"]."','".$row["TEXTOFIN"]."','".$row["RESULTADO"]."');\" title=\"Aplicar encuesta\" ".
		                                                   "class=\"btn btn-white btn-waarning btn-bold\">".
		                                                   "<i class=\"ace-icon fa fa-list-alt  bigger-160 green \"></i>".
		                                                   "</a></td>";
						               	    }
						            else {
						            	    echo "<td style= \"text-align: center;\" ><span class=\"label label-danger label-white middle\">Encuesta Enviada Gracias!</span></td>";
											echo "<td style= \"text-align: center;\" ><button onclick=\"comprobante(".$yaesta.",'".$row["ID"]."');\" class=\"btn btn-white btn-info btn-bold\"><i class=\"ace-icon fa fa-building bigger-120 blue\"></i></button></td>";
						            }
									echo "     <td>".$elresultado."</td>";

						            echo "     </tr>";
				               	    $noti++;
				               }
				         ?>
				  
			        </thead> 
			  </table>	
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

 
<script src="<?php echo $nivel; ?>js/subirArchivos.js"></script>          
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
<script src="<?php echo $nivel; ?>assets/js/bootbox.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/moment.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-datetimepicker.min.js"></script>



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

<script src="<?php echo $nivel; ?>js/utilerias.js"></script>



<script type="text/javascript">
        var todasColumnas;
        var global,globalUni;
		$(document).ready(function($) { var Body = $('body'); Body.addClass('preloader-site');});
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});

		$(document).ready(function($) { cargarCiclo();});

		function cargarCiclo() {

			elsql="SELECT CICL_CLAVE, CICL_DESCRIP from ciclosesc a where a.CICL_CLAVE=getciclo() ";
			parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
				type: "POST",
				data:parametros,
		        url:  "../base/getdatossqlSeg.php",
		        success: function(data){
		       	   losdatos=JSON.parse(data);
		       	   cad1="";cad2="";
		       	   jQuery.each(losdatos, function(clave, valor) { cad1=valor.CICL_CLAVE; cad2=valor.CICL_DESCRIP;	 });   

		              $("#elciclo").html(cad1);
		              $("#elciclod").html(cad2);
		     	          	     
		              },
		        error: function(data) {	                  
		                   alert('ERROR: '+data);
		               }
		       });

		}
			


function verEncuesta(id,descrip,objetivo, textoi, textof, res){
	 window.location="../base/aplicarEncuesta.php?ciclo="+$("#elciclo").html()+"&gridpropio=S&id="+id+"&descrip="+
	 descrip+"&objetivo="+objetivo+"&modulo=<?php echo $_GET["modulo"];?>&nombre=<?php echo $_GET["nombre"];?>&textoi="+
	 textoi+"&textof="+textof+"&res="+res;
}


function comprobante (id,idenc){
	enlace="nucleo/enc_aplicar/comprobante.php?idenc="+idenc+"&id="+id+"&matricula=<?php echo $_SESSION["usuario"]; ?>";
	abrirPesta(enlace,"Comprobante");
}

    
		</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
