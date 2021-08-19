
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

                $sql="select * from vss_alumnos a where a.CICLO='".$_GET["ciclo"].
                "' and a.CARRERA='".$_GET["carrera"]."'  order by NOMBRE";
               // echo $sql;

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

            
            
            function LoadDatosProfesor($prof)
			{				
                $miConex = new Conexion();
                $sql="select EMPL_NUMERO, CONCAT(EMPL_ABREVIA,' ',EMPL_NOMBRE, ' ',EMPL_APEPAT, ' ',EMPL_APEMAT) AS NOMBRE, ".
                " EMPL_DEPTO, EMPL_CORREOINS from pempleados a where a.EMPL_NUMERO='".$prof."'";
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
				$this->SetFont('Montserrat-ExtraBold','B',8);
				$this->MultiCell(100,3,$this->eljefepsto,'','L');
                $this->SetX(10);$this->SetY(-45);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(100,5,$this->eljefe,'T',0,'L');
				
				
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
		$pdf->SetAutoPageBreak(true,45); 
		$pdf->AddPage();
		 
		$data = $pdf->LoadData();
        $miutil = new UtilUser();
        

        
        $dataof=$miutil->verificaOficio("521","ACTASERSOC",$_GET["ciclo"]."_".$_GET["carrera"]);
        $elfolio=$dataof[0]["CONT_NUMOFI"];

        $pdf->Ln(5);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(0,0,utf8_decode('ACTA DE CALIFICACIÓN'),0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'MATERIA: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(50);$pdf->Cell(0,0,utf8_decode("SERVICIO SOCIAL"),0,1,'L');
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"FOLIO:",0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$elfolio,0,1,'R');
        $pdf->Ln(3);

        $dataCiclo = $pdf->LoadDatosCiclo();
        $dataProfesor = $pdf->LoadDatosProfesor("27");
        $pdf->SetFont('Montserrat-Medium','B',5); 
      
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);

        $eldoc=$miutil->getJefe('521');//Nombre del puesto DE SERVICIO SOCIAL 
        $pdf->eljefe=$eldoc;
        $pdf->eljefepsto="JEFE(A) DEL DEPARTAMENTO DE SERVICIO SOCIAL Y RESIDENCIAS PROFESIONALES";



        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'DOCENTE: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(50);$pdf->Cell(0,0,$eldoc,0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"PERIODO:",0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$dataCiclo[0]["CICL_DESCRIP"],0,1,'R');
        $pdf->Ln(3);        
      
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'CARRERA: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',8);$pdf->setX(50);$pdf->Cell(0,0,utf8_decode($data[0]["CARRERAD"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"FECHA:",0,1,'L');
        $fecha=date("d/m/Y");
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$fecha,0,1,'R');
        $pdf->Ln(3); 

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(145);$pdf->Cell(0,0,"GRUPO:",0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,"UNICO",0,1,'R');
        $pdf->Ln(3); 
      
        $pdf->Ln(3);

        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(255);  
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(7,5,'','TLR',0,'C',true);
        $pdf->Cell(17,5,'','TLR',0,'C',true);
        $pdf->Cell(85,5,'','TLR',0,'C',true);
        $pdf->Cell(55,5,'CALIFICACIONES','TLR',1,'C',true);


        $pdf->Cell(7,5,'NO.','LRB',0,'C',true);
        $pdf->Cell(17,5,'CONTROL','LRB',0,'C',true);
        $pdf->Cell(85,5,'NOMBRE','LRB',0,'C',true);
        $pdf->Cell(25,5,utf8_decode('CALIF.'),1,0,'C',true);
        $pdf->Cell(30,5,utf8_decode("DESEMPEÑO"),1,0,'C',true);

        

        $pdf->Ln();
        $pdf->SetFont('Montserrat-Medium','',7);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(7,17, 85, 25,30));
        $pdf->SetAligns(array("L","L", "L", "C","C","C","C","C"));
        $n=1;

        $apr=0;
        foreach($data as $row) {                      
            $pdf->Row(array($n,
                             utf8_decode($row["MATRICULA"]),
                             utf8_decode($row["NOMBRE"]),
                             utf8_decode($row["CALIFICACION"]),
                             utf8_decode($row["CALIFICACIONL"]),                      
                             )
                      );            
            $n++;
        }
        
 
        $pdf->SetTextColor(0);
     
        $pdf->Output();
        



 } else {header("Location: index.php");}
 
 ?>
