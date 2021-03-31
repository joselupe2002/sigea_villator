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

	

			elsqlc="select ifnull(MAX(PDOCVE),getciclo()), COUNT(*) from dlista, cmaterias where ALUCTR='"+usuario+
			"' AND MATCVE=MATE_CLAVE AND IFNULL(MATE_TIPO,'0')='RP'";

			parametros={sql:elsqlc,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
					type: "POST",
					data:parametros,
					url:  "../base/getdatossqlSeg.php",
					success: function(data){ 
						
						losdatos=JSON.parse(data); 
						miciclo=losdatos[0][0];
						$("#elciclo").html(losdatos[0][0]);
					}
				});


			
		cargarAvance();
		verCartaPresentacion();
	});
	
	

	function verCartaPresentacion(){
		elsqlc="select ifnull(MAX(PDOCVE),getciclo()), COUNT(*) from dlista, cmaterias where ALUCTR='"+usuario+
			"' AND MATCVE=MATE_CLAVE AND IFNULL(MATE_TIPO,'0')='RP'";

		parametros={sql:elsqlc,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(dataCic2){ 
				losdatosCic=JSON.parse( dataCic2); 
				elciclo=losdatosCic[0][0];
				elsql="select ifnull(RUTA,'') as RUTA, count(*) as HAY FROM respropuestas a where MATRICULA='"+usuario+"' and CICLO='"+elciclo+"'";
				parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		
				$.ajax({
							type: "POST",
							data:parametros,
							url:  "../base/getdatossqlSeg.php",
							success: function(data){	
								losdatos=JSON.parse(data); 				
								if ((losdatos[0]["HAY"]>0) && (losdatos[0]["RUTA"]!="")) {							
									$("#lacarta").append(
										"<div class=\"row\">"+
											"<div class=\"row\">"+
												"<button  onclick=\"abrirPesta('"+losdatos[0]["RUTA"]+"','Carta');\" class=\"btn  btn-bold btn-danger\" value=\"Agregar\">"+
												"     <i class=\"ace-icon white fa fa-file-text bigger-200\"></i><span class=\"fontRobotoB text-white\">Ver Carta Presentación</span>"+
												"</button>"+
											"<div>"+
										"</div>");							
									}									 
							}
					});
			}
		});
	}
	

		 
	function cargarAvance() {

		elsql="select ALUM_MATRICULA, ALUM_CORREO, ALUM_FOTO, ALUM_MAPA, ALUM_ESPECIALIDAD,"+ 
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
			   $("#micorreo").html(losdatos[0]["ALUM_CORREO"]);

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


						//========================================================
						
						losdatos=JSON.parse(data); 						

						if ((porcReal>=80)) {
														
							$("#servicio").append("<div class=\"row\" style=\"text-align:left;\">"+
							"    <div class=\"col-sm-12\"> "+
							"     <div id=\"documentos2\" class=\"col-sm-12\" ></div>"+										
							"    </div>"+
							"</div>");

							$("#servicio").append("<div class=\"row\" style=\"text-align:left;\">"+
							"    <div class=\"col-sm-12\"> "+
							"     <div id=\"documentos3\" class=\"col-sm-12\" ></div>"+										
							"    </div>"+
							"</div>");
							$("#ppuede").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
							$("#ppuede").addClass("fa  fa-check green bigger-260");	

							OpcionesResidencia();
							abrirCapturaProyecto();
							documentosRes();
							OpcionesEvaluaciones();							
							
						}	
					else {
						alert ("No cumples los créditos todavia para cursar la  Residencia Profesional");
						$("#ppuede").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
						$("#ppuede").addClass("fa fa-times red bigger-260");	
					}			
					}
				});

		   }
	   });
   }  


function cargarDatosPropuesta(tipo){
	$("#servicio").append("<div class=\"fontRobotoB\" id=\"lapropuesta\" style=\"text-align:left;\"></div>");

	$("#lapropuesta").append("<div class=\"row\">"+
								 "<div class=\"col-sm-6\"> "+
									"<label>Nombre de la Empresa</label><input class=\"form-control\" id=\"empresa\"></input>"+
								"</div>"+
								"<div class=\"col-sm-6\"> "+
								    "<label>Persona para Oficio</label><input class=\"form-control\" id=\"persona\"></input>"+
								"</div>");

	$("#lapropuesta").append("<div class=\"row\">"+
								"<div class=\"col-sm-6\"> "+
								   "<label>Puesto de la Persona</label><input class=\"form-control\" id=\"puesto\"></input>"+
							   "</div>"+
							   "<div class=\"col-sm-6\"> "+
								   "<label>Dirección Empresa, (Calle, número, ciudad, estado) </label><input class=\"form-control\" id=\"direccion\"></input>"+
							   "</div>");

	$("#lapropuesta").append("<div class=\"row\">"+
								 "<div class=\"col-sm-6\"> "+
									"<label>Inicia Residencia</label>"+
									" <div class=\"input-group\"><input  class=\"form-control date-picker\"  id=\"inicia\" "+
									" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
									" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
								"</div>"+
								"<div class=\"col-sm-6\"> "+
									"<label>Termina Residencia</label>"+
									" <div class=\"input-group\"><input  class=\"form-control date-picker\"  id=\"termina\" "+
									" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
									" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
								"</div>");
	$("#lapropuesta").append("<br><div style=\"text-align:center;\"><button  onclick=\"enviarPropuesta();\" class=\"btn  btn-bold btn-danger\" value=\"Agregar\">"+
	                         "     <i class=\"ace-icon white fa fa-save bigger-200\"></i><span class=\"btn-lg\">Enviar propuesta</span>"+
	                         "</button></div>");								

	//Para los componentes de fecha 
    $('.date-picker').datepicker({autoclose: true,todayHighlight: true}).next().on(ace.click_event, function(){$(this).prev().focus();});
   }



   function OpcionesResidencia(){
	var abierto=false;
	elsql="select count(*) as N from ecortescal where  CICLO='"+miciclo+"'"+
	" and ABIERTO='S' and STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') "+
	" Between STR_TO_DATE(INICIA,'%d/%m/%Y') "+
	" AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='RESPROF' "+
	" order by STR_TO_DATE(TERMINA,'%d/%m/%Y')  DESC LIMIT 1";

	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	$.ajax({
		type: "POST",
		data:parametros,
		url:  "../base/getdatossqlSeg.php",
		success: function(data){	
			if (JSON.parse(data)[0]["N"]>0) {	

					$("#pcarta").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
					$("#pcarta").addClass("fa  fa-check green bigger-260");
					// Verficamos si ya envio su propuesta de empresa. 
					var abierto=false;
					elsql="select a.*, count(*) as HAY FROM respropuestas a where MATRICULA='"+usuario+"' and CICLO='"+miciclo+"'";
					parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		
					$.ajax({
							type: "POST",
							data:parametros,
							url:  "../base/getdatossqlSeg.php",
							success: function(data){	
								losdatos=JSON.parse(data); 
								if (losdatos[0]["HAY"]>0) {	
									$("#pacarta").removeClass("glyphicon glyphicon-unchecked blue bigger-260");									    							
									$("#pcarta").addClass("fa  fa-check purple bigger-260");									
									}									 
								else {cargarDatosPropuesta(0);}
							}
					});

				} //DEL SI ESTA ABIERO 
				else { console.log("No hay proceso abierto para solicitud de Residencia Profesional");
						$("#pcarta").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
						$("#pcarta").addClass("fa  fa-times red bigger-260");
				}
			}
		}); //del ajax de busqueda de corte abierto 
	}
	
	function enviarPropuesta(){
		if (($("#puesto").val()!='') && ($("#persona").val()!='') && ($("#inicia").val()!='') && ($("#empresa").val()!='') && ($("#domicilio").val()!='')) {
			var hoy= new Date();
			lafecha=hoy.getDate()+"/"+hoy.getMonth()+"/"+hoy.getFullYear()+" "+ hoy.getHours()+":"+hoy.getMinutes();
			parametros={tabla:"respropuestas",
						bd:"Mysql",
						_INSTITUCION:"ITSP",
						_CAMPUS:"0",
						MATRICULA:usuario,
						CICLO:miciclo,					
						PUESTO:$("#puesto").val().toUpperCase(),
						DOMICILIO:$("#direccion").val().toUpperCase(),
						PERSONA:$("#persona").val().toUpperCase(),
						EMPRESA:$("#empresa").val().toUpperCase(),
						INICIA:$("#inicia").val(),
						TERMINA:$("#termina").val(),
						FECHAENVIADA:lafecha,
						USUARIO:usuario,
						FECHAUS:lafecha
					};
						$.ajax({
							type: "POST",
							url:"../base/inserta.php",
							data: parametros,
							success: function(data){ 
								$("#servicio").html("<div class=\"alert alert-warning\" style=\"width:100%;\">"+ 									        
								"    Tu Solicitud ya fue enviada"+
								"</div>");	
								correoalJefe(usuario, "<html>El alumno <span style=\"color:green\"><b>"+usuario+" "+nombreuser+
								"</b></span> ha capturado los datos de Carta de Presentaci&oacute;n para Residencia Profesional, "+
								"Favor de Revisarlo para proceder a su autorizaci&oacute;n <BR>","ITSP: CAPTURA DE DATOS DE PRESENTACION DE "+usuario+" "+nombreuser);			

								setNotificacionalJefe(usuario,"Sol. Carta Presentación RP."+usuario+" "+nombreuser,"nucleo/respropuestas/grid.php?modulo=respropuestas","P",lainstitucion,elcampus);
								}
							});
						}
		else {alert ("Todos los datos son necesarios, por favor llene todos los campos");}
	
	}









	function abrirCapturaProyecto (){

		var abierto=false;
		elsql="select count(*) as N from ecortescal where  CICLO='"+miciclo+"'"+
		" and ABIERTO='S' and STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') "+
		" Between STR_TO_DATE(INICIA,'%d/%m/%Y') "+
		" AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='CAPTPROYRES' "+
		" order by STR_TO_DATE(TERMINA,'%d/%m/%Y')  DESC LIMIT 1";


		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){	

				
				if (JSON.parse(data)[0]["N"]>0) {						
					//Si esta abierto aparecemos la opción de capturar Proyecto.
					$("#pcapt").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
					$("#pcapt").addClass("fa  fa-check green bigger-260");
					$("#documentos2").append("<hr><div style=\"text-align:center;\">"+
							"<button  onclick=\"capturaProyecto();\" class=\"btn btn-white btn-info btn-bold\">"+
							"     <i class=\"ace-icon green glyphicon glyphicon-book\"></i>1. Capturar Sol. Proyecto"+
							"</button> &nbsp;  &nbsp; "+
							"<button  onclick=\"verProyecto();\" class=\"btn btn-white btn-success btn-bold\">"+
							"     <i class=\"ace-icon pink glyphicon glyphicon-print\"></i>2. Imprimir Sol. Proyecto"+
							"</button>"+
					"</div>");

				}
				
			}
		});

	}


	function documentosRes(){

		var abierto=false;
		elsql="select count(*) as N from ecortescal where  CICLO='"+miciclo+"'"+
		" and ABIERTO='S' and STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') "+
		" Between STR_TO_DATE(INICIA,'%d/%m/%Y') "+
		" AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='SUBIRDOCRES' "+
		" order by STR_TO_DATE(TERMINA,'%d/%m/%Y')  DESC LIMIT 1";
	
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){					
				if (JSON.parse(data)[0]["N"]>0) {	
						elsql="SELECT IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_CARTAPRES'),'') AS RUTAPRES, "+
						" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_SOLANTEP'),'') AS RUTASOLANTEP, "+
						" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_ANTEP'),'') AS RUTAANTEP, "+
						" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_LIBSER'),'') AS RUTALIBSER, "+
						" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_IMSS'),'') AS RUTAIMSS, "+
						" IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_CARDEX'),'') AS RUTACARDEX, "+
						"       IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+miciclo+"_CARTAACEP'),'') AS RUTAACEP FROM DUAL"; 

						parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
						$.ajax({
							type: "POST",
							data:parametros,
							url:  "../base/getdatossqlSeg.php",
							success: function(data){	

								$("#documentos2").append("<div class=\"row\"><div id=\"cartaPres\" class=\"col-sm-6\"></div>"+
																			"<div id=\"cartaAcep\" class=\"col-sm-6\"></div>"+
														"</div>"+
														"<div class=\"row\"><div id=\"Libser\" class=\"col-sm-6\"></div>"+
																			"<div id=\"Imss\" class=\"col-sm-6\"></div>"+
														"</div>"+
														"<div class=\"row\"><div id=\"Cardex\" class=\"col-sm-6\"></div>"+
																			"<div id=\"Otro\" class=\"col-sm-6\"></div>"+
														"</div>"+
														"<div class=\"row\"><div id=\"solAntep\" class=\"col-sm-6\"></div>"+
																			"<div id=\"Antep\" class=\"col-sm-6\"></div>"+
														"</div>");
								activaEliminar="";
								if (JSON.parse(data)[0]["RUTAPRES"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("cartaPres","Carta Presenta. Sellada","cartapres",'residenciasProf','pdf',
								'ID',usuario,'CARTA DE PRESENTACIÓN','eadjresidencia','alta',usuario+"_"+miciclo+"_CARTAPRES",JSON.parse(data)[0]["RUTAPRES"],activaEliminar,usuario+"_"+miciclo+"_CARTAPRES");
								
								activaEliminar="";
								if (JSON.parse(data)[0]["RUTAACEP"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("cartaAcep","Carta de Aceptación","cartaacep",'residenciasProf','pdf',
								'ID',usuario,'CARTA DE ACEPTACIÓN','eadjresidencia','alta',usuario+"_"+miciclo+"_CARTAACEP",JSON.parse(data)[0]["RUTAACEP"],activaEliminar,usuario+"_"+miciclo+"_CARTAACEP");
								

								activaEliminar="";
								if (JSON.parse(data)[0]["RUTASOLANTEP"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("solAntep","Sol. Anteproyecto","solantep",'residenciasProf','pdf',
								'ID',usuario,'SOLICITUD DE ANTEPROYECTO FIRMADA Y SELLADA','eadjresidencia','alta',usuario+"_"+miciclo+"_SOLANTEP",JSON.parse(data)[0]["RUTASOLANTEP"],activaEliminar,usuario+"_"+miciclo+"_SOLANTEP");
								

								activaEliminar="";
								if (JSON.parse(data)[0]["RUTAANTEP"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("Antep","Anteproyecto Autorizado","antep",'residenciasProf','pdf',
								'ID',usuario,'ANTEPROYECTO AUTORIZADO','eadjresidencia','alta',usuario+"_"+miciclo+"_ANTEP",JSON.parse(data)[0]["RUTAANTEP"],activaEliminar,usuario+"_"+miciclo+"_ANTEP",JSON.parse(data)[0]["RUTAANTEP"]);
								
								activaEliminar="";
								if (JSON.parse(data)[0]["RUTALIBSER"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("Libser","Liberación Serv. Soc.","libser",'residenciasProf','pdf',
								'ID',usuario,'LIBERACIÓN DE SERVICIO SOCIAL','eadjresidencia','alta',usuario+"_"+miciclo+"_LIBSER",JSON.parse(data)[0]["RUTALIBSER"],activaEliminar,usuario+"_"+miciclo+"_LIBSER");
								
								activaEliminar="";
								if (JSON.parse(data)[0]["RUTAIMSS"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("Imss","Carta Vigencia IMSS","imss",'residenciasProf','pdf',
								'ID',usuario,'ANTEPROYECTO AUTORIZADO','eadjresidencia','alta',usuario+"_"+miciclo+"_IMSS",JSON.parse(data)[0]["RUTAIMSS"],activaEliminar);
								
								activaEliminar="";
								if (JSON.parse(data)[0]["RUTACARDEX"]!='') {	activaEliminar='S';}					
								dameSubirArchivoLocal("Cardex","Cardex de estudios","cardex",'residenciasProf','pdf',
								'ID',usuario,'CARDEX DE ESTUDIOS','eadjresidencia','alta',usuario+"_"+miciclo+"_CARDEX",JSON.parse(data)[0]["RUTACARDEX"],activaEliminar,usuario+"_"+miciclo+"_CARDEX");
												
							}
						});					

				}
			}
		});

	}

	function capturaProyecto(){


		elsql="select ifnull(ID,'0') as ID,ifnull( MATRICULA,'') AS MATRICULA,ifnull( CICLO,'') AS CICLO,ifnull( INICIA,'') AS INICIA,"+
		"ifnull( PROYECTO,'') AS PROYECTO,ifnull( TERMINA,'') AS TERMINA,ifnull( EMPRESA,'') AS EMPRESA,ifnull( DEPARTAMENTO,'')AS DEPARTAMENTO,ifnull( GIRO,'') AS GIRO,"+
		"ifnull( SECTOR,'') AS SECTOR,ifnull( DOMICILIO,'') AS DOMICILIO,ifnull( CP,'') AS CP,ifnull( TELEFONO,'') AS TELEFONO,ifnull( MISION,'') AS MISION,"+
		"ifnull( TITULAR,'') AS TITULAR,ifnull( PSTOTITULAR,'') AS PSTOTITULAR,ifnull( ASESOREX,'') AS ASESOREX,"+
		"ifnull( PSTOASESOREX,'') AS PSTOASESOREX,ifnull( ASESOR,'') AS ASESOR,ifnull( CORREOASESOREX,'') AS CORREOASESOREX,"+
		"ifnull( FIRMA,'') AS FIRMA,ifnull( PSTOFIRMA,'') as PSTOFIRMA,ifnull( CORREOFIRMA,'') as CORREOFIRMA,"+
		"ifnull( HORARIO,'LUNES A VIERNES DE DE HH:MM HORAS A HH:MM HORAS') as HORARIO,ifnull( VALIDADO,'') AS VALIDADI,"+
		"ifnull( USUARIO,'') AS USUARIO,ifnull( FECHAUS,'') AS FECHAUS,ifnull( _INSTITUCION,'') AS _INSTITUCION,"+
		"ifnull( _CAMPUS,'') AS _CAMPUS,ifnull( RFC,'') AS RFC, VALIDADO AS VALIDADO,"+
		"count(*) as HAY from rescapproy a where  CICLO='"+miciclo+"'"+
		" and MATRICULA='"+usuario+"'";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){

				misdatos=JSON.parse(data);

				if (misdatos[0]["HAY"]>0) {proceso="actualizaDatos("+misdatos[0]["ID"]+");"; } else {proceso="grabarDatos();";}
				mostrarConfirm2("infoError","grid_pa_residencia","Captura de Datos del Proyecto",
				"<div class=\"ventanaSC\" style=\"text-align:justify; width:99%; height:250px; overflow-y:auto; overflow-x:hidden;\">"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Nombre del Proyecto</label><input class=\"form-control captProy\" id=\"proyecto\" value=\""+misdatos[0]["PROYECTO"]+"\"></input>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label  class=\"fontRobotoB\">Inicia</label>"+
							" <div class=\"input-group\"><input  class=\"form-control  captProy date-picker\"  value=\""+misdatos[0]["INICIA"]+"\" id=\"inicia\" "+
							" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
							" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label class=\"fontRobotoB\">Termina</label>"+
							" <div class=\"input-group\"><input  class=\"form-control captProy date-picker\" value=\""+misdatos[0]["TERMINA"]+"\" id=\"termina\" "+
							" type=\"text\" autocomplete=\"off\"  data-date-format=\"dd/mm/yyyy\" /> "+
							" <span class=\"input-group-addon\"><i class=\"fa fa-calendar bigger-110\"></i></span></div>"+
						"</div>"+
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Nombre de la empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["EMPRESA"]+"\" id=\"empresa\"></input>"+
						"</div>"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Departamento de la empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["DEPARTAMENTO"]+"\" id=\"departamento\"></input>"+
						"</div>"+		
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Giro de la empresa</label><select class=\"form-control captProy\"  id=\"giro\"></select>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label class=\"fontRobotoB\">Sector de la empresa</label><select class=\"form-control captProy\" id=\"sector\"></select>"+
						"</div>"+
						"<div class=\"col-sm-3\">"+
							"<label class=\"fontRobotoB\">RFC Empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["RFC"]+"\" id=\"rfc\"></input>"+
						"</div>"+		
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-8\">"+
							"<label class=\"fontRobotoB\">Domicilio</label><input class=\"form-control captProy\" value=\""+misdatos[0]["DOMICILIO"]+"\" id=\"domicilio\"></input>"+
						"</div>"+
						"<div class=\"col-sm-2\">"+
							"<label class=\"fontRobotoB\">C.P.</label><input class=\"form-control captProy\" value=\""+misdatos[0]["CP"]+"\" id=\"cp\"></input>"+
						"</div>"+
						"<div class=\"col-sm-2\">"+
							"<label class=\"fontRobotoB\">Teléfono</label><input class=\"form-control captProy\" value=\""+misdatos[0]["TELEFONO"]+"\" id=\"telefono\"></input>"+
						"</div>"+		
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-12\">"+
							"<label class=\"fontRobotoB\">Misión de la Empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["MISION"]+"\" id=\"mision\"></input>"+
						"</div>"+	
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Nombre del Titular Empresa (LIC./ING./MTRO. XXXX)</label><input class=\"form-control captProy\" value=\""+misdatos[0]["TITULAR"]+"\" id=\"titular\"></input>"+
						"</div>"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Puesto que ocupa en la Empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["PSTOTITULAR"]+"\" id=\"pstotitular\"></input>"+
						"</div>"+	
					"</div>"+
					"<div class=\"row\">"+
						"<div class=\"col-sm-4\">"+
							"<label class=\"fontRobotoB\">Grado y Nombre Asesor Externo</label><input class=\"form-control captProy\" value=\""+misdatos[0]["ASESOREX"]+"\" id=\"asesorex\"></input>"+
						"</div>"+
						"<div class=\"col-sm-4\">"+
							"<label class=\"fontRobotoB\">Puesto que ocupa en la empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["PSTOASESOREX"]+"\" id=\"pstoasesorex\"></input>"+
						"</div>"+
						"<div class=\"col-sm-4\">"+
							"<label class=\"fontRobotoB\">Correo del Asesor externo</label><input class=\"form-control captProy\" value=\""+misdatos[0]["CORREOASESOREX"]+"\" id=\"correoasesorex\"></input>"+
						"</div>"+		
					"</div>"+

					"<div class=\"row\">"+
						"<div class=\"col-sm-4\">"+
							"<label class=\"fontRobotoB\">Grado y Nombre Firma Acuerdo</label><input class=\"form-control captProy\" value=\""+misdatos[0]["FIRMA"]+"\" id=\"firma\"></input>"+
						"</div>"+
						"<div class=\"col-sm-4\">"+
							"<label class=\"fontRobotoB\">Puesto que ocupa en la empresa</label><input class=\"form-control captProy\" value=\""+misdatos[0]["PSTOFIRMA"]+"\" id=\"pstofirma\"></input>"+
						"</div>"+
						"<div class=\"col-sm-4\">"+
							"<label class=\"fontRobotoB\">Correo</label><input class=\"form-control captProy\" value=\""+misdatos[0]["CORREOFIRMA"]+"\" id=\"correofirma\"></input>"+
						"</div>"+		
					"</div>"+

					"<div class=\"row\">"+
						"<div class=\"col-sm-12\">"+
							"<label class=\"fontRobotoB\">Horario Establecido para la Residencia</label><input class=\"form-control captProy\" id=\"horario\" value=\""+misdatos[0]["HORARIO"]+"\" ></input>"+
						"</div>"+			
					"</div>"+

					"<div class=\"row\">"+
						"<div class=\"col-sm-6\">"+
							"<label class=\"fontRobotoB\">Asesor Interno</label><select class=\"form-control captProy\"  id=\"asesor\"></select>"+
						"</div>"+		
					"</div>"+

				"</div>"
				,"Grabar Datos",proceso,"modal-lg");

				actualizaSelectMarcar("giro", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='GIROEMPRESAS'", "","",misdatos[0]["GIRO"]); 
				actualizaSelectMarcar("sector", "SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='REGIMENEMPRESAS'", "","",misdatos[0]["SECTOR"]); 
				actualizaSelectMarcar("asesor", "SELECT EMPL_NUMERO, CONCAT(EMPL_NOMBRE, ' ',EMPL_APEPAT,' ', EMPL_APEMAT) FROM pempleados where EMPL_ACTIVO='S' ORDER BY EMPL_NOMBRE, EMPL_APEPAT", "","",misdatos[0]["ASESOR"]); 
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
			mostrarEspera("esperaInf","grid_pa_residencia","Cargando Datos...");

			fecha=dameFecha("FECHAHORA");
			parametros={tabla:"rescapproy",
					bd:"Mysql",
					MATRICULA:usuario,
					CICLO:miciclo,
					INICIA:$("#inicia").val(),
					TERMINA:$("#termina").val(),
					ASESOR:$("#asesor").val(),
					PROYECTO:$("#proyecto").val().toUpperCase(),
					EMPRESA:$("#empresa").val().toUpperCase(),
					DEPARTAMENTO:$("#departamento").val().toUpperCase(),
					GIRO:$("#giro").val().toUpperCase(),
					SECTOR:$("#sector").val().toUpperCase(),
					DOMICILIO:$("#domicilio").val().toUpperCase(),
					CP:$("#cp").val().toUpperCase(),
					RFC:$("#rfc").val().toUpperCase(),
					TELEFONO:$("#telefono").val().toUpperCase(),
					MISION:$("#mision").val().toUpperCase(),
					TITULAR:$("#titular").val().toUpperCase(),
					PSTOTITULAR:$("#pstotitular").val(),
					ASESOREX:$("#asesorex").val().toUpperCase(),
					PSTOASESOREX:$("#pstoasesorex").val().toUpperCase(),
					CORREOASESOREX:$("#correoasesorex").val().toLowerCase(),
					FIRMA:$("#firma").val().toUpperCase(),
					PSTOFIRMA:$("#pstofirma").val().toUpperCase(),
					CORREOFIRMA:$("#correofirma").val().toLowerCase(),
					HORARIO:$("#horario").val().toUpperCase(),
					USUARIO:usuario,
					FECHAUS:fecha,
					_INSTITUCION: lainstitucion, 
					_CAMPUS: elcampus}						
					$.ajax({
							type: "POST",
							url:"../base/inserta.php",
							data: parametros,
							success: function(data){ 
								 alert (data);
								 ocultarEspera("esperaInf");  
								 ocultarEspera("infoError"); 
								   							
							
								}
							});			
		 }
		 else {alert ("No ha capturado toda la información de su proyecto");}
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
			mostrarEspera("esperaInf","grid_pa_residencia","Cargando Datos...");
			fecha=dameFecha("FECHAHORA");
			parametros={tabla:"rescapproy",
					bd:"Mysql",
					campollave:"ID",
					valorllave:elid,
					MATRICULA:usuario,
					CICLO:miciclo,
					INICIA:$("#inicia").val(),
					TERMINA:$("#termina").val(),
					PROYECTO:$("#proyecto").val().toUpperCase(),
					EMPRESA:$("#empresa").val().toUpperCase(),
					DEPARTAMENTO:$("#departamento").val().toUpperCase(),
					GIRO:$("#giro").val().toUpperCase(),
					SECTOR:$("#sector").val().toUpperCase(),
					DOMICILIO:$("#domicilio").val().toUpperCase(),
					CP:$("#cp").val().toUpperCase(),
					RFC:$("#rfc").val().toUpperCase(),
					TELEFONO:$("#telefono").val().toUpperCase(),
					MISION:$("#mision").val().toUpperCase(),
					TITULAR:$("#titular").val().toUpperCase(),
					PSTOTITULAR:$("#pstotitular").val(),
					ASESOREX:$("#asesorex").val().toUpperCase(),
					PSTOASESOREX:$("#pstoasesorex").val().toUpperCase(),
					CORREOASESOREX:$("#correoasesorex").val().toLowerCase(),
					FIRMA:$("#firma").val().toUpperCase(),
					PSTOFIRMA:$("#pstofirma").val().toUpperCase(),
					CORREOFIRMA:$("#correofirma").val().toLowerCase(),
					HORARIO:$("#horario").val().toUpperCase(),
					ASESOR:$("#asesor").val(),
					USUARIO:usuario,
					FECHAUS:fecha,
					_INSTITUCION: lainstitucion, 
					_CAMPUS: elcampus}						
					$.ajax({
							type: "POST",
							url:"../base/actualiza.php",
							data: parametros,
							success: function(data){ 
								 ocultarEspera("esperaInf");  
								 ocultarEspera("infoError"); 								   														
								}
							});			
		 }
		 else {alert ("No ha capturado toda la información de su proyecto");}
	}


	function  verProyecto(){

		elsql="select ID, count(*) as HAY from rescapproy a where  CICLO='"+miciclo+"' and MATRICULA='"+usuario+"'";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){
				misdatos=JSON.parse(data);
				if (misdatos[0]["HAY"]>0) {
					enlace="nucleo/pa_residencia/formato.php?id="+misdatos[0]["ID"];
					abrirPesta(enlace, "Sol. Proyecto");
				} 
				else {alert ("No se ha capturado aún los datos de la Solicitud del Proyecto")}
			}
		});
	
	}



	function OpcionesEvaluaciones(){

		//Verificamos el ciclo escolar 
		elsq="select max(CICLO), count(*) AS HAY from residencias a where a.MATRICULA='"+usuario+"'";
		parametros={sql:elsq,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){	

				if (JSON.parse(data)[0]["HAY"]>0) {
					$("#preg").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
					$("#preg").addClass("fa  fa-check green bigger-260");
					elciclo=JSON.parse(data)[0][0];
					var abierto=false;
					elsql="select count(*) as N from ecortescal where  CICLO='"+elciclo+"'"+
					" and ABIERTO='S' and STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') "+
					" Between STR_TO_DATE(INICIA,'%d/%m/%Y') "+
					" AND STR_TO_DATE(TERMINA,'%d/%m/%Y') and CLASIFICACION='RESEVALUA' "+
					" order by STR_TO_DATE(TERMINA,'%d/%m/%Y')  DESC LIMIT 1";
				
					parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
					$.ajax({
						type: "POST",
						data:parametros,
						url:  "../base/getdatossqlSeg.php",
						success: function(data){	
							if (JSON.parse(data)[0]["N"]>0) {	
								$("#peval").removeClass("glyphicon glyphicon-unchecked blue bigger-260");								
								$("#peval").addClass("fa fa-check green bigger-260");
								// Abrimos las opciones para subir documentacion de residencia 	
								elsql="SELECT IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+elciclo+"_EVAL1'),'') AS EVAL1, "+
										"IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+elciclo+"_EVAL2'),'') AS EVAL2,"+
										"IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+elciclo+"_EVALF'),'') AS EVALF,"+
										"IFNULL((select RUTA from eadjresidencia where  AUX='"+usuario+"_"+elciclo+"_REPTEC'),'') AS REPTEC FROM DUAL"; 
			
								parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
								$.ajax({
									type: "POST",
									data:parametros,
									url:  "../base/getdatossqlSeg.php",
									success: function(data){	
			
										$("#documentos3").append("<hr><div class=\"row\"><div id=\"EVAL1\" class=\"col-sm-6\"></div><div id=\"EVAL2\" class=\"col-sm-6\"></div></div>"+
										"<div class=\"row\"><div id=\"EVALF\" class=\"col-sm-6\"></div><div id=\"REPTEC\" class=\"col-sm-6\"></div></div>");
						
										activaEliminar="";
										if (JSON.parse(data)[0]["EVAL1"]!='') {	activaEliminar='S';}					
										dameSubirArchivoLocal("EVAL1","1ra Evaluación","eval1",'residenciasProf','pdf',
										'ID',usuario,'PRIMERA EVALUACIÓN','eadjresidencia','alta',usuario+"_"+elciclo+"_EVAL1",JSON.parse(data)[0]["EVAL1"],activaEliminar,usuario+"_"+elciclo+"_EVAL1");
										
										activaEliminar="";
										if (JSON.parse(data)[0]["EVAL2"]!='') {	activaEliminar='S';}					
										dameSubirArchivoLocal("EVAL2","Segunda Evaluación","eval2",'residenciasProf','pdf',
										'ID',usuario,'SEGUNDA EVALUACION','eadjresidencia','alta',usuario+"_"+elciclo+"_EVAL2",JSON.parse(data)[0]["EVAL2"],activaEliminar,usuario+"_"+elciclo+"_EVAL2");
										
										activaEliminar="";
										if (JSON.parse(data)[0]["EVALF"]!='') {	activaEliminar='S';}					
										dameSubirArchivoLocal("EVALF","Evaluación Final","evalf",'residenciasProf','pdf',
										'ID',usuario,'EVALUACIÓN FINAL','eadjresidencia','alta',usuario+"_"+elciclo+"_EVALF",JSON.parse(data)[0]["EVALF"],activaEliminar,usuario+"_"+elciclo+"_EVALF");							
			
										activaEliminar="";
										if (JSON.parse(data)[0]["REPTEC"]!='') {	activaEliminar='S';}					
										dameSubirArchivoLocal("REPTEC","Reporte Técnico","reptec",'residenciasProf','pdf',
										'ID',usuario,'REPORTE TÉCNICO','eadjresidencia','alta',usuario+"_"+elciclo+"_REPTEC",JSON.parse(data)[0]["REPTEC"],activaEliminar,usuario+"_"+elciclo+"_REPTEC");							
										
									}
								});		
								} //DEL SI ESTA ABIERO 
								else {		
									$("#peval").removeClass("glyphicon glyphicon-unchecked blue bigger-260");	
									$("#peval").addClass("fa  fa-times red bigger-260");							
								}
							}
						}); //del ajax de busqueda de corte abierto 		
					}
					else {
						$("#preg").removeClass("glyphicon glyphicon-unchecked blue bigger-260");
						$("#preg").addClass("fa  fa-times red bigger-260");
					
					}
			}
		});

			}
	