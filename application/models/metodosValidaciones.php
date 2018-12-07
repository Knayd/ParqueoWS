<?php
  class metodosValidaciones {

	public static function validarFacultad($datos){
		$check = 1;
		for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->facultad['valor'];
			$regex = " /^[^0-9]+$/ ";

			if (preg_match($regex, $key)) {

			} else {
			    echo "Error de formato de Facultad en celda: ". $datos[$i]->facultad['celda'];
			    echo "<br>";
				$check = 0;

			}

		}
		return $check;

	}

	public static function validarEscuela($datos){
		$check = 1;
		for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->escuela['valor'];
			$regex = " /^[^0-9]+$/ ";

			if (preg_match($regex, $key)) {

			} else {
			    echo "Error de formato de Escuela en celda: ". $datos[$i]->escuela['celda'];
			    echo "<br>";
				$check = 0;
			}
		}
		return $check;

	}

	public static function validarCodMateria(){
		
	}
	
	public static function validarNombre(){
		
	}

	public static function validarDocente($datos){
		$check = 1;
	
		for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->docente['valor'];
			$regex = " /^[^0-9]+$/ ";

			if (preg_match($regex, $key)) {

			} else {
			    echo "Error de formato de Docente en celda: ". $datos[$i]->docente['celda'];
			    echo "<br>";
				$check = 0;
			}

		}
		return $check;

	}

	public static function validarSeccion($datos){
		$check = 1;
		for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->seccion['valor'];
			$regex = " /^[0-9]{2}$/ ";

			if (preg_match($regex, $key)) {
			   
			} else {
			    echo "Error de formato  Seccion en celda: ". $datos[$i]->seccion['celda'];
			    echo "<br>";
			    $check = 0;
			}

		}
		return $check;

		
	}

	public static function validarHora($datos){
		$check = 1;
		for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->hora['valor'];
			$regex = " /^[0-9]{2}+[:]+[0-9]{2}+[-]+[0-9]{2}+[:]+[0-9]{2}+$/";

			if (preg_match($regex, $key)) {
			   
			} else {
			    echo "Error de formato de Hora en celda: ". $datos[$i]->hora['celda'];
			    echo "<br>";
			    $check = 0;
			}

		}
		return $check;

	}

	public static function validarDias($datos){

		$check = 1;

		for ($i=0; $i < count($datos) ; $i++) {

			$strDia = $datos[$i]->dias['valor'];

			$validacion = 0;
			$dias = explode("-",$strDia);

			$diasValidos = array("Lu","Ma","Mie","Jue","Vie","Sab","Dom");

			//Recorre cada día del string ingresado y lo compara con los valores válidos
			foreach ($dias as $dia => $valor) {

				foreach ($diasValidos as $diaValido => $valorValido) {
					if($valor == $valorValido){
						//Si el día es correcto, se suma uno a la variable de validación
						$validacion += 1;
						break;
					}
				}
			}
			
			//Si todos los días son correctos, el tamaño del array que surge de 'explotar' el string
			//debería ser igual a la variable 'validacion'
			if($validacion == sizeof($dias)){
				// echo "Gud";
			} else {
				echo "Error de formato de Días en celda: ". $datos[$i]->dias['celda'];
			    echo "<br>";
			    $check = 0; //Si hay errores, retorna 0
			}

		}

		return $check;

	}

	public static function validarInscritos($datos){
		$check = 1;
		for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->inscritos['valor'];
			$regex = " /^[0-9]+$/ ";

			if (preg_match($regex, $key)) {

			} else {
			    echo "Error de formato de Incsritos en celda: ". $datos[$i]->inscritos['celda'];
			    echo "<br>";
			    $check = 0; //Si hay errores, retorna 0

			}

		}
		return $check;
	}

	public static function validarAula($datos){
			$check = 1; //Esto es para verificar que no haya habido ningún error de formato

			for ($i=0; $i < count($datos) ; $i++) { 
			$key   = $datos[$i]->aula['valor'];
			$regex = " /^[A-Z]{2}+[-]+[0-9]{3}+$/";

			if (preg_match($regex, $key)) {

			} else {
			    echo "Error de formato de Aula en celda: ". $datos[$i]->aula['celda'];
			    echo "<br>";
			    $check = 0; //Si hay errores, retorna 0
			}
		}

		return $check;
	}
}

?>