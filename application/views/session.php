<?php
	
	session_start();

	$verificar = $_SESSION['user'];
	
	if(!isset($_SESSION['user']) || !($_SESSION['user']=="Autenticado") )
	{
		header("location: http://localhost:8080/epro/ProyectoAsistencia/welcome/login");
	}
	

?>