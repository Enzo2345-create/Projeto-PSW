<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$nome = $_GET['nome'] ?? '';

if(!$nome){
    echo json_encode(['foto' => null]);
    exit;
}

$cache_arquivo = '../data/fotos_cache.json';

// Carrega cache existente
$cache = [];
if(file_exists($cache_arquivo)){
    $cache = json_decode(file_get_contents($cache_arquivo), true) ?? [];
}

// Se já está no cache retorna direto
if(array_key_exists($nome, $cache)){
    echo json_encode(['foto' => $cache[$nome]]);
    exit;
}

function buscarCurl($url){
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
        CURLOPT_FOLLOWLOCATION => true,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$foto = null;

// 1. Wikipedia EN
$query = str_replace(' ', '_', $nome);
$response = buscarCurl("https://en.wikipedia.org/api/rest_v1/page/summary/{$query}");
if($response){
    $data = json_decode($response, true);
    $foto = $data['thumbnail']['source'] ?? null;
}

// 2. TheSportsDB
if(!$foto){
    $response2 = buscarCurl("https://www.thesportsdb.com/api/v1/json/3/searchplayers.php?p=" . urlencode($nome));
    if($response2){
        $data2 = json_decode($response2, true);
        $foto = $data2['player'][0]['strThumb'] ?? null;
    }
}

// 3. Wikidata
if(!$foto){
    $sparql = urlencode("SELECT ?image WHERE { ?person wikibase:directClaim wdt:P18 . ?person rdfs:label \"{$nome}\"@en . ?person wdt:P18 ?image . } LIMIT 1");
    $response3 = buscarCurl("https://query.wikidata.org/sparql?query={$sparql}&format=json");
    if($response3){
        $data3 = json_decode($response3, true);
        $foto = $data3['results']['bindings'][0]['image']['value'] ?? null;
    }
}

// 4. Wikipedia PT
if(!$foto){
    $response4 = buscarCurl("https://pt.wikipedia.org/api/rest_v1/page/summary/{$query}");
    if($response4){
        $data4 = json_decode($response4, true);
        $foto = $data4['thumbnail']['source'] ?? null;
    }
}

// Salva no cache (mesmo se null, para não buscar de novo)
$cache[$nome] = $foto;
file_put_contents(
    $cache_arquivo,
    json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode(['foto' => $foto]);