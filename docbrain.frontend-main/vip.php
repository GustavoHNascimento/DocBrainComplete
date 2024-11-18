<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://s1.aviso.vip:8080/messages');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"number\": \"5519995627352\",\n  \"message\": \"Teste 12358\",\n  \"dataParaEnvio\": \"\"\n}");

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Token: qJvWx-5iu1XGq-6j2g8kD';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

//Enviar o SMS com o código
            // Criar a mensagem
            $mensagem = urlencode("[Blio] Codigo de verificacao: $codigo_autenticacao");

            // URL com os dados
            $url_api = "https://api.iagentesms.com.br/webservices/http.php?metodo=envio&usuario=". USUARIOIAGENTE."&senha=".SENHAIAGENTE."&celular=".$row_usuario['celular'] ."&mensagem=$mensagem&codigosms=300";

            // Realizar a requisição HTTP
            $resposta_api = file_get_contents($url_api);

            if($resposta_api == "OK") {
                // Salvar os dados do usuário na sessão
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['celular'] = $row_usuario['celular'];

                // Redirecionar o usuário
                header('Location: validar_codigo.php');

                // Pausar o processamento
                exit();
            };