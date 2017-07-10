<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$db = new mysqli('localhost', 'root', '', 'api_mc_fly');

// Configuración de cabeceras
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}



// LISTAR TODAS LAS nota
$app->get('/nota', function() use($db, $app){
	$sql = 'SELECT * FROM notas ORDER BY id DESC;';
	$query = $db->query($sql);

	$nota = array();
	while ($nota = $query->fetch_assoc()) {
		$notas[] = $nota;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $notas
		);
	var_dump($nota);
	echo json_encode($result);
});


// LISTAR TODAS LAS nota FAVORITAS
$app->get('/nota-favoritas', function() use($db, $app){
	$sql = 'SELECT * FROM notas WHERE favorito = "true" ORDER BY id DESC;';
	$query = $db->query($sql);

	$nota = array();
	while ($nota = $query->fetch_assoc()) {
		$notas[] = $nota;
	}

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $notas
		);
	var_dump($nota);
	echo json_encode($result);
});

// DEVOLVER UNA NOTA EN PARTICULAR
$app->get('/nota/:id', function($id) use($db, $app){
	$sql = 'SELECT * FROM notas WHERE id = '.$id;
	$query = $db->query($sql);

	$result = array(
		'status' 	=> 'error',
		'code'		=> 404,
		'message' 	=> 'nota no disponible'
	);

	if($query->num_rows == 1){
		$nota = $query->fetch_assoc();

		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'data' 	=> $nota
		);
	}

	echo json_encode($result);
});


//  UNA NOTA FAVORITA
$app->post('/update-nota/:id', function($id) use($db, $app){
	$sql = 'UPDATE notas SET favorito = "true" WHERE id = '.$id;
	$query = $db->query($sql);


	if($query){
		$result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'El nota se ha editado correctamente!!'
		);
	}else{
		$result = array(
			'status' 	=> 'error',
			'code'		=> 404,
			'message' 	=> 'El nota no se ha editado!!'
		);
	}
	echo json_encode($result);

});

// GUARDAR NOTA
$app->post('/nota', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if(!isset($data['autor'])){
		$data['autor']=null;
	}

	if(!isset($data['nota'])){
		$data['nota']=null;
	}

	if(!isset($data['favorito'])){
		$data['favorito']='false';
	}

	$query = "INSERT INTO notas VALUES(NULL,".
			 "'{$data['autor']}',".
			 "'{$data['nota']}',".
			 "'{$data['favorito']}'".
			 ");";
			 var_dump($query);

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'nota NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'nota creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->run();