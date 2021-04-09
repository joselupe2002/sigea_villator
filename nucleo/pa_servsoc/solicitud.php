
<?php session_start(); if (($_SESSION['inicio']==1)) {
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	require('../../fpdf/fpdf.php');
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";
	$nivel="../../";


	
   class PDF extends FPDF {
   	
   	/*========================================================================================================*/
   	var $widths;
   	var $aligns;
   	
   	function SetWidths($w) {$this->widths=$w;}
   	
   	function SetAligns($a) {$this->aligns=$a;}
   	
   	function Row($data)
   	{
   		//Calculate the height of the row
   		$nb=0;
   		for($i=0;$i<count($data);$i++)
   			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
   			$h=5*$nb;
   			//Issue a page break first if needed
   			$this->CheckPageBreak($h);
   			//Draw the cells of the row
   			for($i=0;$i<count($data);$i++)
   			{
   				$w=$this->widths[$i];
   				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
   				//Save the current position
   				$x=$this->GetX();
   				$y=$this->GetY();
   				//Draw the border
   				$this->Rect($x,$y,$w,$h);
   				//Print the text
   				$this->MultiCell($w,4,$data[$i],0,$a);
   				//Put the position to the right of the cell
   				$this->SetXY($x+$w,$y);
   			}
   			//Go to the next line
   			$this->Ln($h);
   	}
   	
   	function CheckPageBreak($h)
   	{
   		//If the height h would cause an overflow, add a new page immediately
   		if($this->GetY()+$h>$this->PageBreakTrigger)
   			$this->AddPage($this->CurOrientation);
   	}
   	
   	function NbLines($w,$txt)
   	{
   		//Computes the number of lines a MultiCell of width w will take
   		$cw=&$this->CurrentFont['cw'];
   		if($w==0)
   			$w=$this->w-$this->rMargin-$this->x;
   			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
   			$s=str_replace("\r",'',$txt);
   			$nb=strlen($s);
   			if($nb>0 and $s[$nb-1]=="\n")
   				$nb--;
   				$sep=-1;
   				$i=0;
   				$j=0;
   				$l=0;
   				$nl=1;
   				while($i<$nb)
   				{
   					$c=$s[$i];
   					if($c=="\n")
   					{
   						$i++;
   						$sep=-1;
   						$j=$i;
   						$l=0;
   						$nl++;
   						continue;
   					}
   					if($c==' ')
   						$sep=$i;
   						$l+=$cw[$c];
   						if($l>$wmax)
   						{
   							if($sep==-1)
   							{
   								if($i==$j)
   									$i++;
   							}
   							else
   								$i=$sep+1;
   								$sep=-1;
   								$j=$i;
   								$l=0;
   								$nl++;
   						}
   						else
   							$i++;
   				}
   				return $nl;
   	}
   	
   	/*========================================================================================================*/
   	        
   	     var $eljefe="";
   	       
   	      
        
   	
			function LoadData()
			{				
				
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"select * from vss_alumnos a where  ID=".$_GET["id"]);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


	
			function LoadDatosGen()
			{
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta("SQLite","SELECT * from INSTITUCIONES where _INSTITUCION='".$_SESSION['INSTITUCION']."'");
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
			
			function Header()
			{
				$miutil = new UtilUser();
				$miutil->getEncabezado($this,'V');	
				
				//Para cuando las tablas estas no se coirten 
				$this->SetX(20);
				$this->Ln(5);
			}

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'V');
				
			}
		
			
   }
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Times','',10);
		$pdf->SetMargins(20, 25 , 25);
		$pdf->SetAutoPageBreak(true,25); 
		$pdf->AddPage();
		
		
		$pdf->SetY(50);
		$data = $pdf->LoadData();
		
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,3,utf8_decode("SOLICITUD DE SERVICIO SOCIAL"),"",1,'C',false);
		$pdf->Cell(0,5,utf8_decode("DEPARTAMENTO VINCULACIÓN"),"",1,'C',false);

		$pdf->Cell(0,5,utf8_decode("DATOS PERSONALES"),"",1,'L',false);

		$pdf->SetFont('Montserrat-Medium','B',10);	
		$pdf->Cell(40,5,utf8_decode("Nombre Completo"),"",0,'L',false);
		$pdf->Cell(130,5,utf8_decode($data[0]["NOMBRE"]),"B",1,'L',false);
		$pdf->Ln(5);	
	
		$pdf->Cell(20,5,utf8_decode("Sexo:"),"",0,'L',false);
		$pdf->Cell(10,5,utf8_decode($data[0]["SEXO"]),"B",0,'L',false);
		$pdf->Cell(20,5,utf8_decode("Teléfono:"),"",0,'L',false);
		$pdf->Cell(30,5,utf8_decode($data[0]["TELEFONO"]),"B",0,'L',false);
		$pdf->Cell(20,5,utf8_decode("Domicilio:"),"",0,'L',false);
		$pdf->MultiCell(70,5,utf8_decode($data[0]["DIRECCION"]),"B",'L',false);
		$pdf->Ln(5);
		$pdf->Cell(20,5,utf8_decode("E-mail:"),"",0,'L',false);
		$pdf->Cell(150,5,utf8_decode($data[0]["CORREO"]),"B",0,'L',false);

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,5,utf8_decode("ESCOLARIDAD"),"",1,'L',false);
		$pdf->SetFont('Montserrat-Medium','',10);

		$pdf->Cell(30,5,utf8_decode("No. de Control:"),"",0,'L',false);
		$pdf->Cell(30,5,utf8_decode($data[0]["MATRICULA"]),"B",0,'L',false);
		$pdf->Cell(20,5,utf8_decode("Carrera:"),"",0,'L',false);
		$pdf->Cell(90,5,utf8_decode($data[0]["CARRERAD"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(30,5,utf8_decode("Periodo:"),"",0,'L',false);
		$pdf->Cell(30,5,utf8_decode($data[0]["CICLOD"]),"B",0,'L',false);
		$pdf->Cell(20,5,utf8_decode("Semestre:"),"",0,'L',false);
		$pdf->Cell(90,5,utf8_decode($data[0]["PERIODOS_INS"]),"B",0,'L',false);

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,5,utf8_decode("DATOS DEL PROGRAMA"),"",1,'L',false);
		$pdf->SetFont('Montserrat-Medium','',10);

		$pdf->Cell(50,5,utf8_decode("Dependencia Oficial:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["EMPRESA"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(50,5,utf8_decode("Titular de la Dependencia:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["REPRESENTANTE"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(50,5,utf8_decode("Cargo del Titular:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["PUESTO"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(50,5,utf8_decode("Nombre del Programa:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["PROGRAMA"]),"B",0,'L',false);
		
		$pdf->Ln(5);
		$pdf->Cell(20,5,utf8_decode("Modalidad:"),"",0,'L',false);
		$pdf->Cell(80,5,utf8_decode($data[0]["MODALIDADSSD"]),"B",0,'L',false);
		$pdf->Cell(45,5,utf8_decode("Fecha de Inicio:"),"",0,'L',false);
		$pdf->Cell(25,5,utf8_decode($data[0]["INICIO"]),"B",0,'L',false);

		$pdf->Ln(5);
		$pdf->Cell(20,5,utf8_decode("Lugar:"),"",0,'L',false);
		$pdf->Cell(80,5,utf8_decode($data[0]["MUNICIPIOD"]." ".$data[0]["ESTADOD"]),"B",0,'L',false);

		$pdf->Cell(45,5,utf8_decode("Fecha de Terminación:"),"",0,'L',false);
		$pdf->Cell(25,5,utf8_decode($data[0]["TERMINO"]),"B",0,'L',false);


		$pdf->Ln(10);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);		
		$pdf->Cell(0,5,utf8_decode("ACTIVIDADES"),"",1,'L',false);
		$pdf->SetFont('Montserrat-Medium','',10);

		$pdf->MultiCell(170,5,utf8_decode($data[0]["ACTIVIDADES"]),"",'L',false);

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,5,utf8_decode("TIPO DE PROGRAMA O PROYECTO"),"",1,'L',false);
		$pdf->SetFont('Montserrat-Medium','',10);
		$pdf->MultiCell(70,5,utf8_decode($data[0]["TIPOPROGD"]),"",'L',false);

		


		$pdf->Cell(60,5,utf8_decode("En caso de elegir otro, especifique:"),"",1,'L',false);
		$pdf->MultiCell(110,5,utf8_decode($data[0]["TIPOPROGADD"]),"",'L',false);


		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,5,utf8_decode("FIRMA DE (LA PRESTADOR (S): _____________________________"),"",1,'L',false);
		$pdf->SetFont('Montserrat-Medium','',7);

		$pdf->MultiCell(170,3,utf8_decode("Plasmo mi firma, con lo que hago constar que conforme a mis intereses personales y de desarrollo profesional, es mi decisión realizar la prestación del servicio en el lugar anteriormente mencionado, bajo mi responsabilidad y estoy consciente de que debo cuidar el protocolo activo de Seguridad Sanitaria (PASSA-IMSS) debido a la Pandemia actual y cuyo cuidado y seguimiento me corresponde a mi como estudiante. "),"",'J',false);
		
		


		$pdf->Ln(5);
		$pdf->Cell(60,5,utf8_decode("PARA USO EXCLUSIVO DE LA OFICINA DE SERVICIO SOCIAL"),"",1,'L',false);
		$pdf->Cell(60,5,utf8_decode("ACEPTADO:    SI (  )   NO ( )  MOTIVO:  "),"",1,'L',false);
		$pdf->Cell(60,5,utf8_decode("OBSERVACIONES"),"",1,'L',false);

		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
