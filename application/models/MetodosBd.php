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
		function registrar_parqueo($placa,$fecha,$hora,$idParqueo) {
			$query = $this->db->query("INSERT into tblregistroparqueo values ('$placa', '$hora', '$fecha' ,$idParqueo)");
		}

		function get_edificios() {
			$query = $this->db->query("SELECT * from tbledificio");
			return $query->result_array();
		}

		function reservar($idParqueo, $motivoReservacion, $cantidadReservada) {
			$query = $this->db->query("INSERT into tblreservaciones (id_parqueo_fk, motivo_reservacion, cantidad_reservada) values ($idParqueo, '$motivoReservacion', $cantidadReservada)");
		}

		function get_parqueos_list() {
			$query = $this->db->query("SELECT tblparqueo.nombre_parqueo, (tblparqueo.cantidad_parqueo-IFNULL(SUM(tblreservaciones.cantidad_reservada),0)) as disponibles, IFNULL(SUM(tblreservaciones.cantidad_reservada),0) as reservados from tblparqueo LEFT join tblreservaciones on tblreservaciones.id_parqueo_fk = tblparqueo.id_parqueo GROUP by tblparqueo.nombre_parqueo");
			return $query->result_array();
		}

		function get_registro_by_placa($placa) {
			$query = $this->db->query("SELECT tblregistroparqueo.num_placa, tblregistroparqueo.hora_registro, tblregistroparqueo.fecha_registro, tblparqueo.nombre_parqueo from tblregistroparqueo INNER JOIN tblparqueo on tblparqueo.id_parqueo = tblregistroparqueo.id_parqueo_fk where tblregistroparqueo.num_placa = '$placa' ");
			return $query->result_array();
		}
	}
?>