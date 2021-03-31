
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
                $h=4*$nb;
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

/*===================================================================================================================*/
   	        var $eljefe="";
   	        var $eljefepsto="";
 
   	
			function LoadData()
			{				
                $miConex = new Conexion();

                $sql="select ID, LISCAL, (CASE WHEN EXTRA>0 THEN 'NA' ELSE LISCAL END) AS LISCAL1, ".
                                "(CASE WHEN EXTRA>0 AND EXTRA2=0 THEN LISCAL ELSE '' END) AS LISCAL2,".
                                "(CASE WHEN EXTRA2>0  THEN LISCAL ELSE '' END) AS LISCAL3,".
                       "LISFALT, (CASE WHEN NUMREP=1 THEN 'R' WHEN NUMREP>1 THEN 'E' ELSE '' END) AS REP, ".               
                "MATRICULA, NOMBRE, EXTRA".
                " from vboleta a  where  a.CICLO='".$_GET["ciclo"].
                "' and a.MATERIA='".$_GET["materia"]."' and a.GRUPO='".$_GET["grupo"]."' order by NOMBRE";
               // echo $sql;

        		$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
            
            function LoadDatosGrupo()
			{				
                $miConex = new Conexion();
                $sql="select * from vedgrupos a where a.IDDETALLE='".$_GET["id"]."'";
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }

            function LoadDatosCiclo()
			{				
                $miConex = new Conexion();
                $sql="select * from ciclosesc a where a.CICL_CLAVE='".$_GET["ciclo"]."'";
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }

            function LoadDatosUnidad()
			{				
                $miConex = new Conexion();
                $sql="select count(*) as N from eunidades a where a.UNID_MATERIA='".$_GET["materia"]."' and UNID_PRED=''";
               // echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }
            
            function LoadDatosProfesor()
			{				
                $miConex = new Conexion();
                $sql="select EMPL_NUMERO, CONCAT(EMPL_ABREVIA,' ',EMPL_NOMBRE, ' ',EMPL_APEPAT, ' ',EMPL_APEMAT) AS NOMBRE, ".
                " EMPL_DEPTO, EMPL_CORREOINS from pempleados a where a.EMPL_NUMERO='".$_GET["profesor"]."'";
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
				$miutil = new UtilUser();
                $miutil->getEncabezado($this,'V');	
                //Para que cuando se cambie a la otra pagina empiece a la derecha y la stablas no se descuadren
                $this->SetX(10);
				$this->Ln(5);		
			}
			
			

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'V');
				
				$this->SetX(10);$this->SetY(-30);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(60,5,'Firma del Docente','T',1,'L');
				
				
			}
			
			function LoadProf($matricula)
			{
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],
						"select  EMPL_NUMERO, CONCAT(EMPL_NOMBRE,' ',EMPL_APEPAT,' ',EMPL_APEMAT) as NOMBRE, EMPL_CORREOINS ".
						"from pempleados where EMPL_NUMERO='".$matricula."'");
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

		}
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(25, 25 , 25);
		$pdf->SetAutoPageBreak(true,40); 
		$pdf->AddPage();
		 
		$data = $pdf->LoadData();
        $miutil = new UtilUser();
        
        $pdf->Ln(5);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(0,0,utf8_decode('ACTA DE CALIFICACIÓN'),0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'MATERIA: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(50);$pdf->Cell(0,0,utf8_decode($_GET["materia"]."-".$_GET["materiad"]),0,1,'L');
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"FOLIO:",0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$_GET["id"],0,1,'R');
        $pdf->Ln(3);

        $dataGrupo = $pdf->LoadDatosGrupo();
        $dataCiclo = $pdf->LoadDatosCiclo();
        $dataUnidad = $pdf->LoadDatosUnidad();
        $numUni=$dataUnidad[0][0];
        $dataProfesor = $pdf->LoadDatosProfesor();
        $pdf->SetFont('Montserrat-Medium','B',5); 
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(255);
        $pdf->Cell(20,3,"LUNES",1,0,'L',true);
        $pdf->Cell(20,3,"MARTES",1,0,'L',true);
        $pdf->Cell(20,3,"MIERCOLES",1,0,'L',true);
        $pdf->Cell(20,3,"JUEVES",1,0,'L',true);
        $pdf->Cell(20,3,"VIERNES",1,0,'L',true);
        $pdf->Cell(20,3,"SABADO",1,0,'L',true);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"PERIODO:",0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$dataCiclo[0]["CICL_DESCRIP"],0,1,'R');
        $pdf->Ln(3);        
        $pdf->SetFont('Montserrat-Medium','',5); 
        $pdf->Cell(20,3,$dataGrupo[0]["LUNES"],1,0,'L');
        $pdf->Cell(20,3,$dataGrupo[0]["MARTES"],1,0,'L');
        $pdf->Cell(20,3,$dataGrupo[0]["MIERCOLES"],1,0,'L');
        $pdf->Cell(20,3,$dataGrupo[0]["JUEVES"],1,0,'L');
        $pdf->Cell(20,3,$dataGrupo[0]["VIERNES"],1,0,'L');
        $pdf->Cell(20,3,$dataGrupo[0]["SABADO"],1,0,'L');
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"FECHA:",0,1,'L');
        $fecha=date("d/m/Y");
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$fecha,0,1,'R');
        $pdf->Ln(6); 
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'DOCENTE: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(50);$pdf->Cell(0,0,utf8_decode($dataProfesor[0]["NOMBRE"]),0,1,'L');
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"GRUPO:",0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$_GET["semestre"].$_GET["grupo"],0,1,'R');
        $pdf->Ln(3); 
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'CARRERA: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(50);$pdf->Cell(0,0,utf8_decode($dataGrupo[0]["CARRERAD"]." ".$dataGrupo[0]["MAPA"]),0,1,'L');
       
        $pdf->Ln(3);

        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(255);  
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(7,5,'','TLR',0,'C',true);
        $pdf->Cell(17,5,'','TLR',0,'C',true);
        $pdf->Cell(85,5,'','TLR',0,'C',true);
        $pdf->Cell(15,5,'','TLR',0,'C',true);
        $pdf->Cell(30,5,'CALIFICACIONES','TLR',0,'C',true);
        $pdf->Cell(10,5,'','TLR',1,'C',true);

        $pdf->Cell(7,5,'NO.','LRB',0,'C',true);
        $pdf->Cell(17,5,'CONTROL','LRB',0,'C',true);
        $pdf->Cell(85,5,'NOMBRE','LRB',0,'C',true);
        $pdf->Cell(15,5,'FALTAS','LRB',0,'C',true);
        $pdf->Cell(15,5,utf8_decode('1RA'),1,0,'C',true);
        $pdf->Cell(15,5,utf8_decode('2DA'),1,0,'C',true);
       // $pdf->Cell(15,5,utf8_decode('3RA'),1,0,'C',true); //se quito no se manje 3ra oportunidad
        $pdf->Cell(10,6,'REP','LRB',0,'C',true);
        

        $pdf->Ln();
        $pdf->SetFont('Montserrat-Medium','',7);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(7,17, 85, 15,15,15,10));
        $pdf->SetAligns(array("L","L", "L", "C","C","C","C","C"));
        $n=1;

        $apr=0;
        foreach($data as $row) {
            //$pdf->Cell(70,5,$row["NOMBRE"],1,0,'L');            
            $pdf->Row(array($n,
                             utf8_decode($row["MATRICULA"]),
                             utf8_decode($row["NOMBRE"]),
                             utf8_decode($row["LISFALT"]),
                             utf8_decode($row["LISCAL1"]),
                             utf8_decode($row["LISCAL2"]),
                            // utf8_decode($row["LISCAL3"]), //se quito no se manje 3ra oportunidad
                             utf8_decode($row["REP"])                            
                             )
                      );
            if ($row["LISCAL"]>=70) {$apr++; }
            $n++;
        }
        
        $porapr=round(($apr/($n-1)),2)*100;
        $porrep=(100-$porapr);

        $pdf->SetFont('Montserrat-ExtraBold','B',9);
       // $pdf->SetFillColor(240, 249, 170);
        $pdf->SetTextColor(0);
        $pdf->SetTextColor(0, 9, 193);
        $pdf->Cell(94,5,utf8_decode('% DE APROBACIÓN'),1,0,'R',false);
        $pdf->Cell(15,5,$apr,1,0,'C',false);
        $pdf->Cell(55,5,$porapr." %",1,1,'L',false);
       
        $pdf->SetTextColor(193, 12, 0);
        $pdf->Cell(94,5,utf8_decode('% DE REPROBACIÓN'),1,0,'R',false);
        $pdf->Cell(15,5,($n-1)-$apr,1,0,'C',false);
        $pdf->Cell(55,5,$porrep." %",1,0,'L',false);

        
 
        $pdf->SetTextColor(0);
     

        if ($_GET["tipo"]=='0') { $pdf->Output(); }
		
		if ($_GET["tipo"]=='2') {
			$doc = $pdf->Output('', 'S');
			?>
		       <html lang="en">
	               <head>
						<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
						<meta charset="utf-8" />
						<link rel="icon" type="image/gif" href="imagenes/login/sigea.ico">
						<title>Sistema de Gesti&oacute;n Escolar-Administrativa</title>
						<meta name="description" content="User login page" />
						<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
						<link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
						<link rel="stylesheet" href="../../assets/font-awesome/4.5.0/css/font-awesome.min.css" />
						<link rel="stylesheet" href="../../assets/css/select2.min.css" />
						<link rel="stylesheet" href="../../assets/css/fonts.googleapis.com.css" />
					    <link rel="stylesheet" href="../../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
						<link rel="stylesheet" href="../../assets/css/ace-rtl.min.css" />		
						<script src="../../assets/js/ace-extra.min.js"></script>		
						<link rel="stylesheet" href="../../assets/css/jquery-ui.min.css" />
	                </head>
	      <?php 
					foreach($dataProfesor as $rowdes)
					{

						$res=$miutil->enviarCorreo($rowdes['EMPL_CORREOINS'],'SIGEA:ITSP Acta de Calificación'.$_GET["materia"]."-".$_GET["materiad"],
						'Materia:  '.$_GET["materia"]."-".$_GET["materiad"].'<br>'.
						'Grupo:  '.$_GET["grupo"].'<br>'.
						'Profesor: '.utf8_decode($dataProfesor[0]["NOMBRE"]).'<br>'.
						' <br/> En adjunto encontrará el Acta de Calificación. ',$doc);	
						if ($res=="") {echo "<span class=\"label label-success arrowed\">Correo Eviado a: ". $rowdes['NOMBRE']." ". $rowdes['EMPL_CORREOINS']."</span><br/><br/>"; }
						else { echo "<span class=\"label label-danger arrowed-in\">".$res."</span><br/><br/>"; }
						
					}
		}
		if ($_GET["tipo"]=='1') {
			$pdf->Output(); 
		}




 } else {header("Location: index.php");}
 
 ?>
