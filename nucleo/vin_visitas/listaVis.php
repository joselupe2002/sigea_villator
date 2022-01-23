
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

				$data=[];
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"select * FROM vvin_visalum where IDVISITA='".$_GET["id"]."' ORDER BY ID");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

			function LoadDatosCiclo($elciclo)
			{
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ciclosesc where CICL_CLAVE='".$elciclo."'");
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

				/*
				$this->SetY(20);$this->SetX(25);
				$this->Cell(40,15,"",1,0,'L',false);
				$this->SetFont('Times','B',9);
				$this->Cell(90,3,utf8_decode("FORMATO PARA LISTA AUTORIZADA"),"TRL",0,'C',false);
				$this->Cell(40,15,"",1,0,'L',false);
				
				$this->SetY(23);$this->SetX(65);
				$this->Cell(90,3,utf8_decode("DE ESTUDIANTES QUE ASISTIRAN A LA VISITA"),"B",0,'C',false);
				$this->SetY(26);$this->SetX(65);
				$this->SetFillColor(242,242,242);
				$this->Cell(90,9,utf8_decode("(IT-VIN-03-F-06)"),1,0,'C',true);
				$this->Image('../../imagenes/empresa/pie1.png',28,22,23,11);
				
				$this->SetFont('Times','',9);
				$this->SetY(20);$this->SetX(155);
				$this->Cell(40,3,utf8_decode("PÁG. 1 DE 2"),"TRL",0,'C',false);
				$this->SetY(23);$this->SetX(155);
				$this->Cell(40,3,utf8_decode("REVISIÓN NO. 02"),"B",0,'C',false);
				$this->SetY(26);$this->SetX(155);
				$this->Cell(40,3,"VIGENTE A PARTIR DEL","",0,'C',false);
				$this->SetY(29);$this->SetX(155);
				$this->Cell(40,3,"08 DE ENERO 2021","",0,'C',false);
				$this->SetY(32);$this->SetX(155);
				$this->Cell(40,3,utf8_decode("VERSIÓN 2015"),"",0,'C',false);
				*/
				$this->SetX(10);
				$this->Ln(5);
				
				
			}
			
			
			function Footer()
			{
				
				$miutil = new UtilUser();
				$data = $this->LoadData();
				$miutil->getPie($this,'V');		
				$this->SetX(10);

				
			
				$this->SetY(-40);
				$this->Cell(0,4,utf8_decode($data[0]["AUTORIZAD"]),"",0,'C',false);
				$this->SetY(-35);
				$this->Cell(0,4,utf8_decode($data[0]["AUTORIZAF"]),"",0,'C',false);
				
		
				
			}
			
	
			
			
			
   }
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Times','',10);
		$pdf->SetMargins(25, 30 , 20);
		$pdf->SetAutoPageBreak(true,45); 
		$pdf->AddPage();
		
		$data = $pdf->LoadData();

		
		$pdf->SetY(40);
		
		$pdf->SetFont('Times','B',12);
		$pdf->Ln(10);
		$pdf->Cell(0,5,utf8_decode("DEPARTAMENTO DE SERVICIO SOCIAL Y RESIDENCIA PROFESIONAL"),0,1,'C',false);
		$pdf->Cell(0,5,utf8_decode("SOLICITUD DE VISITAS A EMPRESAS"),0,1,'C',false);
		$pdf->Ln(5);
	


		

		$w = array(20,65,65,20);
		$pdf->SetFont('Times','',10);
		$pdf->SetAligns(array("C","C","C","C"));
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode("FECHA DE LA VISITA"),
		                utf8_decode("DOMICILIO DE LA EMPRESA"),
						utf8_decode("DOCENTE RESPONSABLE "),
						utf8_decode("HORARIO DE LA VISITA"),							
						));
		$pdf->Row(array(utf8_decode($data[0]["FECHAVIS"]),
						utf8_decode($data[0]["DIRECCION"]),
						utf8_decode($data[0]["SOLICITAD"]),	
						utf8_decode($data[0]["HORARIO"])														
						));

		$pdf->Ln(5);
		$w = array(10,65,30,35,30);
		$pdf->SetAligns(array("C","C","C","C","C"));
		$pdf->SetFont('Times','',10);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode("NO."),
						utf8_decode("NOMBRE DEL ESTUDIANTE"),
						utf8_decode("NO. CONTROL "),
						utf8_decode("CARRERA"),	
						utf8_decode("SEMESTRE")							
										));


		$c=1;
		$pdf->SetAligns(array("L","L","C","L","C"));
		
		foreach($data as $row) {
			$pdf->Row(array(utf8_decode($c),
						utf8_decode($row["NOMBRE"]),
						utf8_decode($row["MATRICULA"]),
						utf8_decode($row["CARRERA"]),
						utf8_decode($row["PERIODO"]),							
						));
			$c++;
		}

		

		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
