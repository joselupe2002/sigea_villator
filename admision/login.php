<?php header('Content-Type: text/html; charset=UTF-8'); 
	include("../includes/Conexion.php");
	include("../includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	session_start();		
	$_SESSION['usuario'] = "ASPIRANTES";
	$_SESSION['nombre'] = "registro de aspirantes";
	$_SESSION['super'] = "N";
	$_SESSION['inicio'] = 1;
	$_SESSION['INSTITUCION'] = "ITSP";
	$_SESSION['CAMPUS'] = "0";
	$_SESSION['encode'] = "ISO-8859-1";
	$_SESSION['carrera'] = "1";
	$_SESSION['depto'] = "0";
	$_SESSION['titApli'] = "Sistema Gesti&oacute;n Escolar - Administrativa";
	$_SESSION['bd'] = "Mysql";

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<meta http-equiv="Expires" content="0" />
        <meta http-equiv="Pragma" content="no-cache" />
		<link rel="icon" type="image/gif" href="imagenes/login/sigea.ico">
		<title>Sistema de Gesti&oacute;n Escolar</title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../assets/css/select2.min.css" />
		<link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />
	    <link rel="stylesheet" href="../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />		
		<link rel="stylesheet" href="../css/sigea.css" />	
		<script src="../assets/js/ace-extra.min.js"></script>		
		<link rel="stylesheet" href="../assets/css/jquery-ui.min.css" />

	</head>
	<?php  //include("./includes/Seguridad.php"); $miSeg = new Seguridad();  echo $miSeg->encriptar(""); ?>
	<body class="login-layout light-login">

	<?php 
		$miConex = new Conexion();
		$resultado=$miConex->getConsulta("SQLite","SELECT * from INSTITUCIONES where inst_clave='".$_SESSION["INSTITUCION"]."'");
		foreach ($resultado as $row) {$titulo= $row["inst_tituloasp"]; }		
	?>

	<div style="height:10px; background-color: #040E5A;"> </div>
	<div class="container-fluid informacion" style="background-color: #DBEEEA;">   
         <div class="row">
             <div class="col-md-4" >
                   <img src="../imagenes/empresa/logo2.png" alt="" width="90px" class="img-fluid" alt="Responsive image" />  
			  </div>
			  <div class="col-md-4" >
				   <div class="text-success" style="padding:0px;  font-size:35px; font-family:'Girassol'; color:#1728A3; text-align:center; font-weight: bold;">
						  <?php echo $titulo ?>
				    </div>				   
			  </div>
              <div class="col-md-4" style="padding-top: 20px; text-align: right;">
			  </div>
        </div>
    </div>
	<div style="height:10px; background-color: #040E5A;"> 
	 </div>

		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
						
							</div>
							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box  no-border">
									<div class="widget-body ">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-lock green"></i>
												Datos de acceso
											</h4>

											<div class="space-6"></div>

											<form>
												<fieldset>
													<label class="block clearfix"></label>
														<span class="block input-icon input-icon-right">
															<input name="login"  id="login" type="text"  autocomplete="off" class="form-control" placeholder="CURP" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													

													<label class="block clearfix"></label>
														<span class="block input-icon input-icon-right">
															<input name="password" id="password"  autocomplete="off" type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													

													<div class="space"></div>

													<div class="clearfix">
														
														<button type="button" id="btnLogin" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110" >Ingresar</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

									
											
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													Recordar clave
												</a>
											</div>

											<div>
												
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Recordar Password
											</h4>

											<div class="space-6"></div>
											<p>
												Ingrese su CURP
											</p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="correo_re" id="correo_re" type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button id="enviarClave" type="button" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Enviar</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Regresar
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												Nuevo Usuario
											</h4>
											
																	
											<form name="frmReg" id="frmReg"  method="get">
												<fieldset>
												<div class="form-group">
												   <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="name" id="name" type="text" class="form-control" placeholder="Nombre" />
															<i class="ace-icon fa fa-users"></i>
														</span>
													</label>
													
												</div>
												<div class="form-group">													
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="correo" id="correo"  type="text" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>
													
											    </div>
												<div class="form-group">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="tel" id="tel"  type="text" class="form-control" placeholder="Celular" />
															<i class="ace-icon fa fa-phone"></i>
														</span>
													</label>
													
												</div>
                                                <div class="form-group">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="pass" id="pass" type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
													
												</div>
												<div class="form-group">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="pass2" id="pass2"  type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>
												</div>
                                                <div class="form-group">
													<label class="block">
														<input type="checkbox" class="ace" id="licencia" name="licencia"/>
														<span class="lbl">
															Acepto
															<a href="terminos.pdf" target="_blank">Licencia</a>
														</span>
													</label>
												</div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Limpiar</span>
														</button>

														<button id ="btnRegistrar" type="submit" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Registrar</span>
															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Regresar a Login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

						
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

	<?php include 'pie.php'?>


<div id="dialogError" class="hide"></div>
<div id="dialogEspera" class="hide"></div>
										
<script src="../assets/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
	   if('ontouchstart' in document.documentElement) document.write("<script src='../assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/ace-elements.min.js"></script>
<script src="../assets/js/ace.min.js"></script>
<script src="../assets/js/wizard.min.js"></script>
<script src="../assets/js/jquery.validate.min.js"></script>
<script src="../assets/js/select2.min.js"></script>
<script src="../ssets/js/ace-elements.min.js"></script>		
<script src="../assets/js/jquery-additional-methods.min.js"></script>
<script src="../assets/js/bootbox.js"></script>
<script src="../assets/js/jquery.maskedinput.min.js"></script>
<script src="../assets/js/jquery-ui.min.js"></script>
<script src="../assets/js/jquery.ui.touch-punch.min.js"></script>


		
<script type="text/javascript">


function ingresar() {
	var parametros = {
            "login" : $('#login').val(),
            "password" : $('#password').val()
    };
	 $.ajax({
		 data:  parametros,
         type: "POST",
         url: "accesoAsp.php",
         success: function(response)
         {							        
	         if (!(response==1)) {
		        	 $("#dialogError").html(response);
		        	 var dialog = $( "#dialogError" ).removeClass('hide').dialog({
							modal: true,
							title: "Error",
							title_html: true,
							buttons: [ 								
								{
									text: "OK",
									"class" : "btn btn-primary btn-minier",
									click: function() {
										$( this ).dialog( "close" ); 
									} 
								}
							]
						});
	         }
	         else 
	         {window.location.href = "principalAsp.php";}

         }
 }); 

}

			
			jQuery(function($) {

				$("#password").keypress(function(e) {
			        if(e.which == 13) {
			        	ingresar();
			        }
			      });
			      

				 $(document).on('click', '.toolbar a[data-target]', function(e) {
						e.preventDefault();
						var target = $(this).data('target');
						$('.widget-box.visible').removeClass('visible');//hide others
						$(target).addClass('visible');//show target
					 });		
				 
				//================================INGRESO AL SISTEMA =========================================
			   $('#btnLogin').on('click', function(e) {
						ingresar();   					
						e.preventDefault();
					 });

			   //================================RECORDAR CONTRASE&Ntilde;A  =========================================
			   $('#enviarClave').on('click', function(e) {
				   var parametros = {			              
			                "curp" : $('#correo_re').val()               
			            };
		            $("#dialogEspera").html("<img src=\"../imagenes/menu/esperar.gif\" style=\"background: transparent; width: 60px; height: 60px\">"+
				            "<span class=\"red\">Procesando...</span>");
		        	 var dialog = $( "#dialogEspera" ).removeClass('hide').dialog({
		        		    open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
							modal: true
						});
				
		            $.ajax({
		                type: "POST",
		                url:"enviaClave.php",
		                data: parametros,
		                success: function(data){	
							$("#dialogEspera").dialog( "close" );						
							alert ("Resultado: "+data);	     
							window.open('login.php','_self');           	
		                }
		            });

					e.preventDefault();
				 });


			   //================================REGISTRO DE NUEVO USUARIO  =========================================

				 $("#frmReg").validate({				    	
					    errorElement: 'div',
						errorClass: 'help-block',
						focusInvalid: false,
						ignore: "",
				        rules: {
				            name: { required: true, minlength: 2},	
				            tel: {  number: true, minlength: 8, maxlength: 10},
				            correo: { required:true, email: true, remote: {url: "verificaUser.php", type: "get"}},
				            pass: { required: true, minlength: 6},
				            pass2:{ required: true, minlength: 6, equalTo: "#pass"}
				        },
				        messages: {
				            name: "Debe introducir su nombre",
				            tel: {
								required: 'Tel&eacute;fono Requerido',
								number: 'Sol&oacute; se permite n&uacute;meros',
								maxlength: 'Longitud M&aacute;xima: 10',
								minlength: 'L&oacute;ngitud M&iacute;nima: 8'
							 },	
				            correo:   {  
		                        required: "Debe introducir un email v&aacute;lido.",  
		                        remote:   "Ya existe un usuario con este correo electr&oacute;nico registrado"  
		                     },  
				            pass:  {  
		                        required: "Campo requerido: Password",  
		                        minlength:   "L&oacute;ngitud M&iacute;nima: 6"  
		                     },    
		                    pass2:  {  
		                         required: "Campo requerido: Confirmaci&oacute;n",  
		                         minlength:   "L&oacute;ngitud M&iacute;nima: 6",
		                         equalTo:   "Las contrase&ntilde;as no coinciden" 
		                      },  
				        },

				    	highlight: function (e) {
							$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
						},
				
						success: function (e) {
							$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
							$(e).remove();
						},
				
						errorPlacement: function (error, element) {
			                  error.insertAfter(element.parent());
					    },
					
				        submitHandler: function(form){	

				        	var condiciones = $("#licencia").is(":checked");
				            if (!condiciones) {
				                alert("Debe aceptar las condiciones para registrarse");				              
				            }
				            else {
				            
					        	 $("#dialogEspera").html("<img src=\"imagenes/menu/espere.gif\" style=\"background: transparent; width: 60px; height: 60px\">"+
						            "<span class=\"red\">Procesando...</span>");
				        	      var dialog = $( "#dialogEspera" ).removeClass('hide').dialog({
				        		       open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
									   modal: true
								  });

                              
								          
						            var parametros = {
							                "name" : $('#name').val(),
							                "correo" : $('#correo').val(),
							                "tel" : $('#tel').val(),
							                "pass" : $('#pass').val(),
							                "pag":"prin",
							                "USUA_CREDITO":1,
							                "idTorneo":"0",
							                "usuario" : $('#correo').val()	                
							            };
						            $.ajax({
						                type: "POST",
						                url:"guardaUser.php",
						                data: parametros,
						                success: function(data){
							                if (data=="") {
							                	$("#dialogEspera").dialog( "close" );
							                	alert("Tu registro esta en proceso se ha enviado un correo electr&oacute;nico para confirmar tu correo (Si no aparece en bandeja de entrada, favor de checar en correos no deseados o spam)");							        	                      
				                                location.href ="index.php";                                
								                } 
							                else {
							                  alert (data);  
						                    }			
						                }
						            });
				            
				       			}//else de las condiciones
				        } //cierre del submit del form
				    });
		
				 			
			});
		</script>
	</body>
</html>
