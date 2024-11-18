<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start(); // Iniciar a sessão

ob_start(); // Limpar o buffer de saída

// Incluir o arquivo com as configurações
include_once './config.php';

// Definir um fuso horario padrao
date_default_timezone_set('America/Sao_Paulo');

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

$uploadDate = date("Y-m-d H:i:s");

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-login.css">
    
    

    <title>Login</title>
</head>

<body>
    
        <div class="container">
        <img src="logo.png" alt="logo" style="width: 170px; height: 50px;">
        <h1>Login</h1>

        <?php
        // Exemplo criptografar a senha
        //echo password_hash(123456, PASSWORD_DEFAULT);

        // Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Acessar o IF quando o usuário clicar no botão acessar do formulário
        if (!empty($dados['SendLogin'])) {
            //var_dump($dados);

            // Recuperar os dados do usuário no banco de dados
            $query_usuario = "SELECT id, nome, celular
                        FROM usuarios
                        WHERE celular=:celular
                        LIMIT 1";

            // Preparar a QUERY
            $result_usuario = $conn->prepare($query_usuario);

            // Substituir o link da query pelo valor que vem do formulário
            $result_usuario->bindParam(':celular', $dados['celular']);

            // Executar a QUERY
            $result_usuario->execute();

            // Acessar o IF quando encontrar usuário no banco de dados
            if (($result_usuario) and ($result_usuario->rowCount() != 0)) {

                // Ler os registros retorando do banco de dados
                $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                var_dump($row_usuario);

                // Acessar o IF quando a Celular é válido
                if ($dados['celular'] == $row_usuario['celular']) {

                    // Recuperar a data atual
                    $data = date('Y-m-d H:i:s');

                    // Gerar número randômico entre 100000 e 999999
                    $codigo_autenticacao = mt_rand(100000, 999999);

                    // QUERY para salvar no banco de dados o código e a data gerada
                    $query_up_usuario = "UPDATE usuarios SET
                        codigo_autenticacao=:codigo_autenticacao,
                        data_codigo_autenticacao=:data_codigo_autenticacao
                        WHERE id=:id
                        LIMIT 1";

                    // Preparar a QUERY
                    $result_up_usuario = $conn->prepare($query_up_usuario);

                    // Substituir o link da QUERY pelos valores
                    $result_up_usuario->bindParam(':codigo_autenticacao', $codigo_autenticacao);
                    $result_up_usuario->bindParam(':data_codigo_autenticacao', $data);
                    $result_up_usuario->bindParam(':id', $row_usuario['id']);

                    // Executar a QUERY
                    $result_up_usuario->execute();

                    // Salvar dados
                    $_SESSION['id'] = $row_usuario['id'];
                    $_SESSION['codigo_autenticacao'] = $codigo_autenticacao;

                    // Substituir o trecho de código 1 pelo código 2
                   
                    $celular = '55' . $row_usuario['celular'];

                    // Restante do seu código
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://zzjpuypfljns3lgtisnhqez5nu0mihgc.lambda-url.us-east-1.on.aws/messages');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\n  \"number\": \"$celular\",\n  \"message\": \"[docbrain] Codigo de verificação: $codigo_autenticacao\",\n  \"dataParaEnvio\": \"\"\n}");


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

                    // Redirecionar o usuário
                    header('Location: validar_codigo.php');

                    // Pausar o processamento
                    exit();
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Celular não cadastrado!</p>";
                }
            } else {
                $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Celular não cadastrado!</p>";
            }
        }

        // Imprimir a mensagem da sessão
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }

        ?>

        <!-- Inicio do formulário de login -->
        <form method="POST" action="">
            <label>Celular: </label>
            <input type="text" name="celular" placeholder="Digite o celular"><br><br>

            <input type="submit" name="SendLogin" value="Acessar"><br><br>

        </form>
        <!-- Fim do formulário de login -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.1.0/vanilla-masker.min.js"></script>
        
    </div>
</body>

</html>
