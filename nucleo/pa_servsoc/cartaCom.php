
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
			var $prestador="";
   	       
   	      
        
   	
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

				$nombre=$miutil->getJefe('303');//Nombre del puesto DECONTRL ESCOLAR
                $this->SetFont('Montserrat-ExtraBold','B',11);

				$this->SetY(-65);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(0,0,'CONFORMIDAD',0,1,'C');

				$this->SetFont('Montserrat-ExtraBold','B',10);
                $this->setY(-50);
                $this->Cell(0,15,$this->prestador,0,1,'C');
				$this->setY(-40);
				$this->SetFont('Montserrat-Medium','',10);
				$this->Cell(0,5,"Nombre y firma del Prestador del Servicio Social",0,1,'C');
				
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
		$pdf->Cell(0,3,utf8_decode("CARTA COMPROMISO DE SERVICIO SOCIAL"),"",1,'C',false);
		$pdf->Cell(0,5,utf8_decode("DEPARTAMENTO DE VINCULACIÓN"),"",1,'C',false);
	
		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-Medium','B',10);
		$pdf->MultiCell(170,5,utf8_decode("Con el fin de dar cumplimiento con lo establecido en la Ley Reglamentaria del Artículo 5º Constitucional relativo al ejercicio de profesiones, el suscrito:"),"",'J',false);
	
		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-Medium','B',9);
		$pdf->Cell(70,5,utf8_decode("Nombre del Prestador del Servicio Social:"),"",0,'L',false);
		$pdf->Cell(100,5,utf8_decode($data[0]["NOMBRE"]),"B",1,'L',false);
		$pdf->prestador=$data[0]["NOMBRE"];
		$pdf->Ln(5);
		$pdf->Cell(30,5,utf8_decode("No. de Control:"),"",0,'L',false);
		$pdf->Cell(30,5,utf8_decode($data[0]["MATRICULA"]),"B",0,'L',false);
		$pdf->Cell(20,3,utf8_decode("Domicilio:"),"",0,'L',false);
		$pdf->MultiCell(90,5,utf8_decode($data[0]["DIRECCION"]),"B",'L',false);
		$pdf->Ln(5);

		$pdf->Cell(23,5,utf8_decode("Teléfono:"),"",0,'L',false);
		$pdf->Cell(23,5,utf8_decode($data[0]["TELEFONO"]),"B",0,'L',false);
		$pdf->Cell(20,5,utf8_decode("Semestre:"),"",0,'L',false);
		$pdf->Cell(10,5,utf8_decode($data[0]["PERIODOS_INS"]),"B",0,'L',false);
		$pdf->Cell(17,5,utf8_decode("Carrera:"),"",0,'L',false);
		$pdf->MultiCell(77,5,utf8_decode($data[0]["CARRERAD"]),"B",'L',false);

		$pdf->Ln(5);
		$pdf->Cell(50,5,utf8_decode("Dependencia u organismo:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["EMPRESA"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(50,5,utf8_decode("Domicilio de la dependencia:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["DIREMPRESA"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(50,5,utf8_decode("Responsable del programa:"),"",0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["REPRESENTANTE"]),"B",0,'L',false);
		$pdf->Ln(5);
		$pdf->Cell(30,5,utf8_decode("Fecha de Inicio:"),"",0,'L',false);
		$pdf->Cell(45,5,utf8_decode($data[0]["INICIO"]),"B",0,'L',false);
		$pdf->Cell(45,5,utf8_decode("Fecha de Terminación:"),"",0,'L',false);
		$pdf->Cell(50,5,utf8_decode($data[0]["TERMINO"]),"B",0,'L',false);

		$pdf->Ln(10);
		$pdf->SetFont('Montserrat-Medium','B',10);
		$pdf->MultiCell(170,5,utf8_decode("Me comprometo a realizar el Servicio Social acatando el reglamento emitido por el Tecnológico Nacional de México y llevarlo a cabo en el lugar y periodos manifestados, así como, a participar con mis conocimientos e iniciativa en las actividades que desempeñe, procurando dar una imagen positiva del Instituto en el Organismo o Dependencia oficial, de no hacerlo así, quedo enterado(a) de la cancelación respectiva, la cual procederá automáticamente. "),"",'J',false);
		
		$miutil = new UtilUser();
		$fechadecof=$miutil->formatFecha($data[0]["FECHACOM"]);
		$fechapie=date("d", strtotime($fechadecof))." de ".strtolower($miutil->getMesLetra(date("m", strtotime($fechadecof))))." del ". date("Y", strtotime($fechadecof));

		$pdf->Ln(5);
		$pdf->MultiCell(170,5,utf8_decode("En la ciudad de Santa María del Oro, El Oro, Dgo.,".
		"de la fecha ".$fechapie),"",'J',false);
		
		

		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
