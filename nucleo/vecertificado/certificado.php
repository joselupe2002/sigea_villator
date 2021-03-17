
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
                "(CASE WHEN TIPOMAT='AC' THEN (select SUBSTRING(CALLET,1,3) from ecalcertificado i where i.MATRICULA=a.MATRICULA and i.MATERIA=a.MATERIA limit 1)".
                "      WHEN TIPOMAT='SS' THEN (select SUBSTRING(CALLET,1,3) from ecalcertificado i where i.MATRICULA=a.MATRICULA and i.MATERIA=a.MATERIA limit 1) ".
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
                $sql="SELECT * FROM vecertificado where FOLIO=".$_GET["folio"];                
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }
            

            function LoadDatosAlumnos()
			{				
                $miConex = new Conexion();
                $sql="select ALUM_MATRICULA, CONCAT(ALUM_NOMBRE, ' ',ALUM_APEPAT, ' ',ALUM_APEMAT) AS NOMBRE, ".
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
		
        
        $pdf->AddFont('lucida-sans-unicode_[allfont.es]','B','lucida-sans-unicode_[allfont.es].php');
        $pdf->AddFont('BRITANIC','B','BRITANIC.php');
        $pdf->AddFont('Britannic Bold Regular','B','Britannic Bold Regular.php');
        
	
		$pdf->SetMargins(45, 2.5, 12);
        $margeniz=45;
		$pdf->SetAutoPageBreak(true,10); 
        $pdf->AddPage();
        $pdf->SetFont('lucida-sans-unicode_[allfont.es]','B',16);
        $pdf->SetTextColor(36, 64, 97);

        $pdf->Ln(3);
        $pdf->Cell(0,5,utf8_decode('Instituto Tecnológico Superior de Santa María de El Oro'),0,1,'C');
        $pdf->SetFont('Arial','',8);
        $pdf->Image("../../imagenes/empresa/logo2.png",12,8,23.2);

      		 
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
        $pdf->setY(14);


        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(0,4,utf8_decode("INSTITUCIÓN DE EDUCACIÓN SUPERIOR, CON CARÁCTER DE ORGANISMO PÚBLICO ".
        "DESCENTRALIZADO POR DECRETO DE CREACIÓN, PUBLICADO EN EL PERIÓDICO OFICIAL DEL GOBIERNO CONSTITUCIONAL ".
        "DEL ESTADO DE DURANGO NO. 34 TOMO CCXIX; DE FECHA DOMINGO 26 DE OCTUBRE DE 2008 Y POR AUTORIZACIÓN MEDIANTE ".
        "OFICIO 513.3-1/0508/08 EMITIDO POR LA DGEST."),0,'J');


        $txt=utf8_decode("<p>EL C. <vb>".$nombre."</vb> DIRECTOR GENERA DEL ". $data2[0]["inst_razon"].
        " CLAVE <vb>". $data2[0]["inst_claveof"]."</vb>, CERTIFICA, QUE SEGÚN CONSTANCIAS QUE EXISTEN EN ESTE INSTITUTO, EL (LA) C. <vb>".
        $dataAlum[0]["NOMBRE"]."</vb> CURSO LAS ASIGNATURAS QUE INTEGRAN EL PLAN DE ESTUDIOS DE <vb>".$dataAlum[0]["CARRERAD"]."</vb>".
        " EN EL PERIODO DE <vb>". $cadInicio."</vb> A <vb>".$cadfin."</vb>".
        ", CON LOS RESULTADOS QUE A CONTINUACIÓN SE ANOTAN</p>");
        $pdf->Ln();
        $pdf->SetStyle("p","arial","",8,"0,0,0");
        $pdf->SetStyle("vb","arial","B",8,"0,0,0");
        $pdf->SetStyle("VB","arial","B",8,"0,0,0");
        $pdf->WriteTag(0,4,$txt,0,"J",0,0);

        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial','B',8);

        $pdf->setY(41);$pdf->setX(9);
        $pdf->Cell(30,5,'NO. DE CONTROL',1,1,'C');
        $pdf->setX(9);
        $pdf->Cell(30,5,$dataCer[0]["MATRICULA"],1,0,'C');

        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(85,5,'MATERIA','TBL',0,'L');
        $pdf->Cell(13,5,'CALIF.','TBR',0,'C');
        $pdf->Cell(32,5,'OBSERVACIONES',1,0,'C');
        $pdf->Cell(28,5,'CR',1,0,'C');

        /*=======================colacamos las calificaciones ==========================*/
        $pdf->Ln();
        $pdf->SetFont('Arial','',7);
        $pdf->SetWidths(array(85, 13,32,28));
        $pdf->SetAligns(array('L', 'C','J','C'));
        $pdf->SetBorder(array('L', '','','R'));
        
        $n=0;
        $sumacal=0;
        $totcred=0;
        foreach($data as $row) {    
            $cadRev='';
            if (($row["GPOCVE"]=='REV') && (($row["TIPOMAT"]!='AC') && ($row["TIPOMAT"]!='SS'))) {$cadRev='*';}
            $pdf->Row(array( utf8_decode($row["MATERIAD"]." ".$cadRev),
                             utf8_decode($row["CAL"]),
                             "",
                             str_pad($row["CREDITO"],  2, "0",STR_PAD_LEFT)                             
                             )
                      );
            $totcred+=$row["CREDITO"];
            if  (($row["TIPOMAT"]!='AC') && ($row["TIPOMAT"]!='SS')) {
                $sumacal+=$row["CAL"];
                $n++;
            }            
        }

        //echo $pdf->getY();
        
        while ($pdf->getY()<=229) {
            $pdf->SetBorder(array('L','','','R'));
            $pdf->SetFondo(array(false,false,false,false));
            $pdf->Row(array("","","",""));
        }
    
        /*=======================colacamos el promedio ==========================*/
        $promedio=round($sumacal/($n));
        $pdf->SetWidths(array(85, 13,32,28));
        $pdf->SetBorder(array('BL', 'B','B','BR'));
        $pdf->SetAligns(array('L', 'R','J','C'));
        $pdf->SetFont('Arial','B',6);
        $pdf->SetFondo(array(false,false, false,false,false));
        $pdf->Row(array( "PROMEDIO",$promedio,"",""));



        $fechaexp=$miutil->formatFecha($dataCer[0]["FECHAEXP"]);
        $fechadecexp="<vb>".$miutil->aletras(date("d", strtotime($fechaexp)))."</vb> DÍAS DEL MES DE ".
                     "<vb>".$miutil->getMesLetra(date("m", strtotime($fechaexp)))."</vb> DEL AÑO <vb>". $miutil->aletras(date("Y", strtotime($fechaexp)))."</vb>";
      
        $pdf->Ln(5);

        $pdf->SetFont('Arial','',8);
        $pdf->Ln();

        $cadCer="CERTIFICADO COMPLETO"; if ($dataAlum[0]["PLACRED"]>$totcred)  {$cadCer="CERTIFICADO PARCIAL"; }
        $txt=utf8_decode("<p>SE EXPIDE EL PRESENTE <vb>".$cadCer."</vb> QUE AMPARA <vb>".$totcred.
        "</vb> CRÉDITOS DE UN TOTAL DE <vb>".$dataAlum[0]["PLACRED"]."</vb> QUE INTEGRAN EL PLAN DE ESTUDIOS CON CLAVE <vb>".
        $dataAlum[0]["MAPA"]."</vb>, EN <vb>SANTA MARÍA DEL ORO, EL ORO, DGO.</vb> A LOS ".strtoupper($fechadecexp)."</p>");


        $pdf->WriteTag(0,4,$txt,0,"J",0,0);



        $pdf->Ln(10);

        $pdf->setX($margeniz);
        $pdf->Cell(0,0,utf8_decode($nombre),0,1,'C');
        $pdf->Ln(3);
        $pdf->setX($margeniz);
        $pdf->Cell(0,0,"DIRECTOR(A) GENERAL",0,1,'C');

        $pdf->TextWithRotation(30,160,'FIRMA DEL INTERESADO',90,0);
        $pdf->SetFont('Arial','',5);
        $pdf->TextWithRotation(32,164,utf8_decode('CERTIFICADO VÁLIDO EN LOS ESTADOS UNIDOS'),90,0);
        $pdf->TextWithRotation(34,165,utf8_decode('MEXICANOS. ESTE DOCUMENTO NO ES VÁLIDO SI '),90,0);
        $pdf->TextWithRotation(36,162,utf8_decode('LLEVA ENMENDADURAS  O RASPADURAS'),90,0);

        

        $pdf->SetFont('Arial','',6);
        //$fechacer= date("Y", strtotime($fechaexp))."-".date("m", strtotime($fechaexp))."-".date("d", strtotime($fechaexp));
      
        $fechacer=$dataCer[0]["FECHAEXP"];

        $pdf->setY(180);
        $pdf->setX(9); $pdf->Cell(30,2,'','TLR',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'REGISTRADO EN EL','LR',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'DEPARTAMENTO','LR',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'DE SERVICIOS ESCOLARES','LR',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'ESCOLARES','LR',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'','LRB',1,'C');

        $pdf->setX(9); $pdf->Cell(30,4,'','TLR',1,'C');
        $pdf->setX(9); $pdf->Cell(10,4,'CON NO.','L',0,'L'); $pdf->Cell(20,4, $dataCer[0]["FOLIO"],'RB',1,'C');
        $pdf->setX(9); $pdf->Cell(18,4,'CON EL LIBRO','L',0,'L'); $pdf->Cell(12,4, $dataCer[0]["LIBRO"],'RB',1,'C');
        $pdf->setX(9); $pdf->Cell(10,4,'A FOJAS','L',0,'L'); $pdf->Cell(20,4, $dataCer[0]["FOJA"],'RB',1,'C');
        $pdf->setX(9); $pdf->Cell(10,4,'FECHA','BL',0,'L'); $pdf->Cell(20,4, $fechacer,'RB',1,'C');

        $pdf->setY(216);
        $pdf->setX(9); $pdf->Cell(30,5,'COTEJO','TLR',1,'C');
        $pdf->setX(9); $pdf->Cell(30,11,'','LRB',1,'C');

        $pdf->setY(235);
        $pdf->setX(9); $pdf->Cell(30,2,'JEFE DEL','',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'DEPARTAMENTO DE','',1,'C');
        $pdf->setX(9); $pdf->Cell(30,2,'SERVICIOS ESCOLARES','',1,'C');
        
        $pdf->setY(252);
        $pdf->setX(9); $pdf->Cell(30,2,'FOLIO','',1,'C');
        $pdf->SetFont('Britannic Bold Regular','B',16);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->setY(256);
        $pdf->setX(9); $pdf->Cell(30,2,$dataCer[0]["FOLIO"],'',1,'C');

        $pdf->Image("../../imagenes/empresa/logo2.png",23,228,3);

        /*
        $pdf->Ln(5);
        $pdf->MultiCell(28,2,$nombreEsc,0,'C',false);
        */

/*=========================COLOCAMOS LAS OBSERVACIONES ===============================*/
        $pdf->setY(56);
        $pdf->Cell(142,0,"",0,0,'C');
        $pdf->MultiCell(25,2,$dataCer[0]["OBS"],0,'J',false);
     
      

            $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
