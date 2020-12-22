
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
   	       
			function LoadDatosPas()
			{				
                $data=[];	
                $miConex = new Conexion();
                $sql="select * FROM vtit_pasantes WHERE MATRICULA='".$_GET["alumno"]."'";
              // echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
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
				
				$this->Image('../../imagenes/empresa/logo2.png',175,10,25,25);
				$this->Image('../../imagenes/empresa/sepescudo.png',20,10,21,26);

			}
			
			
			function Footer()
			{
				
	
				$miutil = new UtilUser();
				$pstodg=$miutil->getJefe('101');
				$pstoce=$miutil->getJefe('303');
		
				//249 ANCHO			
				$this->SetFont('Calibri Bold','',12);
				$this->SetDrawColor(0,0,0);
				$this->SetX(20);

				$this->SetY(-40);
				$this->Cell(90,4,utf8_decode("COTEJÓ"),'',0,'C',false);
				$this->Cell(90,4,utf8_decode("DIRECTOR(A) GENERAL"),'',0,'C',false);
				
				$this->SetY(-30);
				$this->Cell(90,4,utf8_decode($pstoce),'',0,'C',false);
				$this->Cell(90,4,utf8_decode($pstodg),'',0,'C',false);
	
				
			}
			
			
			
			
			
			
   }
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetMargins(20, 25 , 20);
		$pdf->SetAutoPageBreak(true,25); 
		$pdf->AddPage();

		
		$miutil = new UtilUser();
		$dataP = $pdf->LoadDatosPas();
		$dataGen =$pdf->LoadDatosGen();

		$fechadec=$miutil->formatFecha($dataP[0]["FECHA_TITA"]);
		$eldia=date("d", strtotime($fechadec));
		$elmes=$miutil->getFecha($fechadec,'MES');
		$elanio=date("Y", strtotime($fechadec));

		$fechadecA=$miutil->formatFecha($dataP[0]["FECHA_ACTA"]);
		$eldiaA=date("d", strtotime($fechadecA));
		$elmesA=$miutil->getFecha($fechadecA,'MES');
		$elanioA=date("Y", strtotime($fechadecA));


		$pdf->AddFont('Calibri Bold','','Calibri Bold.php');
		$pdf->AddFont('Eureka Sans Light','B','Eureka Sans Light.php');
		$pdf->AddFont('Eureka Sans Light','','Eureka Sans Light.php');

		$pdf->AddFont('Eureka-Sans-Light-Regular','B','Eureka-Sans-Light-Regular.php');
		$pdf->AddFont('Eureka-Sans-Light-Regular','','Eureka-Sans-Light-Regular.php');
		$pdf->Ln(20);

		$pdf->SetFont('Calibri Bold','',18);
		$pdf->SetTextColor(12, 30, 97 );
		$pdf->SetDrawColor(146, 133, 35);
		$pdf->SetLineWidth(0.7);
		$pdf->Cell(0,10,utf8_decode("Instituto Tecnológico Superior de Santa María de El Oro"),"B",1,'C');	
		$pdf->Ln(5);
		$pdf->SetTextColor(0);
		$pdf->Cell(0,0,utf8_decode("ACTA DE EXÁMEN PROFESIONAL"),0,1,'C');	

		$pdf->Ln(5);

		$pdf->SetFont('Eureka-Sans-Light-Regular','',18);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Times','',12);
		$pdf->MultiCell(0,7,utf8_decode("El suscrito Director General del Instituto Tecnológico Superior de Santa María de El Oro, ".
		"certifica que en el Libro de Actas de Examen Profesional No.".$dataP[0]["LIBRO"]." a las ".$dataP[0]["FOJA"]." fojas, ".
		"se encuentra asentada el acta  No. ".$dataP[0]["ACTA"]." que a la letra dice:". 
		" En la ciudad de Santa María del Oro, Dgo., el día ".$eldia." del mes de ".$elmes."  del año ".$elanio.", ".
		"siendo las ".$dataP[0]["HORA_TITA"]." horas, se reunieron en las instalaciones ".
		"del Instituto Tecnológico Superior de Santa María de El Oro, clave 10EIT0004E los integrantes del jurado: "),0,'J',FALSE);

		$pdf->Ln(7);
		$pdf->Cell(30,7,utf8_decode("Presidente(a):"),0,0,'L');	
		$pdf->Cell(120,7,utf8_decode($dataP[0]["PRESAD"]),0,0,'L');	

		$pdf->Ln(7);
		$pdf->Cell(30,7,utf8_decode("Secretario(a):"),0,0,'L');	
		$pdf->Cell(120,7,utf8_decode($dataP[0]["SECAD"]),0,0,'L');	

		$pdf->Ln(7);
		$pdf->Cell(30,7,utf8_decode("Vocal:"),0,0,'L');	
		$pdf->Cell(120,7,utf8_decode($dataP[0]["VOCAD"]),0,0,'L');	

		$pdf->Ln(10);

		$pdf->MultiCell(0,7,utf8_decode("Y de acuerdo con las disposiciones reglamentarias en vigor y la opción seleccionada: ".
		$dataP[0]["OPCIOND"].", se procedió a llevar a cabo el Acto de Recepción Profesional a ".$dataP[0]["PASANTE"].
		", con número de control ".$dataP[0]["MATRICULA"].", pasante de la carrera de: ".$dataP[0]["CARRERAD"].
		" con clave del plan de estudios ".$dataP[0]["MAPA"]."."),0,'J',FALSE);



		$pdf->MultiCell(0,7,utf8_decode("Tomando en cuenta los integrantes del jurado las facultades mostradas en el acto de ".
		"recepción, se dictaminó que fuera: ".$dataP[0]["RESULTADO"]."."),0,'J',FALSE);



		$pdf->MultiCell(0,7,utf8_decode("El(la) Presidente(a) del Jurado hizo saber al sustentante el resultado obtenido, ".
		"el Código de Ética Profesional y le tomó la Protesta de Ley.  Dándose por terminado el Acto a las ".$dataP[0]["HORA_TITAT"].
		" horas, y una vez escrita, leída y aprobada la firmaron para constancia las personas que el acto intervinieron."),0,'J',FALSE);

		$pdf->MultiCell(0,7,utf8_decode("Para los usos legales correspondientes se expide la presente en la ciudad de Santa María del Oro,". 
		" Durango, a los ".$eldiaA." días del mes de ".$elmesA." del año ".$elanioA."."),0,'J',FALSE);


			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
