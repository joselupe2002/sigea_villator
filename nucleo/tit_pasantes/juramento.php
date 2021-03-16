
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
       
        
        function parseVar($key='',$value='') {
            if(empty($key) or empty($value)) return;
            $nb = $this->page;
            for($n=1;$n<=$nb;$n++) {
               $this->pages[$n] = str_replace($key,$value,$this->pages[$n]);
            }
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
 
   	
		
        
            function LoadDatosPas()
			{				
                $data=[];	
                $miConex = new Conexion();
                $sql="select * FROM vtit_pasantes WHERE MATRICULA='".$_GET["alumno"]."'";
              // echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }

            function LoadDepto($carrera)
			{				
                $data=[];	
                $miConex = new Conexion();
                $sql="select * FROM fures WHERE CARRERA='".$carrera."'";
              // echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }

			function LoadDatosGen()
			{
                $data=[];	
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta("SQLite","SELECT * from INSTITUCIONES where _INSTITUCION='".$_SESSION['INSTITUCION']."'");
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
			
			function Header()
			{

                $this->AddFont('Montserrat-Black','B','Montserrat-Black.php');
                $this->AddFont('Montserrat-Black','','Montserrat-Black.php');
                $this->AddFont('Montserrat-Medium','B','Montserrat-Medium.php');
                $this->AddFont('Montserrat-Medium','','Montserrat-Medium.php');
                $this->AddFont('Montserrat-SemiBold','','Montserrat-SemiBold.php');
                $this->AddFont('Montserrat-SemiBold','B','Montserrat-SemiBold.php');
                $this->AddFont('Montserrat-ExtraBold','B','Montserrat-ExtraBold.php');
                $this->AddFont('Montserrat-ExtraBold','','Montserrat-ExtraBold.php');
                $this->AddFont('Montserrat-ExtraBold','I','Montserrat-ExtraBold.php');
                $this->AddFont('Montserrat-ExtraLight','I','Montserrat-ExtraLight.php');
                $this->AddFont('Montserrat-ExtraLight','','Montserrat-ExtraLight.php');

				$this->Image('../../imagenes/empresa/fondo.png',0,0,187,275);
		        $this->Image('../../imagenes/empresa/encabezado.png',20,8,150);

               

			}
			
			

			function Footer()
			{	
                
           

            }
            


          

            function juramento() {
                $this->AddPage();       
                $dataP = $this->LoadDatosPas();
                $dataGen =$this->LoadDatosGen();           
                $miutil = new UtilUser();
                $this->eljefe=$dataP[0]["NOMBREJEFE"];
                $this->eljefepsto=$dataP[0]["FIRMAOF"];
                $fechadec=$miutil->formatFecha($dataP[0]["FECHA_TIT"]);
                $eldia=date("d", strtotime($fechadec));
                $elmes=$miutil->getFecha($fechadec,'MES');
                $elanio=date("Y", strtotime($fechadec));
                 

                $this->SetFont('Montserrat-ExtraBold','B',18);
                $this->Ln(10);
                $this->Cell(0,0,utf8_decode("TECOLÓGICO NACIONAL DE MÉXICO"),0,1,'C');                
                $this->Ln(10);
                $this->Cell(0,0,utf8_decode("CAMPUS SANTA MARÍA DE EL ORO"),0,1,'C');
                $this->Ln(10);

                $this->SetFont('Montserrat-ExtraBold','B',14);
                $this->Ln(10);
                $this->Cell(0,0,utf8_decode("JURAMENTO DE ETICA PROFESIONAL"),0,1,'C');
                $this->Ln(10);
                
                $this->SetFont('Montserrat-Medium','',12);
                $this->MultiCell(0,5,utf8_decode("Como profesionista, dedico mis conocimientos profesionales ".
                "al progreso y mejoramiento del bienestar humano. ")
                ,0,'C',FALSE);

                $this->Ln(5);
                $this->MultiCell(0,5,utf8_decode("Me comprometo: a dar  un rendimiento máximo,")
                ,0,'C',FALSE);
                $this->MultiCell(0,5,utf8_decode("a participar tan solo en empresas dignas;")
                ,0,'C',FALSE);
                $this->MultiCell(0,5,utf8_decode("a vivir de acuerdo con las leyes propias del hombre")
                ,0,'C',FALSE);
                $this->MultiCell(0,5,utf8_decode("y el más elevado nivel de conducta profesional;")
                ,0,'C',FALSE);
                $this->MultiCell(0,5,utf8_decode("a preferir el servicio al provecho;")
                ,0,'C',FALSE);
                $this->MultiCell(0,5,utf8_decode("el honor y la calidad de la profesión a la ventaja personal;")
                ,0,'C',FALSE);
                $this->MultiCell(0,5,utf8_decode("el bien público a toda consideración.")
                ,0,'C',FALSE);

                $this->Ln(10);
                $this->MultiCell(0,5,utf8_decode("Con respeto y horadez, hago el presente juramento.")
                ,0,'C',FALSE);

                $this->Ln(5);


                $this->Ln(15);
                $this->SetFont('Montserrat-ExtraBold','U',12);
                $this->Cell(0,0,utf8_decode($dataP[0]["PASANTE"]),0,1,'C');
                $this->Ln(5);
                $this->SetFont('Montserrat-Medium','',12);
                $this->Cell(0,0,utf8_decode($dataP[0]["CARRERAD"]),0,1,'C');
                $this->Ln(5);
              
                $this->setX(0);
                $this->SetLineWidth(1);
                $this->SetDrawColor(191, 142, 10 );
                $this->Cell(280,5,"","T",1,'C');
                
                

               
               
                $this->Ln(15);
                $this->SetFont('Montserrat-ExtraBold','',18);
                $this->Cell(0,0,utf8_decode("Excelencia en Educatión Tecnológica"),0,1,'C');
                $this->Ln(5);

           
                $this->SetFont('Montserrat-Medium','',10);
                $this->Ln(15);
                $this->Cell(0,0,utf8_decode("Santa María del Oro, Dgo., a ".$eldia." de ".$elmes. " de ". $elanio),0,1,'R');
                

            }


           
		}
        

		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(25, 25 , 25);
		$pdf->SetAutoPageBreak(true,30); 
       
        $miutil = new UtilUser();
        $pdf->juramento();
  
         $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
