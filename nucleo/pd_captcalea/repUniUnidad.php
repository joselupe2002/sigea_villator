
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
                $data=[];		
                $miConex = new Conexion();
                $sql="select ID, concat(ALUM_NOMBRE,' ',ALUM_APEPAT,' ',ALUM_APEMAT) as NOMBRE, ALUM_MATRICULA, IF (LISCAL<70,'NA',LISCAL) as LISCAL".
                " from dlista a, falumnos b  where ALUCTR=ALUM_MATRICULA and a.PDOCVE='".$_GET["ciclo"].
                "' and a.MATCVE='".$_GET["materia"]."' and a.GPOCVE='".$_GET["grupo"]."' ORDER BY ALUM_APEPAT, ALUM_APEMAT, ALUM_NOMBRE";
         
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


            function LoadEvidencias($unidad)
			{		
                $data=[];		
                $miConex = new Conexion();
                $sql="select * from vins_matriz where IDGRUPO='".$_GET["id"]."' and UNIDAD='".$unidad."' ORDER BY ID";
                
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

            function LoadUnidades($materia)
			{		
                $data=[];		
                $miConex = new Conexion();
                $sql="select * from eunidades where UNID_PRED='' and UNID_MATERIA='".$materia."'";
                
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}



            function LoadValor($idalum,$idevid)
			{		
                $entre=false;
                $res="";
                $data=[];		
                $miConex = new Conexion();
                $sql="select LISCAL from dlista_eviapr where IDDLISTA='".$idalum."' AND IDEVAPR='".$idevid."' ORDER BY ID";
              
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {					
                    $res=$row[0];
                    $entre=true;
				}
				if ($entre) {return $res;} else {$res=0;}
			}


            function LoadValorFin($idalum,$launidad)
			{		
                $entre=false;
                $res="";
                $data=[];		
                $miConex = new Conexion();
                $sql="select LISCAL from dlista_eviapr_prom where IDDLISTA='".$idalum."' AND UNIDAD='".$launidad."' ORDER BY ID";
              
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {					
                    $res=$row[0];
                    $entre=true;
				}
				if ($entre) {return $res;} else {$res=0;}
			}
            
            function LoadDatosGrupo()
			{	
                $data=[];			
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
                " EMPL_DEPTO from pempleados a where a.EMPL_NUMERO='".$_GET["profesor"]."'";
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
				
				$this->SetX(10);$this->SetY(-40);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(60,5,'Firma del Profesor','T',1,'L');
				
				
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
		$pdf->SetAutoPageBreak(true,50); 
		$pdf->AddPage();
		 
		$data = $pdf->LoadData();
        $miutil = new UtilUser();
        
        $pdf->Ln(5);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(0,0,'REPORTE DE UNIDADES',0,1,'C');
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
       
     
        $dataUnid=$pdf->LoadUnidades($_GET["materia"]);
        foreach($dataUnid as $rowP) {  
                 $pdf->Ln(10);
                $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'UNIDAD: '.$rowP["UNID_NUMERO"]." ".$rowP["UNID_DESCRIP"],0,1,'L');
                $pdf->Ln(5);

                $pdf->SetFillColor(172,31,6);
                $pdf->SetTextColor(255);  
                $pdf->SetFont('Montserrat-ExtraBold','B',9);
                $pdf->Cell(7,5,'No ',1,0,'C',true);
                $pdf->Cell(17,5,'Control',1,0,'C',true);
                $pdf->Cell(60,5,'Nombre',1,0,'C',true);
                
                $dataEvid=$pdf->LoadEvidencias($rowP["UNID_NUMERO"]);
                for ($i=0;$i<count($dataEvid); $i++) {     
                    $pdf->SetFillColor(172,31,6);
                    $pdf->SetTextColor(255);                 
                    $pdf->Cell(10,5,$dataEvid[$i]["EVAPR"],1,0,'C',true); 
                }

                $pdf->SetFillColor(23,5,124);
                $pdf->SetTextColor(255);
                $pdf->Cell(10,5,'CF',1,0,'C',true);

                $pdf->Ln();
                $pdf->SetFont('Montserrat-Medium','',7);
                $pdf->SetFillColor(172,31,6);
                $pdf->SetTextColor(0);
                $arcol=array(7,17,60);
                for ($i=0;$i<count($dataEvid); $i++) {                        
                    $arcol[$i+3]="10"; 
                }
                $arcol[$i+3]="10"; 
                $pdf->SetWidths($arcol);
                $n=1;

                $apr=[];
                $fal=[];
                $contal=0;
                foreach($data as $row) {  
                      
                    $ardat=array($n,
                                utf8_decode($row["ALUM_MATRICULA"]),
                                utf8_decode($row["NOMBRE"]));
                    
                    for ($i=0;$i<count($dataEvid); $i++) {  
                            $datavalor=0;
                            $datavalor= $pdf->LoadValor($row["ID"],$dataEvid[$i]["ID"]);                    
                            $ardat[$i+3]=$datavalor; 
                            if ( $contal==0) {$apr[$i]=0; $fal[$i]=0;}
                            if ($datavalor>=70) { $apr[$i]=$apr[$i]+1;  }
                            if ($datavalor==0) { $fal[$i]=$fal[$i]+1;  }
                        }

                    $dataProm=$pdf->LoadValorFin($row["ID"],$rowP["UNID_NUMERO"]);
                    $ardat[$i+3]=$dataProm; 

                    $pdf->Row($ardat);
                    $n++;
                    $contal++;
                }

                $pdf->SetFillColor(172,31,6);
                $pdf->SetTextColor(255);  
                $pdf->SetFont('Montserrat-ExtraBold','B',8);
                $pdf->Cell(7,5,'ID',1,0,'C',true);
                $pdf->Cell(120,5,'Evidencia',1,0,'C',true);
                $pdf->Cell(10,5,'APR',1,0,'C',true);
                $pdf->Cell(10,5,'FAL',1,0,'C',true);
                $pdf->Cell(10,5,'%APR',1,1,'C',true);
                $pdf->SetFont('Montserrat-Medium','',7);
                $i=0;
                foreach($dataEvid as $rowE) {                   
                    $pdf->SetFillColor(255);
                    $pdf->SetTextColor(0);
                    $pdf->Cell(7,5,$rowE["EVAPR"],1,0,'C',true);
                    $pdf->Cell(120,5,utf8_decode($rowE["EVAPRD"]." ( ".$rowE["PORC"]."% )"),1,0,'L',true);
                    $pdf->Cell(10,5,$apr[$i],1,0,'L',true);
                    $pdf->Cell(10,5,$fal[$i],1,0,'L',true);
                    $pdf->Cell(10,5,round(($apr[$i]/$contal)*100)."%",1,1,'L',true);
                    $i++;
                }

                
            }

            $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
