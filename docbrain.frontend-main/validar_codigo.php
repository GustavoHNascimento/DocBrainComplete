<?php

session_start(); // Iniciar a sessão

ob_start(); // Limpar o buffer de saída

// Definir um fuso horario padrao
date_default_timezone_set('America/Sao_Paulo');

// Incluir o arquivo com a conexão com banco de dados
include_once './conexao.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-login.css">
    <title>DocBrain -Validar codigo</title>
</head>

<body>

<div class="container">
    <img src="logo.png" alt="logo" style="width: 170px; height: 50px;">   
        <h1>Digite o código</h1>
    

    <?php

    // Receber os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Acessar o IF quando o usuário clicar no botão acessar do formulário
if(!empty($dados['ValCodigo'])) {
    //var_dump($dados);

    // Recuperar os dados do usuário no banco de dados
    $query_usuario = "SELECT id, nome, codigo_autenticacao
                FROM usuarios
                WHERE id=:id
                AND codigo_autenticacao=:codigo_autenticacao
                LIMIT 1";

    // Preparar a QUERY
    $result_usuario = $conn->prepare($query_usuario);
    //var_dump($_SESSION['id']);
    //var_dump($_SESSION['codigo_autenticacao']);
    //var_dump($_SESSION['celular']);

    // Substituir o link da query pelo valor que vem do formulário
    $result_usuario->bindParam(':id', $_SESSION['id']);
    $result_usuario->bindParam(':codigo_autenticacao', $dados['codigo_autenticacao']);

    // Executar a QUERY
    $result_usuario->execute();
   // Acessar o IF quando encontrar usuário no banco de dados
    if(($result_usuario) and ($result_usuario->rowCount() != 0)) {

        // Ler os registros retorando do banco de dados
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

        // QUERY para apagar no banco de dados o código e a data gerada
        $query_up_usuario = "UPDATE usuarios SET 
                codigo_autenticacao = NULL,
                data_codigo_autenticacao = NULL
                WHERE id=:id
                LIMIT 1";

        // Preparar a QUERY
        $result_up_usuario = $conn->prepare($query_up_usuario);

        // Substituir o link da QUERY pelo valores
        $result_up_usuario->bindParam(':id', $_SESSION['id']);

        // Executar a QUERY
        $result_up_usuario->execute();

        // Salvar os dados do usuário na sessão
        $_SESSION['nome'] = $row_usuario['nome'];
        $_SESSION['codigo_autenticacao'] = true;

        // Redirecionar o usuário
        header('Location: tools.php');

    } else {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Código inválido!</p>";
    }
}

// Imprimir a mensagem da sessão
if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>

    <!-- Inicio do formulário validar código -->
    <form method="POST" action="">
    <label>Código: </label>
    <input type="text" name="codigo_autenticacao" placeholder="Digite o código"><br><br>

    <input type="submit" name="ValCodigo" value="Validar"><br><br>
</form>
    <!-- Fim do formulário validar código -->

</body>
</html>