<?php
	class MetodosBd extends CI_Model {

		function __construct(){
			//Llamando al constructor del modelo
			parent::__construct();
		}

		function crud_agregar_usuario($correo,$nivel) {
			$query = $this->db->query("INSERT into tblusuario values ('$correo',$nivel)");
		}

		function get_usuario_by_email($correo) {
			$query = $this->db->query("SELECT * from tblusuario where correo_usuario = '$correo' ");
			return $query->result_array();
		}

		function insertar_datos_archivo($placa,$docente,$horario,$idParqueo,$ciclo) {
			$query = $this->db->query("INSERT into tblplaca values ('$placa','$docente', '$horario',$idParqueo,'$ciclo')");
		}
		function registrar_parqueo($placa,$fecha,$hora,$idParqueo,$comentario,$tipo) {

			$check = $this->db->query("SELECT * from tblplaca where id_placa = '$placa'");

			if($check->num_rows() > 0){

				$check2 = $this->db->query("SELECT * from tblregistroparqueo where CONCAT(fecha_registro,' ',hora_registro) > DATE_SUB(NOW(), INTERVAL 1.2 HOUR) and tblregistroparqueo.num_placa = '$placa' and tblregistroparqueo.tipo_registro = $tipo");
				if($check2->num_rows() > 0){
					$respuesta = array('mensaje' => "Debe esperar al menos una hora para poder registrar asistencia de nuevo");
					return $respuesta;
				}

				$query = $this->db->query("INSERT into tblregistroparqueo values ('$placa', '$hora', '$fecha' ,$idParqueo,'$comentario',$tipo)");
				$respuesta = array('mensaje' => "Registro ingresado de forma correcta");
				return $respuesta;
			} else {
				$respuesta = array('mensaje' => "La placa no existe");
				return $respuesta;
			}

		}

		function get_edificios() {
			$query = $this->db->query("SELECT * from tbledificio");
			return $query->result_array();
		}

		function reservar($idParqueo, $motivoReservacion, $cantidadReservada) {

			$query = $this->db->query("INSERT into tblreservaciones (id_parqueo_fk, motivo_reservacion, cantidad_reservada) values ($idParqueo, '$motivoReservacion', $cantidadReservada)");
		}
		
		function validar_cantidad_reservar($idParqueo, $cantidadReservada) {

			$check = $this->db->query("SELECT (tblparqueo.cantidad_parqueo-IFNULL(SUM(tblreservaciones.cantidad_reservada),0)) as disponibles from tblparqueo 
											INNER JOIN tblreservaciones on tblreservaciones.id_parqueo_fk = tblparqueo.id_parqueo
											where tblparqueo.id_parqueo = $idParqueo");
			return $check->result_array();
		}

		function get_parqueos_list() {
			$query = $this->db->query("SELECT tblparqueo.nombre_parqueo, (tblparqueo.cantidad_parqueo-IFNULL(SUM(tblreservaciones.cantidad_reservada),0)) as disponibles, IFNULL(SUM(tblreservaciones.cantidad_reservada),0) as reservados from tblparqueo LEFT join tblreservaciones on tblreservaciones.id_parqueo_fk = tblparqueo.id_parqueo GROUP by tblparqueo.nombre_parqueo");
			return $query->result_array();
		}

		function get_registro_by_placa($placa) {
			$query = $this->db->query("SELECT tblregistroparqueo.num_placa, tblregistroparqueo.hora_registro, tblregistroparqueo.fecha_registro, tblparqueo.nombre_parqueo from tblregistroparqueo INNER JOIN tblparqueo on tblparqueo.id_parqueo = tblregistroparqueo.id_parqueo_fk where tblregistroparqueo.num_placa = '$placa' ");
			return $query->result_array();
		}

		function get_parqueoid_by_name($name) {
			$query = $this->db->query("SELECT id_parqueo from tblparqueo WHERE nombre_parqueo = '$name' ");
			return $query->result_array();
		}

		function get_nivelid_by_name($name) {
			$query = $this->db->query("SELECT id_nivel from tblnivel WHERE nombre_nivel = '$name' ");
			return $query->result_array();
		}

		function get_niveles() {
			$query = $this->db->query("SELECT * from tblnivel");
			return $query->result_array();
		}

		function usuario_existe($correo) {
			$query = $this->db->query("SELECT * from tblusuario where correo_usuario = '$correo'");
			if ($query->num_rows() > 0) {
				return true;
			} else {
				return false;
			} 
		}

		function crud_agregar_edificio($nombreEdificio, $nombreCortoEdificio) {
			$query = $this->db->query("INSERT into tbledificio values (NULL, '$nombreEdificio', '$nombreCortoEdificio' )");
			echo "Registro ingresado correctamente";
		}

		function crud_actualizar_edificio($id,$nombreEdificio, $nombreCortoEdificio) {
			$query = $this->db->query("UPDATE tbledificio set  nombre_edificio='$nombreEdificio', nombre_corto_edificio='$nombreCortoEdificio' where id_edificio=$id ");

			echo "Registro actualizado correctamente";
		}
	}
?>