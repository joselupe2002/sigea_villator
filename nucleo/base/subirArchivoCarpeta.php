<?php
session_start();
if ($_SESSION['inicio']==1) {   
	if ($_FILES[$_GET['inputFile']]["error"] > 0)
			{
				echo "0|Ocurrio un error al subir el archivo (Extensiones png, bmp, jpg, pdf menos de 1 MB)";
		}
		else
		{      

			    $name=$_FILES[$_GET['inputFile']]['name'];
				if (isset ($_GET['cambianombre'])) {
					$name=$_GET["cambianombre"].".".end(explode(".", $name));
				}	
				else {
					
                    if (strlen($name)>40) {$name=substr($name,(strlen($name)-39),strlen($name));}
				}

				if (($_GET['imganterior'])!="") {	      				
					if (file_exists ($_GET['carpeta'].$_GET['imganterior'])) {
						unlink($_GET['carpeta'].$_GET['imganterior']);
					}						
				}

					
				if (!(move_uploaded_file($_FILES[$_GET['inputFile']]['tmp_name'],
						$_GET['carpeta']. $name)))
				      { echo "0|el archivo no se pudo mover a:".$_GET['carpeta'];}	
				
				      else {echo "1|".$name;}

				
                
			}

}// el del si hay sesion
else
{header("Location: index.php");}
	

				