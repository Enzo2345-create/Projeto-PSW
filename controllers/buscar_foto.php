<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$nome = $_GET['nome'] ?? '';

if(!$nome){
    echo json_encode(['foto' => null]);
    exit;
}

$query = urlencode($nome);
$url = "https://en.wikipedia.org/api/rest_v1/page/summary/{$query}";

$ctx = stream_context_create([
    'http' => [
        'timeout' => 5,
        'header'  => 'User-Agent: MFM-Project/1.0'
    ]
]);

$response = @file_get_contents($url, false, $ctx);

if(!$response){
    echo json_encode(['foto' => null]);
    exit;
}

$data = json_decode($response, true);
$foto = $data['thumbnail']['source'] ?? null;

echo json_encode(['foto' => $foto]);