<!DOCTYPE html>
	<title>Cargar archivo</title>
<?php

//Carga de la librearía de excel y el modelo para guardar los datos
include ".\phpexcel\Classes\PHPExcel.php";
include ".\application\models\\registroExcel.php";

//Para setear las letras como numeros
$chars = array(1 => 'A', 2 => 'B' , 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');

echo "<h2>Seleccionar un archivo de Excel</h2> <br><img src='../images/carga_excel.png' width='130'>";

//Para verificar la extensión del archivo
if(!empty($_FILES)){
	$filename = $_FILES['excel']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
}


if (empty($_FILES))
{
	echo "
		<form method='post' enctype='multipart/form-data' action=''>	
		<input type='file' class='form-control' name='excel' value='Subir' requrided='true'>
		<br>
		<input type='submit'  value='Mostrar'>
		";
}
elseif ($_FILES['excel']['size'] == 0) {
	//Verifica archivo vacío
	echo "
		Por favor seleccionar un archivo.
		<form method='post' enctype='multipart/form-data' action=''>	
		<input type='file' name='excel' value='Subir' requrided='true'>
		<br>
		<input type='submit'  value='Mostrar'>
		";
}
elseif ($ext !== 'xlsx') {
	//verifica formato correcto
	echo "
		Por favor seleccionar un archivo de Excel.
		<form method='post' enctype='multipart/form-data' action=''>	
		<input type='file' name='excel' value='Subir' requrided='true'>
		<br>
		<input type='submit'  value='Mostrar'>
		";
}
else
{
	
	//Array para almacenar todos los objetos con datos.
	$datos = array();
	//cargar archivo de excel
	$excel = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);

	//setear la hoja activa a la primera hoja
	$excel->setActiveSheetIndex(0);

	$col = $excel->setActiveSheetIndex(0)->getHighestColumn(); //Columna mas grande, retorna una letra
	$row = $excel->setActiveSheetIndex(0)->getHighestRow();    //Fila mas grande

	$coln; //columna en numero

	//para convertir la columna en numero
	foreach ($chars as $num => $lett)
	{
		if($lett==$col)
		{
			$coln = $num;
		}
	}

	//echo $col." ".$row." ".$coln."<br>";

	echo "<table border='1'>";

	//Imprime encabezados
	echo "<tr><th>Placa</th><th>Nombre Docente</th><th>Horario</th><th>Id Parqueo</th><th>Ciclo</th></tr>";

	//Comienza en 2 para que no tome en cuenta los encabezados;
	for ($i=2; $i<=$row; $i++)
	{
		//Se recorre todo el archivo
		//Objeto para almacenar los registros y evaluarlos


		$registro = new registroExcel;
		echo "<tr>";
		for ($j=1; $j<=$coln; $j++)
		{
			//Aqui se obtiene el contenido de la celda
			$var = $excel->getActiveSheet()->getCell($chars[$j].$i)->getValue();
			echo"<td>".$var."</td>";

			//Limpiar los datos de los caracteres no deseados antes de ingresarla en los objetos
			$cleanVar = preg_replace('!s:(\d+):"(.*?)";!', "'s:'.strlen('$2').':\"$2\";'", $var);
			$cleanVar = str_replace("'", "", $cleanVar);

			//Para almacenar cada campo individual en el objeto y su posición
			switch ($j) {
				case 1:
				$registro->placa =  array('celda' => ($chars[$j].$i),'valor'=> $cleanVar );
					break;
				case 2:
				$registro->docente =  array('celda' => ($chars[$j].$i),'valor'=> $cleanVar );
					break;
				case 3:
				$registro->horario =  array('celda' => ($chars[$j].$i),'valor'=> $cleanVar );
					break;
				case 4:
				$registro->idParqueo =  array('celda' => ($chars[$j].$i),'valor'=> $cleanVar );
					break;
				case 5:
				$registro->ciclo =  array('celda' => ($chars[$j].$i),'valor'=> $cleanVar );
					break;
			}
		}
		//Inserta dentro de un array los diferentes objetos creados.
		// array_push($datos, $registro);

		echo "</tr>";

		$placa = $registro->placa['valor'];
		$docente  = $registro->docente['valor'];
		$horario =  $registro->horario['valor'];
		$idParqueo = $registro->idParqueo['valor'];
		$ciclo = $registro->ciclo['valor'];

		$url = "http://192.168.0.31:8080/etps1/ProyectoParqueo/welcome/agregarplaca";

		//Se inicia Curl en el servidor especificado
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"placa=$placa&docente=$docente&horario=$horario&idParqueo=$idParqueo&ciclo=$ciclo");
		$response = curl_exec($ch);
		$array = json_decode($response,true); //True convierte el json en array asociativo
		print_r($array);

		// echo "<br>some divider<br>";
	}

	echo "</table>";
	echo "Datos ingresados correctamente";
	//Preparando el array para mandarlo a ser validado
	// $postvalue = serialize($datos);

	// echo "<form method='POST' action='http://localhost:8080/epro/ProyectoAsistencia/welcome/validar_datos'>";

 //  	echo "<input type='hidden' name='datos' value='". $postvalue."'>";
	// echo "<input type='submit' value='Validar datos'>";
	// echo "</form>";
}

?>

</html>