


var colores=[];


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 
		actualizaSelect("selColor","SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='COLORESGUIA' order by CATA_DESCRIP","");
		actualizaSelect("selTipo","SELECT CATA_CLAVE, CATA_DESCRIP FROM scatalogos where CATA_TIPO='TIPOSGUIAS' ORDER BY CATA_DESCRIP","");
		cargaInfografia();
	});
	
	function faq(elid){
		enlace="nucleo/ayudaguias/faq.php?id="+elid;
		abrirPesta(enlace, "FAQ")
	}

	function cargaInfografia(){
		if (tipo=='LIN') { infoInter();}
		if (tipo=='LII') { infoLineal();}
		if (tipo=='CI1') { infoCir1();}
		if (tipo=='CUA') { infoCuadUnidos();}
		if (tipo=='CUL') { infoCuadLinea();}
		if (tipo=='LI1') { infoLineas1();}
		if (tipo=='LISC') { infoLinealSC(true);}
		if (tipo=='LISI') { infoLinealSC(false);}
		if (tipo=='LIE') { infoLinealEnlazado();}
		if (tipo=='LIIM') { infoLinealImagenes();}
		
		
		if (color==1) {colores=["#0A2776","#0A7674","#5C0A76","#76540A","#76230A","#766E0A","#1DD384","#4AD31D","#D3C41D","#D3881D","#87729D","#55E1E9","#9056E7","#DA56E7","#56E7A2"];}
		if (color==2) {colores=["#B58484","#B5B084","#89B584","#84AEB5","#848CB5","#CA72A0","#533D7D","#85BD90","#87729D","#55E1E9","#D3C41D","#D3881D","#1DD384","#4AD31D","#D3C41D"];}
		if (color==3) {colores=["#E96B55","#E7B056","#E7E056","#BBE756","#71E756","#56E7A2","#56E1E7","#5692E7","#9056E7","#DA56E7","#56E7A2","#56E1E7","#1DD384","#4AD31D","#85BD90"];}
		if (color==4) {colores=["#FC0303","#03A0FC","#03F2FC","#03FCBA","#3903FC","#B103FC","#FC03B0","#FC037B","#FCD203","#40FC03","#03FC6B","#ED03FC","#FC033F","#0358FC","#FC8E03"];}


		
	}

	function cambioTipo(eltipo){
		if (eltipo=="TIPO") {tipo=$("#selTipo").val();}
		if (eltipo=="COLOR") {color=$("#selColor").val();}
		cargaInfografia();
	}
	

	function infoLineal(){
		mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
		elsql2="select ORDEN,CONTENIDO,TITULO,"+
		"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+" ORDER BY ORDEN";
					
		$("#miguia").empty();	
		$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
		parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
		c=0;			
		$.ajax({
				type: "POST",
				data:parametrosw,
				url:  "../base/getdatossqlSeg.php",
				success: function(data2){  
					grid_data=JSON.parse(data2);	
					jQuery.each(grid_data, function(clave, valor) { 

						laimagen="../../imagenes/menu/default.png";
						if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}		
							elcontenido="<div class=\"ginumero\" style=\"background-color:"+colores[c]+"\">"+valor.ORDEN+"</div>"+
							   "         <span class=\"gicontenido gititulo sigeaPrin\">"+valor.TITULO+"<br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></span>"+
							   "         <div class=\"giimagen\"><img src=\""+laimagen+"\" widht=60px; height=60px;></div>";					   						
					
						$("#miguia").append("<div class=\"row fontRoboto\">"+
											"     <div class=\"col-sm-1\"></div>"+
											"     <div class=\"col-sm-10\" style=\"font-size:20px;\">"+
											elcontenido+
											"     </div>"+
											"     <div class=\"col-sm-1\"></div>"+
											"</div>");
											c++;
					  
					});
					ocultarEspera("esperaInf"); 
				}
			});
	}


	function infoInter(){
		mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
		elsql2="select ORDEN,CONTENIDO,TITULO,"+
		"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+" ORDER BY ORDEN";
					
		$("#miguia").empty();	
		$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
		parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
		c=1;	
		i=0;		
		$.ajax({
				type: "POST",
				data:parametrosw,
				url:  "../base/getdatossqlSeg.php",
				success: function(data2){  
					grid_data=JSON.parse(data2);	
					jQuery.each(grid_data, function(clave, valor) { 

						laimagen="../../imagenes/menu/default.png";
						if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}
						if (c==1) {
							elcontenido="<div class=\"ginumero\" style=\"background-color:"+colores[i]+"\">"+valor.ORDEN+"</div>"+
							   "         <div class=\"gicontenido sigeaPrin\"><span class=\"gititulo\">"+valor.TITULO+"</span><br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></div>"+
							   "         <div class=\"giimagen\"><img src=\""+laimagen+"\" widht=60px; height=60px;></div>";					   
							   c++;
						}					
						else {
							elcontenido="<div class=\"giimagen\"><img src=\""+laimagen+"\" widht=60px; height=60px;></div>"+					   
							   "         <div class=\"gicontenido sigeaPrin\"><span class=\"gititulo\">"+valor.TITULO+"</span><br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></div>"+
							   "         <div class=\"ginumero\" style=\"background-color:"+colores[i]+"\">"+valor.ORDEN+"</div>";
							c=1;							   
						}
						$("#miguia").append("<div class=\"row fontRoboto\">"+
											"     <div class=\"col-sm-1\"></div>"+
											"     <div class=\"col-sm-10\">"+
											elcontenido+
											"     </div>"+
											"     <div class=\"col-sm-1\"></div>"+
											"</div>");
						i++;
					  
					});

					ocultarEspera("esperaInf"); 


				}
			});
	}


/*=======================================CIRCULAR 1=================================*/
	function infoCir1(){
		mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
		elsql2="select ORDEN,CONTENIDO,TITULO,"+
		"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+"  ORDER BY ORDEN";
					
		$("#miguia").empty();	
		$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
		parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
		c=1;			
		$.ajax({
				type: "POST",
				data:parametrosw,
				url:  "../base/getdatossqlSeg.php",
				success: function(data2){  
					grid_data=JSON.parse(data2);	
					c=0;
					jQuery.each(grid_data, function(clave, valor) { 

						laimagen="../../imagenes/menu/default.png";
						if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}

						elfondo=colores[c];

					
						if ((c%2)==0) {
						    imaD=""; imaI="<div class=\"giimagenCirI\" style=\"background-color:"+elfondo+"\"><img src=\""+laimagen+"\" widht=60px; height=60px;></div>"+
							              "<div class=\"giimagenCirPeqI\" style=\"color:"+elfondo+"\">"+valor.ORDEN+"</div>";
							tituloD="";
							tituloI="<div class=\"giconttitulo\"><div class=\"gititulo\" style=\"color:"+elfondo+"\">"+valor.TITULO+"</div><br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></div>";

						} else {
						
							lineas="<div class=\"line1\"></div><div class=\"line2\"></div>";
							if((c+1)==grid_data.length) {lineas="<div class=\"line1\"></div>";}
							imaI=""; imaD="<div class=\"giimagenCirD\" style=\"background-color:"+elfondo+"\"><img src=\""+laimagen+"\" widht=60px; height=60px;></div>"+
							              "<div class=\"giimagenCirPeqD\" style=\"color:"+elfondo+"\">"+valor.ORDEN+"</div>"+
										  lineas;
							tituloI="";
							tituloD="<div class=\"giconttitulo\"><div class=\"gititulo\" style=\"color:"+elfondo+"\">"+valor.TITULO+"</div><br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></div>";							
						}   
				
						$("#miguia").append("<div class=\"row fontRoboto\">"+
											"     <div class=\"col-sm-1\"></div>"+
											"     <div class=\"col-sm-3\">"+tituloI+"</div>"+
											"     <div class=\"col-sm-2\">"+imaI+"</div>"+
											"     <div class=\"col-sm-2\">"+imaD+"</div>"+
											"     <div class=\"col-sm-3\">"+tituloD+"</div>"+
											"     <div class=\"col-sm-1\"></div>"+
											"</div>");
					  
						c++;
					});

					ocultarEspera("esperaInf"); 


				}
			});
	}


	
/*=======================================CUADROS UNIDOS=================================*/
function infoCuadUnidos(){
	mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
	elsql2="select ORDEN,CONTENIDO,TITULO,"+
	"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+" ORDER BY ORDEN";
				
	$("#miguia").empty();	
	$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
	c=1;			
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				grid_data=JSON.parse(data2);	
				c=0;
				lin=1;
				jQuery.each(grid_data, function(clave, valor) { 

					laimagen="../../imagenes/menu/default.png";
					if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}

					elfondo=colores[c];
		

					if ((c%2)==0) {
						celda="lin_"+lin+"_1";					
					} else {
						celda="lin_"+lin+"_2";	
						lin++;								
					}   

					contenido="<div class=\"gicuadro sigeaPrin\" style=\"background-color:"+elfondo+"\">"+
					          "     <img src=\""+laimagen+"\" widht=60px; height=60px;><br><hr>"+
							  "     <span class=\"gititulo\" style=\"color:white;\">"+valor.TITULO+"</span><br>"+
							  "     <span class=\"gisubtitulo\" style=\"color:white;\" >"+valor.CONTENIDO+"</span><br>"+
							  "</div>"+
							  "<div class=\"ginumeroCuad\" style=\"color:"+elfondo+"\">"+valor.ORDEN+"</div>"							
							  ;
			
					$("#miguia").append("<div class=\"row fontRoboto\">"+
										"     <div class=\"col-sm-1\"></div>"+
										"     <div class=\"col-sm-5\" id=\"lin_"+lin+"_1\"></div>"+
										"     <div class=\"col-sm-5\" id=\"lin_"+lin+"_2\"></div>"+
										"     <div class=\"col-sm-1\"></div>"+
										"</div>");
					$("#"+celda).html(contenido);
				  
					c++; 
				});

				ocultarEspera("esperaInf"); 


			}
		});
}


	
/*=======================================CUADROS UNIDOS=================================*/
function infoCuadLinea(){
	mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
	elsql2="select ORDEN,CONTENIDO,TITULO,"+
	"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+" ORDER BY ORDEN";
				
	$("#miguia").empty();	
	$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
	c=1;			
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				grid_data=JSON.parse(data2);	
				jQuery.each(grid_data, function(clave, valor) { 

					laimagen="../../imagenes/menu/default.png";
					if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}

					elfondo=colores[c];
		
					contenido="<div class=\"gicuadrolin sigeaPrin\" style=\"background-color:"+elfondo+"\">"+
							  "     <div class=\"row center white gititulo\"> <img src=\""+laimagen+"\" widht=60px; height=60px;></div>"+
							  "     <div class=\"row center white gititulo\">"+valor.ORDEN+". "+valor.TITULO+"</div>"+
							  "     <div class=\"row center white gisubtitulo\">"+valor.CONTENIDO+"</div>"+
							  "</div>"							
							  ;
			
					$("#miguia").append("<div class=\"row fontRoboto\" style=\" margin: 0px;\">"+
										"     <div class=\"col-sm-1\"></div>"+
										"     <div class=\"col-sm-10\">"+contenido+"</div>"+
										"     <div class=\"col-sm-1\"></div>"+
										"</div>");					
				  
					c++; 
				});

				ocultarEspera("esperaInf"); 


			}
		});
}



/*=======================================LINEAS PUNTEADAS=================================*/

	function infoLineas1(){
		mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
		elsql2="select ORDEN,CONTENIDO,TITULO,"+
		"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+"  ORDER BY ORDEN";
					
		$("#miguia").empty();	
		$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1><br><br>");
		parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
		c=1;	
		top=-100;		
		$.ajax({
				type: "POST",
				data:parametrosw,
				url:  "../base/getdatossqlSeg.php",
				success: function(data2){  
					grid_data=JSON.parse(data2);	
					c=0;
					jQuery.each(grid_data, function(clave, valor) { 

						laimagen="../../imagenes/menu/default.png";
						if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}

						elfondo=colores[c];

					
						laimagen="";
					    if (!(valor.RUTA=='')) {laimagen= "<img style=\"cursor:pointer; height:100px; \" onclick=\"verImagen('"+valor.RUTA+"','"+valor.TITULO+"');\"  src=\""+valor.RUTA+"\">";}	

						if ((c%2)==0) {
						    imaD=laimagen; 
							estiloD="style=\"display:flex; text-align: left;align-items: center; justify-content: center;\"";
							estiloI="";
							imaI="<div class=\"giCuad_1I sigeaPrin\" id=\"CUADI_"+valor.ID+"\">"+							    
								 "    <div class=\"row center gititulo\">"+valor.TITULO+"</div>"+
								 "    <div class=\"row  gisubtitulo\">"+valor.CONTENIDO+"</div>"+
								 "</div>";
							tituloD="";
							tituloI="<div class=\"giconttitulo\"><div class=\"gititulo\" style=\"color:"+elfondo+"\">"+valor.TITULO+"</div><br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></div>";

						} else {												
							imaI=laimagen; 
							estiloI="style=\"display:flex; text-align: left;align-items: center; justify-content: center;\"";
							estiloD="";
							imaD="<div class=\"giCuad_1D sigeaPrin\" id=\"CUADI_"+valor.ID+"\">"+							    
							"    <div class=\"row center gititulo\">"+valor.TITULO+"</div>"+
							"    <div class=\"row  gisubtitulo\">"+valor.CONTENIDO+"</div>"+
							"</div>"; 
						}   
				
						$("#miguia").append("<div class=\"row fontRoboto\" style=\"font-size:30px; margin:0px;\">"+
											"     <div class=\"col-sm-1\"></div>"+
											"     <div class=\"col-sm-5\" "+estiloI+">"+imaI+"</div>"+
											"     <div class=\"col-sm-5\" "+estiloD+">"+imaD+"</div>"+											
											"     <div class=\"col-sm-1\"></div>"+
											"</div>");
					  
						

						var pos = $("#CUADI_"+valor.ID).offset(); 					  
						var an =  $("#CUADI_"+valor.ID).outerWidth();
						var ang =  $("#miguia").outerWidth();
						 
						console.log("x:"+pos.left+" an="+an+" y:"+pos.top+" r= "+(pos.top-10)+" and="+der);

						$("#miguia").append("<div class=\"giCuadPeq_1\" "+
											"     style=\"color:"+elfondo+"; left:calc(50%); border:2px solid "+colores[c]+"\">"+valor.ORDEN+
											"</div>");

						var der=0; 			
						if ((c%2)==0) {der=((ang/2)-an);} else {der=((ang/2)); }
						
						derV=((ang/2)-95);
						lineas="<div class=\"giLineaV_1\" style=\"left:"+derV+"px;\"></div>";

						$("#miguia").append("<div class=\"giLinea_1\" "+
											"     style=\"background-color:"+elfondo+"; left:"+der+"px; width:"+an+"px;\">"+
											"</div>"+lineas);

					
						top+=250;
						c++;
					});

				

					ocultarEspera("esperaInf"); 


				}
			});
		}

		

/*==========================LINEAL SIN CUADROS ====================================*/
function infoLinealSC(conimagen){
	mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
	elsql2="select ORDEN,CONTENIDO,TITULO,"+
	"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+" ORDER BY ORDEN";
				
	$("#miguia").empty();	
	$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
	c=0;			
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				grid_data=JSON.parse(data2);	
				jQuery.each(grid_data, function(clave, valor) { 

					
					laimagen="../../imagenes/menu/default.png";					
					if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}	
					apimagen="<div class=\"col-sm-1\"><div class=\"giimagenLSC\"><img src=\""+laimagen+"\" widht=60px; height=60px;></div></div>";
					if (!conimagen) {apimagen="";}
					
					
						
						elcontenido="<div class=\"row\">"+
						   "		     <div class=\"col-sm-1\"><div class=\"ginumeroLSC\" style=\"background-color:"+colores[c]+"\">"+valor.ORDEN+"</div></div>"+
						   "             <div class=\"col-sm-10\"><span class=\"gicontenidoLSC gititulo sigeaPrin\">"+valor.TITULO+"<br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></span></div>"+
						   apimagen+						   
						   "         </div>";					   						
				
					$("#miguia").append("<div class=\"row fontRoboto\">"+
										"     <div class=\"col-sm-1\"></div>"+
										"     <div class=\"col-sm-10\" style=\"font-size:20px;\">"+
										elcontenido+
										"     </div>"+
										"     <div class=\"col-sm-1\"></div>"+
										"</div>");
										c++;
				});
				
				ocultarEspera("esperaInf"); 
			}
		});
}




/*=======================================NUMEROS ENLAZADOS=================================*/

function infoLinealEnlazado(){
	mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
	elsql2="select ORDEN,CONTENIDO,TITULO,"+
	"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+"  ORDER BY ORDEN";
				
	$("#miguia").empty();	
	$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1><br><br>");
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
	c=1;	
	topV=0;

	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				grid_data=JSON.parse(data2);	
				c=0;
				lin=1;
				jQuery.each(grid_data, function(clave, valor) { 

					laimagen="../../imagenes/menu/default.png";
					if (!(valor.RUTA=='')) {laimagen=valor.RUTA;}

					elfondo=colores[c];

			
					contenido="<div class=\"giCuadro_LIE sigeaPrin\" style=\"background-color:"+elfondo+"\" id=\"CUADI_"+valor.ID+"\">"+							    
						 "    <div class=\"row  gititulo\">"+valor.TITULO+"</div>"+
						 "    <div class=\"row  gisubtitulo\">"+valor.CONTENIDO+"</div>"+
						 "</div>";

				
					elnumero="<div class=\"giCuadPeq_LIE\" id=\"NUMLIE_"+valor.ID+"\" "+
							"     style=\"color:"+elfondo+"; border:2px solid "+colores[c]+"\">"+valor.ORDEN+
							"</div>";

							
			
					$("#miguia").append("<div class=\"row fontRoboto\" style=\"font-size:30px; margin:0px;\">"+
										"     <div class=\"col-sm-1\"></div>"+
										"     <div class=\"col-sm-1 contNum_LIE\">"+elnumero+"</div>"+
										"     <div class=\"col-sm-9\">"+contenido+"</div>"+											
										"     <div class=\"col-sm-1\"></div>"+
										"</div>");
				  
					

					var posCon  = $("#CUADI_"+valor.ID).offset(); 
					var conAn  = $("#CUADI_"+valor.ID).outerWidth(); 
					var conAl  = $("#CUADI_"+valor.ID).outerHeight(); 
					var posNum = $("#NUMLIE_"+valor.ID).offset(); 
					var numAn = $("#NUMLIE_"+valor.ID).outerWidth(); 
					var numAl = $("#NUMLIE_"+valor.ID).outerHeight(); 					  
					var an =  $("#CUADI_"+valor.ID).outerWidth();
					var ang =  $("#miguia").outerWidth();
					 
					
					//console.log("x:"+posNum.left+" al="+numAl+" y:"+posNum.top+" r= "+(posCon.top-10)+" and="+der);

					var der=0; 			
					if ((c%2)==0) {der=((ang/2)-an);} else {der=((ang/2)); }
					
					derV=(posNum.left+(numAn/2));
					topV=((posNum.top*lin)+numAl);
					altoV=(conAl-(numAn/2)-5);
					
					lineas="<div class=\"giLineaV_LIE\" style=\"left:"+derV+"px; top:"+topV+"px; height:"+altoV+"px;\" ></div>";			
					if (lin<grid_data.length) {$("#miguia").append(lineas);}

					derV=(posNum.left+(numAn));
					topV=((posNum.top*lin)+(numAl/2)+(lin*3));
					anchoV=(posCon.left-posNum.left+numAn);
					console.log(anchoV);
					lineas="<div class=\"giLineaH_LIE\" style=\"left:"+derV+"px; top:"+topV+"px; width:"+anchoV+"px;\" ></div>";			
					$("#miguia").append(lineas);
					
					c++;
					lin++;
				});

			

				ocultarEspera("esperaInf"); 


			}
		});
	}
/*=================================LINEAL IMAGENES GRADES ======================================*/

function infoLinealImagenes(){
	mostrarEspera("esperaInf","grid_guia","Cargando Guías...");
	elsql2="select ORDEN,CONTENIDO,TITULO,"+
	"ifnull((SELECT RUTA FROM eadjico b WHERE b.AUX=a.ID),'') as RUTA from guiasrapcon a where IDGUIA="+elid+" ORDER BY ORDEN";
				
	$("#miguia").empty();	
	$("#miguia").append("<h1 class=\"fontRobotoB\">"+tit+"</h1>");
	parametrosw={sql:elsql2,dato:sessionStorage.co,bd:"Mysql"}	
	c=0;			
	$.ajax({
			type: "POST",
			data:parametrosw,
			url:  "../base/getdatossqlSeg.php",
			success: function(data2){  
				grid_data=JSON.parse(data2);	
				jQuery.each(grid_data, function(clave, valor) { 

					laimagen=""; numcol="10";
					if (!(valor.RUTA=='')) {laimagen="<div class=\"col-sm-2\" style=\"font-size:20px;\">"+
						                             "      <img style=\"cursor:pointer;\" onclick=\"verImagen('"+valor.RUTA+"','"+valor.TITULO+"');\" class=\"giimagen_LIIM\" src=\""+valor.RUTA+"\">"+
						                             "</div>";
											numcol=8;}	
						
						elcontenido="<div class=\"ginumero_LIIM\" style=\"background-color:"+colores[c]+"\">"+valor.ORDEN+"</div>"+
						   "         <span class=\"gicontenido_LIIM gititulo sigeaPrin\">"+valor.TITULO+"<br><span class=\"gisubtitulo\">"+valor.CONTENIDO+"</span></span>";						   ;					   						
				
					$("#miguia").append("<div class=\"row fontRoboto\">"+
										"     <div class=\"col-sm-1\"></div>"+
										"     <div class=\"col-sm-"+numcol+"\" style=\"font-size:20px;\">"+
										          elcontenido+										
										"     </div>"+										
										           laimagen+										
										"     <div class=\"col-sm-1\"></div>"+
										"</div>");
										c++;
				  
				});
				ocultarEspera("esperaInf"); 
			}
		});
}




/*===========================================================================*/

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



function bajarInfo(canvasId, filename) {

    var domElement = document.getElementById(canvasId);	
	html2canvas(domElement).then(function(canvas) {
		saveAs(canvas.toDataURL(), 'info.png');

	});

}		


function verImagen(src, titulo) {

	dameVentana("ventanaFoto", "grid_guia",titulo,"lg","bg-successs","fa fa-book blue bigger-180","250");
	$("#body_ventanaFoto").append("<img class=\"imagen_grande\"src=\""+src+"\"></img>");

}




