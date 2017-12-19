<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'/libraries/REST_Controller.php' );
use Restserver\libraries\REST_Controller;


class Pedidos extends REST_Controller {


  public function __construct(){

    header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    header("Access-Control-Allow-Origin: *");


    parent::__construct();
    $this->load->database();

  }


  public function realizar_orden_post($token = "0", $id_usuario = "0"){

    $data = $this->post();

    if( $token == "0" || $id_usuario == "0" ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Token invalido y/o usuario invalido."
                  );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
      return;
    }

    if( !isset( $data["items"] ) || strlen( $data['items'] )== 0 ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Faltan los items en el post"
                  );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
      return;
    }

    // AQUI, items, usuario, token
    $condiciones = array('id' => $id_usuario, 'token'=> $token );
    $this->db->where( $condiciones );
    $query = $this->db->get('login');

    $existe = $query->row();

    if( !$existe ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Usuario y Token incorrectos"
                  );
      $this->response( $respuesta );
      return;
    }

    // Usuario y Token son correctos
    $this->db->reset_query();

    $insertar = array('usuario_id' => $id_usuario );
    $this->db->insert( 'ordenes', $insertar );
    $orden_id = $this->db->insert_id();

    // crear el detalle de la orden
    $this->db->reset_query();
    $items = explode( ',', $data['items'] );

    foreach( $items as &$producto_id ){
      $data_insertar = array( 'producto_id' => $producto_id, 'orden_id'=>$orden_id );
      $this->db->insert('ordenes_detalle', $data_insertar);
    }

    $respuesta = array(
                  'error' => FALSE,
                  'orden_id' => $orden_id
                );


    $this->response( $respuesta );

  }


  public function obtener_pedidos_get($token = "0", $id_usuario ="0" ){

    if( $token == "0" || $id_usuario == "0" ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Token invalido y/o usuario invalido."
                  );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
      return;
    }

    $condiciones = array('id' => $id_usuario, 'token'=> $token );
    $this->db->where( $condiciones );
    $query = $this->db->get('login');

    $existe = $query->row();

    if( !$existe ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Usuario y Token incorrectos"
                  );
      $this->response( $respuesta );
      return;
    }

    // Retornar todas las ordenes del usuario
    $query = $this->db->query('SELECT * FROM `ordenes` where usuario_id = ' . $id_usuario );

    $ordenes = array();

    foreach( $query->result() as $row ){

      $query_detalle = $this->db->query('SELECT a.orden_id, b.* FROM `ordenes_detalle` a inner join productos b on a.producto_id = b.codigo where orden_id = '. $row->id );

      $orden = array(
          'id' => $row->id,
          'creado_en' => $row->creado_en,
          'detalle' => $query_detalle->result()
        );

      array_push( $ordenes, $orden );

    }

    $respuesta = array(
                  'error' => FALSE,
                  'ordenes'=> $ordenes
                );


      $this->response( $respuesta );

  }


  public function borrar_pedido_delete( $token = "0", $id_usuario = "0", $orden_id = "0" ){

    if( $token == "0" || $id_usuario == "0" || $orden_id == "0" ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Token invalido y/o usuario invalido."
                  );
      $this->response( $respuesta, REST_Controller::HTTP_BAD_REQUEST );
      return;
    }

    $condiciones = array('id' => $id_usuario, 'token'=> $token );
    $this->db->where( $condiciones );
    $query = $this->db->get('login');

    $existe = $query->row();

    if( !$existe ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Usuario y Token incorrectos"
                  );
      $this->response( $respuesta );
      return;
    }

    // Verificar si la orden es de ese usuario
    $this->db->reset_query();
    $condiciones = array('id' => $orden_id, 'usuario_id'=> $id_usuario);
    $this->db->where( $condiciones );
    $query = $this->db->get( 'ordenes' );

    $existe = $query->row();

    if( !$existe ){
      $respuesta = array(
                    'error' => TRUE,
                    'mensaje'=> "Esa orden no puede ser borrada"
                  );
      $this->response( $respuesta );
      return;
    }

    // Todo esta BIEN!
    $condiciones = array( 'id' => $orden_id );
    $this->db->delete( 'ordenes', $condiciones );

    $condiciones = array('orden_id' => $orden_id );
    $this->db->delete( 'ordenes_detalle', $condiciones );

    $respuesta = array(
              'error' => FALSE,
              'mensaje'=>'Orden eliminada'
          );

    $this->response( $respuesta );

  }




}
