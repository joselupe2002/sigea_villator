
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
   	        
   	     var $eljefe="";
   	     var $elprofe="";
   	       
   	       function getDatosPersona($num){   		       
            	$miConex = new Conexion();  
            	$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT EMPL_ABREVIA,EMPL_NOMBREC, EMPL_ULTIGRAD, EMPL_EGRESADODED, ".
            			"EMPL_FOTO, EMPL_DEPTOD, EMPL_JEFEABREVIA,EMPL_JEFE, EMPL_JEFED, EMPL_RFC, EMPL_CURP, EMPL_NUMERO, EMPL_FECING ".
            			" FROM vempleados WHERE EMPL_NUMERO= '".$num."'" );
                foreach ($resultado as $row) {$data[] = $row;}            
            	return $data;            		
            }
   	
			function LoadData()
			{				
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from vedescarga a where DESC_PROFESOR='".$_GET["profesor"]."' AND DESC_CICLO=getciclo()");				
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
			
			
			function LoadPlan($actividad)
			{
				$miConex = new Conexion();	
				$data[]=null;
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT PLAN_ORDEN,PLAN_ACTIVIDAD,PLAN_ENTREGABLE,PLAN_FECHAENTREGA from eplandescarga a where PLAN_IDACT='".$actividad."' order by PLAN_ORDEN");
				//echo "SELECT PLAN_ORDEN,PLAN_ACTIVIDAD,PLAN_ENTREGABLE,PLAN_FECHAENTREGA from eplandescarga a where PLAN_IDACT='".$actividad."' order by PLAN_ORDEN"; 
				foreach ($resultado as $row) {
					$data[] = $row;					
				}
				return $data;
			}
			
			function Header()
			{
				$miutil = new UtilUser();
				$miutil->getEncabezado($this,'V');			
			}
			
			function dameHoras($lin){
				$lashoras=0;
					for ($i=2; $i<=8; $i++) {
						if (($lin[$i]!='') && (strlen($lin[$i])>10)) {
							$hor1=substr($lin[$i],0,2);
							$min1=substr($lin[$i],3,2);

							$hor2=substr($lin[$i],6,2);
							$min2=substr($lin[$i],9,2);

							$lashoras+=(($hor2*60)+$min2)-(($hor1*60)+$min1);
						}
					}
				return ($lashoras/60);
			}

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'V');
				
				
				$dir=$miutil->getJefe('301');
				$subdir=$miutil->getJefe('304');
				
				
				//249 ANCHO
				$this->SetFont('Montserrat-Medium','B',7);
				$this->SetDrawColor(0,0,0);
				$this->SetX(30);
				$this->SetY(-40);
				$this->Cell(60,4,utf8_decode($this->elprofe),'T',0,'C',false);
				$this->SetX(130);
				$this->Cell(60,4,utf8_decode($this->eljefe),'T',0,'C',false);
				
		
				$this->SetY(-37);
				$this->SetX(30);
				$this->Cell(60,4,utf8_decode('PROFESOR'),'',0,'L',false);
				$this->SetX(130);
				$this->Cell(60,4,utf8_decode('JEFE DIVISIÓN'),'',0,'C',false);
				
		
				
				
			}
			
			
			
		
			
			
			function descarga()
			{
				$entre=false;
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"select a.DESC_ID, a.DESC_ACTIVIDADD,".
						"LUNES, MARTES, MIERCOLES, JUEVES, VIERNES, SABADO, DOMINGO, DESC_HORAS ".
						" from  vedescarga a where a.DESC_CICLO='".$_GET["ciclo"]."' and a.DESC_PROFESOR='".$_GET["profesor"]."'" );
				foreach ($resultado as $row) {
					$data[] = $row;
					$entre=true;
				}
				if ($entre) {return $data;}
				else  return null;
			}
			
			function imprimeDescarga($headerdes, $datades)
			{
				$this->Ln(5);
				// Colores, ancho de l�nea y fuente en negrita
				$this->SetFillColor(172,31,6);
				$this->SetTextColor(255);
				$this->SetDrawColor(181,57,35);
				$this->SetLineWidth(.3);
				
			
				$w = array(10,53,16,16,16,16,16,16,16,10);
				$this->SetFont('Montserrat-ExtraBold','B',8);
				$this->SetWidths($w);	

				for($i=0;$i<count($headerdes);$i++)
					$this->Cell($w[$i],7,$headerdes[$i],1,0,'C',true);
					$this->Ln();
					// Restauraci�n de colores y fuentes
					$this->SetFillColor(255,254,174);
					$this->SetTextColor(0);
					$this->SetFont('');
					// Datos
					$fill = false;
					$this->SetFont('Montserrat-Medium','',6);
					$suma=0;
					
					
					foreach($datades as $rowdes)
					{
										       
						if (count($rowdes)) {
							
							/*
							$this->Cell($w[0],4,utf8_decode($rowdes[0]),'LR',0,'J',$fill);	
							$this->Cell($w[1],4,utf8_decode($rowdes[1]),'LR',0,'L',$fill);
							$this->Cell($w[2],4,$rowdes[2],'LR',0,'L',$fill);
							$this->Cell($w[3],4,$rowdes[3],'LR',0,'L',$fill);
							$this->Cell($w[4],4,$rowdes[4],'LR',0,'L',$fill);
							$this->Cell($w[5],4,$rowdes[5],'LR',0,'L',$fill);
							$this->Cell($w[6],4,$rowdes[6],'LR',0,'L',$fill);
							$this->Cell($w[7],4,$rowdes[7],'LR',0,'C',$fill);
							$this->Cell($w[8],4,$rowdes[8],'LR',0,'C',$fill);
							$this->Cell($w[9],4,$this->dameHoras($rowdes),'LR',0,'C',$fill);
*/

							$this->Row(array(utf8_decode($rowdes[0]),
											 utf8_decode($rowdes[1]),
											 utf8_decode($rowdes[2]),
											 utf8_decode($rowdes[3]),
											 utf8_decode($rowdes[4]),
											 utf8_decode($rowdes[5]),
											 utf8_decode($rowdes[6]),
											 utf8_decode($rowdes[7]),
											 utf8_decode($rowdes[8]),
											 $this->dameHoras($rowdes)
											)
										);

							$suma+=$rowdes[7];
							
							$this->Ln();
							$fill = !$fill;
						}
						
					}
					
					$this->Cell(array_sum($w),0,'','T');
					$this->Ln();
					$this->SetFont('Montserrat-ExtraBold','B',8);
					$this->Cell(array_sum($w)-10,4,'Suma de Horas','LR',0,'R',$fill);
					$this->Cell(10,4,$suma,'LR',0,'C',$fill);
					$this->Ln();
					$this->Cell(array_sum($w),0,'','T');
					// L�nea de cierre
			}
			
			
			function imprimePlanDescarga($datades)
			{
				
				
				foreach($datades as $rowdes)
				{
					$this->Ln(10);
					$this->SetFont('Montserrat-ExtraBold','B',10);
					$this->Cell(0,0,"PLAN DE TRABAJO: ".utf8_decode($rowdes[1]),0,0,'L');
					$this->Ln(3);
					$dataPlan = $this->loadPlan($rowdes[0]);
					
					$this->SetFillColor(4,53,142);
					$this->SetTextColor(255);
					$this->SetDrawColor(55,85,172);
					$this->SetLineWidth(.3);
					
					$w = array(10,78,73,20);
					$this->SetFont('Montserrat-ExtraBold','B',8);
					
					$headerplan = array('No','Actividad','Entregable','Fecha');	
					
					$this->setX(15);
					for($i=0;$i<count($headerplan);$i++)
						$this->Cell($w[$i],7,$headerplan[$i],1,0,'C',true);
						$this->Ln();
						// Restauraci�n de colores y fuentes
						$this->SetFillColor(183,201,255);
						$this->SetTextColor(0);
						$this->SetFont('');
						// Datos
						$fill = false;
						$this->SetFont('Montserrat-Medium','',8);
						$suma=0;
						
						if (!(empty($dataPlan))) {
								foreach($dataPlan as $rowdes)
								{
									$this->setX(15);
								
										$this->Cell($w[0],4,utf8_decode($rowdes[0]),'LR',0,'J',$fill);
										$this->Cell($w[1],4,utf8_decode($rowdes[1]),'LR',0,'L',$fill);
										$this->Cell($w[2],4,utf8_decode($rowdes[2]),'LR',0,'L',$fill);
										$this->Cell($w[3],4,utf8_decode($rowdes[3]),'LR',0,'L',$fill);
									    $this->Ln();
										$fill = !$fill;
				
									
								}
						}
						$this->setX(15);
						$this->Cell(array_sum($w),0,'','T');
					
				}
				
				
				
				
			}
			
			
		}
		
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(10, 25 , 25);
		$pdf->SetAutoPageBreak(true,45); 
		$pdf->AddPage();
		
		
		$dataEmpl = $pdf->getDatosPersona($_GET["profesor"]);
		
		$pdf->Ln(10);
		
	    $pdf->SetFont('Montserrat-ExtraBold','B',10); 
	    $pdf->Cell(0,0,"PLAN DE TRABAJO DE ACTIVIDADES DE DESCARGA ",0,0,'C');
	    $pdf->Ln(3);
	    $pdf->Cell(0,0,$_GET["ciclod"],0,1,'C');
		
	    $pdf->Ln(10);
		
		$pdf->setX(40); $pdf->SetFont('Montserrat-ExtraBold','B',8); $pdf->Cell(0,0,"NOMBRE: ",0,0,'L');	
		$pdf->setX(60); $pdf->SetFont('Montserrat-Medium','U',8); $pdf->Cell(0,0,utf8_decode($dataEmpl[0]["EMPL_NOMBREC"]),0,1,'L');
		$pdf->Ln(4);
		$pdf->setX(40); $pdf->SetFont('Montserrat-ExtraBold','B',8); $pdf->Cell(0,0,"DEPTO: ",0,0,'L');
		$pdf->setX(60); $pdf->SetFont('Montserrat-Medium','U',8); $pdf->Cell(0,0,utf8_decode($dataEmpl[0]["EMPL_DEPTOD"]),0,1,'L');
		
			
		$pdf->eljefe=$dataEmpl[0]["EMPL_JEFEABREVIA"]." ".$dataEmpl[0]["EMPL_JEFED"];
		$pdf->elprofe=$dataEmpl[0]["EMPL_ABREVIA"]." ".$dataEmpl[0]["EMPL_NOMBREC"];
		
		$headerdes = array('ID','Actividades de Descarga','Lunes','Martes','utf8_decode(Miercoles)','Jueves','Viernes','Sabado','Domingo','TH');		
		$datades = $pdf->descarga();
		if (!($datades[0]==null)) { $pdf->imprimeDescarga($headerdes,$datades);}
		
		if (!($datades[0]==null)) { $pdf->imprimePlanDescarga($datades);}
		
		
			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
