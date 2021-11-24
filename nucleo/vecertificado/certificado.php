
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
        var $border;
        var $fondo;

        function SetWidths($w) {$this->widths=$w;}
        
        function SetAligns($a) {$this->aligns=$a;}

        function SetBorder($bor) {$this->border=$bor;}

        function SetFondo($fon) {$this->fondo=$fon;}
        
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
                    $bor=$this->border[$i];
                    $fon=isset($this->fondo[$i]) ? $this->fondo[$i] : false;
                    
                    $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                    //Save the current position
                    $x=$this->GetX();
                    $y=$this->GetY();
                    //Draw the border
                   // $this->Rect($x,$y,$w,$h);
                    //Print the text
                    $this->MultiCell($w,3,$data[$i],$bor,$a,$fon);
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
 
   	
			function LoadDatosCursadas()
			{				
                $miConex = new Conexion();
                $sql="SELECT MATRICULA, NOMBRE,MATERIA, MATERIAD, SEMESTRE, GPOCVE,". 
                "(CASE WHEN TIPOMAT='AC' THEN (select CALLET from ecalcertificado i where i.MATRICULA=a.MATRICULA and i.MATERIA=a.MATERIA limit 1)".
                "      WHEN TIPOMAT='SS' THEN (select CALLET from ecalcertificado i where i.MATRICULA=a.MATRICULA and i.MATERIA=a.MATERIA limit 1) ".
                " ELSE CAL END) AS CAL,".
                "TCAL,CICLO,CREDITO,TIPOMAT, VECES, PRIMERA, SEGUNDA, TERCERA FROM kardexcursadas a ".
                " where MATRICULA='".$_GET["matricula"]."' AND CAL>=70 AND CERRADO='S' AND CERRADO='S' ORDER BY SEMESTRE, MATERIAD";
            
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

            function LoadDatosCertificado()
			{				
                $miConex = new Conexion();
                $sql="SELECT * FROM vecertificado where FOLIO='".$_GET["folio"]."'";                
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }
            

            function LoadDatosAlumnos()
			{				
                $miConex = new Conexion();
                $sql="select ALUM_MATRICULA, ALUM_SEXO AS SEXO, CONCAT(ALUM_NOMBRE, ' ',ALUM_APEPAT, ' ',ALUM_APEMAT) AS NOMBRE, ".
                " ALUM_CARRERAREG AS CARRERA, ALUM_ACTIVO AS SITUACION, ALUM_CICLOTER AS CICLOTER, ".
                " ALUM_CICLOINS AS CICLOINS, CARR_DESCRIP AS CARRERAD, ".
                " PLACRED, PLAMAT,  c.CLAVEOF AS ESPECIALIDAD, ALUM_MAPA AS MAPA,".
                " getavance('".$_GET["matricula"]."') as AVANCE, ".
                " getPromedio('".$_GET["matricula"]."','N') as PROMEDIO_SR,".
                " getPromedio('".$_GET["matricula"]."','S') as PROMEDIO_CR, ".
                " getPeriodos('".$_GET["matricula"]."',getciclo()) AS PERIODOS,".
                " (select SUM(a.CREDITO) from kardexcursadas a where a.CICLO=getciclo() and CERRADO='N' and a.MATRICULA='".$_GET["matricula"]."') AS CRECUR, ".
                " (select SUM(a.CREDITO) from kardexcursadas a where CERRADO='S' and a.cal>=70 and a.MATRICULA='".$_GET["matricula"]."') AS CREDACUM ".
                " from falumnos a LEFT outer JOIN especialidad c on (a.ALUM_ESPECIALIDAD=c.ID), ccarreras b, mapas d where ".
                " CARR_CLAVE=ALUM_CARRERAREG".
                " and ALUM_MAPA=d.MAPA_CLAVE and a.ALUM_MATRICULA='".$_GET["matricula"]."'";
               //echo $sql;
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
			}
			

			function Footer()
			{				
               
			}
			
		}
		
		$pdf = new PDF('P','mm');
		header("Content-Type: text/html; charset=UTF-8");
		
        
        $pdf->AddFont('Humanst521 BT','B','Humanst521 BT.php');
        $pdf->AddFont('Humanst521 BT','','Humanst521 BT.php');
        $pdf->AddFont('Humanst521 BT Bold','B','Humanst521 BT Bold.php');
        $pdf->AddFont('Humanst521 BT Bold','','Humanst521 BT Bold.php');
        $pdf->AddFont('lucida-sans-unicode_[allfont.es]','B','lucida-sans-unicode_[allfont.es].php');
        $pdf->AddFont('BRITANIC','B','BRITANIC.php');
        $pdf->AddFont('Britannic Bold Regular','B','Britannic Bold Regular.php');
        
	
		$pdf->SetMargins(43, 13, 12);
        $margeniz=40;
		$pdf->SetAutoPageBreak(true,10); 
        $pdf->AddPage();
       
        $pdf->Ln(3);
        $pdf->SetStyle("p","arial","",8,"0,0,0");
        $pdf->SetStyle("vb","arial","B",8,"0,0,0");
        $pdf->SetStyle("VB","arial","B",8,"0,0,0");

        $pdf->SetFont('Humanst521 BT Bold','B',12);
        $pdf->Cell(0,5,utf8_decode('GOBIERNO DEL ESTADO DE VERACRUZ DE IGNACIO DE LA LLAVE'),0,1,'C');
        $pdf->Cell(0,5,utf8_decode('INSTITUTO TECNOLÓGICO SUPERIOR DE PEROTE'),0,1,'C');

        $pdf->SetFont('Arial','',8);
       // $pdf->Image("../../imagenes/empresa/logo2.png",12,8,23.2);

      		 
        $pdf->SetTextColor(0);
        $dataAlum = $pdf->LoadDatosAlumnos();
        $data = $pdf->LoadDatosCursadas();
        $data2 = $pdf->LoadDatosGen();
        $dataCer = $pdf->LoadDatosCertificado();
        $miutil = new UtilUser();
        $nombre=$miutil->getJefe('101');//Nombre del puesto DIRECTOR GENERAL
        $nombreEsc=$miutil->getJefe('303');//Nombre del puesto ESCOLARES

        
        $iniCiclo=$miutil->formatFecha($dataCer[0]["FECHAINICIO"]);
        $cadInicio=strtoupper($miutil->getMesLetra(date("m", strtotime($iniCiclo))). " DE ".date("Y", strtotime($iniCiclo)));

        $finCiclo=$miutil->formatFecha($dataCer[0]["FECHATERMINO"]);
        $cadfin=strtoupper($miutil->getMesLetra(date("m", strtotime($finCiclo))). " DE ".date("Y", strtotime($finCiclo)));

		
        $pdf->setX($margeniz);
        $pdf->setY(25);
        $pdf->SetFont('Humanst521 BT','B',10);
     
       
        $txt=utf8_decode("<p>FOLIO:   <vb>".$dataCer[0]["FOLIO"]."</vb> - L ");
        $pdf->Ln();
        $pdf->SetStyle("p","arial","",8,"0,0,0");
        $pdf->SetStyle("vb","arial","B",8,"0,0,0");
        $pdf->SetStyle("VB","arial","B",8,"0,0,0");
        $pdf->WriteTag(0,3,$txt,0,"R",0,0);

        $mgTabla=43;
        $pdf->setY(35);
        $pdf->SetFont('Humanst521 BT','B',10);
        $ella='EL'; if ($dataAlum[0]["SEXO"]==2) {$ella='LA';}
        $txt=utf8_decode("<p>EL C. <vb>".$nombre."</vb> DIRECTOR GENERAL DEL <vb>". $data2[0]["inst_razon"].
        "</vb> CLAVE <vb>". $data2[0]["inst_claveof"]."</vb>, CERTIFICA, QUE SEGÚN CONSTANCIAS QUE EXISTEN EN EL ARCHIVO ESCOLAR DE ESTE INSTITUTO, ".$ella." C. <vb>".
        $dataAlum[0]["NOMBRE"]."</vb> CURSÓ LAS ASIGNATURAS QUE INTEGRAN EL PLAN DE ESTUDIOS DE LA CARRERA DE <vb>".$dataAlum[0]["CARRERAD"]."</vb>".
        " (PLAN - CRÉDITOS) <vb>". $cadInicio."</vb> A <vb>".$cadfin."</vb>".
        ", CON LOS RESULTADOS QUE A CONTINUACIÓN SE ANOTAN:</p>");

        $pdf->Ln();
        $pdf->SetStyle("p","arial","",8,"0,0,0");
        $pdf->SetStyle("vb","arial","B",8,"0,0,0");
        $pdf->SetStyle("VB","arial","B",8,"0,0,0");
        $pdf->WriteTag(0,3,$txt,0,"J",0,0);

        $pdf->SetTextColor(0);
        $pdf->SetFont('Humanst521 BT','',8);

        $pdf->setY(54);$pdf->setX(4);
        $pdf->Cell(32,5,'MATRICULA',"LRT",1,'C');
        $pdf->setX(4);
        $pdf->SetFont('Humanst521 BT','B',8);
        //$pdf->Cell(32,5,$dataCer[0]["MATRICULA"],"LTRB",0,'C');
        $pdf->WriteTag(32,5,"<vb>".$dataCer[0]["MATRICULA"]."</vb>",1,"C",0,0);

        $colMat=90; $colCal=21; $colObs=32; $colCre=13; 
        
        $pdf->setY(54);  $pdf->setX($mgTabla);    
        $pdf->SetFont('Humanst521 BT','B',8);
        $pdf->Cell($colMat,5,'MATERIA','TBL',0,'L');
        $pdf->Cell($colCal,5,'CALIF.','TLBR',0,'C');
        $pdf->Cell($colObs,5,'OBSERVACIONES',1,0,'C');
        $pdf->Cell($colCre,5,utf8_decode('CR'),1,0,'C');

        
        /*=======================colacamos las calificaciones ==========================*/
        $pdf->Ln();
        $pdf->SetFont('Humanst521 BT','',7);
        $pdf->SetWidths(array($colMat,$colCal ,$colObs,$colCre));
        $pdf->SetAligns(array('L', 'C','J','C'));
        $pdf->SetBorder(array('L', 'L','LR','R'));
        
        $n=0;
        $sumacal=0;
        $totcred=0;
          
        foreach($data as $row) {   
            $pdf->setX($mgTabla); 
            $cadRev='';
            $mical=$row["CAL"];
            if ($row["TIPOMAT"]=='SC') {
                if ($row["CAL"]>=70) {$mical="AC";} else {$mical='NA';}
            }
            if (($row["TCAL"]=='93') && (($row["TIPOMAT"]!='AC') && ($row["TIPOMAT"]!='SS'))) {$cadRev='*';}
            $pdf->Row(array( utf8_decode($row["MATERIAD"]." ".$cadRev),
                             utf8_decode($mical),
                             "",
                             str_pad($row["CREDITO"],  2, "0",STR_PAD_LEFT)                             
                             )
                      );
            $totcred+=$row["CREDITO"];
            if  (($row["TIPOMAT"]!='AC') && ($row["TIPOMAT"]!='SS') && ($row["TIPOMAT"]!='SC')) {
                $sumacal+=$row["CAL"];
                $n++;
            }            
        }

        //echo $pdf->getY();

        $matFin; $cadCer="CERTIFICADO"; if ($dataAlum[0]["PLACRED"]>$totcred)  {$cadCer="CERTIFICADO"; $matFin="***** I N C O M P L E T O *****"; }

        $pdf->SetBorder(array('L','L','LR','R'));
        $pdf->SetFondo(array(false,false,false,false));
        $pdf->Row(array($matFin,"","",""));

        
        while ($pdf->getY()<=229) {
            $pdf->setX($mgTabla); 
            $pdf->SetBorder(array('L','L','LR','R'));
            $pdf->SetFondo(array(false,false,false,false));
            $pdf->Row(array("","","",""));
        }
    
        /*=======================colacamos el promedio ==========================*/
        $pdf->setX($mgTabla); 
        
        $pdf->SetFont('Humanst521 BT Bold','B',11);
        $promedio=round($sumacal/($n),2);
        $promedio=number_format($promedio, 2, '.', ',');
        $pdf->SetWidths(array($colMat,$colCal ,$colObs,$colCre));
        $pdf->SetFillColor(223, 223, 223);
        $pdf->SetBorder(array('1', '1','1','1'));
        $pdf->SetAligns(array('R', 'C','J','C'));
        $pdf->SetFont('Humanst521 BT','B',8);
        $pdf->SetFondo(array(true,true, true,true,true));
        $pdf->Row(array( "PROMEDIO",$promedio,"",""));



        $fechaexp=$miutil->formatFecha($dataCer[0]["FECHAEXP"]);
        $fechadecexp=$miutil->aletras(date("d", strtotime($fechaexp)))." DÍAS DEL MES DE ".
                     $miutil->getMesLetra(date("m", strtotime($fechaexp)))." DEL AÑO ". $miutil->aletras(date("Y", strtotime($fechaexp)));
        $pdf->SetFont('Humanst521 BT','',8);            
        $txt=utf8_decode("<p>SE EXTIENDE EL PRESENTE ".$cadCer." QUE AMPARA <vb>".$totcred."</vb> ".
        " CRÉDITOS DE UN TOTAL DE <vb>".$dataAlum[0]["PLACRED"]."</vb> QUE INTEGRAN EL PLAN DE ESTUDIOS CON CLAVE ".
        $dataAlum[0]["MAPA"].", EN LA CIUDAD DE PEROTE, VERACRUZ, A LOS ".strtoupper($fechadecexp).".</p>");


        $pdf->WriteTag(156,4,$txt,1,"J",0,0);



        $pdf->setY(250);
        $pdf->SetFont('Humanst521 BT','',8);   
        $pdf->Cell(0,0,"DIRECTOR",0,1,'C');

        $pdf->Ln(5);
        $pdf->Line(90, 278, 155, 278);
        $pdf->setY(280);
        $pdf->SetFont('Humanst521 BT','B',8);         
        $pdf->WriteTag(0,0,"<vb>".utf8_decode($nombre)."</vb>",'0',"C",0,0);

       


        $pdf->SetFont('Humanst521 BT','',6);
        $pdf->Line(26, 166, 26, 144);
        $pdf->TextWithRotation(30,165,'FIRMA DEL ALUMNO',90,0);
        $pdf->SetFont('Humanst521 BT','',5);

        $pdf->TextWithRotation(42,174,utf8_decode('ESTE DOCUMENTO NO ES VÁLIDO SI LLEVA ENMENDADURAS O RASPADURAS'),90,0);
       

        

        $pdf->SetFont('Humanst521 BT','',6);
        //$fechacer= date("Y", strtotime($fechaexp))."-".date("m", strtotime($fechaexp))."-".date("d", strtotime($fechaexp));
      
        $fechacer=$dataCer[0]["FECHAEXP"];

        $pdf->setY(186);
        $pdf->setX(4); $pdf->Cell(35,2,'','TLR',1,'C');
        $pdf->SetFont('Humanst521 BT','B',6);
        $pdf->setX(4); $pdf->Cell(35,2,'REGISTRADO EN EL','LR',1,'C');
        $pdf->setX(4); $pdf->Cell(35,2,'DEPARTAMENTO DE CONTROL','LR',1,'C');
        $pdf->setX(4); $pdf->Cell(35,2,'ESCOLAR','LR',1,'C');        
        $pdf->setX(4); $pdf->Cell(35,2,'','BLR',1,'C');

        $pdf->SetFont('Humanst521 BT','',7);
        $pdf->setX(4); $pdf->Cell(35,4,'','LR',1,'C');
        $pdf->setX(4); $pdf->Cell(18,4,'CON NO.','L',0,'L'); $pdf->Cell(17,4, $dataCer[0]["FOLIO"],'R',1,'C');
        $pdf->setX(4); $pdf->Cell(18,4,'EN EL LIBRO','L',0,'L'); $pdf->Cell(17,4, $dataCer[0]["LIBRO"],'R',1,'C');
        $pdf->setX(4); $pdf->Cell(18,4,'A FOJAS','L',0,'L'); $pdf->Cell(17,4, $dataCer[0]["FOJA"],'R',1,'C');
        $pdf->setX(4); $pdf->Cell(5,4,'','L',0,'L'); $pdf->Cell(25,4, $fechacer,'',0,'C');$pdf->Cell(5,4,'','R',1,'L');
        $pdf->setX(4); $pdf->Cell(5,4,'','LB',0,'L'); $pdf->Cell(25,4,"FECHA",'TB',0,'C');$pdf->Cell(5,4,'','RB',1,'L');

        $pdf->setY(225);
        $pdf->SetFont('Arial','B',6);
        $pdf->setX(4); $pdf->Cell(35,5,utf8_decode('COTEJO'),'',1,'C');
        $pdf->setY(236);
        $pdf->Line(7, 235, 35, 235);
        $pdf->setX(4); $pdf->MultiCell(35,3,$nombreEsc,0,'C',false);

        $pdf->SetFont('Arial','',6);
        $pdf->setX(4); $pdf->Cell(35,2,'JEFA DEL DEPARTAMENTO','',1,'C');
        $pdf->setX(4); $pdf->Cell(35,2,'DE CONTROL ESCOLAR','',1,'C');
        
       

       // $pdf->Image("../../imagenes/empresa/logo2.png",23,228,3);

        /*
        $pdf->Ln(5);
        $pdf->MultiCell(28,2,$nombreEsc,0,'C',false);
        */

/*=========================COLOCAMOS LAS OBSERVACIONES ===============================*/
        $pdf->SetFont('Humanst521 BT','',8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setY(60); 
        $pdf->Cell(110,0,"",0,0,'C');
        $pdf->MultiCell(25,3,$dataCer[0]["OBS"],0,'J',false);
     
      

            $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
