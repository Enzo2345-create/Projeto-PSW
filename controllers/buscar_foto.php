<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$nome = $_GET['nome'] ?? '';

if(!$nome){
    echo json_encode(['foto' => null]);
    exit;
}

$cache_arquivo = '../data/fotos_cache.json';

$cache = [];
if(file_exists($cache_arquivo)){
    $cache = json_decode(file_get_contents($cache_arquivo), true) ?? [];
}

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

// Limpa o nome removendo informações extras (posição, país, ano)
// Ex: "Ronaldo Nazário Fenômeno centroavante Brasil 2002" → "Ronaldo Nazário"
function limparNomeParaWikipedia($nome){
    $remover = [
        // posições
        'goleiro','zagueiro','lateral','volante','meia','atacante','centroavante',
        'ponta','libero','marcador','meia-atacante','capitão','camisa',
        // países
        'Brasil','Argentina','Alemanha','Italia','Itália','França','Inglaterra',
        'Espanha','Uruguai','Holanda','Hungria','Polônia','Croácia','Bulgária',
        'Colômbia','Portugal','Tchecoslováquia','Suécia','Suíça',
        // palavras extras
        'goleiro','técnico','artilheiro','final','direito','esquerdo','jovem',
        // anos (remove qualquer número de 4 dígitos)
    ];

    // Remove anos (4 dígitos)
    $nome = preg_replace('/\b\d{4}\b/', '', $nome);

    // Remove palavras da lista (case-insensitive)
    foreach($remover as $palavra){
        $nome = preg_replace('/\b' . preg_quote($palavra, '/') . '\b/iu', '', $nome);
    }

    // Remove espaços extras
    $nome = trim(preg_replace('/\s+/', ' ', $nome));

    return $nome;
}

$foto = null;
$nomeWiki = limparNomeParaWikipedia($nome);
$queryWiki = str_replace(' ', '_', $nomeWiki);
$queryOriginal = str_replace(' ', '_', $nome);

// 1. Wikipedia EN (nome limpo)
$response = buscarCurl("https://en.wikipedia.org/api/rest_v1/page/summary/{$queryWiki}");
if($response){
    $data = json_decode($response, true);
    $foto = $data['thumbnail']['source'] ?? null;
}

// 2. TheSportsDB (nome original com contexto)
if(!$foto){
    $response2 = buscarCurl("https://www.thesportsdb.com/api/v1/json/3/searchplayers.php?p=" . urlencode($nomeWiki));
    if($response2){
        $data2 = json_decode($response2, true);
        $foto = $data2['player'][0]['strThumb'] ?? null;
    }
}

// 3. Wikidata (nome limpo)
if(!$foto){
    $sparql = urlencode("SELECT ?image WHERE { ?person wikibase:directClaim wdt:P18 . ?person rdfs:label \"{$nomeWiki}\"@en . ?person wdt:P18 ?image . } LIMIT 1");
    $response3 = buscarCurl("https://query.wikidata.org/sparql?query={$sparql}&format=json");
    if($response3){
        $data3 = json_decode($response3, true);
        $foto = $data3['results']['bindings'][0]['image']['value'] ?? null;
    }
}

// 4. Wikipedia PT (nome limpo)
if(!$foto){
    $response4 = buscarCurl("https://pt.wikipedia.org/api/rest_v1/page/summary/{$queryWiki}");
    if($response4){
        $data4 = json_decode($response4, true);
        $foto = $data4['thumbnail']['source'] ?? null;
    }
}

// Salva no cache com o nome original como chave
$cache[$nome] = $foto;
file_put_contents(
    $cache_arquivo,
    json_encode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode(['foto' => $foto]);