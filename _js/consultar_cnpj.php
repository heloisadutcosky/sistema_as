<?php
 
$cnpj = $_POST['cnpj'];

$ch = curl_init(); //Inicializa
curl_setopt($ch, CURLOPT_URL, "https://www.receitaws.com.br/v1/cnpj/".$cnpj); //Acessa a URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Permite a captura do Retorno
$retorno = curl_exec($ch); //Executa o cURL e guarda o Retorno em uma variável
curl_close($ch); //Encerra a conexão

$retorno = json_decode($retorno); //Ajuda a ser lido mais rapidamente
echo json_encode($retorno, JSON_PRETTY_PRINT);
//
 
?>