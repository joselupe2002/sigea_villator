
<div class='space-20'></div>
<?php 
		$miConex = new Conexion();
		$resultadoA=$miConex->getConsulta("SQLite","SELECT * from INSTITUCIONES where inst_clave='".$_SESSION["INSTITUCION"]."'");
		foreach ($resultadoA as $rowA) {
			$facebook= $rowA["inst_facebook"]; 
			$twitter= $rowA["inst_twitter"]; 
			$instagram= $rowA["inst_instagram"]; 
			$razon =$rowA["inst_razon"]; 
			$telsoporte= $rowA["inst_telsoporte"]; 
			$correosoporte= $rowA["inst_correosoporte"]; 
			$direccion= $rowA["inst_direccion"]; 
			$fechaof= $rowA["inst_fechaof"]; 
		}		
?>

<div style="height:5px; background-color:#DBEEEA;"> </div>
<div class="container-fluid informacion" >   
		 <div class="row" style="background-color: #040E5A;">
		     <div class="col-md-2" > </div>
             <div class="col-md-3" > 
			    <div class='space-8'></div>
			    <div class="row"> 
					<div class="col-md-12"> 
						 <span style="color:#9E9494; font-weight: bold;"> REDES SOCIALES</span>
					</div>
				 </div>
				 <div class="row"> 
				    <div class="col-md-12"> 
						 <a href="<?php echo $facebook; ?>" target="_blank">
						 <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
						 <span style="color:white; font-weight: bold;"> Facebook</span></a>
                    </div>
				 </div>
				 <div class="row"> 
				    <div class="col-md-12"> 
						 <a href="<?php echo $twitter; ?>" target="_blank">
						 <i class="ace-icon fa fa-twitter-square text-primary bigger-150"></i>
						 <span style="color:white; font-weight: bold;"> Twitter</span></a>
                    </div>
				 </div>

				 <div class="row"> 
				    <div class="col-md-12"> 
						 <a href="<?php echo $instagram;?>" target="_blank">
						 <i class="ace-icon fa fa-instagram text-primary bigger-150"></i>
						 <span style="color:white; font-weight: bold;"> Instagram</span></a>
                    </div>
				 </div>

             </div>
			  <div class="col-md-3" >
					<div class='space-8'></div>
					<div class="row"> 
							<div class="col-md-12"> 
								<span style="color:#9E9494; font-weight: bold;"> CONTACTO / SOPORTE</span>
							</div>
					</div>
					<div class="row"> 
							<div class="col-md-12"> 
							    <i class="ace-icon fa fa-mobile white bigger-150"></i>
								<span style="color:white;"><?php echo $telsoporte; ?></span> <br/>
								<i class="ace-icon fa fa-envelope-o white bigger-150"></i>
								<span style="color:white;"><?php echo $correosoporte; ?></span>
							</div>
					</div>				
			  </div>				

			  <div class="col-md-4" >
					<div class='space-8'></div>
					<div class="row"> 
							<div class="col-md-12"> 
								<span style="color:#9E9494; font-weight: bold;"><?php echo $razon; ?></span>
							</div>
					</div>
					<div class="row"> 
							<div class="col-md-12"> 
								<i class="ace-icon fa fa-map-marker green bigger-150"></i>
								<span style="color:white; font-weight: bold;"> <?php echo $direccion; ?></span>
						    </div>
					</div>
					<div class="row"> 
							<div class="col-md-12"> 								
								<span style="color:white; font-weight: bold;"> <?php echo $fechaof; ?></span>
						    </div>
					</div>
			  </div>

			  <div class="col-md-1" style="padding-top: 20px; text-align: right;">
			    
			  </div>
        </div>
</html>
