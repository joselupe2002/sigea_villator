
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
   	
   	/*========================================================================================================*/
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
   			$h=5*$nb;
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
   	

       function ClippingText($x, $y, $txt, $outline=false)
       {
           $op=$outline ? 5 : 7;
           $this->_out(sprintf('q BT %.2F %.2F Td %d Tr (%s) Tj ET',
               $x*$this->k,
               ($this->h-$y)*$this->k,
               $op,
               $this->_escape($txt)));
       }
   
       function ClippingRect($x, $y, $w, $h, $outline=false)
       {
           $op=$outline ? 'S' : 'n';
           $this->_out(sprintf('q %.2F %.2F %.2F %.2F re W %s',
               $x*$this->k,
               ($this->h-$y)*$this->k,
               $w*$this->k,-$h*$this->k,
               $op));
       }
   
       function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
       {
           $h = $this->h;
           $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
               $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
       }
   
       function ClippingRoundedRect($x, $y, $w, $h, $r, $outline=false)
       {
           $k = $this->k;
           $hp = $this->h;
           $op=$outline ? 'S' : 'n';
           $MyArc = 4/3 * (sqrt(2) - 1);
   
           $this->_out(sprintf('q %.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
           $xc = $x+$w-$r ;
           $yc = $y+$r;
           $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
   
           $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
           $xc = $x+$w-$r ;
           $yc = $y+$h-$r;
           $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
           $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
           $xc = $x+$r ;
           $yc = $y+$h-$r;
           $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
           $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
           $xc = $x+$r ;
           $yc = $y+$r;
           $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
           $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
           $this->_out(' W '.$op);
       }
   
       function ClippingEllipse($x, $y, $rx, $ry, $outline=false)
       {
           $op=$outline ? 'S' : 'n';
           $lx=4/3*(M_SQRT2-1)*$rx;
           $ly=4/3*(M_SQRT2-1)*$ry;
           $k=$this->k;
           $h=$this->h;
           $this->_out(sprintf('q %.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
               ($x+$rx)*$k,($h-$y)*$k,
               ($x+$rx)*$k,($h-($y-$ly))*$k,
               ($x+$lx)*$k,($h-($y-$ry))*$k,
               $x*$k,($h-($y-$ry))*$k));
           $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
               ($x-$lx)*$k,($h-($y-$ry))*$k,
               ($x-$rx)*$k,($h-($y-$ly))*$k,
               ($x-$rx)*$k,($h-$y)*$k));
           $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
               ($x-$rx)*$k,($h-($y+$ly))*$k,
               ($x-$lx)*$k,($h-($y+$ry))*$k,
               $x*$k,($h-($y+$ry))*$k));
           $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c W %s',
               ($x+$lx)*$k,($h-($y+$ry))*$k,
               ($x+$rx)*$k,($h-($y+$ly))*$k,
               ($x+$rx)*$k,($h-$y)*$k,
               $op));
       }
   
       function ClippingCircle($x, $y, $r, $outline=false)
       {
           $this->ClippingEllipse($x, $y, $r, $r, $outline);
       }
   
       function ClippingPolygon($points, $outline=false)
       {
           $op=$outline ? 'S' : 'n';
           $h = $this->h;
           $k = $this->k;
           $points_string = '';
           for($i=0; $i<count($points); $i+=2){
               $points_string .= sprintf('%.2F %.2F', $points[$i]*$k, ($h-$points[$i+1])*$k);
               if($i==0)
                   $points_string .= ' m ';
               else
                   $points_string .= ' l ';
           }
           $this->_out('q '.$points_string . 'h W '.$op);
       }
   
       function UnsetClipping()
       {
           $this->_out('Q');
       }
   
       function ClippedCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
       {
           if($border || $fill || $this->y+$h>$this->PageBreakTrigger)
           {
               $this->Cell($w,$h,'',$border,0,'',$fill);
               $this->x-=$w;
           }
           $this->ClippingRect($this->x,$this->y,$w,$h);
           $this->Cell($w,$h,$txt,'',$ln,$align,false,$link);
           $this->UnsetClipping();
       }

       function TextWithDirection($x, $y, $txt, $direction='R')
{
    if ($direction=='R')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    elseif ($direction=='L')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    elseif ($direction=='U')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    elseif ($direction=='D')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    else
        $s=sprintf('BT %.2F %.2F Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    if ($this->ColorFlag)
        $s='q '.$this->TextColor.' '.$s.' Q';
    $this->_out($s);
}

function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
{
    $font_angle+=90+$txt_angle;
    $txt_angle*=M_PI/180;
    $font_angle*=M_PI/180;

    $txt_dx=cos($txt_angle);
    $txt_dy=sin($txt_angle);
    $font_dx=cos($font_angle);
    $font_dy=sin($font_angle);

    $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
    if ($this->ColorFlag)
        $s='q '.$this->TextColor.' '.$s.' Q';
    $this->_out($s);
}

   	/*========================================================================================================*/
   	        
   	     var $eljefe="";
   	       
            
            function LoadData()
			{				
				$data=[];
				$miConex = new Conexion();
                $sql="SELECT a.*, b.*, getPeriodos(ALUM_MATRICULA,getciclo()) as PERIODO from falumnos a, ".
                " ccarreras b where ALUM_MATRICULA='".$_GET["mat"]."' AND ALUM_CARRERAREG=CARR_CLAVE";

				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

			function LoadDatosCiclo()
			{
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from ciclosesc where CICL_CLAVE=getciclo()");
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
		
		$pdf = new PDF('L','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
        $pdf->AddFont('Humanst521 BT','B','Humanst521 BT.php');
        $pdf->AddFont('Humanst521 BT','','Humanst521 BT.php');
		$pdf->SetMargins(25, 30 , 20);
		$pdf->SetAutoPageBreak(true,25); 
		$pdf->AddPage();
		
		$data = $pdf->LoadData();
        $dataCic = $pdf->LoadDatosCiclo();
        $pdf->Image("../../imagenes/empresa/fondoCredF.png",10,10,100,140);
        $pdf->Image("../../imagenes/empresa/fondoCredA.png",110,10,100,140);

        $pdf->SetDrawColor(225, 231, 231 );
        $pdf->SetLineWidth(1);
        $pdf->ClippingCircle(60,50,15,true);
        $pdf->Image($data[0]["ALUM_FOTO"],43,35,35,35);
        $pdf->UnsetClipping();
		
        $pdf->SetFont('Humanst521 BT','B',14);
		$pdf->SetY(70);$pdf->SetX(15);
		$pdf->MultiCell(90,5,utf8_decode($data[0]["ALUM_NOMBRE"]." ".$data[0]["ALUM_APEPAT"]." ".$data[0]["ALUM_APEMAT"]),0,'C',false);
		

        $pdf->SetFont('Humanst521 BT','B',14);
		$pdf->SetY(85);$pdf->SetX(15);
		$pdf->MultiCell(90,5,utf8_decode($data[0]["CARR_DESCRIP"]),0,'C',false);
		

        $pdf->SetFont('Humanst521 BT','B',14);
		$pdf->SetY(95);$pdf->SetX(10);
        $pdf->SetFillColor(13, 27, 91);
        $pdf->SetTextColor(255);
		$pdf->Cell(100,7,utf8_decode("MATRÍCULA: ".$data[0]["ALUM_MATRICULA"]),0,0,'C',true);


        $cadena= $_GET["liga"]."?t=".base64_encode("C1")."&i=".base64_encode($_GET["mat"]);
        $pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$cadena.'&.png',45,105,30,30);     
      

        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetY(120);$pdf->SetX(15);
        $pdf->SetFont('Humanst521 BT','B',40);
		$pdf->Cell(20,5,utf8_decode($data[0]["PERIODO"]),0,0,'C',true);
        $pdf->SetFont('Humanst521 BT','B',10);
        $pdf->SetTextColor(0);
        $pdf->TextWithDirection(33,130,'SEMESTRE','U');
        $pdf->SetTextColor(13, 27, 91 );
        

        /*==============================atras ======================*/
        $pdf->SetY(70);$pdf->SetX(115);
        $pdf->SetFont('Humanst521 BT','B',14);
        $pdf->SetTextColor(0);
		$pdf->Cell(90,5,utf8_decode("FIRMA DEL ALUMNO"),0,0,'C',true);

		$pdf->Line(120,68,200,68);

        $pdf->SetDrawColor(13, 27, 91);
        $pdf->SetLineWidth(0.1);
        $pdf->SetY(90);$pdf->SetX(120);
        $pdf->SetFont('Humanst521 BT','B',14);
        $pdf->SetFillColor(13, 27, 91);
        $pdf->SetTextColor(255);

		$pdf->Cell(80,5,utf8_decode("VIGENCIA"),1,0,'C',true);
        $pdf->Ln();
        $pdf->SetY(95);$pdf->SetX(120);
        $pdf->SetFont('Humanst521 BT','B',14);
        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);
        $pdf->SetLineWidth(0.1);
        $pdf->SetDrawColor(0);
        
        $pdf->SetFont('Humanst521 BT','B',12);
		$pdf->Cell(40,5,utf8_decode($dataCic[0]["CICL_INICIOR"]),1,0,'C',true);
        $pdf->Cell(40,5,utf8_decode($dataCic[0]["CICL_FINR"]),1,0,'C',true);


        $pdf->SetFont('Humanst521 BT','',6);
		$pdf->SetY(135);$pdf->SetX(113);
		$pdf->MultiCell(90,3,utf8_decode("ESTA CREDENCIAL LO IDENTIFICA COMO ESTUDIANTE DEL INSTITUTO TECNOLÓGICO SUPERIOR DE PEROTE, ESTE DOCUMENTO ES INTRANSFERIBLE, NO ES VÁLIDO SI MUESTRA TACHADURAS O ENMENDADURAS."),0,'C',false);
		

        

			
		$pdf->Output();

 } else {header("Location: index.php");}
 
 ?>
