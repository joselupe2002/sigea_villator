

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
   	        var $eljefe="";
   	        var $eljefepsto="";
 
			function LoadData()
			{				
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from vss_alumnos where ID=".$_GET["id"]);				
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
			

			function Footer()
			{	
                
                $this->SetFont('Arial','',8);
				$this->setY(-10);
				$this->Cell(0,5,utf8_decode("Km. 2.5 Carretera Federal Perote – México; Perote, Ver. C.P. 91270. Teléfonos: 01(282) 8 25 31 50, 8 25 31 51, Fax 8 25 36 68"),0,1,'C');

	
            
		
			}



			
		

		}
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(20, 20 , 20);
		$pdf->SetAutoPageBreak(true,20); 
		$pdf->AddPage();
		$dataGen = $pdf->LoadDatosGen();
		
		$pdf->AddFont('Montserrat-Black','B','Montserrat-Black.php');
		$pdf->AddFont('Montserrat-Black','','Montserrat-Black.php');
		$pdf->AddFont('Montserrat-Medium','B','Montserrat-Medium.php');
		$pdf->AddFont('Montserrat-Medium','','Montserrat-Medium.php');
		$pdf->AddFont('Montserrat-SemiBold','','Montserrat-SemiBold.php');
		$pdf->AddFont('Montserrat-SemiBold','B','Montserrat-SemiBold.php');
		$pdf->AddFont('Montserrat-ExtraBold','B','Montserrat-ExtraBold.php');
		$pdf->AddFont('Montserrat-ExtraBold','','Montserrat-ExtraBold.php');
		$pdf->AddFont('Montserrat-ExtraBold','I','Montserrat-ExtraBold.php');
		$pdf->AddFont('Montserrat-ExtraLight','I','Montserrat-ExtraLight.php');
		$pdf->AddFont('Montserrat-ExtraLight','','Montserrat-ExtraLight.php');

		
		$pdf->SetFont('Arial','B',16);
		$pdf->SetY(30);
		$pdf->Cell(170,5,utf8_decode($dataGen[0]["inst_aux2"]),0,1,'C');

		$pdf->Image('../../imagenes/empresa/logo2.png',20,12,25,25);

		$pdf->SetY(50);
		$pdf->SetFont('Montserrat-Black','',11);
		$pdf->Cell(174,5,utf8_decode("CONSTANCIA DE LIBERACIÓN DE SERVICIO SOCIAL"),0,1,'C');



		$pdf->SetY(77);
		$data = $pdf->LoadData();
		$miutil = new UtilUser();
		$fechadec=$miutil->formatFecha($data[0]["INICIO"]);
		$fechaini=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del año ".date("Y", strtotime($fechadec));
		$fechadec=$miutil->formatFecha($data[0]["TERMINO"]);
		$fechafin=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del año ".date("Y", strtotime($fechadec));
				
		
		
		$fechadec=$miutil->formatFecha($data[0]["FECHAOF"]);
		$anioTer=date("Y", strtotime($fechadec));
		$mesTer=$miutil->getMesCorto(date("m",strtotime($fechadec)));
		

		//$dataof=$miutil->verificaOficio("521","COMISION",$_GET["id"]);		
		//$fechadecof=$miutil->formatFecha($dataof[0]["CONT_FECHA"]);
		//$fechaof=date("d", strtotime($fechadecof))."/".$miutil->getFecha($fechadecof,'MES'). "/".date("Y", strtotime($fechadecof));
		
		
		$ss=$miutil->getJefe('521'); //empleado servicio social y residencia
		$elpsto=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_FIRMAOF");
		$pdf->eljefe=$ss;
		$pdf->eljefepsto=$elpsto;


		$dir=$miutil->getJefe('101'); //Director General
		
		
		$elperiodo='del '.$fechaini.' al '.$fechafin;

		$fechadecof=strtotime($miutil->formatFecha($data[0]["FECHAOF"]));
		$fechaof=$miutil->aletras(date("d",$fechadecof)).utf8_decode(" días del mes de ").$miutil->getMesLetra(date("m",$fechadecof))." del año ". $miutil->aletras(date("Y",$fechadecof));
        $pdf->Ln(5);


		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,5,utf8_decode("A QUIEN CORRESPONDA"),0,1,'L');
		$pdf->Ln(5);

		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,5,utf8_decode("El ".$dataGen[0]["inst_aux2"].", hace constar que el (la) C. ".$data[0]["NOMBRE"].
	          ", de la carrera de: ".utf8_decode($data[0]["CARRERAD"]).", con número de matrícula: ".
			  utf8_decode($data[0]["MATRICULA"]).", concluyó satisfactoriamente su Servicio Social conforme a lo dispuesto en el Artículo 45,".
			  " de la Ley del Ejercicio Profesional para el Estado de Veracruz de Ignacio de la Llave, ".
			  " cubriendo un total de ".$data[0]["TOTALHORAS"]." hrs. durante el periodo comprendido ".
			  $elperiodo."."),0,'J',false);
		$pdf->Ln(5);

		$pdf->MultiCell(0,5,utf8_decode("Por lo que se extiende la presente, para los efectos legales que ".
		"haya lugar, en la Ciudad de  ".$dataGen[0]["inst_extiende"].", a los ").
		strtolower($fechaof).".",0,'J',FALSE);

		$pdf->SetFont('Arial','',12);
		$lasact=preg_replace("[\n|\r|\n\r]", ", ", $data[0]["ACTIVIDADES"]);


		$dataof=$miutil->getConsecutivoDocumento("LIBERACIONSS",$data[0]["MATRICULA"].$data[0]["FECHAOF"]);
		$folio=strtoupper($mesTer)."/".$anioTer."/".$data[0]["CARRERACD"]."/".str_pad($dataof[0]["CONSECUTIVOSOLO"],3,'0',STR_PAD_LEFT);
		
		//$pdf->Cell(0,5,$folio,0,1,'L');



		$firma=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_FIRMA");
		$sello=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_SELLO");
		if (($_GET["tipo"]=='1') ||($_GET["tipo"]=='2')) {			
			$pdf->Image($sello,160,160,45);
			$pdf->Image($firma,90,150,40);			
		}



		$pdf->setY(160);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(40,3,"",0,0,'C');
		$pdf->Cell(100,3,utf8_decode("A T E N T A M E N T E"),0,0,'C');
		$pdf->Cell(40,3,"",0,0,'C');
		$pdf->SetFont('Arial','B',10);

		$pdf->SetFont('Arial','',12);
		$pdf->setY(180);
		$pdf->Cell(40,5,"",0,0,'C');
		$pdf->Cell(100,5,utf8_decode($ss),'T',0,'C');
		$pdf->Cell(40,5,"",0,0,'C');
		$pdf->SetFont('Arial','B',10);
		
		
		$pdf->SetFont('Arial','B',12);
		$pdf->setY(185);
		$pdf->Cell(40,3,"",0,0,'C');
		$pdf->MultiCell(100,5,utf8_decode($elpsto),0,'C',false);
		$pdf->Cell(40,3,"",0,0,'C');
		$pdf->SetFont('Arial','B',10);
	

		$pdf->SetFont('Arial','',8);
		$pdf->setY(220);
		$pdf->Cell(0,5,"C.c.p. Servicios Escolares.-Expediente del alumno",0,1,'L');
		$pdf->setY(225);
		$pdf->Cell(0,5,"           Interesado",0,1,'L');
	

		$pdf->SetFont('Arial','',8);
		$pdf->setY(240);
		$pdf->Cell(0,5,"Control de registro: ".$folio,0,1,'C');


		
		


		$pdf->Output(); 
	
	
 } else {header("Location: index.php");}
 
 ?>
