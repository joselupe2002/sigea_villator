
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
				$resultado=$miConex->getConsulta($_SESSION['bd'],"select * FROM vvin_visitas where ID='".$_GET["id"]."' ORDER BY ID");				
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
				$this->Cell(90,3,utf8_decode("FORMATO DE REPORTE DE RESULTADOS "),"TRL",0,'C',false);
				$this->Cell(40,15,"",1,0,'L',false);
				
				$this->SetY(23);$this->SetX(65);
				$this->Cell(90,3,utf8_decode("EN LA VISITA"),"B",0,'C',false);
				$this->SetY(26);$this->SetX(65);
				$this->SetFillColor(242,242,242);
				$this->Cell(90,9,utf8_decode("(IT-VIN-03-F-05)"),1,0,'C',true);
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
				
			}
			
			
			function Footer()
			{
				
				$miutil = new UtilUser();
				$data = $this->LoadData();
				$miutil->getPie($this,'V');		
				$this->SetX(10);

				$this->SetY(-90);
				

				$this->SetY(-75);
				$this->Cell(80,4,utf8_decode($data[0]["SOLICITAD"]),0,0,'C',false);
				$this->Cell(5,4,"",0,0,'C',false);
				$this->Cell(80,4,utf8_decode($data[0]["AUTORIZAD"]),"0",0,'C',false);
				
				$this->SetY(-70);
				$this->Cell(80,4,utf8_decode("FIRMA DEL DOCENTE RESPONSABLE"),"T",0,'C',false);
				$this->Cell(5,4,"",0,0,'C',false);
				$this->Cell(80,4,utf8_decode("SELLO, NOMBRE, CARGO Y FIRMA"),"T",0,'C',false);

				$this->SetY(-65);
				$this->Cell(80,4,"","",0,'C',false);
				$this->Cell(5,4,"",0,0,'C',false);
				$this->Cell(80,4,utf8_decode("JEFE DE DIVISIÓN"),"0",0,'C',false);


				$this->SetY(-40);
				$this->Cell(40,4,"","",0,'C',false);
				$this->Cell(85,4,utf8_decode("NOMBRE Y FIRMA DE LIBERACION"),"T",0,'C',false);
				$this->Cell(40,4,"","0",0,'C',false);
				$this->SetY(-35);
				$this->Cell(40,4,"","",0,'C',false);
				$this->Cell(85,4,utf8_decode("DEL DEPTO. DE SERVICIO SOCIAL"),0,0,'C',false);
				$this->Cell(40,4,"","0",0,'C',false);
			
				
			}
			
			
   }
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Times','',10);
		$pdf->SetMargins(25, 30 , 20);
		$pdf->SetAutoPageBreak(true,25); 
		$pdf->AddPage();
		
		$data = $pdf->LoadData();

		
		$pdf->SetY(40);
		
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(0,5,utf8_decode("DIRECCIÓN DE VINCULACIÓN"),0,1,'C',false);
		$pdf->SetFont('Times','B',11);
		$pdf->Ln(10);
		$pdf->Cell(0,5,utf8_decode("DEPARTAMENTO DE SERVICIO SOCIAL Y RESIDENCIA PROFESIONAL"),0,1,'C',false);
		
		$pdf->Ln(5);
		$dataCic = $pdf->LoadDatosCiclo($data[0]["CICLO"]);

	

		$pdf->SetFont('Times','',10);
		$pdf->Cell(20,5,utf8_decode("Fecha"),0,0,'L',false);
		$pdf->Cell(60,5,utf8_decode($data[0]["FECHARES"]),0,0,'L',false);

		$pdf->Ln(5);
		$w = array(30,28,28,28,28,28);
		$pdf->SetFont('Times','',10);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode("Nombre del docente responsable:"),
		                utf8_decode("Carrera:"),
						utf8_decode("Número de Estudiantes H-M:"),
						utf8_decode("Fecha en que se realizó la visita:"),	
						utf8_decode("Horario en que se realizó la visita: ")	,
						utf8_decode("Nombre de la Empresa: ")								
						));
		$pdf->Row(array(utf8_decode($data[0]["SOLICITAD"]),
		                utf8_decode($data[0]["CARRERAD"]),
						utf8_decode($data[0]["HOMBRES"]."-".$data[0]["MUJERES"]),
						utf8_decode($data[0]["FECHAVIS"]),	
						utf8_decode($data[0]["HORARIO"]),
						utf8_decode($data[0]["EMPRESA"]." / ".$data[0]["MUNICIPIOD"].", ".$data[0]["ESTADOD"])								
						));

		$pdf->MultiCell(170,5,utf8_decode("NOTA: El informe deberá ser entregado como máximo 5 días hábiles posteriores a la realización de la visita."),0,'L',false);	
		$pdf->Ln(5);
						
		$pdf->Ln(5);
		$pdf->SetFont('Times','',10);
		$pdf->Cell(20,5,utf8_decode("Materia:"),0,0,'L',false);
		$pdf->Cell(150,5,utf8_decode($data[0]["MATERIA"]." ".$data[0]["MATERIAD"]),0,0,'L',false);	
		$pdf->Ln(5);

		$pdf->SetFont('Times','',10);
		$pdf->Cell(170,5,utf8_decode("Unidades de la materia que se cubrieron con visita: "),0,1,'L',false);
		$pdf->MultiCell(170,5,utf8_decode($data[0]["TEMA"]." ".$data[0]["UNID_DESCRIP"]),0,'L',false);	
		$pdf->Ln(5);

		$pdf->SetFont('Times','',10);
		$pdf->Cell(170,5,utf8_decode("¿Se cumplieron con los objetivos de la visita? Explique:  "),0,1,'L',false);
		$pdf->MultiCell(170,5,utf8_decode($data[0]["CUMPLIOOBJ"]),0,'L',false);	
		$pdf->Ln(5);

		
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',10);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode("Incidentes")));
		$pdf->Row(array(utf8_decode($data[0]["INCIDENCIAS"])));
				
		
		/*
		$pdf->Cell(30,5,utf8_decode("Periodo escolar: "),0,0,'L',false);
		$pdf->Cell(60,5,utf8_decode($dataCic[0]["CICL_CLAVE"]." ".$dataCic[0]["CICL_DESCRIP"]),0,0,'L',false);
		$pdf->Ln(5);


		$pdf->SetFont('Times','',10);
		$pdf->Cell(50,5,utf8_decode("Clave y nombre de la asignatura:"),0,0,'L',false);
		$pdf->Cell(120,5,utf8_decode($data[0]["MATERIA"]." ".$data[0]["MATERIAD"]),0,0,'L',false);	
		$pdf->Ln(5);

		$pdf->SetFont('Times','',10);
		$pdf->Cell(40,5,utf8_decode("Unidad o tema:"),0,0,'L',false);
		$pdf->Cell(130,5,utf8_decode($data[0]["TEMA"]." ".$data[0]["UNID_DESCRIP"]),0,0,'L',false);	
		$pdf->Ln(5);

		$pdf->SetFont('Times','',10);
		$pdf->Cell(40,5,utf8_decode("Solicitante:"),0,0,'L',false);
		$pdf->Cell(130,5,utf8_decode($data[0]["SOLICITA"]." ".$data[0]["SOLICITAD"]),0,0,'L',false);	
		$pdf->Ln(10);



		$w = array(10,50,50,20,20,20);
		$pdf->SetFont('Times','',10);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode("No."),
		                utf8_decode("Empresa / Ciudad."),
						utf8_decode("Área a observar y objetivo. "),
						utf8_decode("Fecha / Turno. "),	
						utf8_decode("Carrera y semestre. ")	,
						utf8_decode("No. de alumnos. ")								
						));
		$pdf->Row(array(utf8_decode("1"),
		                utf8_decode($data[0]["EMPRESA"]." / ".$data[0]["MUNICIPIOD"].", ".$data[0]["ESTADOD"]),
						utf8_decode($data[0]["DEPARTAMENTO"]),
						utf8_decode($data[0]["FECHAVIS"]." / ".$data[0]["TURNO"]),	
						utf8_decode($data[0]["CARRERAC"]." / SEM.".$data[0]["SEMESTRE"])	,
						utf8_decode($data[0]["NUMALUM"])								
						));

		$pdf->Row(array(utf8_decode("2"),"\n\n\n","","","",""));
		$pdf->Row(array(utf8_decode("3"),"\n\n\n","","","",""));
		$pdf->Row(array(utf8_decode("4"),"\n\n\n","","","",""));
*/


		
			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
