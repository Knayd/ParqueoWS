<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Hola Ricardo y Diana

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function cargar_archivo()
	{
		$this->load->view('cargar_archivo');
	}

	public function hola()
	{
		$this->load->view('saludo');
	}

	//Here's where the actual methods start

	//=======================USUARIOS=======================
	public function agregarusuario(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$correo = $json['correo'];
		$nivel = $json['nivel'];
		$pass = $json['pass'];

		$check = $this->MetodosBd->usuario_existe($correo);
		if($check==true){
			$mensaje = array('mensaje' => "El usuario ya existe" );
			echo json_encode($mensaje);
			return;
		}

		$idNivel = $this->MetodosBd->get_nivelid_by_name($nivel);

		$data = $this->MetodosBd->crud_agregar_usuario($correo,$idNivel[0]['id_nivel']);

		$apiKey="AIzaSyDUkOKeyJguMLnIWYMdRdR96bYCbgOeRCo";

		$url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/signupNewUser?key=$apiKey";

		//Se inicia Curl en el servidor especificado
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"email=$correo&password=$pass&returnSecureToken=true");
		$response = curl_exec($ch);

		$mensaje = array('mensaje' => "Usuario agregado con exito." );
		echo json_encode($mensaje);
	}

	public function checklogin(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$correo = $this->input->get('correo');
		$pass = $this->input->get('pass');

		$apiKey="AIzaSyDUkOKeyJguMLnIWYMdRdR96bYCbgOeRCo";

		$url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPassword?key=$apiKey";

		//Se inicia Curl en el servidor especificado
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"email=$correo&password=$pass&returnSecureToken=true");
		$response = curl_exec($ch);
		$array = json_decode($response,true); //True convierte el json en array asociativo

		// print_r($array);

		if(isset($array['error'])) {
			$data = array('correo_usuario' => "error@error.com", 'nivel_usuario' => "-1" );
			echo json_encode($data);

		} else {
			$correo = $array['email'];
			$data = $this->MetodosBd->get_usuario_by_email($correo);
			// print_r($data);
			echo json_encode($data[0]);
		}
	}

	public function eliminarusuario(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$correo = $this->input->get('correo');
		$pass = $this->input->get('pass');

		$apiKey="AIzaSyDUkOKeyJguMLnIWYMdRdR96bYCbgOeRCo";

		$url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPassword?key=$apiKey";

		//Se inicia Curl en el servidor especificado
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"email=$correo&password=$pass&returnSecureToken=true");
		$response = curl_exec($ch);
		$array = json_decode($response,true); //True convierte el json en array asociativo

		if(isset($array['error'])) {
			$data = array('mensaje' => "Credenciales incorrectas");
			echo json_encode($data);

		} else {
			$token = $array['idToken'];
			$correo = $array['email'];

			$url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/deleteAccount?key=$apiKey";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,"idToken=$token");
			$response = curl_exec($ch);
			$array = json_decode($response,true); //True convierte el json en array asociativo

			$data = $this->MetodosBd->crud_eliminar_usuario_por_correo($correo);

			$mensaje = array('mensaje' => "Usuario eliminado");
			echo json_encode($data);
		}
	}

	public function crud_listar_usuario(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->crud_listar_usuario();
		echo json_encode($data);
	}

	public function get_usuario_por_correo(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$correo = $this->input->post('correo');
		$data = $this->MetodosBd->get_usuario_por_correo($correo);
		echo json_encode($data);
	}

	//==================APP===============================

	public function registrar(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$placa = $json['placa'];
		$comentario = $json['comentario'];
		$tipo = $json['tipo'];
		$fecha = date("Y-m-d");
		$hora = date("H:i");
		$nombreParqueo = $json['parqueo'];

		$idParqueo = $this->MetodosBd->get_parqueoid_by_name($nombreParqueo);

		$data = $this->MetodosBd->registrar_parqueo($placa,$fecha,$hora,$idParqueo[0]['id_parqueo'],$comentario,$tipo);

		echo json_encode($data);
	}

	public function getedificios(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');
		$data = $this->MetodosBd->get_edificios();
		echo json_encode($data);
	}

	public function getparqueoslist(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->get_parqueos_list();
		echo json_encode($data);
	}

	public function reservar(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$parqueo = $json['parqueo'];
		$motivoReservacion = $json['motivoReservacion'];
		$cantidadReservada = $json['cantidadReservada'];

		$idParqueo = $this->MetodosBd->get_parqueoid_by_name($parqueo);

		$flag = $this->MetodosBd->validar_cantidad_reservar($idParqueo[0]['id_parqueo'], $cantidadReservada);

		 if ($flag[0]['disponibles'] >= $cantidadReservada){
		 	$data = $this->MetodosBd->reservar($idParqueo[0]['id_parqueo'], $motivoReservacion, $cantidadReservada);
		 	$mensaje = array('mensaje' => "Reservacion creada");
		 } else {
		 	$mensaje = array('mensaje' => "La cantidad no es valida");
		 }

		echo json_encode($mensaje);
	}

	public function getregistroporplaca(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$placa = $this->input->get('placa');

		$data = $this->MetodosBd->get_registro_by_placa($placa);
		echo json_encode($data);
	}


	public function getniveles(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->get_niveles();
		echo json_encode($data);
	}

	//===================EDIFICIO===================

	public function crud_agregar_edificio(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$nombre = $this->input->post('txtNombreEdificio');
		$nombreCorto = $this->input->post('txtNombreCortoEdificio');

		$data = $this->MetodosBd->crud_agregar_edificio($nombre, $nombreCorto);
	}

	public function crud_actualizar_edificio(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idEdificio');
		$nombre = $this->input->post('txtNombreEdificio');
		$nombreCorto = $this->input->post('txtNombreCortoEdificio');
		$data = $this->MetodosBd->crud_actualizar_edificio($id,$nombre, $nombreCorto);
	}

	public function crud_listar_edificio(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->crud_listar_edificio();
		echo json_encode($data);
	}

	public function crud_eliminar_edificio(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idEdificio');
		$data = $this->MetodosBd->crud_eliminar_edificio($id);
	}

	public function get_edificio_por_nombre(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$nombre = $this->input->post('nombreEdificio');
		$data = $this->MetodosBd->get_edificio_por_nombre($nombre);
		echo json_encode($data);
	}

	//===================PARQUEO===================

	public function crud_agregar_parqueo(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$nombre = $this->input->post('nombreParqueo');
		$cantidad = $this->input->post('cantidad');
		$reservados = $this->input->post('reservados');
		$idEdificio = $this->input->post('idEdificio');

		$data = $this->MetodosBd->crud_agregar_parqueo($nombre,$cantidad,$reservados,$idEdificio);
		// echo json_encode($data);
	}

	public function crud_actualizar_parqueo(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idParqueo');
		$nombre = $this->input->post('nombreParqueo');
		$cantidad = $this->input->post('cantidad');
		$reservados = $this->input->post('reservados');
		$idEdificio = $this->input->post('idEdificio');

		$data = $this->MetodosBd->crud_actualizar_parqueo($id,$nombre,$cantidad,$reservados,$idEdificio);
		// echo json_encode($data);
	}

	public function crud_listar_parqueo(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->crud_listar_parqueo();
		echo json_encode($data);
	}

	public function get_parqueo_por_nombre(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$nombre = $this->input->post('nombreParqueo');
		$data = $this->MetodosBd->get_parqueo_por_nombre($nombre);
		echo json_encode($data);
	}

	public function crud_eliminar_parqueo(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idParqueo');
		$data = $this->MetodosBd->crud_eliminar_parqueo($id);
	}


	//===================PLACA===================

	public function agregarplaca(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$placa = $this->input->post('placa');
		$docente = $this->input->post('docente');
		$horario = $this->input->post('horario');
		$idParqueo = $this->input->post('idParqueo');
		$ciclo = $this->input->post('ciclo');
		$tipo = $this->input->post('tipo');

		$data = $this->MetodosBd->insertar_datos_archivo($placa,$docente,$horario,$idParqueo,$ciclo,$tipo);

		// echo json_encode($data);
	}

	public function crud_actualizar_placa(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$placa = $this->input->post('placa');
		$docente = $this->input->post('docente');
		$horario = $this->input->post('horario');
		$idParqueo = $this->input->post('idParqueo');
		$ciclo = $this->input->post('ciclo');
		$tipo = $this->input->post('tipo');

		$data = $this->MetodosBd->crud_actualizar_placa($placa,$docente,$horario,$idParqueo,$ciclo,$tipo);

		// echo json_encode($data);
	}

	public function crud_listar_placa(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->crud_listar_placa();
		echo json_encode($data);
	}

	public function get_placa_por_id(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idPlaca');
		$data = $this->MetodosBd->get_placa_por_id($id);
		echo json_encode($data);
	}

	public function crud_eliminar_placa(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idPlaca');
		$data = $this->MetodosBd->crud_eliminar_placa($id);
	}



	//===================RESERVACIONES===================

	public function crud_listar_reservacion(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->crud_listar_reservacion();
		echo json_encode($data);
	}

	public function get_reservacion_por_parqueo(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$nombre = $this->input->post('nombreParqueo');
		$data = $this->MetodosBd->get_reservacion_por_parqueo($nombre);
		echo json_encode($data);
	}

	public function crud_eliminar_reservacion(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$id = $this->input->post('idReservacion');
		$data = $this->MetodosBd->crud_eliminar_reservacion($id);
	}

}
