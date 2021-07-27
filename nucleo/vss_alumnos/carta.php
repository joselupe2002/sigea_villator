

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
			var $ini="";
			var $fin="";
 
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
			
			function Header()
			{
				$miutil = new UtilUser();
				$miutil->getEncabezado($this,'V');			
			}
			
			

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'V');
				
				$this->SetX(10);$this->SetY(-80);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(0,0,'A T E N T A M E N T E',0,1,'L');
				
				$this->SetX(10);$this->SetY(-75);
				$this->SetFont('Montserrat-ExtraLight','I',8);
				$this->Cell(0,0,utf8_decode('Excelencia en Educación Tecnológica'),0,1,'L');

				
				$this->SetX(10);$this->SetY(-65);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(0,5,utf8_decode($this->eljefe),0,1,'L');
				
				$this->SetX(10);$this->SetY(-60);
				$this->MultiCell(150,3,utf8_decode($this->eljefepsto),0,'L');
				
				
				$this->SetX(10);$this->SetY(-40);
				$this->SetFont('Montserrat-Medium','',8);
				$this->Cell(0,0,utf8_decode("** El Servicio Social se valida en la Dependencia e inicia a partir del ".$this->ini."  al ".$this->fin."** "),0,1,'C');
				
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
		$fechadec=$miutil->formatFecha($data[0]["INICIO"]);
		$fechaini=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del ".date("Y", strtotime($fechadec));
		$pdf->ini=$fechaini;

		$fechadec=$miutil->formatFecha($data[0]["TERMINO"]);
		$fechafin=date("d", strtotime($fechadec))." de ".$miutil->getFecha($fechadec,'MES'). " del ".date("Y", strtotime($fechadec));
		$pdf->fin=$fechafin;
		
		$dataGen = $pdf->LoadDatosGen();
	
	
		$dataof=$miutil->verificaOficio("521","CARTAPRES",$_GET["id"]);
		
		$fechadecof=$miutil->formatFecha($dataof[0]["CONT_FECHA"]);
		$fechaof=date("d", strtotime($fechadecof))."/".$miutil->getFecha($fechadecof,'MES'). "/".date("Y", strtotime($fechadecof));
		
		
		$ss=$miutil->getJefe('521'); //empleado servicio social y residencia
		$elpsto=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_FIRMAOF");
		$pdf->eljefe=$ss;
		$pdf->eljefepsto=$elpsto;
		
		
		$pdf->SetFont('Montserrat-Medium','',9);
		$pdf->Ln(10);		
		$pdf->Cell(0,0,'OFICIO No. '.utf8_decode($dataof[0]["CONT_NUMOFI"]),0,1,'R');
		$pdf->Ln(5);
		$pdf->Cell(0,0,'ASUNTO: '.utf8_decode("Oficio de Presentación Servicio Social"),0,1,'R');
		$pdf->Ln(5);
		$pdf->Cell(0,0,utf8_decode("Anexo XXI del SGI"),0,1,'R');
		$pdf->Ln(5);
		$pdf->Cell(0,0,$dataGen[0]["inst_fechaof"]." ".$fechaof,0,1,'R');	
		
		

		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Ln(10);

		$pdf->Cell(0,4,utf8_decode(strtoupper ($data[0]["REPRESENTANTE"])),0,1,'L');
		$pdf->Cell(0,4,utf8_decode(strtoupper ($data[0]["PUESTO"])),0,1,'L');
		$pdf->MultiCell(150,5,utf8_decode(strtoupper ($data[0]["EMPRESA"])),0,'L',false);
		$pdf->MultiCell(150,5,utf8_decode(strtoupper ($data[0]["MUNICIPIOD"]).", ".strtoupper ($data[0]["ESTADOD"])),0,'L',false);
		$pdf->MultiCell(150,5,utf8_decode("PRESENTE"),0,'L',false);

		$pdf->Ln(10);

		if ($data[0]["SEGURO1"]=='') {$elseguro="EN TRÁMITE";} else {$elseguro=$data[0]["SEGURO1"];}
		$pdf->SetFont('Montserrat-Medium','',10);
		$elperiodo='del '.$fechaini.' al '.$fechafin;
		$pdf->MultiCell(0,8,utf8_decode('Por este conducto, presentamos a sus finas atenciones al (la): C. ').utf8_decode($data[0]["NOMBRE"]).
		utf8_decode(", con número de control escolar: ").utf8_decode($data[0]["MATRICULA"]).utf8_decode(",  ESTUDIANTE de la carrera de: ").utf8_decode($data[0]["CARRERAD"]).
		utf8_decode(", sistema: ".utf8_decode($data[0]["MODALIDADALD"])." con  No. de seguridad social IMSS: ".$elseguro.
		", quien desea realizar su Servicio Social en esa Dependencia, cubriendo un total de 480 horas y máximo 500 en el programa: ").
		utf8_decode($data[0]["PROGRAMA"]). utf8_decode(", en un periodo mínimo de seis meses y no mayor de dos años."),0,'J', false);
		$pdf->Ln(5);



		$pdf->MultiCell(0,8,utf8_decode('Agradezco las atenciones que se sirva brindar al (la) portador (a) de la presente. '),0,'J', false);

		$firma=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_FIRMA");
		$sello=$miUtil->getDatoEmpl($miutil->getJefeNum('521'),"EMPL_SELLO");
		if (($_GET["tipo"]=='1') ||($_GET["tipo"]=='2')) {			
			$pdf->Image($sello,150,194,45);
			$pdf->Image($firma,50,190,40);			
		}
		
	
		$pdf->Ln(5);
		
		$pdf->Output(); 
	
		
 } else {header("Location: index.php");}
 
 ?>
