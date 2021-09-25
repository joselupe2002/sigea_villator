var id_unico="";
var estaseriando=false;
var matser="";
contR=1;
contMat=1;
var laCarrera="";
var elalumno="";
var miciclo="";


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 
		
		elsql="select CICLO,CICL_INICIOR, CICL_FINR, count(*) HAY from vdlista_cred, ciclosesc where "+
		" MATRICULA='"+usuario+"' and CICLO=getciclo() and CICLO=CICL_CLAVE";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){      
				losdatos=JSON.parse(data);
				if (losdatos[0]["HAY"]>0) {
					$("#contenedor").html("<div id=\"credencial\" style=\" width:22cm; height: 14cm; text-align:center; display: inline-block;\">"+
										  "    <div id=\"frente\" style=\"border:1px dotted #DFDFEA; width:10cm; height: 14cm; text-align:center; position:absolute; top:0px; left:0px; "+
					                      "        background-image: url('../../imagenes/empresa/fondoCredF.png'); "+
										  "        background-size: 100% 100%;\">"+
										  "    </div>"+
										  "    <div id=\"atras\" style=\"border:1px dotted #DFDFEA; width:10cm; height: 14cm; text-align:center; position:absolute; top:0px; left:380px; "+
					                      "        background-image: url('../../imagenes/empresa/fondoCredA.png'); "+
										  "        background-size: 100% 100%;\">"+
										  "    </div>"+
										 
										  "</div>"+
										  "<button type=\"button\" onclick=\"bajarCrdedencial('contenedor','CREDENCIAL.png');\" class=\"btn btn-primary\">Descargar</button>");
					llenaCredencial(losdatos[0]["CICL_INICIOR"],losdatos[0]["CICL_FINR"]);
				}	else {
					$("#contenedor").html("<div id=\"credencial\" class=\"alert alert-danger\">No te encuentras inscrito en el CICLO ESCOLAR ACTUAL</div>");
				}
					
				    		   
			},
	
			}); 		
	});
	
	


    function llenaCredencial(inicio,termina){
		mostrarEspera("esperaInf","grid_pa_micredencial","Cargando Datos...");
		elsql="SELECT a.*, b.*, getPeriodos(ALUM_MATRICULA,getciclo()) as PERIODO from falumnos a, ccarreras b where ALUM_MATRICULA='"+usuario+"' AND ALUM_CARRERAREG=CARR_CLAVE";

		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
		type: "POST",
		data:parametros,
		url:  "../base/getdatossqlSeg.php",
		success: function(data){  
			 alum=JSON.parse(data);	
			 if (alum[0]["ALUM_VALIDAFOTO"]=='N') {
				$("#contenedor").html("<div id=\"credencial\" class=\"alert alert-danger\">TU FOTO NO HA SIDO VALIDADA/ASIGNADA VE AL MENU ALUMNOS-DATOS GENERALES, VERIFICA TU FOTO Y HAZ CLIC EN EL BOTÓN ASIGNAR FOTO</div>");
			 }	
		
			 $("#frente").append(
			 						 "   <div class=\"fontAmaranthB\" style=\" font-size:18px; font-color:#08083E; "+
									  "      margin:0px; padding:0px 30px 0px 30px; position:absolute; "+
									  "      top:240px; width:373px;\">"+
									         alum[0]["ALUM_NOMBRE"]+" "+alum[0]["ALUM_APEPAT"]+" "+alum[0]["ALUM_APEMAT"]+
									 "   </div>"+
			                         "   <div style=\"border:2px solid #F2EEEB; border-radius:50%; width:3cm; height: 3cm; position:absolute; top:120px; left:150px;"+
									 "           background-image:url('"+alum[0]["ALUM_FOTO"]+"'); background-size: 100% 100%;\">"+
									 "   </div>"+
									 "   <div class=\"fontAmaranthB\" style=\" font-size:18px; font-color:#08083E; "+
									 "      margin:0px; padding:0px 30px 0px 30px; position:absolute; "+
									 "      top:300px; width:373px;\">"+
									        alum[0]["CARR_DESCRIP"]+
									 "   </div>"+
									 "   <div class=\"fontAmaranthB\" style=\" font-size:18px; font-color:#08083E; "+
									 "      margin:0px; padding:0px 0px 0px 0px; position:absolute; "+
									 "      top:360px; width:373px;\">"+
									 "      <div style=\"background-color:#10117F; color:white;\">MATRICULA: "+alum[0]["ALUM_MATRICULA"]+"</div>"+
									"   </div>"+
									"   <div class=\"fontAmaranthB\" style=\" font-size:18px; font-color:#08083E; "+
									"      margin:0px; padding:0px 5px 0px 5px; position:absolute; "+
									"      top:390px; width:373px;\">"+
									"      <img id=\"codigoQR\" style=\" width:100px; height:100px;\"></div>"+
								   "   </div>"+
								   "   <div class=\"fontRobotoB\" style=\" font-size:14px; color:black; "+
								   "      margin:0px; padding:0px 5px 0px 5px; position:absolute; "+
									"      top:440px; left:0px; width:150px; height:5px;\">SEMESTRE: "+alum[0]["PERIODO"]+
								   "   </div>"
								 						
									 );	
			var protocol = location.protocol;
			var slashes = protocol.concat("//");
			var host = slashes.concat(window.location.hostname);

			new QRious({
						element: document.querySelector("#codigoQR"),
						value: host+"/sigeaAPI/api.php?t="+btoa("C1")+"&i="+btoa(usuario), // La URL o el texto
						size: 200,
						backgroundAlpha: 0, // 0 para fondo transparente
						foreground: "#8bc34a", // Color del QR
						level: "H", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
						});
									

			$("#atras").append(										
									   "  <div class=\"fontAmaranthB\" style=\" font-size:18px; font-color:#08083E; "+
									   "      margin:0px; padding:0px 0px 0px 0px; position:absolute; "+
									   "      top:240px; width:373px;\">FIRMA DEL ALUMNO"+
									   "  </div>"+

									  "   <div class=\"fontRoboto\" style=\" border:solid 1px #7B7B82; font-size:14px; background-color:black; "+
									"      margin:0px; padding:0px 5px 0px 5px; position:absolute; "+
									 "      top:230px; left:55px; width:250px; height:1px;\">"+
									"   </div>"+
									 
									 "   <div class=\"fontRoboto\" style=\" border:solid 1px #7B7B82; font-size:14px; color:white; background-color:#10117F; "+
									  "      margin:0px; padding:0px 5px 0px 5px; position:absolute; "+
									  "      top:300px; left:30px; width:300px;\">VIGENCIA"+
									 "   </div>"+
									 "   <div class=\"fontRoboto\" style=\" border:solid 1px #7B7B82; font-size:14px; color:black; "+
									 "      margin:0px; padding:0px 5px 0px 5px; position:absolute; "+
									 "      top:323px; left:30px; width:150px;\">"+inicio+
									"   </div>"+
									"   <div class=\"fontRoboto\" style=\" border:solid 1px #7B7B82; font-size:14px; color:black; "+
									"      margin:0px; padding:0px 5px 0px 5px; position:absolute; "+
									"      top:323px; left:180px; width:150px;\">"+termina+
								   "   </div>"+
									 "   <div class=\"fontRoboto\" style=\" font-size:8px; font-color:#08083E; "+
									  "      margin:0px; padding:0px 25px 0px 15px; position:absolute; "+
									  "      top:440px; width:100%;\">ESTA CREDENCIAL LO IDENTIFICA COMO ESTUDIANTE DEL INSTITUTO TECNOLÓGICO SUPERIOR DE PEROTE, ESTE DOCUMENTO ES INTRANSFERIBLE, NO ES VALIDO SI MUESTRA TACHADURAS O ENMENDADURAS.</div>"+
									 "   </div>"								
									   );		


									   

			ocultarEspera("esperaInf");   

			
			
		
		}
	
		}); 					  		
}





function saveAs(uri, filename) {
    var link = document.createElement('a');
    if (typeof link.download === 'string') {
      link.href = uri;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    } else {
      window.open(uri);
    }
  }



function bajarCrdedencial(canvasId, filename) {
	
    var domElement = document.getElementById(canvasId);	
	html2canvas(domElement).then(function(canvas) {
		saveAs(canvas.toDataURL(), 'canvas.png');
	
	});

}