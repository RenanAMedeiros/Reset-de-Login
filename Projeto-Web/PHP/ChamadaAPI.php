<?php
// Defina os parâmetros de login e senha
$login = "rh.metadados";
$senha = "Rhmetadados@2023";

// Construção da URL da API parâmetros . Utilizando a Função http_build_query transformando o array em um formato de querystring, 
//pronto para ser passada para uma URL. Ele vai transformar a sequencia de chave-valor e separar por um &.
$url = "http://10.192.80.166:8206/api/CSLOG/login?" . http_build_query([
    'usuario' => $login,
    'senha' => $senha
]);

// Fazendo uma requisição GET para a API e salvando a resposta em uma variável
$resposta = file_get_contents($url);


// Solicite ao usuário um login
$usuarioreset = readline("Digite seu login: ");

// Fazendo uma requisição GET para a primeira API para obter um token
$url_token = "http://10.192.80.166:8206/api/CSLOG/login?" . http_build_query([
    'usuario' => $login,
    'senha' => $senha
]);
$token_response = file_get_contents($url_token);
$token = json_decode($token_response)->token;

// Monte o corpo JSON para a segunda API
$data = [
    "metodo" => "RESET",
    "token" => "$token",
    "cpf" => "",
    "infoAdicional" => "",
    "login" => "$usuarioreset"
];
$body = json_encode($data);

// fazendo a requisição via POST para a segunda API com o corpo JSON
$url_post = "http://10.192.80.166:8206/api/CSLOG/processaCadastroUsuario";
$options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $body
    )
);
$context = stream_context_create($options);
$result = file_get_contents($url_post, false, $context);

// Verifica  se o consumo status retornado foi 200 e apresente a mensagem apropriada
if (strpos($http_response_header[0], '200') !== false) {
    echo "Reset concluído.";
} else {
    echo "Reset não foi possível Falar com Sistemas.";
}
?>
