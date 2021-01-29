var id_unico="";
var estaseriando=false;
var matser="";
contR=1;
contMat=1;
var laCarrera="";
var porcProyectado=0;
var porcPosible=0;
var porcReal=0;
var miciclo="";


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 
		
		$('.easy-pie-chart.percentage').each(function(){
			var barColor = $(this).data('color') || '#2979FF';
			var trackColor = '#E2E2E2';
			var size = parseInt($(this).data('size')) || 72;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size/5),
				animate:false,
				size: size
			}).css('color', barColor);
			});

		elsql="select CICL_CLAVE, CICL_DESCRIP from ciclosesc where CICL_CLAVE=getciclo()";	
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
				type: "POST",
				data:parametros,
				url:  "../base/getdatossqlSeg.php",
				success: function(data){ 
					losdatos=JSON.parse(data); 
					miciclo=losdatos[0][0];
					$("#elciclo").html(losdatos[0][0]+" "+losdatos[0][1]);
				}
			});

			
		cargarAvance();
	});
	
	
		 
	function cargarAvance() {

		elsql="select ALUM_MATRICULA, ALUM_FOTO, ALUM_MAPA, ALUM_ESPECIALIDAD,"+ 
		"CONCAT(ALUM_NOMBRE,' ',ALUM_APEPAT,' ',ALUM_APEMAT) as ALUM_NOMBREC,"+
		"b.CLAVEOF as CVEESP, b.DESCRIP AS ESPECIALIDADD, ALUM_CARRERAREG, CARR_DESCRIP AS CARRERAD, PLACRED "+
		" from falumnos a "+
		" LEFT OUTER join especialidad b on (a.ALUM_ESPECIALIDAD=b.ID)"+
		" LEFT OUTER join ccarreras c on (a.ALUM_CARRERAREG=c.CARR_CLAVE)"+
		" LEFT OUTER join mapas d on (a.ALUM_MAPA=d.MAPA_CLAVE)"+
		" where a.ALUM_MATRICULA='"+usuario+"'";
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	   $.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
		   success: function(data){ 
			   losdatos=JSON.parse(data); 
			   $("#matricula").html(losdatos[0]["ALUM_MATRICULA"]);                               
			   $("#foto").attr('src',losdatos[0]["ALUM_FOTO"]);
			   $("#nombre").html(losdatos[0]["ALUM_NOMBREC"]); 
			   $("#carrera").html(losdatos[0]["ALUM_CARRERAREG"]+" "+losdatos[0]["CARRERAD"]); 
			   $("#mapa").html(losdatos[0]["ALUM_MAPA"]); 
			   $("#especialiad").html(losdatos[0]["CVEESP"]+" "+losdatos[0]["ESPECIALIDADD"]);
			   $("#carrera").html(losdatos[0]["ALUM_CARRERAREG"]+" "+losdatos[0]["CARRERAD"]);

			   totalcred=losdatos[0]["PLACRED"];
			   elmapa=losdatos[0]["ALUM_MAPA"];
			   laesp=losdatos[0]["ALUM_ESPECIALIDAD"];
			   //Los avances de los creditos 
			   real=0;
				elsql="select IFNULL(sum(h.CICL_CREDITO),0) from dlista b, eciclmate h where "+
				" b.ALUCTR='"+usuario+"' and h.CICL_MAPA='"+elmapa+"'  and ((IFNULL(h.cveesp,'0')='"+laesp+"') "+
				" or (IFNULL(h.cveesp,'0')='0'))"+
				" and h.CICL_MATERIA=b.MATCVE and b.LISCAL>=70 and CERRADO='S' "+
				" and CICL_TIPOMAT NOT IN ('T'); ";

				parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
				$.ajax({
					type: "POST",
					data:parametros,
					url:  "../base/getdatossqlSeg.php",
					success: function(data){ 
						losdatos=JSON.parse(data); 
						real=losdatos[0][0];
						porcReal=(parseInt(real)/parseInt(totalcred)*100).toFixed(0);
						console.log("Real="+real+" Cred:"+totalcred);
						$("#etelavance").html(porcReal);                               
						$('#elavance').data('easyPieChart').update(porcReal);
						$("#etelavance2").html("Real ("+real+")");  

						//checamos los avances posibles 
						elsql="select ifnull(sum(h.CICL_CREDITO),0) "+
						" from dlista b, eciclmate h where "+
						" b.ALUCTR='"+usuario+"'"+
						" and h.CICL_MAPA='"+elmapa+"'"+
						" and ((IFNULL(h.cveesp,'0')='"+laesp+"') or (IFNULL(h.cveesp,'0')='0'))"+
						" and h.`CICL_MATERIA`=b.`MATCVE`"+
						" and CERRADO='N' "+
						" and CICL_TIPOMAT NOT IN ('T')";

						posible=0;
						parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
						$.ajax({
							type: "POST",
							data:parametros,
							url:  "../base/getdatossqlSeg.php",
							success: function(data){
								losdatos=JSON.parse(data); 
								posibles=losdatos[0][0];
								porcPosible=(parseInt(posibles)/parseInt(totalcred)*100).toFixed(0);  
								$("#etposible").html(porcPosible);     				                     
								$('#posible').data('easyPieChart').update(porcPosible);
								$("#etposible2").html("Posibles ("+posibles+")");  
															
								porcProyectado=((parseInt(real)+parseInt(posibles))/parseInt(totalcred)*100).toFixed(0);
								
								$("#etproyectados").html(porcProyectado);                      
								$('#proyectados').data('easyPieChart').update(porcProyectado);
								$("#etproyectados2").html("Proyectado ("+(parseInt(real)+parseInt(posibles))+")");  

								elsql="select count(*) from dlista b, eciclmate h where "+
								" b.ALUCTR='"+usuario+"' and h.CICL_MAPA='"+elmapa+"'"+
								" and h.CICL_MATERIA=b.MATCVE and b.LISCAL>=70 and CERRADO='S' "+
								" and CICL_TIPOMAT  IN ('SS'); ";

								parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
								$.ajax({
									type: "POST",
									data:parametros,
									url:  "../base/getdatossqlSeg.php",
									success: function(data){ 
										losdatos=JSON.parse(data); 						
										if ((porcProyectado>=70) && (losdatos[0][0]<=0)) {
											$("#servicio").html("<div class=\"alert alert-warning\" style=\"width:100%;\">"+ 									        
															   "  Podrías cursar el Servicio Social, si llegas a cursar todas las asignaturas de el semestre no cerrado"+
															   "</div>");
											// OpcionesServicio();			   
										}
										if ((porcProyectado<70) && (losdatos[0][0]<=0)) {
											$("#servicio").html("<div class=\"alert alert-danger\" style=\"width:100%;\">"+ 									        
															   "    Todavía no cumples los créditos necesarios para inscribir el Servicio Social "+
															   "</div>");
										}
										if (losdatos[0][0]>1) {
											$("#servicio").html("<div class=\"alert alert-success\" style=\"width:100%;\">"+ 									        
															   "   Ya cursaste el Servicio Social "+
															   "</div>");
										}
										if ((porcReal>=70) && (losdatos[0][0]<=0)) {
											$("#servicio").html("<div class=\"alert alert-warning\" style=\"width:100%;\">"+ 									        
															   "    Ya puede cursar el Servicio Social"+
															   "</div>");

													
											OpcionesServicio();
										}

									}
								});
							}
						});
						
					}
				});

		   }
	   });
   }  


   function OpcionesServicio(){

	var abierto=false;
	$("#servicio").empty();

	elsql="select ID, count(*) as HAY from ss_alumnos a where  CICLO='"+miciclo+"' and MATRICULA='"+usuario+"'";

	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	$.ajax({
		type: "POST",
		data:parametros,
		url:  "../base/getdatossqlSeg.php",
		success: function(data){
	
			if (JSON.parse(data)[0]["HAY"]>0) {				
					elsql="select CLAVE, DOCGEN_RUTA FROM edocgen where CLAVE IN ('SOLSS','CARCOMSS') ORDER BY CLAVE";
					parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
					$.ajax({
						type: "POST",
						data:parametros,
						url:  "../base/getdatossqlSeg.php",
						success: function(data){ 
							losdatos=JSON.parse(data); 
				

							$("#servicio").append("<div class=\"row\" style=\"text-align:left;\">"+
												"    <div class=\"col-sm-12\"> "+
												"     <div id=\"documentos\" class=\"col-sm-12\" ></div>"+
												"    </div>"+
												"</div>");

							$("#documentos").append("<div class=\"row\"><div id=\"cont_sssol\" class=\"col-sm-6\"></div>"+
													"                   <div id=\"cont_sscartacom\" class=\"col-sm-6\"></div></div>"+
													"<div class=\"row\"><div id=\"cont_ssbim1\" class=\"col-sm-6\"></div>"+
													"                   <div id=\"cont_ssbim2\" class=\"col-sm-6\"></div></div>"+
													"<div class=\"row\"><div id=\"cont_ssbim3\" class=\"col-sm-6\"></div>"+
													"                   <div id=\"cont_ssfinal\" class=\"col-sm-6\"></div>"+
													"</div>");


							elsql="SELECT IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SSSOLSS'),'') AS RUTA_SSSOL, "+
							" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SSCARTACOM'),'') AS RUTA_SSCARTACOM, "+
							" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SSBIM1'),'') AS RUTA_SSBIM1, "+
							" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SSBIM2'),'') AS RUTA_SSBIM2, "+
							" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SSBIM3'),'') AS RUTA_SSBIM3, "+
							" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SSFINAL'),'') AS RUTA_SSFINAL, "+
							"       IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_INFORMEFIN'),'') AS RUTA_SSREPFIN FROM DUAL"; 
	
							parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
							$.ajax({
								type: "POST",
								data:parametros,
								url:  "../base/getdatossqlSeg.php",
								success: function(data){
									activaEliminar="";
									if (JSON.parse(data)[0]["RUTA_SS_SOL"]!='') {	activaEliminar='S';}					
									dameSubirArchivoLocal("cont_sssol","Solicitud Firmada","sssol",'servicioSocial','pdf',
									'ID',usuario,'SOLICITUD FIRMADA','eadjresidencia','alta',usuario+"_"+miciclo+"_SSSOLSS",JSON.parse(data)[0]["RUTA_SSSOL"],activaEliminar,usuario+"_"+miciclo+"_SSSOL");
										
									activaEliminar="";
									if (JSON.parse(data)[0]["RUTA_SS_SOL"]!='') {	activaEliminar='S';}					
									dameSubirArchivoLocal("cont_sscartacom","Carta Compromiso","sscartacom",'servicioSocial','pdf',
									'ID',usuario,'CARTA COMPROMISO','eadjresidencia','alta',usuario+"_"+miciclo+"_SSCARTACOM",JSON.parse(data)[0]["RUTA_SSCARTACOM"],activaEliminar,usuario+"_"+miciclo+"_SSCARTACOM");
										
									activaEliminar="";
									if (JSON.parse(data)[0]["RUTA_SSBIM1"]!='') {	activaEliminar='S';}					
									dameSubirArchivoLocal("cont_ssbim1","Reportes Bimestre 1","ssbim1",'servicioSocial','pdf',
									'ID',usuario,'REPORTES BIMESTRE 1','eadjresidencia','alta',usuario+"_"+miciclo+"_SSBIM1",JSON.parse(data)[0]["RUTA_SSBIM1"],activaEliminar,usuario+"_"+miciclo+"_SSBIM1");
									
									activaEliminar="";
									if (JSON.parse(data)[0]["RUTA_SSBIM2"]!='') {	activaEliminar='S';}					
									dameSubirArchivoLocal("cont_ssbim2","Reportes Bimestre 2","ssbim2",'servicioSocial','pdf',
									'ID',usuario,'REPORTES BIMESTRE 2','eadjresidencia','alta',usuario+"_"+miciclo+"_SSBIM2",JSON.parse(data)[0]["RUTA_SSBIM2"],activaEliminar,usuario+"_"+miciclo+"_SSBIM2");
									
									activaEliminar="";
									if (JSON.parse(data)[0]["RUTA_SSBIM3"]!='') {	activaEliminar='S';}					
									dameSubirArchivoLocal("cont_ssbim3","Reportes Bimestre 3","ssbim3",'servicioSocial','pdf',
									'ID',usuario,'REPORTES BIMESTRE 3','eadjresidencia','alta',usuario+"_"+miciclo+"_SSBIM3",JSON.parse(data)[0]["RUTA_SSBIM3"],activaEliminar,usuario+"_"+miciclo+"_SSBIM3");
									
									activaEliminar="";
									if (JSON.parse(data)[0]["RUTA_FINAL"]!='') {	activaEliminar='S';}					
									dameSubirArchivoLocal("cont_ssfinal","Documentos Finales","ssfinal",'servicioSocial','pdf',
									'ID',usuario,'DOCUMENTOS FINALES','eadjresidencia','alta',usuario+"_"+miciclo+"_SSFINAL",JSON.parse(data)[0]["RUTA_SSFINAL"],activaEliminar,usuario+"_"+miciclo+"_SSFINAL");
									

								}
							});

						}
					});
				} //DEL SI ESTA ABIERO 
				else { console.log("No hay proceso abierto");
						$("#servicio").append("<div class=\"row\">"+
						"    <div class=\"col-sm-12\"> "+
						"          <div class=\"alert alert-danger\">No se ha realizado Solicitud de Servicio Social para este Ciclo</div> "+
						"    </div>");
				}
			}
		}); //del ajax de busqueda de corte abierto 

		$("#servicio").append("<div class=\"fontRobotoB\" id=\"pcapt\" style=\"text-align:left;\"></div>");
		abrirCaptura();

	}



	function abrirCaptura(){

		var abierto=false;
		elsql="select count(*) as N from ecortescal where  CICLO='"+miciclo+"'"+
		" and ABIERTO='S' and STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') "+
		" Between STR_TO_DATE(INICIA,'%d/%m/%Y') "+
		" AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='SOLSERSOC' "+
		" order by STR_TO_DATE(TERMINA,'%d/%m/%Y')  DESC LIMIT 1";


		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){

				
				if (JSON.parse(data)[0]["N"]>0) {	
					
					elsql="select  ifnull(ENVIADA,'N') AS ENVIADA, count(*) as HAY from ss_alumnos a where  CICLO='"+miciclo+"' and MATRICULA='"+usuario+"'";			
					parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
					$.ajax({
						type: "POST",
						data:parametros,
						url:  "../base/getdatossqlSeg.php",
						success: function(data){

							bt1="<button  onclick=\"capturaProyecto();\" class=\"btn btn-white btn-info btn-bold\">"+
							"     <i class=\"ace-icon green glyphicon glyphicon-book\"></i>1. Capturar Solicitud"+
							"</button> &nbsp;  &nbsp; ";
							bt2="<button  onclick=\"verProyecto();\" class=\"btn btn-white btn-success btn-bold\">"+
							"     <i class=\"ace-icon pink glyphicon glyphicon-print\"></i>2. Imprimir Solicitud"+
							"</button>  &nbsp;  &nbsp; ";
							bt3="<button  onclick=\"verCartaCom();\" class=\"btn btn-white btn-warning btn-bold\">"+
							"     <i class=\"ace-icon pink glyphicon glyphicon-print\"></i>3. Carta Compromiso"+
							"</button>  &nbsp;  &nbsp; ";
							bt4="<button  onclick=\"enviarSol();\" class=\"btn btn-white btn-danger btn-bold\">"+
							"     <i class=\"ace-icon blue  fa  fa-share-square-o\"></i>4. Enviar"+
							"</button>";

							if (JSON.parse(data)[0]["ENVIADA"]=='S') { bt4=''; bt1="";}	

								//Si esta abierto aparecemos la opción de capturar Proyecto.
							$("#pcapt").append(bt1+bt2+bt3+bt4+
							"</div>"+"<hr><div style=\"text-align:center;\">");

							
						}
					});
				

				}
				else { console.log("No hay proceso abierto");
						$("#servicio").append("<div class=\"row\">"+
						"    <div class=\"col-sm-12\"> "+
						"          <div class=\"alert alert-danger\">No se encuentra abierto el proceso de  Servicio Social para este Ciclo</div> "+
						"    </div>");
				}
				
			}
		});

	}

	
	function capturaProyecto(){

		elsql="select ifnull(ID,'0') as ID,ifnull( MATRICULA,'') AS MATRICULA,ifnull( CICLO,'') AS CICLO,ifnull( INICIO,'') AS INICIO,"+
		"ifnull( PROGRAMA,'') AS PROYECTO,ifnull( TERMINO,'') AS TERMINO,ifnull( EMPRESA,'') AS EMPRESA,"+
		"ifnull( REPRESENTANTE,'') AS REPRESENTANTE,ifnull( PUESTO,'') AS PUESTO,ifnull(PROGRAMA,'') AS PROGRAMA,"+
		"ifnull( MODALIDAD,'') AS MODALIDAD,ifnull( ACTIVIDADES,'') AS ACTIVIDADES,ifnull( DIRECCION,'') AS DIRECCION,"+
		"ifnull( TIPOPROG,'') AS TIPOPROG,ifnull(ENVIADA,'') AS ENVIADA, ifnull( TIPOPROGADD,'') AS TIPOPROGADD, ifnull( FECHACOM,'') AS FECHACOM, VALIDADO AS VALIDADO,"+
		"count(*) as HAY from ss_alumnos a where  CICLO='"+miciclo+"'"+
		" and MATRICULA='"+usuario+"'";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){

				misdatos=JSON.parse(data);

				if (misdatos[0]["HAY"]>0) {proceso="actualizaDatos("+misdatos[0]["ID"]+");"; } else {proceso="grabarDatos();";}
				mostrarConfirm2("infoError","grid_pa_servsoc","Captura de Solicitud",
				"<div class=\"ventanaSC\" style=\"text-align:justify; width:99%; height:250px; overflow-y:auto; overflow-x:hidden;\">"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Dependencia Oficial</label><input class=\"form-control captProy\" id=\"empresa\" value=\""+misdatos[0]["EMPRESA"]+"\"></input>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label  class=\"fontRobotoB\">Inicia</label>"+
							" <div class=\"input-group\"><input  class=\"form-control  captProy date-picker\"  value=\""+misdatos[0]["INICIO"]+"\" id=\"inicio\" "+
							" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
							" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label class=\"fontRobotoB\">Termina</label>"+
							" <div class=\"input-group\"><input  class=\"form-control captProy date-picker\" value=\""+misdatos[0]["TERMINO"]+"\" id=\"termino\" "+
							" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
							" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
						"</div>"+						
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Titular de la Dependencia: </label><input class=\"form-control captProy\" value=\""+misdatos[0]["REPRESENTANTE"]+"\" id=\"representante\"></input>"+
						"</div>"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Cargo del Titular:</label><input class=\"form-control captProy\" value=\""+misdatos[0]["PUESTO"]+"\" id=\"puesto\"></input>"+
						"</div>"+	
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-12\">"+
							"<label class=\"fontRobotoB\">Domicilio Empresa: </label><input class=\"form-control captProy\" value=\""+misdatos[0]["DIRECCION"]+"\" id=\"direccion\"></input>"+
						"</div>"+						
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-9\">"+
							"<label class=\"fontRobotoB\">Nombre del Programa </label><input class=\"form-control captProy\" value=\""+misdatos[0]["PROGRAMA"]+"\" id=\"programa\"></input>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label class=\"fontRobotoB\">Modalidad</label><select class=\"form-control captProy\"  id=\"modalidad\"></select>"+
						"</div>"+					
					"</div>"+

					"<div class=\"row\">"+
						"<div class=\"col-sm-12\">"+
							"<label class=\"fontRobotoB\">Actividades</label><textarea rows=\"5\"  class=\"form-control captProy\" style=\"width:100%;\"  id=\"actividades\">"+misdatos[0]["ACTIVIDADES"]+"</textarea>"+
						"</div>"+					
					"</div>"+

					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Tipo de Programa</label><select class=\"form-control captProy\"  id=\"tipoprog\"></select>"+
						"</div>"+	
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Otro, especifique</label><input class=\"form-control captProyOP\" value=\""+misdatos[0]["TIPOPROGADD"]+"\" id=\"tipoprogadd\"></input>"+
						"</div>"+			
					"</div>"+

					"<div class=\"row\">"+
						"<div class=\"col-sm-3\">"+
							"<label class=\"fontRobotoB\">Fecha Firma de Compromiso</label>"+
							" <div class=\"input-group\"><input  class=\"form-control captProy date-picker\" value=\""+misdatos[0]["FECHACOM"]+"\" id=\"fechacom\" "+
							" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
							" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
						"</div>"+		
					"</div>"+					
				"</div>"
				,"Grabar Datos",proceso,"modal-lg");

				actualizaSelectMarcar("modalidad", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='SSMODALIDAD'", "","",misdatos[0]["MODALIDAD"]); 
				actualizaSelectMarcar("tipoprog", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='SSTIPOPROG'", "","",misdatos[0]["TIPOPROG"]); 
				$('.date-picker').datepicker({autoclose: true,todayHighlight: true}).next().on(ace.click_event, function(){$(this).prev().focus();});
				
			
				$('.captProy').keypress(function(){
					$(this).css("border-color","black");				
				});
			} //del successs
		});

	}


	
	function grabarDatos(){
		vacios=false;
		$('.captProy').each(function(){
			if (($(this).val()=="") || (($(this).val()=="0"))) {
				$(this).css("border-color","red");
				vacios=true;
			}
		 });
		 if (!vacios) {
			mostrarEspera("esperaInf","grid_pa_servsoc","Cargando Datos...");

			fecha=dameFecha("FECHAHORA");
			parametros={tabla:"ss_alumnos",
					bd:"Mysql",
					MATRICULA:usuario,
					CICLO:miciclo,
					INICIO:$("#inicio").val(),
					TERMINO:$("#termino").val(),
					EMPRESA:$("#empresa").val(),
					MODALIDAD:$("#modalidad").val(),
					PUESTO:$("#puesto").val(),
					REPRESENTANTE:$("#representante").val(),
					PROGRAMA:$("#programa").val(),
					TIPOPROG:$("#tipoprog").val(),
					DIRECCION:$("#direccion").val(),
					FECHACOM:$("#fechacom").val(),
					TIPOPROGADD:$("#tipoprogadd").val(),
					ACTIVIDADES:$("#actividades").val(),
					USUARIO:usuario,
					FECHAUS:fecha,
					_INSTITUCION: lainstitucion, 
					_CAMPUS: elcampus}						
					$.ajax({
							type: "POST",
							url:"../base/inserta.php",
							data: parametros,
							success: function(data){ 
								console.log(data);
								 ocultarEspera("esperaInf");  
								 ocultarEspera("infoError"); 
								   							
							
								}
							});	
					OpcionesServicio();
								
		 }
		 else {alert ("No ha capturado toda la información de su solicitud");}
	}


	function actualizaDatos(elid){
		vacios=false;
		$('.captProy').each(function(){
			if($(this).val()==""){
				$(this).css("border-color","red");
				vacios=true;
			}
		 });
		 if (!vacios) {
			mostrarEspera("esperaInf","grid_pa_servsoc","Cargando Datos...");
			fecha=dameFecha("FECHAHORA");
			parametros={tabla:"ss_alumnos",
					bd:"Mysql",
					campollave:"ID",
					valorllave:elid,
					MATRICULA:usuario,
					CICLO:miciclo,
					INICIO:$("#inicio").val(),
					TERMINO:$("#termino").val(),
					EMPRESA:$("#empresa").val(),
					PUESTO:$("#puesto").val(),
					REPRESENTANTE:$("#representante").val(),
					PROGRAMA:$("#programa").val(),
					MODALIDAD:$("#modalidad").val(),
					TIPOPROG:$("#tipoprog").val(),
					DIRECCION:$("#direccion").val(),
					FECHACOM:$("#fechacom").val(),
					TIPOPROGADD:$("#tipoprogadd").val(),
					ACTIVIDADES:$("#actividades").val(),
					USUARIO:usuario,
					FECHAUS:fecha,
					_INSTITUCION: lainstitucion, 
					_CAMPUS: elcampus}						
					$.ajax({
							type: "POST",
							url:"../base/actualiza.php",
							data: parametros,
							success: function(data){ 
								console.log(data);
								 ocultarEspera("esperaInf");  
								 ocultarEspera("infoError"); 								   														
								}
							});		
							OpcionesServicio();
							
		 }
		 else {alert ("No ha capturado toda la información de su solicitud");}
	}


	function  verProyecto(){

		elsql="select ID, count(*) as HAY from ss_alumnos a where  CICLO='"+miciclo+"' and MATRICULA='"+usuario+"'";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){
				misdatos=JSON.parse(data);
				if (misdatos[0]["HAY"]>0) {
					enlace="nucleo/pa_servsoc/solicitud.php?id="+misdatos[0]["ID"];
					abrirPesta(enlace, "Solic.");
				} 
				else {alert ("No se ha capturado aún los datos de la Solicitud");}
			}
		});
	
	}


	function  verCartaCom(){

		elsql="select ID, count(*) as HAY from ss_alumnos a where  CICLO='"+miciclo+"' and MATRICULA='"+usuario+"'";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){
				misdatos=JSON.parse(data);
				if (misdatos[0]["HAY"]>0) {
					enlace="nucleo/pa_servsoc/cartaCom.php?id="+misdatos[0]["ID"];
					abrirPesta(enlace, "Solic.");
				} 
				else {alert ("No se ha capturado aún los datos de la Solicitud");}
			}
		});
	
	}


	function enviarSol() {

		elsql="select ID, NOMBRE, URES_JEFE, count(*) as HAY from vss_alumnos a, fures b where  b.URES_URES=521 AND CICLO='"+miciclo+"' and MATRICULA='"+usuario+"' group by ID, NOMBRE, URES_JEFE";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){
				misdatos=JSON.parse(data);
				if (misdatos[0]["HAY"]>0) {
					var hoy= new Date();
					lafecha=hoy.getDate()+"/"+hoy.getMonth()+"/"+hoy.getFullYear()+" "+ hoy.getHours()+":"+hoy.getMinutes();
					parametros={tabla:"ss_alumnos",
							bd:"Mysql",
							campollave:"ID",
							valorllave:misdatos[0]["ID"],
							ENVIADA:'S',
							FECHAENVIO:lafecha
					};

					nombreuser=misdatos[0]["NOMBRE"];
					eljefe=misdatos[0]["URES_JEFE"];

					$.ajax({
						type: "POST",
						url:"../base/actualiza.php",
						data: parametros,
						success: function(data){ 
							$("#servicio").html("<div class=\"alert alert-warning\" style=\"width:100%;\">"+ 									        
							"    Tu Solicitud ya fue enviada"+
							"</div>");	
							
							correoPersona(eljefe, "<html>El alumno <span style=\"color:green\"><b>"+usuario+" "+nombreuser+
							"</b></span> ha capturado su solicitud para Servicio Social, "+
							"Favor de Revisarlo para proceder a su autorizaci&oacute;n <BR>","SOLICITUD SERVICIO SOCIAL "+usuario+" "+nombreuser);			

							setNotificacion(eljefe,"Sol. Serv. Social "+usuario+" "+nombreuser,"","",lainstitucion,elcampus);
							}
					});
				}					
			else {alert ("Todos los datos son necesarios, por favor llene todos los campos");}
			}
		});
	}