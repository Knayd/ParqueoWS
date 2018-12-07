<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function cargar_archivo()
	{
		$this->load->view('cargar_archivo');
	}

	//Example functions
	public function crudeditarusuario(){
		$this->load->model('MetodosBd');
		
		$id= $this->input->post('id');
		$correo = $this->input->post('correo');
		$clave = $this->input->post('clave');
		$nivel = $this->input->post('nivel');
		$data = $this->MetodosBd->crud_editarusuario($id,$correo,$clave,$nivel);

	}

	public function resgitrarasistencia(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$carnetFk = $json['carnetFk'];
		$idAulaFk = $json['idAulaFk'];
		$idMateriaFk = $json['idMateriaFk'];

		$data = $this->MetodosBd->registrarAsistencia($carnetFk,$idAulaFk,$idMateriaFk);

		echo json_encode($data);
	}
	//===============================


	//Here's where the actual methods start

	//User related methods=======================
	public function agregarusuario(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$correo = $json['correo'];
		$nivel = $json['nivel'];
		$pass = $json['pass'];

		$data = $this->MetodosBd->crud_agregar_usuario($correo,$nivel);

		$apiKey="AIzaSyDUkOKeyJguMLnIWYMdRdR96bYCbgOeRCo";

		$url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/signupNewUser?key=$apiKey";

		//Se inicia Curl en el servidor especificado
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"email=$correo&password=$pass&returnSecureToken=true");
		$response = curl_exec($ch);
		echo $response;
	}

	public function checklogin(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		// $json = json_decode(file_get_contents('php://input'), true);

		// $correo = $json['correo'];
		// $pass = $json['pass'];

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
			echo json_encode($data[0]);
		}
	}
	//End of user related methods ==================================================

	public function agregarplaca(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		// $json = json_decode(file_get_contents('php://input'), true);

		// $placa = $json['placa'];

		$placa = $this->input->post('placa');
		$docente = $this->input->post('docente');
		$horario = $this->input->post('horario');
		$idParqueo = $this->input->post('idParqueo');
		$ciclo = $this->input->post('ciclo');

		$data = $this->MetodosBd->insertar_datos_archivo($placa,$docente,$horario,$idParqueo,$ciclo);

		echo json_encode($data);
	}

	public function registrar(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$placa = $json['placa'];
		$fecha = date("Y-m-d");
		$hora = date("H:i");
		$idParqueo = $json['parqueo'];

		$data = $this->MetodosBd->registrar_parqueo($placa,$fecha,$hora,$idParqueo);

		echo json_encode($data);
	}

	public function getedificios(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');
		$data = $this->MetodosBd->get_edificios();
		echo json_encode($data);
	}

	public function reservar(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$json = json_decode(file_get_contents('php://input'), true);

		$idParqueo = $json['idParqueo'];
		$motivoReservacion = $json['motivoReservacion'];
		$cantidadReservada = $json['cantidadReservada'];

		$data = $this->MetodosBd->reservar($idParqueo, $motivoReservacion, $cantidadReservada);
		// echo json_encode($data);
	}

	public function getparqueoslist(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$data = $this->MetodosBd->get_parqueos_list();
		echo json_encode($data);
	}

	public function getregistroporplaca(){

		date_default_timezone_set('America/El_Salvador');
		$this->load->model('MetodosBd');

		$placa = $this->input->get('placa');

		$data = $this->MetodosBd->get_registro_by_placa($placa);
		echo json_encode($data);
	}


}
