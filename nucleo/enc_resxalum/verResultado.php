
<?php 

    session_start(); 
    if (($_SESSION['inicio']==1)) {
        include("../.././includes/Conexion.php");
        include("../.././includes/UtilUser.php");
        $miConex = new Conexion();
        $miUtil= new UtilUser();

        $elresultado=$miUtil->getResultado($_POST['lapersona'],$_SESSION['super'],$_POST["periodicidad"],$_POST["idenc"],$_POST["idres"]);
        echo $elresultado;
?>
<?php } else {header("Location: index.php");}?>