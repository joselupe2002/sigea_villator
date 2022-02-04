
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
		<link rel="stylesheet" href="<?php echo $nivel; ?>css/sigea.css" />

        <style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }
               th, td {  word-wrap: break-word;        
                         overflow-wrap: break-word;   }
               
        </style>
	</head>


	<body id="grid_evaluar" style="background-color: white; width:98%;">
	    
	    
	    
	    
	<div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>
    
    <div class="page-header">
         
         
         
         <div class="row">	   
            
		      <div class="col-sm-4"> 
		               <span class="label label-success" >Unidad</span>
			           <select id="unidades" style="width: 100%;"> </select>
			           <select style="display:none;" id="base" style="width: 100%;">
			              <?php for ($x=0;$x<=100;$x++) {?>
			                  echo  <option value="<?php echo $x;?>"><?php echo $x;?></option>			            
			              <?php } ?>		              
			           </select>
		       </div> 	
			   <div class="col-sm-4">  
                   <h1><?php echo $_GET["materiad"] ?><small><i class="ace-icon fa fa-angle-double-right"></i><?php echo $_GET["materia"] ?></small></h1>
              </div>

			   <div class="col-sm-4" style="padding-top:20px;">  
			   			<button onclick="calculaPromedioGen();" class="btn btn-white btn-primary"><i class="fa fa-wrench blue"></i> Calcular Promedio</button>
						<button class="btn btn-white btn-danger btn-bold" onclick="regresar();"><i class="ace-icon fa fa-arrow-circle-left red bigger-120 "></i>Regresar    </button>
              </div>	
		        	           
         </div>
    
    </div>
    
    
	<div id="eltitulo"> </div>		      
	<div  class="sigeaPrin table-responsive" style="overflow-y: auto; height: 300px;" >
		  
		  <table id="latabla" class= "display table-condensed table-striped table-sm table-bordered table-hover nowrap" ></table>
		  
	</div>
	
	<div class="space-10"></div>

		
 
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

 
<script src="<?php echo $nivel; ?>js/utilerias.js?v=<?php echo date('YmdHis');?>"></script>     
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




<script type="text/javascript">
        var todasColumnas;
        var losCriterios;
        var cuentasCal=0;
        var eltidepocorte;
		var idcorte;
		var losalumnos=[];

		var materia='<?php echo $_GET["materia"];?>';
		var profesor='<?php echo $_GET["profesor"];?>';
		var grupo='<?php echo $_GET["grupo"];?>';
		var elciclocal='<?php echo $_GET["ciclo"];?>';

        
		$(document).ready(function($) { var Body = $('body'); $(document).bind("contextmenu",function(e){return false;});  Body.addClass('preloader-site'); cargarUnidades(); $("#unidades").change(function(){cargarCalificaciones();}); });
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});




		function cargarUnidades(){		
			 $("#laTabla").empty();
			 $("#unidades").empty();
			 $("#unidades").append("<option value=\"0\">Elija Unidad</option>");	
			 
			 base_materia=0;
			 base_grupo=0;
			 
			 //Buscamos los cortes que esta abierto para la asignatura de acuerdo al ciclo 
			 sqlCor="select * from ecortescal where  CICLO='<?php echo $_GET["ciclo"]?>'"+
		            " and STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') "+
					" Between STR_TO_DATE(INICIA,'%d/%m/%Y') "+
		            " AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='CALIFICACION' and ABIERTO='S' "+
		            " order by STR_TO_DATE(TERMINA,'%d/%m/%Y')  DESC LIMIT 1";
			
			parametros={sql:sqlCor,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
				 type: "POST",
				 data:parametros,
		         url:  "../base/getdatossqlSeg.php",
		         success: function(dataCor){   
					 iniCorte=""; finCorte=""; 		        				         
		        	 jQuery.each(JSON.parse(dataCor), function(clave, valorCor) { 	
					    iniCorte=valorCor.INICIA; finCorte=valorCor.TERMINA; idcorte=valorCor.ID; eltidepocorte=	valorCor.TIPO;					
					 });

					//alert (iniCorte+" "+finCorte);
					elsql="SELECT DGRU_MATERIA AS MATERIA,SIE FROM edgrupos p where p.`DGRU_ID`='<?php echo $_GET["base"]?>'";
					parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
					$.ajax({
						type: "POST",
						data:parametros,
						url:  "../base/getdatossqlSeg.php",
						success: function(data){    		        				         
							jQuery.each(JSON.parse(data), function(clave, valor) { 
								base_grupo=valor.SIE; base_materia=valor.MATERIA;    });
							

							sqlUni="select UNID_ID, UNID_NUMERO, UNID_DESCRIP, "+
										"(select count(*) from eplaneacion b where STR_TO_DATE(FECHA,'%d/%m/%Y')  BETWEEN STR_TO_DATE('"+iniCorte+"','%d/%m/%Y') "+ 
										" AND STR_TO_DATE('"+finCorte+"','%d/%m/%Y') and b.NUMUNIDAD=a.UNID_NUMERO "+ 
										" and b.MATERIA=a.UNID_MATERIA and b.GRUPO='<?php echo $_GET["grupo"]?>' and b.CICLO='<?php echo $_GET["ciclo"]?>') as ABIERTO,"+
										"(select count(*) from vecortesexp g where DATE_FORMAT(NOW(),'%Y-%m-%d') BETWEEN STR_TO_DATE(INICIA,'%d/%m/%Y')  "+
										" AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and g.UNIDAD=a.UNID_ID and g.MATERIA=a.UNID_MATERIA and "+
										"g.PROFESOR='<?php echo $_GET["profesor"]?>' and g.CICLO='<?php echo $_GET["ciclo"]?>' and g.GRUPO='<?php echo $_GET["grupo"]?>')  as ABIERTO2, "+
										"(select count(*) from eplaneacion b where STR_TO_DATE(FECHA,'%d/%m/%Y')  BETWEEN STR_TO_DATE('"+iniCorte+"','%d/%m/%Y') "+ 
										" AND STR_TO_DATE('"+finCorte+"','%d/%m/%Y') and b.NUMUNIDAD=a.UNID_NUMERO "+ 
										" and b.MATERIA='"+base_materia+"' and b.GRUPO='"+base_grupo+"' and b.CICLO='<?php echo $_GET["ciclo"]?>') as ABIERTO3 "+
										" from eunidades a where a.UNID_MATERIA='<?php echo $_GET["materia"]?>' and UNID_PRED=''"
						
		
				            parametros2={sql:sqlUni,dato:sessionStorage.co,bd:"Mysql"}
							$.ajax({
								type: "POST",
								data:parametros2,
								url:  "../base/getdatossqlSeg.php",
								success: function(data){    
									
									jQuery.each(JSON.parse(data), function(clave, valor) {										
										ab=parseInt(valor.ABIERTO)+parseInt(valor.ABIERTO2)+parseInt(valor.ABIERTO3);
										
										cadVer='C';
										if (ab>0) {cadVer='A';}
										$("#unidades").append("<option value=\""+valor.UNID_NUMERO+"\">"+utf8Decode(valor.UNID_NUMERO+" "+valor.UNID_DESCRIP)+" |"+cadVer+"|</option>");       	     
									});
									},
								error: function(data) {	                  
											alert('ERROR: '+data);
										}
								});		     
						}
					}); //del ajax de las Unidades 					 
				 } // del success
			}); // del ajax de los cortes 			    
		}


        function cargarCalificaciones() {
        	 //Cargar Calificaciones
        	 launidad=parseInt($("#unidades").val());  
			 launinum=$('#unidades option:selected').text().split(" ")[0];
        	 abierto=$('#unidades option:selected').text().split("|")[1];

	
			 $("#eltitulo").empty();
			 mensaje="<div class=\"fontRobotoB\" style=\"text-align:center; padding:2px; border:0px; background-color:#831303; color:white;\"><i class=\"fa fa-ban white\"></i>  CERRADO PARA CAPTURA</div>";
			 if (abierto=='A') {
				mensaje="<div class=\"fontRobotoB\" style=\"text-align:center; padding:2px; border:0px; background-color:#031883; color:white;\"><i class=\"fa fa-keyboard-o white\"></i>  ABIERTO PARA CAPTURA</div>"
				}
			$("#eltitulo").append(mensaje); 
				 
   
			 elsql="select a.ID, ALUM_MATRICULA,  CONCAT(ALUM_APEPAT,' ',ALUM_APEMAT,' ',ALUM_NOMBRE) AS NOMBRE"+
				          " from dlista a, falumnos b where a.ALUCTR=b.ALUM_MATRICULA and a.GPOCVE='<?php echo $_GET["grupo"];?>'"+
				          " and PDOCVE='<?php echo $_GET["ciclo"];?>' and LISTC15='<?php echo $_GET["profesor"];?>'"+
						  " and MATCVE='<?php echo $_GET["materia"];?>' and a.BAJA='N' order by ALUM_APEPAT,ALUM_APEMAT,ALUM_NOMBRE";
				
			
	          parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			 $.ajax({
				 type: "POST",
				 data:parametros,
		         url:  "../base/getdatossqlSeg.php",
		         success: function(data){    

					elsql="select * from vins_matriz where idgrupo='<?php echo $_GET["id"]?>'"+
						  " and UNIDAD='"+launinum+"'  order by id";
	
					parametrosE={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
					$.ajax({
						type: "POST",
						data:parametrosE,
						url:  "../base/getdatossqlSeg.php",
						success: function(dataE){    

							

							evidencias="";

							jQuery.each(JSON.parse(dataE), function(claveE, valorE) { 
								evidencias+="<th style=\"text-align: center; width:12%; font-size:10px; color:#09074E\">"+valorE.EVAPRD+
								            " <span class=\"badge badge-primary\" id=\"PORC_"+valorE.ID+"\" ELPORC=\""+valorE.PORC+"\">"+valorE.PORC+"</span></th>"	
							});

							$("#latabla").empty();
		        	 		$("#latabla").append("<thead><tr class=\"fontRobotoB\" id=\"titulo\">"+
					 								"<th style=\"text-align: center;\">No. Control</th>"+ 
                                                    "<th style=\"text-align: center;\">Nombre</th>"+evidencias+
													"<th style=\"text-align: center;\">Final</th>"+
													"</tr></thead>"); 
							$("#latabla").append("<tbody id=\"cuerpo\">");	


							losalumnos=JSON.parse(data);
							jQuery.each(losalumnos, function(clave, valor) { 
		        		    $("#cuerpo").append("<tr id=\"row"+valor.ID+"\" class=\"fontRoboto\">");				
				    	    $("#row"+valor.ID).append("<td id=\"matricula_"+valor.ID+"\">"+valor.ALUM_MATRICULA+"</td>");
				    	    $("#row"+valor.ID).append("<td id=\"nombre_"+valor.ID+"\">"+utf8Decode(valor.NOMBRE)+"</td>");

				    	    caddis="disabled"; 
							if (abierto=='A') {caddis="";}

								jQuery.each(JSON.parse(dataE), function(claveE, valorE) { 
									//Para capturar calificaciones
									$("#row"+valor.ID).append("<td id=\"nombre_"+valor.ID+"\">"+
										"<div class=\"input-group\">"+
										"		<span id=\"ICO_"+valor.ID+"_"+valorE.ID+"\" PORC=\""+valorE.PORC+"\" class=\"input-group-addon\">"+
										"			<i class=\"ace-icon black fa fa-refresh\"></i>"+
										"		</span>"+
										"<select "+caddis+" class=\"text-primary MAT_"+valor.ALUM_MATRICULA+"\" style=\"width:70px; font-size:14px; color:#0E0B7E;\" "+
										"				title=\""+valorE.EVAPRD+"\""+
										"				id=\"SEL_"+valor.ID+"_"+valorE.ID+"\" PORC=\""+valorE.PORC+"\" TIPOEV=\""+valorE.TIPO+"\""+
						    	    	" 				onchange=\"guardar("+valor.ID+",'"+launinum+"','<?php echo $_GET["id"];?>','"+valorE.ID+"','"+valor.ALUM_MATRICULA+"','<?php echo $_GET["ciclo"];?>','"+valorE.TIPO+"');\"></select>"+
										"</div></td>");
									
	
				    	    		$("#SEL_"+valor.ID+"_"+valorE.ID).html($("#base").html());                             
									
									elsqlV="select LISCAL, (select LISCAL from dlista_eviapr_prom b where b.IDDLISTA=a.IDDLISTA and b.UNIDAD='"+launinum+"' ) as PROM, count(*) AS HAY "+
									" from dlista_eviapr a where IDDLISTA='"+valor.ID+"' and IDEVAPR='"+valorE.ID+"'";
									parametrosV={sql:elsqlV,dato:sessionStorage.co,bd:"Mysql"}
									$.ajax({
										type: "POST",
										data:parametrosV,
										url:  "../base/getdatossqlSeg.php",
										success: function(dataV){  										
											if (JSON.parse(dataV)[0]["HAY"]>0) {											
											     $("#SEL_"+valor.ID+"_"+valorE.ID).val(JSON.parse(dataV)[0]["LISCAL"]);
												 $("#ICO_"+valor.ID+"_"+valorE.ID).html(dameIcon(JSON.parse(dataV)[0]["LISCAL"]));												 
											}	
											
										}
									});									  
								});	

									elsqlP="select LISCAL, count(*) as HAY from dlista_eviapr_prom b where b.IDDLISTA='"+valor.ID+"' and b.UNIDAD='"+launinum+"'";
									parametrosP={sql:elsqlP,dato:sessionStorage.co,bd:"Mysql"}
									$.ajax({
										type: "POST",
										data:parametrosP,
										url:  "../base/getdatossqlSeg.php",
										success: function(dataP){  										
														
												lacalet="<span class=\"badge badge-danger\">60</span>";
												if (JSON.parse(dataP)[0]["HAY"]>0) {
													lacalet="<span class=\"badge badge-danger\">"+JSON.parse(dataP)[0][0]+"</span>";
													if (JSON.parse(dataP)[0][0]>=70) {lacalet="<span class=\"badge badge-primary\">"+JSON.parse(dataP)[0][0]+"</span>";}
													
												}
												$("#row"+valor.ID).append("<td id=\"PROM_"+valor.ID+"_"+valor.ALUM_MATRICULA+"_"+launinum+"\">"+lacalet+"</td>");												 
									
											
										}
									});		


																	
								
								

		              		 });// fin Rrecorrido de los alumnos 							

						}
					});    	 
		        	 
		             },
		         error: function(data) {	                  
		                    alert('ERROR: '+data);
		                }
		        });
            }
		

		function calculaPromedioGen(){
			launinum=$('#unidades option:selected').text().split(" ")[0];
			agregarDialogResultadov2("grid_evaluar","dlgresul","modal-lg","Registros Calculados");	
			jQuery.each(losalumnos, function(clave, valor) { 
				calculaPromUnidad(valor.ID,valor.ALUM_MATRICULA,launinum);

			});		
		}


		function calculaPromUnidad(id, matricula,numeroUni,tipoev){			    
				prom=0;
				$(".MAT_"+matricula).each(function(){   
					console.log($(this).attr("PORC")+" |*| "+$(this).val());
					prom+=parseFloat($(this).attr("PORC")/100*$(this).val());																								 										
				});		
				elprom=Math.round(prom);
				color="danger"; if (elprom>=70) { color="primary"; }

				$("#PROM_"+id+"_"+matricula+"_"+numeroUni).html("<span class=\"badge badge-"+color+"\">"+Math.round(prom)+"</span>");	
				

				lafecha=dameFecha("FECHAHORA");					
				var losdatos=[];
					losdatos[0]=id+"|"+numeroUni+"|"+elprom+"|<?php echo $_SESSION['usuario']?>|"+lafecha;
					var loscampos = ["IDDLISTA","UNIDAD","LISCAL","USUARIO","FECHAUS"];

				parametros={
					tabla:"dlista_eviapr_prom",
					campollave:"concat(IDDLISTA,UNIDAD)",
					bd:"Mysql",
					valorllave:id+numeroUni,
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

				tipocal=0;
				if (eltidepocorte=='CCO1'){tipocal="1";}
				if (eltidepocorte=='CCO2'){tipocal="1";}
				if (eltidepocorte=='CCO3'){tipocal="1";}
				if (eltidepocorte=='CCO4'){tipocal="1";}
				if (eltidepocorte=='CCO5'){tipocal="1";}
				if (eltidepocorte=='CCC1'){tipocal="2";}
				if (eltidepocorte=='CCC2'){tipocal="3";}
				if (tipocal>0) {
					laurl="../pd_captcal/actualizaCal.php?valorllave="+id+"&numeroUni="+parseInt(numeroUni)+
								"&c="+elprom+"&tipocal="+tipocal+"&materia="+materia+"&profesor="+profesor+
								"&ciclo="+elciclocal+"&matricula="+matricula+"&grupo="+grupo+"&tipo=CAL"+
								"&idcorte="+idcorte+"&tipocorte="+eltidepocorte;
					$.ajax({
							type: "POST",
							url:laurl,		    
							success: function(data){									
								$('#resul').append(dameitemRes("Califición Actualizada: "+matricula+" Unidad:"+numeroUni+" TipoCal="+tipocal,"fa fa-check green bigger-180",""));																																													
							}					     
						});    
					}
				else {
					$('#resul').html(dameitemRes("No se puede actualizar calificaciones a Unidades, ya que al parecer no existe corte abierto","fa fa-times red bigger-180",""));					
				}			

		}	


		function dameIcon(elvalor){
			res="";
			if (elvalor==0) {res="<i class=\"ace-icon black fa fa-refresh\"></i>"}
			if (elvalor>=70) {res="<i class=\"ace-icon blue fa fa-check\"></i>"}
			if ((elvalor<70)&&(elvalor!=0)) {res="<i class=\"ace-icon red fa fa-times\"></i>"}		
			return res;
		}


		function guardar(id,numeroUni,idmateria, idev, matricula,ciclo, tipoev){

			if (eltidepocorte=='CCO1'){tipocal="1";}
			if (eltidepocorte=='CCO2'){tipocal="1";}
			if (eltidepocorte=='CCO3'){tipocal="1";}
			if (eltidepocorte=='CCO4'){tipocal="1";}
			if (eltidepocorte=='CCO5'){tipocal="1";}
			if (eltidepocorte=='CCO6'){tipocal="1";}

			if (eltidepocorte=='CCC1'){tipocal="2";}
			if (eltidepocorte=='CCC2'){tipocal="3";}

			lafecha=dameFecha("FECHAHORA");	
	    	elcorte=$("#selCortes").val();
			elvalor=$("#SEL_"+id+"_"+idev).val();

			$("#ICO_"+id+"_"+idev).html(dameIcon(elvalor));

			var losdatos=[];
			losdatos[0]=id+"|"+idev+"|"+elvalor+"|<?php echo $_SESSION['usuario']?>|"+lafecha;
			var loscampos = ["IDDLISTA","IDEVAPR","LISCAL","USUARIO","FECHAUS"];

		   parametros={
			tabla:"dlista_eviapr",
			 campollave:"concat(IDDLISTA,IDEVAPR)",
			 bd:"Mysql",
			 valorllave:id+idev,
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
				 //Calcular el Promedio 				 
				 prom=0;
				 calculaPromUnidad(id, matricula,numeroUni,tipoev)

				var losdatos=[];
					losdatos[0]=id+"|"+numeroUni+"|"+elprom+"|<?php echo $_SESSION['usuario']?>|"+lafecha;
					var loscampos = ["IDDLISTA","UNIDAD","LISCAL","USUARIO","FECHAUS"];

				parametros={
					tabla:"dlista_eviapr_prom",
					campollave:"concat(IDDLISTA,UNIDAD)",
					bd:"Mysql",
					valorllave:id+numeroUni,
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
		 });    	   


	}

		function regresar(){
			window.location="grid.php?modulo=<?php echo $_GET["modulo"];?> ";	
			}
		</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
