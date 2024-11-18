<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start(); // Iniciar a sessão

ob_start(); // Limpar o buffer de saída




$uploadDir = 'uploads/';
$uploadDate = date("Y-m-d H:i:s"); 

if (!file_exists($uploadDir)) {
 mkdir($uploadDir, 0777, true);
}


$host = "localhost";
$user = "sitess31_analise";
$pass = ")laqr(y$*6y?";
$dbname = "sitess31_analise";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Oops... " . $conn->connect_error);
}


// Definir um fuso horario padrao
date_default_timezone_set('America/Sao_Paulo');

// Acessar o IF quando o usuário não estão logado e redireciona para página de login
if((!isset($_SESSION['id'])) and (!isset($_SESSION['celular'])) and (!isset($_SESSION['codigo_autenticacao']))){

    // Criar a mensagem de erro
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Necessário realizar o login para acessar a página!</p>";

    // Redirecionar o usuário
    header('Location: index.php');

    // Pausar o processamento
    exit();
}

// Link para sair do sistema administrativo
echo "<a href='sair.php'>Sair</a><br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-tools.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.lordicon.com/ritcuqlt.js"></script>
    
    <title>DocBrain Platform</title>
    </head>
<body>
    <div id="sidebar">
        <h2>
             <img src="logo.png" alt="logo" style="width: 130px; height: 37px;">
        </h2>
        <ul>
            <li class="item-menu">
                <a href="#"onclick="loadLibrary()">
                    <span class="icon"><i class="bi bi-collection"></i></span>
                    <span class="txt-link">Biblioteca</span>
                </a>
                <li class="item-menu">
                    <a href="#"onclick="loadChat()">
                        <span class="icon"><i class="bi bi-robot"></i></span>
                        <span class="txt-link">Chat</span>
                    </a>
                    <a href="sair.php">Sair</a>
                    
        </ul>
    </div>

<?php
if ($_FILES){
 $arquivo = basename($_FILES['file']['name']);
 $caminho = $uploadDir . $arquivo;

 if (move_uploaded_file($_FILES['file']['tmp_name'], $caminho)) {
    echo $caminho;
  //echo 'Arquivo enviado com sucesso: ' . htmlspecialchars(basename($arquivo));
  $sql = "INSERT INTO uploaded_files (id_cliente, caminho) VALUES (1, '" . mysqli_real_escape_string($conn, $caminho) . "')";
  if (mysqli_query($conn, $sql)) {
   //echo "Arquivo enviado com sucesso e dados inseridos na fila.";
  }
  else {
   echo "Erro ao inserir dados na fila: " . mysqli_error($conn);
  }
 }
 else {
  echo 'Ocorreu um erro ao enviar o arquivo.';
 }
}

?>

    <div id="content">
        <!-- Primeira área de conteúdo para Biblioteca -->
        <div id="library-container">
            <div id="library-content">
                <form id="uploadForm" action="tools.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" id="fileInput"><br><br>
                    <input type="submit" value="Enviar Arquivo" id="uploadButton">
                </form>
                                      
                <div id="progressStatus"></div>
            </div>
        </div>
    
 <!-- Segunda área de conteúdo para Histórico -->

 <div id="historico-content">
                <!-- Tabela Histórico -->
                <h2>Histórico</h2>
                <table id="historicoTable">
                    <thead>
                        <tr>
                            <th>Nome do Arquivo</th>
                            <th>Extensão</th>
                            <th>Data de Upload</th>
                        </tr>
                        </thead>
                    <tbody>
             
 <?php
 $sql = "SELECT * FROM uploaded_files";
 $resultado = $conn->query($sql);

 foreach ($resultado as $registro){
  echo "<tr>";
  echo "<th>" . $registro['caminho'] . "</th>";
  echo "<th>" . substr($registro['caminho'], -3) . "</th>";
  echo "<th>" . $registro['data'] . "</th>";
  echo "</tr>";
 }

 ?> 
                        <!-- As linhas da tabela serão adicionadas dinamicamente aqui -->
                    </tbody>
                </table>
            </div>
        </div>
</div>

<?php

$conn->close();

?>

    
</body>

</html>