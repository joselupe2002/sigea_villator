
<?php session_start(); if (($_SESSION['inicio']==1)) {
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	require('../../fpdf/PDF_WriteTag.php');
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";
	$nivel="../../";
	

	
	class PDF extends PDF_WriteTag {
       
        
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
 
   	


            function LoadDatosCiclo()
			{		
                $data=[];			
                $miConex = new Conexion();
                $sql="SELECT * FROM ciclosesc where CICL_CLAVE='".$_GET["ciclo"]."'";               
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }
            

            function LoadDatosAlumnos()
			{		
                $data=[];			
                $miConex = new Conexion();
                $sql="SELECT * FROM falumnos, ccarreras where ALUM_MATRICULA='".$_GET["mat"]."' and ALUM_CARRERAREG=CARR_CLAVE";               
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
				$miutil = new UtilUser();
                //$miutil->getEncabezado($this,'V');			
                //Para que cuando se cambie a la otra pagina empiece a la derecha y la stablas no se descuadren

                $datagen=$this->LoadDatosGen();

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
                $this->Image('../../imagenes/empresa/tit_sep.png',20,8,60);
                $this->Image('../../imagenes/empresa/tit_tecnm.png',85,8,28);
                $this->Image('../../imagenes/empresa/pie1.png',115,8,13);
                $this->SetY(8);
                $this->SetFont('Arial','',12);
                $this->SetX(100);
                $this->SetFont('Montserrat-Medium','B',7);
                $this->Cell(0,3,utf8_decode("Secretaria de Educacion Pública"),0,1,'R');    
                $this->Cell(0,3,utf8_decode("Tecnológico Nacional de México"),0,1,'R');    
                $this->Cell(0,3,utf8_decode("Secretaria Académica, de Investigación e Innovación"),0,1,'R'); 
                $this->Cell(0,3,utf8_decode("Dirección de Docencia e Innovación Educativa"),0,1,'R'); 
                $this->Cell(0,3,utf8_decode("Campus Perote"),0,1,'R');   
                $this->Cell(0,3,utf8_decode("Departamento de Desarrollo Académico"),0,1,'R');            
                $this->Ln(5);	

                $this->SetX(10);
                $this->Ln(5);	
               
			}
            

        
			

			function Footer()
			{	                
                $miutil = new UtilUser(); 
                $datagen=$this->LoadDatosGen();           
               // $miutil->getPie($this,'V');

                $direccion=$datagen[0]["inst_direccion"];
			    $telefonos=$datagen[0]["inst_telefono"];
			    $pagina=$datagen[0]["inst_pagina"];

                
                $this->SetX(20);
                $this->SetY(-20);
                $this->SetFont('Montserrat-Medium','B',7);
                $this->Cell(90,3,utf8_decode("Km. 2.5 Carretera Perote-México 91270 Perote, Ver."),0,1,'C');  
                $this->Cell(90,3,utf8_decode("Tel(s). 2282 825 3150 y 51 2281 825 36 63 Fax. 22 81 26 21 27 Cel. Fax (922) 22 243 36"),0,1,'C');  
                $this->Cell(90,3,utf8_decode("Correos: dda_dperote@tecnm.mx, dir_perote@tecnm.mx"),0,1,'C');  

               $this->Image('../../imagenes/empresa/ed_pie1.png',120,250,18);
               $this->Image('../../imagenes/empresa/ed_pie2.png',140,250,18);
               $this->Image('../../imagenes/empresa/ed_pie3.png',160,250,19);
               $this->Image('../../imagenes/empresa/ed_pie4.png',180,250,21);

               $this->SetDrawColor(243, 240, 229);
               $this->SetLineWidth(0.7);
               $this->Line(20,247,200,247);
		
			}

		}
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(25, 20 , 15);
		$pdf->SetAutoPageBreak(true,30); 
        $pdf->AddPage();

        $dataciclo=$pdf->LoadDatosCiclo();
        $dataalum=$pdf->LoadDatosAlumnos();
        $datagen=$pdf->LoadDatosGen();
      
        $pdf->Ln(10);
        $pdf->SetFont('Montserrat-Black','B',8);
        $pdf->Cell(0,0,utf8_decode('DEPENDENCIA: EVALUACIÓN DOCENTE'),0,1,'R');
        $pdf->Ln(5);
        $pdf->Cell(0,0,utf8_decode('EXPEDIENTE:'.$_GET['mat']),0,1,'R');
        $pdf->Ln(5);
        $pdf->Cell(0,0,utf8_decode('ASUNTO: ACUSE DE REGISTRO'),0,1,'R');
        $pdf->Ln(5);
       
        $pdf->SetFont('Montserrat-Black','B',10);
        $pdf->Cell(0,0,utf8_decode('A QUIEN CORRESPONDA: '),0,1,'L');
        $pdf->Ln(5);

        $pdf->SetFont('Montserrat-Medium','B',11);        
        $pdf->MultiCell(0,5,utf8_decode('Por medio de la presente hago constar que el (la) C.'),0,'J',false);
        $pdf->Ln(10);
        $pdf->SetFont('Montserrat-Black','B',11);  
        $pdf->MultiCell(0,5,utf8_decode($dataalum[0]["ALUM_NOMBRE"]." ".$dataalum[0]["ALUM_APEPAT"]." ".$dataalum[0]["ALUM_APEMAT"]),0,'C',false);
        $pdf->Ln(10);

        $pdf->SetFont('Montserrat-Medium','B',11);  

        $pdf->SetStyle("p","Montserrat-Medium","",11,"0,0,0");
        $pdf->SetStyle("vs","Montserrat-Medium","U",11,"0,0,0");
		$pdf->SetStyle("vsb","Montserrat-Medium","UB",11,"0,0,0");
        $pdf->SetStyle("vb","Montserrat-ExtraBold","B",11,"0,0,0");
        
        $pdf->WriteTag(0,5,utf8_decode("<p>PERTENECIENTE A LA CARRERA DE <vb>".$dataalum[0]["CARR_DESCRIP"].
        "</vb>, REALIZÓ LA EVALUACIÓN DOCENTE CORRESPONDIENTE AL PERIODO <vb>".$dataciclo[0]["CICL_CLAVE"]." ".$dataciclo[0]["CICL_DESCRIP"].
        "</vb>. POR TAL MOTIVO, SUS REGISTROS CORRESPONDIENTES A SUS MAESTROS HA QUEDADO REGISTRADO SATISFACTORIAMENTE.</p>"
        ),0,'J',false);
         

         //CODIGO QR
         $cadena= "OF:".$dataalum[0]["ALUM_MATRICULA"]."|".str_replace(" ","|",$dataalum[0]["ALUM_NOMBRE"]).
         "|".str_replace(" ","|",$dataalum[0]["ALUM_APEPAT"])."|".str_replace(" ","|",$dataalum[0]["ALUM_APEMAT"])."|".
         str_replace(" ","|",$dataalum[0]["CARR_DESCRIP"])."|".$_GET["ciclo"]."|";     
         $pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$cadena.'&.png',20,150,35,35);     


         $pdf->Output(); 

 } else {header("Location: index.php");}
 
 ?>
