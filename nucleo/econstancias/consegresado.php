
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
                $sql="SELECT * FROM ciclosesc where CICL_CLAVE=(select MAX(ifnull(PDOCVE,0)) from dlista where ALUCTR='".$_GET["matricula"]."');";
               
                
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
                $sql="select ALUM_MATRICULA, CONCAT(ALUM_NOMBRE, ' ',ALUM_APEPAT, ' ',ALUM_APEMAT) AS NOMBRE, ".
                " ALUM_CARRERAREG AS CARRERA, ALUM_CURP, ALUM_CICLOINS, x.CICL_INICIO AS INICIO, y.CICL_FIN AS FIN, ALUM_CICLOTER, ALUM_ACTIVO AS SITUACION, ALUM_CICLOTER AS CICLOTER, ".
                " ALUM_CICLOINS AS CICLOINS, CARR_DESCRIP AS CARRERAD, ".
                " PLACRED, PLAMAT,  c.CLAVEOF AS ESPECIALIDAD, c.DESCRIP AS ESPECIALIDADD, ALUM_MAPA AS MAPA,".
                " round(getavanceCred('".$_GET["matricula"]."'),0) as AVANCE, ".
                " getPromedio('".$_GET["matricula"]."','N') as PROMEDIO_SR,".
                " getPeriodos('".$_GET["matricula"]."',(select MAX(ifnull(PDOCVE,0)) from dlista where ALUCTR='".$_GET["matricula"]."')) AS PERIODOS,".
                " (SELECT CATA_DESCRIP FROM scatalogos where CATA_TIPO='STALUM' AND CATA_CLAVE=ALUM_ACTIVO) AS STATUS,".
                " getcuatrialum('".$_GET["matricula"]."',getciclo()) as SEMESTRE,".
                " (select SUM(a.CREDITO) from kardexcursadas a where CERRADO='S'  and a.MATRICULA='".$_GET["matricula"]."' AND CAL>=70) AS CRETOT, ".
                " (select SUM(a.CREDITO) from kardexcursadas a where a.CICLO=getciclo() AND CERRADO='N'  and a.MATRICULA='".$_GET["matricula"]."') AS CRECUR ".
                " from falumnos a LEFT outer JOIN especialidad c on (a.ALUM_ESPECIALIDAD=c.ID)".
                " LEFT outer JOIN ciclosesc x on (a.ALUM_CICLOINS=x.CICL_CLAVE)".
                " LEFT outer JOIN ciclosesc y on (a.ALUM_CICLOTER=y.CICL_CLAVE), ccarreras b, mapas d where ".
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
                $miutil->getEncabezado($this,'V');			
                //Para que cuando se cambie a la otra pagina empiece a la derecha y la stablas no se descuadren
                $this->SetX(10);
                $this->Ln(5);	
          

			}
			
			

			function Footer()
			{	
                
                $miutil = new UtilUser();
                $nombre=$miutil->getJefe('303');//Nombre del puesto de Recursos Humanos
                $miutil->getPie($this,'V');
                $this->SetFont('Montserrat-ExtraBold','B',10);
                $this->setY(-60);
                $this->Cell(0,5,"ATENTAMENTE",0,1,'C');
                $this->setY(-50);
                $this->Cell(0,5,utf8_decode($nombre),0,1,'C');
                $this->setY(-45);
                $this->Cell(0,5,"JEFE DEL DEPARTAMENTO DE SERVICIOS ESCOLARES",0,1,'C');
		
			}

		}
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(25, 25 , 25);
		$pdf->SetAutoPageBreak(true,50); 
        $pdf->AddPage();
        $dataCiclo=$pdf->LoadDatosCiclo();
        $elciclo=$dataCiclo[0]["CICL_CLAVE"];
         
        $dataGen = $pdf->LoadDatosGen();
        $dataAlum = $pdf->LoadDatosAlumnos();
        $miutil = new UtilUser();

        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',11);
        $pdf->SetX(120);
        $pdf->Cell(35,5,utf8_decode('ÁREA:'),0,0,'L');
        $pdf->Cell(35,5,'SERVICIOS ESCOLARES',0,0,'L');
        $pdf->Ln(5);
        $pdf->SetX(120);
        $pdf->Cell(35,5,'OFICIO NO.:',0,0,'L');
        $pdf->Cell(35,5,$_GET["consec"]."/".$_GET["anio"],0,0,'L');
        $pdf->Ln(5);
        $pdf->SetX(120);
        $pdf->Cell(35,5,'CLAVE:',0,0,'L');
        $pdf->Cell(35,5,$dataGen[0]["inst_claveof"],0,0,'L');
        $pdf->Ln(5);
        $pdf->SetX(120);
        $pdf->Cell(35,5,'ASUNTO:',0,0,'L');
        $pdf->Cell(35,5,"CONSTANCIA DE EGRESO",0,0,'L');
        $pdf->Ln(15);
        $pdf->Cell(0,5,"A QUIEN CORRESPONDA:",0,0,'L');
        $pdf->Ln(10);
        $pdf->SetFont('Montserrat-Medium','',11);
        
        $loscre=$dataAlum[0]["CRETOT"];
        if ($dataAlum[0]["CRETOT"]>$dataAlum[0]["PLACRED"]) { $loscre=$dataAlum[0]["PLACRED"];}
     

        $miutil = new UtilUser();
        $elsem=$miutil->dameCardinal($dataAlum[0]["PERIODOS"]);

   
        $inicio=$miutil->formatFecha($dataAlum[0]["INICIO"]);
		$finicio=date("d", strtotime($inicio))." DE ".strtoupper($miutil->getMesLetra(date("m", strtotime($inicio))))." DE ".date("Y", strtotime($inicio));
        
        $fin=$miutil->formatFecha($dataAlum[0]["FIN"]);
		$ffin=date("d", strtotime($fin))." DE ".strtoupper($miutil->getMesLetra(date("m", strtotime($fin))))." DE ".date("Y", strtotime($fin));
        
        $pdf->SetStyle("p","Montserrat-Medium","",10,"0,0,0");
        $pdf->SetStyle("vs","Montserrat-Medium","U",10,"0,0,0");
		$pdf->SetStyle("vsb","Montserrat-Medium","UB",10,"0,0,0");
        $pdf->SetStyle("vb","Montserrat-ExtraBold","B",10,"0,0,0");



        $pdf->WriteTag(0,5,utf8_decode("<p>LA (EL) QUE SUSCRIBE, JEFE(A) DEL DEPARTAMENTO DE SERVICIOS ESCOLARES, HACE CONSTAR QUE EL (LA) <vb>C.".
        $dataAlum[0]["NOMBRE"]."</vb> ES <vb>EGRESADO</vb> DE LA CARRERA DE <vb>".$dataAlum[0]["CARRERAD"]."</vb>, CON PLAN DE ESTUDIOS <vb>".
        $dataAlum[0]["MAPA"]."</vb> Y LA ESPECIALIDAD DE <vb>".$dataAlum[0]["ESPECIALIDADD"]."</vb> CON NÚMERO DE CONTROL <vb>".
        $dataAlum[0]["ALUM_MATRICULA"]."</vb>, QUIEN HA CUMPLIDO AL <vb>".$dataAlum[0]["AVANCE"]."%</vb> CON LOS <vb>".
        $dataAlum[0]["PLACRED"]."</vb> CREDITOS QUE CORRESPONDEN A LAS MATERIAS DE SU CURRICULA ADEMÁS DE HABER ".
        "CUBIERTO CON LOS CREDITOS DE SU SERVICIO SOCIAL , CREDITOS DE RESIDENCIA PROFESIONAL Y CREDITOS ". 
        "DE LAS ACTIVIDADES COMPLEMENTARIAS, QUIEN ADEMÁS YA HA REALIZADO EL TRAMITE DE CERTIFICADO PROFESIONAL ".
        "ESTE ÚLTIMO SE ENCUENTRA EN REVISION POR LA DIRECCIÓN DE EDUCACIÓN TECNOLÓGICA, Y POSTERIORMENTE SERÁ ".
        "LEGALIZADO POR LA SECRETARÍA DE GOBERNACION DEL ESTADO DE VERACRUZ, TENIENDO UN PLAZO DE CINCO MESES ".
        "PARA RECIBIRLO </p>"),0,'J',FALSE);

        $pdf->Ln(5);
        $pdf->WriteTag(0,5,utf8_decode("<p>HACIENDO CONSTAR ADEMÁS QUE LLEVÓ A CABO SUS ESTUDIOS EN EL PERIODO DEL <vb>".
        $finicio."</vb> AL <vb>".$ffin."</vb> AL OBSERVANDO EN SU ESTANCIA BUENA CONDUCTA. SE ESPECIFÍCA QUE LA CURP QUE EL JOVEN TIENE ES: ".
        "QUIEN CUBRIÓ UN TOTAL DE 260 CRÉDITOS DE LOS QUE CONSTA LA CARRERA, ADEMÁS DE CONTAR CON UN ". 
        "PROMEDIO FINAL DE: <vb>".$dataAlum[0]["PROMEDIO_SR"]."</vb>.</p>"),0,'J',FALSE);
        
		$fechaof=$miutil->aletras(date("d"))." DÍAS DEL MES DE ".$miutil->getMesLetra(date("m"))." DEL AÑO ". $miutil->aletras(date("Y"));
        $pdf->Ln(5);
   

        $pdf->WriteTag(0,5,utf8_decode("<p>SE EXTIENDE LA PRESENTE EN LA CIUDAD DE ".strtoupper($dataGen[0]["inst_extiende"])." A LOS ".
        strtoupper($fechaof).", PARA LOS FINES QUE CONVENGAN AL INTERESADO.</p>"),0,'J',FALSE);
        
        $pdf->Ln(25);

        
        //CODIGO QR
        $cadena= "OF:".$_GET["consec"]."-".$_GET["anio"]."|".$dataAlum[0]["ALUM_MATRICULA"]."|".str_replace(" ","|",$dataAlum[0]["NOMBRE"]).
        str_replace(" ","|",$dataAlum[0]["CARRERAD"])."|CREDAVANCE:".$loscre."|AVANCE:".$dataAlum[0]["AVANCE"];     
        $pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$cadena.'&.png',20,210,28,28);     


    
            

         $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
