
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
				

			}
			
			
			function Footer()
			{
				
				
			}
			
			
			
			
			
			
   }
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetMargins(20, 25 , 20);
		$pdf->SetAutoPageBreak(true,0); 
		$pdf->AddPage();

		
		$miutil = new UtilUser();
		$dataP = $pdf->LoadDatosPas();
		$dataGen =$pdf->LoadDatosGen();

		$pdf->AddFont('book-antiqua','B','book-antiqua.php');
		$pdf->AddFont('MervaleScript-Regular','B','MervaleScript-Regular.php');

		
		$pdf->Ln(20);


		$pdf->Image('../../imagenes/empresa/logotit1.png',10,5,38,38);
		$pdf->Image('../../imagenes/empresa/logotit2.png',168,5,38,38);
		$pdf->Image('../../imagenes/empresa/fototit.png',18,62,49,69);
		$pdf->Image('../../imagenes/empresa/firmatit.png',26,160,5,30);
		$pdf->Image('../../imagenes/empresa/lintit.png',17,249,65,0.5);
		$pdf->Image('../../imagenes/empresa/lintit.png',137,249,65,0.5);

		$derFoto=(18+49)-20; //left de foto + 49 de ancho de foto - 20 del margen
		$hojaw=216; $hojah=273;
		   
		$pdf->SetFont('book-antiqua','B',16);
		$pdf->SetY(20);
		$pdf->Cell(28,5,"",0,0,'C'); //debe ser 48 pero se suman los 20 del margen izquierdo
		$pdf->Multicell(120,5,utf8_decode($dataGen[0]["inst_razon"]),0,'C');	
		//$pdf->SetY(25);
		//$pdf->Cell(28,5,"",0,0,'C');
		//$pdf->Cell(120,5,utf8_decode("DE ....."),0,0,'C');	

		$pdf->SetY(60); $pdf->SetX(67);
		$pdf->SetFont('MervaleScript-Regular','B',16);		
		$pdf->Multicell(110,5,utf8_decode("Otorga a:"),0,'C',false);	

		$pdf->SetFont('book-antiqua','B',16);
		$pdf->SetY(80); $pdf->SetX(67);
		$pdf->Multicell(110,5,utf8_decode($dataP[0]["PASANTE"]." AGUIRRE MENDOZA "),0,'C',false);	

		$pdf->SetY(100); $pdf->SetX(67);
		$pdf->SetFont('MervaleScript-Regular','B',16);		
		$pdf->Multicell(110,5,utf8_decode("El título de:"),0,'C',false);	

		$pdf->SetFont('book-antiqua','B',16);
		$pdf->SetY(120); $pdf->SetX(67);
		$pdf->Multicell(110,5,utf8_decode($dataP[0]["CARRERAT"]),0,'C',false);	


		$fechadec=$miutil->formatFecha($dataP[0]["FECHA_ACTA"]);
		$eldia=date("d", strtotime($fechadec));
		$elmes=$miutil->getFecha($fechadec,'MES');
		$elanio=date("Y", strtotime($fechadec));

		$fechadec=$miutil->formatFecha($dataP[0]["FECHA_ELTIT"]);
		$eldia_titn=date("d", strtotime($fechadec));
		$eldia_tit=$miUtil->aletras(date("d", strtotime($fechadec)));
		$elmes_tit=$miutil->getFecha($fechadec,'MES');
		$elanio_titn=date("Y", strtotime($fechadec));
		$elanio_tit=$miUtil->aletras(date("Y", strtotime($fechadec)));

	
		$pdf->Image('../../imagenes/empresa/logotit2.png',75,145,6,6);
		$pdf->Image('../../imagenes/empresa/logotit2.png',111,145,6,6);
		$pdf->Image('../../imagenes/empresa/logotit2.png',161,145,6,6);

		$pdf->SetY(140); $pdf->SetX(52);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(138,5,utf8_decode("En virtud de haber concluido los estudios requeridos de acuerdo a los planes y programas en"),0,0,'C');
		$pdf->SetY(145);$pdf->SetX(52);
		$pdf->Cell(138,5,utf8_decode("vigor y haber sido aprobado en el acto recepcional, que sustento con fecha del ".$eldia." de"),0,0,'C');
		$pdf->SetY(150);$pdf->SetX(52);
		$pdf->Cell(138,5,utf8_decode($elmes." del ".$elanio." en las instalaciones de esta institución educativa"),0,0,'C');
		
		$pdf->SetY(180);$pdf->SetX(52);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(138,5,utf8_decode($dataGen[0]["inst_extiende"].", a los "),0,0,'C');
		$pdf->SetY(185);$pdf->SetX(52);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(138,5,utf8_decode($eldia_tit." días del mes de ".$elmes_tit." de ".$elanio_tit),0,0,'C');

		
		$pdf->SetY(220);$pdf->SetX(30);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(0,5,utf8_decode("Director del Instituto"),0,0,'L');

		$pdf->SetY(215);$pdf->SetX(137);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(65,5,utf8_decode("Secretario de Educación del"),0,0,'C');
		
		$pdf->SetY(220);$pdf->SetX(137);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(65,5,utf8_decode("Estado de Durango"),0,0,'C');

		$pdf->SetY(250);$pdf->SetX(17);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(65,5,utf8_decode($dataGen[0]["inst_director"]),0,0,'C');


		$pdf->SetY(250);$pdf->SetX(137);
		$pdf->SetFont('MervaleScript-Regular','B',16);
		$pdf->Cell(65,5,utf8_decode($dataGen[0]["inst_rfc"]),0,0,'C');


		/*====================================================*/
		$pdf->AddPage();

		$pdf->SetY(20);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(0,5,utf8_decode("\"Construyendo una Sociedad Tecnológica\""),0,0,'C');

		$pdf->SetY(40);$pdf->SetX(40);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(140,10,utf8_decode("REGISTRADO EN EL DEPARTAMENTO DE SERVICIOS ESCOLARES"),"LTR",0,'C');
		$pdf->SetY(50);$pdf->SetX(40);
				
			$pdf->Cell(5,5,"","L",0,'L');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(20,5,utf8_decode("Con no."),"",0,'C');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(20,5,$dataP[0]["TITULO"],"B",0,'C');


			$pdf->Cell(5,5,"","",0,'C');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(20,5,utf8_decode("en el libro"),"",0,'C');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(20,5,$dataP[0]["LIBROT"],"B",0,'C');

			$pdf->Cell(5,5,"","",0,'C');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(20,5,utf8_decode("a fojas"),"",0,'C');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(20,5,$dataP[0]["FOJAT"],"B",0,'C');
			$pdf->Cell(5,5,"","R",0,'C');

		$pdf->SetY(55);$pdf->SetX(40);
		//FECHA 
			$pdf->Cell(5,5,"","L",0,'L');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(30,5,utf8_decode($dataGen[0]["inst_fechaof"]),"",0,'C');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(10,5,$eldia_titn,"B",0,'C');

			$pdf->Cell(5,5,"","",0,'C');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(10,5,utf8_decode("de"),"",0,'C');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(30,5,$elmes_tit,"B",0,'C');

			$pdf->Cell(5,5,"","",0,'C');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(10,5,utf8_decode("de"),"",0,'C');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(30,5,$elanio_titn,"B",0,'C');
			$pdf->Cell(5,5,"","R",0,'C');

			//CIERRE
			$pdf->SetY(60);$pdf->SetX(40);
			$pdf->Cell(140,5,"","LRB",0,'L');
		
			$nombre=$miutil->getJefe('303');//Nombre del puesto de Recursos Humanos

	
			$pdf->SetY(70);$pdf->SetX(40);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(140,20,utf8_decode("COTEJÓ"),"LTR",0,'C');
			$pdf->SetY(90);$pdf->SetX(40);
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(140,5,$nombre,"LR",0,'C');

			$pdf->SetY(95);$pdf->SetX(40);
			$pdf->Cell(5,5,"","L",0,'C');
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(130,5,"","B",0,'C');
			$pdf->Cell(5,5,"","R",0,'C');

			$pdf->SetY(100);$pdf->SetX(40);
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(140,5,"JEFE (A)  DEL DEPARTAMENTO DE SERVICIOS ESCOLARES","LRB",0,'C');
				


			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
