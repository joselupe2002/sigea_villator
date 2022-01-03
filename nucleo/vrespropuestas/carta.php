

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
 

			function LoadJefe($mat)
			   {			
				   $data=[];	
				   $miConex = new Conexion();
				   $resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT CONCAT(EMPL_ABREVIA,' ',EMPL_NOMBRE,' ',EMPL_APEPAT,' ',EMPL_APEMAT) AS NOMBRE, EMPL_FIRMAOF from falumnos, ccarreras, pempleados ".
				   " where ALUM_MATRICULA='".$mat."' and ALUM_CARRERAREG=CARR_CLAVE AND EMPL_NUMERO=CARR_JEFE ");				
				   foreach ($resultado as $row) {
					   $data[] = $row;
				   }
				   return $data;
			   }

			function LoadData()
			{				
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from vrespropuestas where ID=".$_GET["id"]);				
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
			}
			
			

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'V');
				
				$this->SetX(10);$this->SetY(-60);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(0,0,'A T E N T A M E N T E',0,1,'L');
				
				$this->SetX(10);$this->SetY(-55);
				$this->SetFont('Montserrat-ExtraLight','I',8);
				$this->Cell(0,0,utf8_decode('Excelencia en Educación Tecnológica'),0,1,'L');

				
				$this->SetX(10);$this->SetY(-45);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(0,0,utf8_decode($this->eljefe),0,1,'L');
				
				$this->SetX(10);$this->SetY(-40);
				$this->Cell(0,0,utf8_decode($this->eljefepsto),0,1,'L');
				
				
				$this->SetX(10);$this->SetY(-30);
				$this->SetFont('Montserrat-Medium','',8);
				$this->Cell(0,0,"c.c.p. Archivo.",0,1,'L');
				
			}
		

		}
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(25, 25 , 25);
		$pdf->SetAutoPageBreak(true,30); 
		$pdf->AddPage();
		
	
		 
		$data = $pdf->LoadData();
		$miutil = new UtilUser();

		$dataJefe=$pdf->LoadJefe($data[0]["MATRICULA"]);
		$fechadec=$miutil->formatFecha($data[0]["INICIA"]);
		$fechaini=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del ".date("Y", strtotime($fechadec));
		
		$fechadec=$miutil->formatFecha($data[0]["TERMINA"]);
		$fechafin=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del ".date("Y", strtotime($fechadec));
		
		
		$dataGen = $pdf->LoadDatosGen();
	
	
		$dataof=$miutil->verificaOficio("402","CARTPRESRES",$_GET["id"]);
		
		$fechadecof=$miutil->formatFecha($dataof[0]["CONT_FECHA"]);
		$fechaof=date("d", strtotime($fechadecof))."/".$miutil->getFecha($fechadecof,'MES'). "/".date("Y", strtotime($fechadecof));
		
		
		$ss=$miutil->getJefe('402'); //empleado servicio social y residencia
		$elpsto=$miUtil->getDatoEmpl($miutil->getJefeNum('402'),"EMPL_FIRMAOF");
		$pdf->eljefe=$ss;
		$pdf->eljefepsto=$elpsto;


		//$pdf->eljefe=$dataJefe[0]["NOMBRE"];
		//$pdf->eljefepsto=$dataJefe[0]["EMPL_FIRMAOF"];
		
		
		$pdf->SetFont('Montserrat-Medium','',9);
		$pdf->Ln(10);
		$pdf->Cell(0,0,$dataGen[0]["inst_fechaof"]." ".$fechaof,0,1,'R');	
		$pdf->Ln(5);
		$pdf->Cell(0,0,'OFICIO No. '.utf8_decode($dataof[0]["CONT_NUMOFI"]),0,1,'R');
		$pdf->Ln(5);
		$pdf->Cell(0,0,utf8_decode('Asunto: Oficio de presentación.'),0,1,'R');

		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Ln(10);

		$pdf->Cell(0,4,utf8_decode(mb_strtoupper ($data[0]["PERSONA"])),0,1,'L');
		$pdf->Cell(0,4,utf8_decode(mb_strtoupper ($data[0]["PUESTO"])),0,1,'L');
		$pdf->MultiCell(120,5,utf8_decode(mb_strtoupper ($data[0]["EMPRESA"])),0,'L',false);
		$pdf->MultiCell(120,5,utf8_decode(mb_strtoupper ($data[0]["DOMICILIO"])),0,'L',false);

		$pdf->Ln(10);

		$pdf->SetFont('Montserrat-Medium','',9);
		$elperiodo='del '.$fechaini.' al '.$fechafin;
		$pdf->MultiCell(0,5,utf8_decode('El '.$dataGen[0]["inst_razon"].', tiene a bien presentar a sus finas atenciones al (la) C. ').
		utf8_decode($data[0]["NOMBRE"]).utf8_decode(" con número de control ").utf8_decode($data[0]["MATRICULA"]).
		utf8_decode(', de la carrera de ').utf8_decode($data[0]["CARRERAD"]).utf8_decode(", quien desea desarrollar en ese organismo el proyecto de Residencias ".
		"Profesionales, denominado: ").utf8_decode($data[0]["PROYECTO"]).utf8_decode(", cubriendo un total de 500 horas, en un período de cuatro a seis meses."),0,'J', false);
		$pdf->Ln(5);


		$pdf->MultiCell(0,5,utf8_decode('Es importante hacer de su conocimiento que todos los alumnos que se encuentran inscritos '.
		'en esta Institución cuentan con un seguro contra accidentes personales con la empresa: ').
		utf8_decode($dataGen[0]["inst_aux3"]). utf8_decode(" e inscripción en el IMSS: ").$data[0]["SEGURO"],0,'J', false);
		$pdf->Ln(5);

		$pdf->MultiCell(0,5,utf8_decode('Así mismo, hacemos patente nuestro sincero agradecimiento por su buena disposición y colaboración para que nuestros estudiantes, aun estando en proceso de formación, desarrollen un proyecto de trabajo profesional, donde puedan aplicar el conocimiento y el trabajo en el campo de acción en el que se desenvolverán como futuros profesionistas.'),0,'J', false);
		$pdf->Ln(5);
		
		$pdf->MultiCell(0,5,utf8_decode('Al vernos favorecidos con su participación en nuestro objetivo, sólo nos resta manifestarle la seguridad de nuestra más atenta y distinguida consideración.'),0,'J', false);
		$pdf->Ln(5);
	
		$firma=$miUtil->getDatoEmpl($miutil->getJefeNum('402'),"EMPL_FIRMA");
		$sello=$miUtil->getDatoEmpl($miutil->getJefeNum('402'),"EMPL_SELLO");
		if (($_GET["tipo"]=='1') ||($_GET["tipo"]=='2')) {			
			$pdf->Image($sello,150,185,45);
			$pdf->Image($firma,50,190,40);			
		}
		
		$pdf->Output(); 
	
		
 } else {header("Location: index.php");}
 
 ?>
