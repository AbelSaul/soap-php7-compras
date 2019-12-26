<?php

 require_once('dbconn.php');
 require_once('lib/nusoap.php');
 $server = new nusoap_server();

 /* Method to isnter a new compra */
function guardarCompra($serie, $numero, $fecha_emision, $fecha_vencimiento,$operaciones_gravadas, $impuesto_general_ventas, $importe_total, $moneda_id, $tipo_cambio_id,$tipo_comprobante_id, $tipo_pago_id, $proveedor_id){

    global $dbconn;
    $sql_insert = "insert into compras
     (
      serie, numero, fecha_emision,
      fecha_vencimiento, operaciones_gravadas,
      impuesto_general_ventas,
      importe_total, moneda_id, tipo_cambio_id,
      tipo_comprobante_id, tipo_pago_id, proveedor_id
     )
     values
     (
      :serie, :numero, :fecha_emision,
      :fecha_vencimiento, :operaciones_gravadas,
      :impuesto_general_ventas,
      :importe_total, :moneda_id, :tipo_cambio_id,
      :tipo_comprobante_id, :tipo_pago_id, :proveedor_id
      )";

      $stmt = $dbconn->prepare($sql_insert);
    // insert a row
    $result = $stmt->execute(array(
      ':serie'=>$serie, ':numero'=>$numero, ':fecha_emision'=>$fecha_emision,
      ':fecha_vencimiento'=>$fecha_vencimiento,':operaciones_gravadas'=>$operaciones_gravadas,
      ':impuesto_general_ventas'=> $impuesto_general_ventas,
      ':importe_total' => $importe_total, ':moneda_id' => $moneda_id, ':tipo_cambio_id'=> $tipo_cambio_id, ':tipo_comprobante_id' => $tipo_comprobante_id, ':tipo_pago_id' => $tipo_pago_id, ':proveedor_id'=> $proveedor_id)
    );

    if($result) {
      return json_encode(array('status'=> 200, 'msg'=> 'success'));
    }
    else {
      return json_encode(array('status'=> 400, 'msg'=> 'fail'));
    }
    $dbconn = null;
  }

function obtenerDatosCompra($proveedor_id){
	global $dbconn;
	$sql = "SELECT id, serie, numero, fecha_emision, fecha_vencimiento, operaciones_gravadas, impuesto_general_ventas, importe_total, moneda_id, tipo_cambio_id, tipo_comprobante_id, tipo_pago_id, proveedor_id FROM compras
	        where proveedor_id = :proveedor_id";
  // prepare sql and bind parameters
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':proveedor_id', $proveedor_id);
    // insert a row
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}
$server->configureWSDL('comprasServer', 'urn:compra');
$server->register('obtenerDatosCompra',
			array('proveedor_id' => 'xsd:int'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:compra',   //namespace
			'urn:compra#obtenerDatosCompra' //soapaction
      );
      $server->register('guardarCompra',
			array('serie' => 'xsd:string', 'numero' => 'xsd:string', 'fecha_emision' => 'xsd:string', 'fecha_vencimiento' => 'xsd:string', 'operaciones_gravadas' => 'xsd:string' , 'impuesto_general_ventas' => 'xsd:string', 'importe_total' => 'xsd:string', 'moneda_id' => 'xsd:string', 'tipo_cambio_id' => 'xsd:string','tipo_comprobante_id' => 'xsd:string', 'tipo_pago_id' => 'xsd:string', 'proveedor_id' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:compra',   //namespace
			'urn:compra#obtenerDatosCompra' //soapaction
			);
$server->service(file_get_contents("php://input"));

// $datos = obtenerDatosCompra(1);
// var_dump($datos);

$datos = guardarCompra("B001","2","2019-12-24 08:05:36", "2019-12-24 08:05:36", "0","0","200", "1", "1", "1","1","1");
var_dump($datos);
?>
