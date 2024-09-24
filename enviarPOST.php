<?php

// URL de la API
$url = 'http://ws.liza.agcontrol.gob.ar/api/account/login';

// Parámetros de consulta
$params = [
    'nombreUsuario' => 'usuarioserviciosexternos@liza.agc',
    'clave' => 'pepe'
];

// Iniciar cURL
$ch = curl_init();

// Establecer opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($ch);

// Verificar si hubo algún error
if (curl_errno($ch)) {
    echo 'Error en cURL: ' . curl_error($ch);
} else {
    // Decodificar la respuesta JSON
    $result = json_decode($response, true);
    
    // Mostrar el resultado
    print_r($result);
}

// Cerrar cURL
curl_close($ch);

?>