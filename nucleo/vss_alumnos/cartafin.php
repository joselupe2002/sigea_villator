

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

		
		$pdf->SetFont('Montserrat-Black','',9);
		$pdf->SetY(10);
	
	
		$pdf->Image('../../imagenes/empresa/enc2.png',13,16,43,25);
		$pdf->Image('../../imagenes/empresa/sepescudo.png',100,10,25,25);
		$pdf->Image('../../imagenes/empresa/logo2.png',166,16,25,25);

		$pdf->SetY(50);
		$pdf->SetFont('Montserrat-Black','',12);
		$pdf->Cell(174,5,utf8_decode("CONSTANCIA DE TERMINACIÓN DE SERVICIO SOCIAL"),0,1,'C');

		$pdf->SetY(60);
		$data = $pdf->LoadData();
		$miutil = new UtilUser();

		$dataof=$miutil->verificaOficio("521","CARTAFINSS",$_GET["id"]);
		$fechadecof=$miutil->formatFecha($dataof[0]["CONT_FECHA"]);
		$fechaof=date("d", strtotime($fechadecof))."/".$miutil->getFecha($fechadecof,'MES'). "/".date("Y", strtotime($fechadecof));
		
		$pdf->SetFont('Montserrat-ExtraBold','B',9);
		$pdf->Ln(5);
		$pdf->Cell(0,0,$dataGen[0]["inst_fechaof"]." ".$fechaof,0,1,'R');	
		$pdf->Ln(5);
		$pdf->Cell(0,0,'OFICIO No. '.utf8_decode($dataof[0]["CONT_NUMOFI"]),0,1,'R');
		$pdf->Ln(5);
		$pdf->Cell(0,0,'ASUNTO: Constancia',0,1,'R');

		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,5,utf8_decode("A QUIEN CORRESPONDA"),0,1,'L');
		$pdf->Ln(5);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,5,utf8_decode("Por medio de la presente se hace constar que:"),0,1,'L');
		$pdf->Ln(5);



	
		$fechadec=$miutil->formatFecha($data[0]["INICIO"]);
		$fechaini=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del ".date("Y", strtotime($fechadec));
		$fechadec=$miutil->formatFecha($data[0]["TERMINO"]);
		$fechafin=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del ".date("Y", strtotime($fechadec));
				
		

		//$dataof=$miutil->verificaOficio("521","COMISION",$_GET["id"]);		
		//$fechadecof=$miutil->formatFecha($dataof[0]["CONT_FECHA"]);
		//$fechaof=date("d", strtotime($fechadecof))."/".$miutil->getFecha($fechadecof,'MES'). "/".date("Y", strtotime($fechadecof));
		
		
		$ss=$miutil->getJefe('521'); //empleado servicio social y residencia
		$elpsto=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_FIRMAOF");
		$pdf->eljefe=$ss;
		$pdf->eljefepsto=$elpsto;


		$dir=$miutil->getJefe('101'); //Director General
	

		$pdf->SetFont('Arial','',12);
		$elperiodo='del '.$fechaini.' al '.$fechafin;

		$lasact=preg_replace("[\n|\r|\n\r]", ", ", $data[0]["ACTIVIDADES"]);

		$pdf->MultiCell(0,5,utf8_decode('Según documentos que obran en los archivos de esta Institución, el (la) C. ').$data[0]["NOMBRE"].
		' con matricula No. '.utf8_decode($data[0]["MATRICULA"]).utf8_decode(', de la carrera de ').utf8_decode($data[0]["CARRERAD"]).
		utf8_decode(' realizó su Servicio Social  en la dependencia ').utf8_decode($data[0]["EMPRESA"]).
		utf8_decode(', desarrollando las siguiente actividades: ').utf8_decode($lasact).
		utf8_decode(', cubriendo un mínimo total de 500 horas, durante el período comprendido').$elperiodo.
		utf8_decode(" con un nivel de desempeño ").utf8_decode($data[0]["CALIFICACION2"]." ".$data[0]["CALIFICACIONL"]),0,'J', false);
		$pdf->Ln(5);

		$pdf->MultiCell(0,5,utf8_decode("Este Servicio Social fue realizado de acuerdo con lo establecido en la Ley Reglamentaria del Artículo 5° Constitucional relativo al ejercicio de las Profesiones y los Reglamentos que rigen la normativa emitida por el Tecnológico Nacional de México."),0,'J', false);
		$pdf->Ln(5);
	

		$fechadecof=strtotime($miutil->formatFecha($data[0]["FECHAOF"]));
		$fechaof=date("d",$fechadecof).utf8_decode(" días del mes  de ").$miutil->getMesLetra(date("m",$fechadecof))." de ". $miutil->aletras(date("Y",$fechadecof));
        $pdf->Ln(5);
   

        $pdf->MultiCell(0,5,utf8_decode("Se extiende la presente para los fines legales que al interesado convengan en la ciudad de ".$dataGen[0]["inst_extiende"].", a los ").
		strtolower($fechaof).".",0,'J',FALSE);
		

		$pdf->setY(200);
		$pdf->Cell(174,3,"ATENTAMENTE",0,0,'C');

		$pdf->setY(205);		
		$pdf->Cell(82,3,"",0,0,'C');
		$pdf->Cell(10,3,"",0,0,'C');
		$pdf->Cell(82,3,utf8_decode("COTEJÓ"),0,0,'C');
		$pdf->SetFont('Arial','B',10);
		
		$pdf->setY(235);		
		$pdf->Cell(82,3,utf8_decode($dir),0,0,'C');
		$pdf->Cell(10,3,"",0,0,'C');
		$pdf->Cell(82,3,utf8_decode($ss),0,0,'C');
		
		$pdf->setY(240);	
		$pdf->Cell(82,3,"DIRECTOR GENERAL",0,0,'C');
		$pdf->Cell(10,3,"",0,0,'C');
		$pdf->Cell(82,3,utf8_decode($elpsto),0,0,'C');

		$pdf->SetFont('Arial','',7);
		$pdf->setY(248);
		$pdf->Cell(0,5,"C.c.p. Servicios Escolares.- Expediente del alumno",0,1,'L');
		$pdf->setY(251);
		$pdf->Cell(0,5,"C.c.p. Expediente del alumnos",0,1,'L');
		$pdf->setY(254);
		$pdf->Cell(0,5,"C.c.p. Expediente",0,1,'L');
	

		$pdf->Output(); 
	
	
 } else {header("Location: index.php");}
 
 ?>
