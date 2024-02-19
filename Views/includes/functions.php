<?php
date_default_timezone_set('America/Buenos Aires');
	

	function fechaArgentina(){
		$mes = array("","Enero",
					  "Febrero",
					  "Marzo",
					  "Abril",
					  "Mayo",
					  "Junio",
					  "Julio",
					  "Agosto",
					  "Septiembre",
					  "Octubre",
					  "Noviembre",
					  "Diciembre");
		return date('d')." de ". $mes[date('n')] . " de " . date('Y');
	}


	function TiempoSession(){
	$inactive = 300;
        if (isset($_SESSION["timeout"])) {
	        // Calcula el "tiempo de vida" de la sesión.
            $sessionTTL = time() - $_SESSION["timeout"];
            if ($sessionTTL > $inactive) {
		        session_unset();
				echo "<script> window.location='./index.php'; </script>";
            //		header("Location: ./index.php");
            }
        }       
        $_SESSION["timeout"] = time();
	}


		/*session_start();
    if (!ISSET($_SESSION['Username'])) {
	    header('location: Home.php');
    }else{
	    if ((time() - $_SESSION['time']) > 60) {
		    header('location: session_expirada.php');
	}
}*/


 ?>
