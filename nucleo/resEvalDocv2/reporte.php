
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
 
   	
			function LoadDatosAsignaturas()
			{		
                $data=[];		
                $miConex = new Conexion();
                $sql="select a.IDDETALLE, PROFESOR, PROFESORD, MATERIA, MATERIAD, SEMESTRE, SIE AS GRUPO, ".
                "(SELECT COUNT(DISTINCT(l.MATRICULA)) FROM ed_respuestasv2 l where l.TERMINADA='S' and l.IDGRUPO=a.IDDETALLE ".
                " or (l.IDGRUPO IN (SELECT DGRU_ID FROM edgrupos g where g.DGRU_BASE=a.IDDETALLE)) ".
                ") AS RES, ".
                " (select count(*) from dlista where (IDGRUPO=a.IDDETALLE)  ".
                " or (IDGRUPO IN (SELECT g.DGRU_ID FROM edgrupos g where IFNULL(g.DGRU_BASE,0)=a.IDDETALLE)) AND BAJA='N') AS ALUM ".
                " from vedgrupos a, cmaterias b  ".
                " where MATERIA=MATE_CLAVE  and ifnull(MATE_TIPO,'') AND EVALDOC='S' NOT IN ('T','RP','I')  AND IFNULL(a.BASE,'0')='0' and a.CICLO='".$_GET["ciclo"]."'  and PROFESOR='".$_GET["profesor"]."'". " ORDER BY SEMESTRE,MATERIAD";

                //echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

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
            

            function LoadDatosSecciones()
			{				
                $data=[];	
                $miConex = new Conexion();
                $sql="select  o.SECCION, o.LETRASECCION, count(*) AS NUM".
                " from ed_preguntas o  group by o.SECCION, o.LETRASECCION ORDER BY LETRASECCION";
                //echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


            function LoadDatosObs()
			{				
                $data=[];	
                $miConex = new Conexion();
                $sql="select DESCRIP AS OBS from ed_observa u, edgrupos v where u.IDGRUPO=v.DGRU_ID and ".
                "v.DGRU_PROFESOR='".$_GET["profesor"]."' and v.DGRU_CICLO='".$_GET["ciclo"]."' and DESCRIP<>''";

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
            

            function LoadEvalDoc()
			{				
                $data=[];	
                $miConex = new Conexion();
                $sql=" select * from ed_respuestasv2 b, cmaterias where MATERIA=MATE_CLAVE and ifnull(MATE_TIPO,'') NOT IN ('T','RP','I')  AND  b.CICLO='".$_GET["ciclo"]."' and b.PROFESOR='".$_GET["profesor"]."'";


				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
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
        $datagen=$pdf->LoadDatosGen();
        $elanio=substr($dataciclo[0]["CICL_INICIO"],6,4);
       
        $pdf->Ln(10);
        $pdf->Cell(0,0,utf8_decode('EVALUACIÓN DOCENTE'),0,1,'C');
        $pdf->Ln(5);
        $pdf->Cell(0,0,utf8_decode('TIPO DE EVALUACIÓN: POR ALUMNOS'),0,1,'C');
        $pdf->Ln(5);
        $pdf->Cell(0,0,utf8_decode('PERIODO: '.$dataciclo[0]["CICL_DESCRIP"]),0,1,'C');
        $pdf->Ln(5);
       
        /*
        $pdf->Cell(0,0,utf8_decode($datagen[0]["inst_razon"]),0,1,'C');
        $pdf->Ln(5);
        $pdf->Cell(0,0,utf8_decode("REPORTE POR PROFESOR"),0,1,'C');
        $pdf->SetFont('Arial','B',10);
        $pdf->Ln(5);
        $pdf->Cell(0,0,utf8_decode($_GET["deptod"]),0,1,'C');
        $pdf->Ln(5);
        */


        $pdf->SetFont('Arial','',8); 
        $pdf->Cell(50,5,'',0,0,'L',false);        
        $pdf->Cell(15,5,utf8_decode('Número:'),1,0,'L',false);
        $pdf->Cell(55,5,$_GET["profesor"],1,0,'L',false);
        $pdf->Cell(50,5,'',0,0,'L',false);  
        $pdf->Ln();

        $pdf->Cell(50,5,'',0,0,'L',false);        
        $pdf->Cell(15,5,utf8_decode('Nombre:'),1,0,'L',false);
        $pdf->Cell(55,5,utf8_decode($_GET["profesord"]),1,0,'L',false);
        $pdf->Cell(50,5,'',0,0,'L',false);  
        $pdf->Ln();
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(110,5,'MATERIA',1,0,'C',false);
        $pdf->Cell(20,5,'EVALUARON',1,0,'C',false);
        $pdf->Cell(20,5,'INSCRITOS',1,0,'C',false);
        $pdf->Cell(20,5,'PORCENTAJE',1,0,'C',false);
        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(110,20,20,20));
        $pdf->SetAligns(array('L','C','C','C'));
        $n=1;
        $dataAs=$pdf->LoadDatosAsignaturas();
        $evaluaron=0;$totalalum=0;
        foreach($dataAs as $row) {
            $pdf->Row(array( utf8_decode($row["MATERIA"]." ".$row["MATERIAD"]),
                             utf8_decode($row["RES"]),
                             utf8_decode($row["ALUM"]),
                             number_format(round(($row["RES"]/$row["ALUM"])*100,2),2)." %"
                             )
                      );
            $totalalum+=$row["ALUM"];
            $evaluaron+=$row["RES"];
            $n++;
        }

       
        $pdf->SetTextColor(0); 
        $pdf->Cell(110,5,'TOTAL EVALUARON',1,0,'R',false);
        $pdf->Cell(20,5,$evaluaron,1,0,'C',false);
        $pdf->Cell(20,5,'',1,0,'C',false);
        $pdf->Cell(20,5,'',1,0,'C',false);
        $pdf->Ln();
        $pdf->SetTextColor(0); 
        $pdf->Cell(110,5,'TOTAL DE ALUMNOS DE INSCRITOS',1,0,'R',false);
        $pdf->Cell(20,5,$totalalum,1,0,'C',false);
        $pdf->Cell(20,5,'',1,0,'C',false);
        $pdf->Cell(20,5,'',1,0,'C',false);
        $pdf->Ln();
        $pdf->SetTextColor(0); 
        $pdf->Cell(110,5,'PORCENTAJE',1,0,'R',false);
        $pdf->Cell(20,5,number_format(round(($evaluaron/$totalalum)*100,2),2),1,0,'C',false);
        $pdf->Cell(20,5,'',1,0,'C',false);
        $pdf->Cell(20,5,'',1,0,'C',false);
        $pdf->Ln(8);

        $miscolores="";
        $ethorizontal="chl=";
        $valores="chd=t:";
        $dataSec=$pdf->LoadDatosSecciones();
        $dataProf=$pdf->LoadEvalDoc();
        $proceso=[];
        $i=0;
        $j=0;
        $contCol=0;
        foreach($dataSec as $rowSec) {           
            $ethorizontal.=$rowSec["LETRASECCION"]."|";
            //calculamos el porcentaje de la seccion 
           
            $misPuntos=0;
            $contPuntos=0;
            
            for ($j=1; $j<=$rowSec["NUM"]; $j++) {
                $lin=1;
                 foreach($dataProf as $mieval) { 
                    $misPuntos+=$mieval["RESPUESTAS"][$contCol];
                    $contPuntos++;
                    $lin++;                
                    }
                    $contCol++;
                  //  echo "lineas: ".$lin."<br>";
                }
                

            $elpor=round(($misPuntos/$contPuntos),2);
            $valores.=$elpor.",";
           
           // echo "j:".$j." ".$contPuntos." / ".$misPuntos."=".$elpor."<br>";

           if ($elpor>0 && $elpor<=3.5) {$miscolores.="EC1C07|";} 
           if ($elpor>3.5 && $elpor<=3.74) {$miscolores.="834004|";}  //suficiente
           if ($elpor>3.75 && $elpor<=4.24) {$miscolores.="FBFF00|";}  
           if ($elpor>4.25 && $elpor<=4.74) {$miscolores.="0C9F02|";} 
           if ($elpor>4.75 && $elpor<=5) {$miscolores.="070EEC|";}   

    
            $proceso[$i]=$rowSec["SECCION"]."|".$elpor."|";
            $i++;
        }
 

        $miscolores=substr($miscolores,0,strlen($miscolores)-1);
        //echo $miscolores;
        $ethorizontal=substr($ethorizontal,0,strlen($ethorizontal)-1);
        $valores=substr($valores,0,strlen($valores)-1);

        //echo $ethorizontal;
        $tipogra="cht=bvs";
        $etBarra="chm=N,000000,0,-1,14";
        $escalaVal="chds=0,6";
        $etvertical="chxl=1:|0|1|2|3|4|5|";
        $titulo="chtt=";
        //$valores="chd=t:1.32,4.33,4.54,5,5,3,4,4.9,5,3.62";
        $tamanio="chs=660x400";
        //$colores="chco=FFC6A5|FFFF42|DEF3BD|00A5C6|DEBDDE|2B43CC|2BCCC5|CA623B|CA3B9A|268D9E";
        $colores="chco=".$miscolores;
        //$ethorizontal="chl=A|B|C|D|E|F|G|H|I|J";
        $verejes="chxt=x,y";
        $tambarra="chbh=60,2,20";

        $pdf->Image('http://chart.googleapis.com/chart?'.$tipogra."&".$etBarra."&".$escalaVal."&".$etvertical."&".
        $titulo."&".$valores."&".$tamanio."&".$verejes."&".$tambarra."&".$colores."&".$ethorizontal
        ,40, $pdf->getY(),130,60,'PNG');


        $pdf->setY($pdf->getY()+65);

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(90,5,'ASPECTOS',1,0,'C',false);
        $pdf->Cell(30,5,'PUNTAJE',1,0,'C',false);
        $pdf->Cell(30,5,utf8_decode('DESEMPEÑO'),1,0,'C',false);
        $pdf->Ln();
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(90,30,30));
        $pdf->SetAligns(array('L','C','C'));

        $i=0;
        $suma=0;
        foreach($proceso as $valor) {  
            $porc=explode("|",$valor)[1];
            
            if ($porc>0 && $porc<=3.5) {$etval="INSUFICIENTE";} 
            if ($porc>3.5 && $porc<=3.74) {$etval="SUFICIENTE";}  
            if ($porc>3.75 && $porc<=4.24) {$etval="BUENO";}  
            if ($porc>4.25 && $porc<=4.74) {$etval="NOTABLE";} 
            if ($porc>4.75 && $porc<=5) {$etval="EXCELENTE";}   

            $pdf->Row(array( utf8_decode(explode("|",$valor)[0]),
                             sprintf('%0.2f',$porc)." %",
                             utf8_decode($etval)
                           )
                      );
            (float)$suma+=(float)$porc;
           $i++;
        }
        $global=round(($suma/$i),2);
        $etval="";
        if ($global>0 && $global<=3.5) {$etval="INSUFICIENTE";} 
        if ($global>3.5 && $global<=3.74) {$etval="SUFICIENTE";}  
        if ($global>3.75 && $global<=4.24) {$etval="BUENO";}  
        if ($global>4.25 && $global<=4.74) {$etval="NOTABLE";} 
        if ($global>4.75 && $global<=5) {$etval="EXCELENTE";}   
      

        $pdf->SetTextColor(0);  
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(150,5,"RESULTADO GLOBAL ".$global." ".$etval,1,0,'C',false);
   
        $pdf->AddPage();
        $pdf->ln(10);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(0);  
        $pdf->Cell(10,5,'NO.',1,0,'L',false);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(10,5,$_GET["profesor"],1,0,'L',false);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,'NOMBRE',1,0,'L',false);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(120,5,utf8_decode($_GET["profesord"]),1,0,'L',false);
        $pdf->Ln(5);

        $dataObs=$pdf->LoadDatosObs();
        $pdf->SetWidths(array(170));
        $pdf->SetAligns(array('J'));
        foreach($dataObs as $row) {
            $pdf->Row(array( utf8_decode($row["OBS"]),                        
                             )
                      );
        }


         $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
