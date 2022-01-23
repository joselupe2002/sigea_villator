
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
				$this->Cell(90,3,utf8_decode("FORMATO PARA SOLICITUD DE"),"TRL",0,'C',false);
				$this->Cell(40,15,"",1,0,'L',false);
				
				$this->SetY(23);$this->SetX(65);
				$this->Cell(90,3,utf8_decode("VISITA A EMPRESA"),"B",0,'C',false);
				$this->SetY(26);$this->SetX(65);
				$this->SetFillColor(242,242,242);
				$this->Cell(90,9,utf8_decode("(IT-VIN-03-F-01)"),1,0,'C',true);
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

				$this->SetY(-100);
				$this->Cell(80,4,utf8_decode("Autorización"),'',0,'C',false);
				$this->SetX(130);
				$this->Cell(70,4,utf8_decode("Aval"),'',0,'C',false);

				$this->SetY(-85);
				$this->Cell(80,4,utf8_decode($data[0]["AUTORIZAD"]),'T',0,'C',false);
				$this->SetX(130);
				$this->Cell(70,4,utf8_decode($data[0]["AVALAD"]),'T',0,'C',false);
				

				$this->SetY(-80);
				$this->Cell(80,4,utf8_decode($data[0]["AUTORIZAF"]),"",0,'C',false);
				$this->SetX(130);
				$this->Cell(70,4,utf8_decode("PRESIDENTE DE ACADEMIA"),"",0,'C',false);

				
			
				$this->SetY(-40);
				$this->Cell(0,4,utf8_decode("Vo. Bo."),'',0,'C',false);
				
				$this->SetY(-35);
				$this->Cell(0,4,utf8_decode($data[0]["VOBOD"]),"",0,'C',false);
				$this->SetY(-45);
				$this->Cell(0,4,utf8_decode($data[0]["VOBOF"]),"",0,'C',false);
				$this->Line(70,238,148,238);

				$this->SetY(-40);
				$this->Cell(0,4,utf8_decode("C.c.p. Dirección Académica"),'',0,'L',false);
				$this->SetY(-35);
				$this->Cell(0,4,utf8_decode("c.c.p. Subdirección Académica"),'',0,'L',false);
				$this->SetY(-30);
				$this->Cell(0,4,utf8_decode("c.c.p. Archivo"),'',0,'L',false);
			
				
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
		$pdf->Cell(0,5,utf8_decode("DIRECCION ACADEMICA"),0,1,'C',false);
		$pdf->SetFont('Times','B',11);
		$pdf->Ln(10);
		$pdf->Cell(0,5,utf8_decode("DEPARTAMENTO DE SERVICIO SOCIAL Y RESIDENCIA PROFESIONAL"),0,1,'C',false);
		$pdf->Cell(0,5,utf8_decode("SOLICITUD DE VISITAS A EMPRESAS"),0,1,'C',false);
		$pdf->Ln(5);
		$dataCic = $pdf->LoadDatosCiclo($data[0]["CICLO"]);

	

		$pdf->SetFont('Times','',10);
		$pdf->Cell(20,5,utf8_decode("Fecha"),0,0,'L',false);
		$pdf->Cell(60,5,utf8_decode($data[0]["FECHAELA"]),0,0,'L',false);
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



/*
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(56,5,utf8_decode("Origen de la Acción"),"1",0,'C',false);
		$pdf->Cell(56,5,utf8_decode("Fecha:"),"1",0,'C',false);
		$pdf->Cell(58,5,utf8_decode("Tipo de Hallazgo:"),"1",0,'C',false);
		$pdf->Ln(5);

		$w = array(56,56,58);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["ORIGEND"]),
		                utf8_decode($data[0]["FECHA"]),
						utf8_decode($data[0]["HALLAZGOD"])								
						));

		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Descripción de la no conformidad u oportunidad de mejora (si es necesario usar otra hoja y anexarla):"),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["DESCRIPCION"])));

		
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Corrección"),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["CORRECCION"])));

		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("ANÁLISIS DE CAUSA RAIZ"),"1",0,'C',false);
		$pdf->Ln(5);
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Primer ¿por qué? "),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["PORQUE1"])));

		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Segundo ¿por qué? "),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["PORQUE2"])));

		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Tercer ¿por qué? "),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["PORQUE3"])));

		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Cuarto ¿por qué? "),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["PORQUE4"])));

		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Quinto ¿por qué? "),"1",0,'L',false);
		$pdf->Ln(5);
		$w = array(170);
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode($data[0]["PORQUE5"])));


		
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("ACCIONES PARA LA MEJORA"),"1",0,'C',false);
		$pdf->Ln(5);

		$w = array(56,42,30,42);
		$pdf->SetFont('Times','B',10);
		$pdf->SetWidths($w);
		$pdf->SetAligns("C","C","C","C");
		$pdf->Row(array(utf8_decode("Acción"),
		                utf8_decode("Responsable Nombre y Firma"),
						utf8_decode("Fecha de Realización"),
						utf8_decode("Nombre y firma de quien verifica su cumplimiento"),							
						));
		
		$pdf->SetFont('Times','',8);
		$pdf->SetWidths($w);
		$pdf->SetAligns("J","J","J","J");
		foreach($dataAcc as $row) {
			$pdf->Row(array(utf8_decode($row["ACCION"]),
		                utf8_decode($row["RESPONSABLED"]),
						utf8_decode($row["FECHA"]),
						utf8_decode($row["VERIFICAD"]),							
						));
		}


		$pdf->SetFont('Times','B',10);
		$pdf->Cell(170,5,utf8_decode("Evidencias para el cierre de las acciones de mejora:"),"1",0,'C',false);
		$pdf->Ln(5);

		$w = array(170);
		$pdf->SetFont('Times','B',10);
		$pdf->SetWidths($w);
		$pdf->SetAligns("J");
		$pdf->Row(array(utf8_decode($data[0]["EVIDENCIAS"])));


		$pdf->SetFont('Times','B',10);
		$pdf->Cell(60,5,utf8_decode("Fecha efectiva de cierre del hallazgo:"),"LRT",0,'C',false);
		$pdf->Cell(110,5,utf8_decode("Vo. Bo. Del cierre del hallazgo:"),"LRT",0,'C',false);
		$pdf->Ln(5);

		$pdf->Cell(60,10,"","LR",0,'C',false);
		$pdf->Cell(110,10,"","LR",0,'C',false);
		$pdf->Ln(5);

		$pdf->SetFont('Times','',8);
		$pdf->Cell(60,3,utf8_decode($data[0]["FECHACIERRE"]),"LR",0,'C',false);
		$pdf->Cell(110,3,utf8_decode($data[0]["PERSONACIERRED"]),"LR",0,'C',false);

		$pdf->Ln(5);
		$pdf->Cell(60,3,"","LRB",0,'C',false);
		$pdf->Cell(110,3,utf8_decode($data[0]["FIRMACIERRE"]),"LRB",0,'C',false);


		
		/*
		$pdf->Cell(0,0,utf8_decode("INSTITUTO TECNOLÓGICO SUPERIOR DE MACUSPANA"),"",0,'C',false);
		$pdf->Ln(5);
		
		$pdf->MultiCell(0,5,utf8_decode("REVISIÓN Y VALIDACIÓN DE LA  INSTRUMENTACIÓN DIDÁCTICA PARA LA FORMACIÓN Y DESARROLLO DE ").
		utf8_decode("COMPETENCIAS PROFESIONALES DIGITAL Y REGISTRO DE PLANEACIÓN DIDÁCTICA EN EL SIGEA"),0,'C', false);
		
		$pdf->Ln(5);
		$pdf->SetX(100);$pdf->SetY(90);
		$pdf->Cell(0,0,"PERIODO: ".$_GET["ciclod"],"",0,'C',false);
		
		$header= array('N', 'ASIGANTURA', 'PROGRAMA','NO. DE COMPETENCIAS');
		$data = $pdf->LoadData();
		$pdf->imprimeCargaAcad($header,$data);
		
		if ($_GET["tipo"]=='DEPTO') {
		    $dataEmpl = $pdf->getDatosPersona($_GET["profesor"]);		
		    $pdf->eljefe=$dataEmpl[0]["EMPL_JEFEABREVIA"]." ".$dataEmpl[0]["EMPL_JEFED"]; }
		else {
			$dataEmpl = $pdf->getJefeCarrera($_GET["tipov"]);
			$pdf->eljefe=$dataEmpl[0]["EMPL_JEFEABREVIA"]." ".$dataEmpl[0]["EMPL_JEFED"];
		}
		

		if (($_GET["tipoRep"]=='1') ||($_GET["tipoRep"]=='2')) {

			$lafirma=$dataEmpl[0]["EMPL_JEFEFIRMA"];
			$elsello=$dataEmpl[0]["EMPL_JEFESELLO"];
		
			$pdf->Image($elsello,140,160,45);
			$pdf->Image($lafirma,130,215,40);

			$miutil = new UtilUser();
		
		
			$lafirmaE=$miutil->getDatoEmpl($_GET["profesor"],"EMPL_FIRMA");	
			if ($lafirmaE!="") {$pdf->Image($lafirmaE,30,215,40);}
			
			
		}
		*/
	
		
			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
