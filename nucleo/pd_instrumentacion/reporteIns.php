
<?php session_start(); if (($_SESSION['inicio']==1)) {
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	require('../../fpdf/fpdf.php');
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";
	$nivel="../../";

	class VariableStream
	{
		private $varname;
		private $position;
	
		function stream_open($path, $mode, $options, &$opened_path)
		{
			$url = parse_url($path);
			$this->varname = $url['host'];
			if(!isset($GLOBALS[$this->varname]))
			{
				trigger_error('Global variable '.$this->varname.' does not exist', E_USER_WARNING);
				return false;
			}
			$this->position = 0;
			return true;
		}
	
		function stream_read($count)
		{
			$ret = substr($GLOBALS[$this->varname], $this->position, $count);
			$this->position += strlen($ret);
			return $ret;
		}
	
		function stream_eof()
		{
			return $this->position >= strlen($GLOBALS[$this->varname]);
		}
	
		function stream_tell()
		{
			return $this->position;
		}
	
		function stream_seek($offset, $whence)
		{
			if($whence==SEEK_SET)
			{
				$this->position = $offset;
				return true;
			}
			return false;
		}
		
		function stream_stat()
		{
			return array();
		}

		
	}

	
   class PDF extends FPDF {
			   
			function __construct($orientation='P', $unit='mm', $size='A4')
			{
				parent::__construct($orientation, $unit, $size);
				// Register var stream protocol
				stream_wrapper_register('var', 'VariableStream');
			}

			function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
			{
				// Display the image contained in $data
				$v = 'img'.md5($data);
				$GLOBALS[$v] = $data;
				$a = getimagesize('var://'.$v);
				if(!$a)
					$this->Error('Invalid image data');
				$type = substr(strstr($a['mime'],'/'),1);
				$this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
				unset($GLOBALS[$v]);
			}

			function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
			{
				// Display the GD image associated with $im
				ob_start();
				imagepng($im);
				$data = ob_get_clean();
				$this->MemImage($data, $x, $y, $w, $h, $link);
			}

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
					$h=3*$nb;
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
						$this->MultiCell($w,3,$data[$i],0,$a);
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

	/*============================================================================================*/
	


   	     var $eljefe="";
   	       
   	       function getDatosPersona($num){   		       
            	$miConex = new Conexion();  
            	$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT EMPL_ABREVIA,EMPL_NOMBREC, EMPL_ULTIGRAD, EMPL_EGRESADODED, ".
            			"EMPL_FOTO, EMPL_HORAS, EMPL_DEPTOD, EMPL_JEFEFIRMAOF,EMPL_JEFEABREVIA,EMPL_JEFE, EMPL_JEFED, EMPL_RFC, EMPL_CURP, EMPL_NUMERO, EMPL_FECING ".
            			" FROM vempleados WHERE EMPL_NUMERO= '".$num."'" );
                foreach ($resultado as $row) {$data[] = $row;}            
            	return $data;            		
            }
   	
		
			function LoadCiclo($ciclo)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ciclosesc  where CICL_CLAVE='".$ciclo."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


			function LoadUnidades()
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from eunidades  where UNID_MATERIA='".$_GET["materia"]."' and UNID_PRED='' ORDER BY UNID_NUMERO");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

			function LoadTemas($pred)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from eunidades  where UNID_MATERIA='".$_GET["materia"]."' AND UNID_PRED='".$pred."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
			
			function LoadInd($pred)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from vins_indicadores  where IDGRUPO='".$_GET["id"]."' AND UNIDAD='".$pred."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

			function LoadAna($pred)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ins_analisis  where IDGRUPO='".$_GET["id"]."' AND UNIDAD='".$pred."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


			function LoadMat($pred)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ins_matriz  where IDGRUPO='".$_GET["id"]."' AND UNIDAD='".$pred."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


			function LoadNiv($pred)
			{			
				$data=[];	
				$miConex = new Conexion();
				
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ins_niveles  where IDGRUPO='".$_GET["id"]."' AND UNIDAD='".$pred."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


			function LoadCal()
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ins_calendario  where IDGRUPO='".$_GET["id"]."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}



			function LoadGrupo()
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from vedgrupos  where IDDETALLE='".$_GET["id"]."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				
				return $data;
			}


			function LoadCom($comp)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"select * from scatalogos where CATA_TIPO='COMPGENERICAS' AND ".
				" CATA_CLAVE IN ('".str_replace(",","','",$comp)."') order by CATA_CLAVE");	
		

				foreach ($resultado as $row) {
					$data[] = $row;
				}
				
				return $data;
			}

			function LoadAE($comp)
			{			
				$data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"select * from scatalogos where CATA_TIPO='ACTENSENANZA' AND ".
				" CATA_CLAVE IN ('".str_replace(",","','",$comp)."') order by CATA_CLAVE");	
		

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


			function LoadEncuadre()
			{
				$data=[];
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta("Mysql","SELECT * from encuadresadd where ENCU_IDDETGRUPO='".$_GET['id']."'");
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

			
			
			function Header()
			{
				$miutil = new UtilUser();
				$miutil->getEncabezado($this,'H');	
				$this->SetX(20);
				$this->Ln(5);		
			}
			
			

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'H');
				

				$dataEmpl = $this->getDatosPersona($_GET["prof"]);
				$this->setY(-38);	
				$this->Cell(120,4,utf8_decode($dataEmpl[0]["EMPL_ABREVIA"]." ".$dataEmpl[0]["EMPL_NOMBREC"]),0,0,'C');
				$this->Cell(120,4,utf8_decode($dataEmpl[0]["EMPL_JEFEABREVIA"]." ".$dataEmpl[0]["EMPL_JEFED"]),0,1,'C');
				$this->setY(-35);
				$this->Cell(120,4,utf8_decode("PROFESOR"),0,0,'C');
				$this->Cell(120,4,utf8_decode($dataEmpl[0]["EMPL_JEFEFIRMAOF"]),0,1,'C');

			}
			
			
			
			function loadMaterias()
			{
			    $entre=false;
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * FROM cmaterias where MATE_CLAVE='".$_GET["materia"]."'" );				
				foreach ($resultado as $row) {
					$data[] = $row;
					$entre=true;
				}
				if ($entre) {return $data;}
				else  return null;
			}
			

		
							
		}
			
		
		$pdf = new PDF('L','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(20, 25 , 25);
		$pdf->SetAutoPageBreak(true,40); 
		$pdf->AddPage();
		
		
		$dataMat = $pdf->loadMaterias();
		$dataGrupo = $pdf->loadGrupo();
		$dataCiclo = $pdf->loadCiclo($_GET["ciclo"]);
		$dataUni= $pdf->LoadUnidades();
		

		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Ln(); 
		$pdf->Cell(0,4,utf8_decode("Tecnológico Nacional de México"),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Subdirección Académica o su equivalente en los Institutos Tecnológicos Descentralizados"),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Instrumentación Didáctica para la Formación y Desarrollo de Competencias Profesionales"),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Periodo: ".$dataCiclo[0]["CICL_CLAVE"]." - ".$dataCiclo[0]["CICL_DESCRIP"]),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Nombre de la asignatura: ".$dataGrupo[0]["MATERIA"]),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Plan de estudios:".$dataGrupo[0]["MAPA"]." ".$dataGrupo[0]["CARRERAD"]),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Clave de asignatura: ".$dataGrupo[0]["MATERIA"]),0,1,'C'); 
		$pdf->Cell(0,4,utf8_decode("Horas teoría - horas prácticas - créditos: ".$dataGrupo[0]["HT"]."-".$dataGrupo[0]["HP"]."-".$dataGrupo[0]["CREDITOS"] ),0,1,'C'); 
	
		$pdf->Ln(10);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,4,utf8_decode("1. Caracterización de la asignatura:"),0,1,'L'); 
		$pdf->SetFont('Montserrat-Medium','',8);
		$pdf->MultiCell(0,4,utf8_decode($dataMat[0]["CARACTERIZACION"]),0,'J'); 

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,4,utf8_decode("2. Intención Didáctica:"),0,1,'L'); 
		$pdf->SetFont('Montserrat-Medium','',8);
		$pdf->MultiCell(0,4,utf8_decode($dataMat[0]["INTENCION"]),0,'J'); 

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,4,utf8_decode("3. Competencias de la asignatura:"),0,1,'L'); 
		$pdf->SetFont('Montserrat-Medium','',8);
		$pdf->MultiCell(0,4,utf8_decode($dataMat[0]["COMPETENCIAS"]),0,'J'); 

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','B',10);
		$pdf->Cell(0,4,utf8_decode("4. Análisis por competencias específicas"),0,1,'L'); 
		$pdf->SetFont('Montserrat-Medium','',8);
		$pdf->MultiCell(0,4,utf8_decode($dataMat[0]["COMPETENCIAS"]),0,'J'); 

		foreach($dataUni as $row){
			$pdf->SetFont('Montserrat-ExtraBold','B',10);
			$pdf->Cell(100,4,utf8_decode("Competencia No. ".$row["UNID_NUMERO"]),1,0,'L'); 
			$pdf->Cell(140,4,utf8_decode("Descripción: ".$row["UNID_DESCRIP"]),1,0,'L');
			$dataTemas= $pdf->LoadTemas($row["UNID_NUMERO"]);
			$dataAna= $pdf->LoadAna($row["UNID_NUMERO"]);

			if (!empty($dataAna)) {
					$dataComp=$pdf->LoadCom($dataAna[0]["DC_INS"]);
					$subtemas="";
					foreach($dataTemas as $row2){$subtemas.=$row2["UNID_DESCRIP"]."\n";}
					$competencias=""; $c=1;
					foreach($dataComp as $row3){ $competencias.=$c.". ".$row3["cata_descrip"]."\n";$c++;}

					$acten=""; $c=1;
					$dataacten=$pdf->LoadAE($dataAna[0]["ACTENSENANZA"]);
					foreach($dataacten as $row4){$acten.=$c.". ".$row4["cata_descrip"]."\n"; $c++;}

					$w = array(50,70,50,50,20);
				    $pdf->SetFont('Montserrat-ExtraBold','B',7);	
					$pdf->SetWidths($w);

					$pdf->Ln();
					$pdf->Row(array(utf8_decode("Temas y subtemas para desarrollar la competencia específica"),
									utf8_decode("Actividades de aprendizaje"),
									utf8_decode("Actividades de enseñanza"),
									utf8_decode("Desarrollo de competencias genéricas"),
									utf8_decode("Horas teórico-práctica")));
			
					$pdf->SetFont('Montserrat-Medium','B',7);
					
					$pdf->Row(array(
										utf8_decode(trim($subtemas)),
										utf8_decode(trim($row["UNID_ACTAPR"])),
										utf8_decode($acten),
										utf8_decode($competencias),
										utf8_decode("HT: ".$dataAna[0]["HORAST"]."\nHP: ".$dataAna[0]["HORASP"])	
										)
								);
							
						
					$pdf->Ln();	
					
					//indicadores del alcance
					$dataInd= $pdf->LoadInd($row["UNID_NUMERO"]);
					if (!empty($dataInd)) {
							$w = array(120,120);
							$pdf->SetFont('Montserrat-ExtraBold','B',7);	
							$pdf->SetWidths($w);
							$pdf->Row(array(utf8_decode("Indicadores de alcance"),
											utf8_decode("Valor del indicador ")
										));
							$pdf->SetFont('Montserrat-Medium','B',7);
							foreach($dataInd as $rowi){
							$pdf->Row(array(utf8_decode($rowi["LETRA"]." ".$rowi["INDICADORD"]),
											utf8_decode($rowi["LETRA"]." ".$rowi["VALOR"]." %")
										));
									}
					} else {
						$pdf->SetFont('Montserrat-ExtraBold','B',10);
						$pdf->Cell(0,4,utf8_decode("NO SE CAPTURO INFORMACIÓN DE INDICADORES DE ALCANCE"),0,1,'L');
					}
					$pdf->Ln();	
					$pdf->SetFont('Montserrat-ExtraBold','B',8);
					$pdf->Cell(0,4,utf8_decode("Niveles de desempeño"),0,1,'L');
					//Niveles de Desempeño
					$dataNiv= $pdf->LoadNiv($row["UNID_NUMERO"]);
					if (!empty($dataNiv)) {
							$w = array(60,60,60,60);
							$pdf->SetFont('Montserrat-ExtraBold','B',7);	
							$pdf->SetWidths($w);
							$pdf->Row(array(utf8_decode("Desempeño"),
											utf8_decode("Nivel de desempeño"),
											utf8_decode("Indicadores de alcance"),
											utf8_decode("Valoración numérica")
										));
							$pdf->SetFont('Montserrat-Medium','B',7);
					
							$pdf->Row(array(utf8_decode("Competencia Alcanzada"),
											utf8_decode("Excelente"),
											utf8_decode($dataNiv[0]["NEX"]),
											utf8_decode("(95-100)")
										));
							$pdf->Row(array(utf8_decode("Competencia Alcanzada"),
										utf8_decode("Notable"),
										utf8_decode($dataNiv[0]["NNO"]),
										utf8_decode("(85-94)")
									));
							$pdf->Row(array(utf8_decode("Competencia Alcanzada"),
									utf8_decode("Bueno"),
									utf8_decode($dataNiv[0]["NBU"]),
									utf8_decode("(75-84)")
								));
									
							$pdf->Row(array(utf8_decode("Competencia Alcanzada"),
								utf8_decode("Suficiente"),
								utf8_decode($dataNiv[0]["NSU"]),
								utf8_decode("(70-74)")
							));

							$pdf->Row(array(utf8_decode("Competencia No Alcanzada"),
								utf8_decode("Insuficiente"),
								utf8_decode($dataNiv[0]["NIN"]),
								utf8_decode("NA\n (No alcanzada)")
							));
					} else {
						$pdf->SetFont('Montserrat-ExtraBold','B',10);
						$pdf->Cell(0,4,utf8_decode("NO SE CAPTURO INFORMACIÓN DE LOS NIVELES DE DESEMPEÑO"),0,1,'L'); 
					}
					$pdf->Ln();	
					//Matriz de Evaluacion
					$pdf->SetFont('Montserrat-ExtraBold','B',8);
					$pdf->Cell(0,4,utf8_decode("Matriz de Evaluación"),0,1,'L');
					$dataMat= $pdf->LoadMat($row["UNID_NUMERO"]);
					if (!empty($dataMat)) {
							
							$pdf->SetFont('Montserrat-ExtraBold','B',7);	


							$titulos=array(utf8_decode("Evidencia de Aprendizaje"),utf8_decode("%"));

							$w = array(70,10);
							$an=intdiv(100,count($dataInd));
							foreach($dataInd as $rowi2){ 								
								array_push($titulos,$rowi2["LETRA"]);
								array_push($w,$an);			
							}
							array_push($titulos,utf8_decode("Evaluación formativa de la competencia"));
							array_push($w,60);	
						
							$pdf->SetWidths($w);
							$pdf->Row($titulos);

							$pdf->SetFont('Montserrat-Medium','B',7);
							foreach($dataMat as $rowm){

								$fila=array(utf8_decode($rowm["EVAPR"]),utf8_decode($rowm["PORC"]));
								foreach($dataInd as $rowi2){ 								
									array_push($fila,utf8_decode($rowm[$rowi2["LETRA"]]));								
								}
								array_push($fila,utf8_decode($rowm["EVALFOR"]));
								$pdf->Row($fila);
							}
						
																	
					} else {
						$pdf->SetFont('Montserrat-ExtraBold','B',10);
						$pdf->Cell(0,4,utf8_decode("NO SE CAPTURO INFORMACIÓN DE MATRIZ DE EVALUACIÓN"),0,1,'L');
					}


					$pdf->Ln();	

				}
			else {
				$pdf->SetFont('Montserrat-ExtraBold','B',10);
				$pdf->Cell(0,4,utf8_decode("NO SE HAN LLENADO TODOS LOS CAMPOS DE LA INSTRUMENTACIÓN"),0,1,'L'); 
			}
		}

		$pdf->SetFont('Montserrat-ExtraBold','B',8);
		$pdf->Cell(0,4,utf8_decode("7. Fuentes de información y apoyos didácticos"),0,1,'L');
		
		$dataEnc= $pdf->LoadEncuadre();
		$w = array(120,120);
		$pdf->SetFont('Montserrat-ExtraBold','B',7);	
		$pdf->SetWidths($w);
		$pdf->Row(array(utf8_decode("Fuentes de Información"),
						utf8_decode("Apoyos didácticos")
				));
		$pdf->SetFont('Montserrat-Medium','B',7);
		foreach($dataEnc as $rowe){
				$pdf->Row(array(utf8_decode($rowe["ENCU_BIBLIOGRAFIA"]),
								utf8_decode($rowe["ENCU_APOYOS"])
								));
		}
		$pdf->Ln();	
		// Calendarizacion en semanas

		$pdf->SetFont('Montserrat-ExtraBold','B',8);
		$pdf->Cell(0,4,utf8_decode("8. Calendarización de evaluación en semanas"),0,1,'L');

		$w = array(20);
		$an=intdiv(220,16);
		$dataCal= $pdf->LoadCal();

		$titulos=array(utf8_decode("Semana"));
		for ($i=1; $i<=16; $i++) { array_push($titulos,$i); array_push($w,$an);}
		$pdf->SetWidths($w);
		$pdf->Row($titulos);
		$pdf->SetFont('Montserrat-Medium','B',7);

		$titulos=array(utf8_decode("TP"));
		for ($i=1; $i<=16; $i++) { array_push($titulos,$i);}

		$fila=array(utf8_decode("TP"));
		for ($i=1; $i<=16; $i++) { 			
			$cad="";
			foreach($dataCal as $rowc){ 	
				if ($rowc["SEM"]==$i) {
					$cad=$rowc["TIPO"];
					break;
				}																		
			}
			array_push($fila,utf8_decode($cad));

		}
		$pdf->Row($fila);

		$fila=array(utf8_decode("TR"));
		for ($i=1; $i<=16; $i++) { array_push($fila,utf8_decode(""));	}
		$pdf->Row($fila);

		$fila=array(utf8_decode("SD"));
		for ($i=1; $i<=16; $i++) { array_push($fila,utf8_decode(""));	}
		$pdf->Row($fila);


		$pdf->SetFont('Montserrat-ExtraBold','B',8);
		$pdf->Cell(0,4,utf8_decode("TP: Tiempo Planeado                TR: Tiempo Real                                       SD: Seguimiento departamental"),0,1,'L');
		$pdf->Cell(0,4,utf8_decode("ED: Evaluación diagnóstica         EFn: Evaluación formativa (competencia específica n)  ES: Evaluación sumativa                         SD: Seguimiento departamental"),0,1,'L');

		$pdf->Ln(5);
		$pdf->Cell(0,4,utf8_decode("Fecha de Elaboración: ".$dataCal[0]["FECHAUS"]),0,1,'L');



		

		$pdf->Ln();	


			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
