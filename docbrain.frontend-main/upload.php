<?php
$uploadDir = 'uploads/';
$uploadDate = date("Y-m-d H:i:s"); 

if (!file_exists($uploadDir)) {
 mkdir($uploadDir, 0777, true);
}


//$arquivo = basename($_FILES['file']['name']);
$arquivo = $_POST['fileInput'];
echo $arquivo;
$caminho = $uploadDir . $arquivo;

$servername = "localhost";
$username = "sitess31_analise";
$password = ")laqr(y$*6y?";
$dbname = "sitess31_analise";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
 die("Falha na conexão: " . $conn->connect_error);
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $caminho)) {
 echo 'Arquivo enviado com sucesso: ' . htmlspecialchars(basename($arquivo));
 $sql = "INSERT INTO uploaded_files (id_cliente, caminho) VALUES (1, '$caminho')";
 if ($conn->query($sql) === TRUE) {
  echo "Arquivo enviado com sucesso e dados inseridos na fila.";
 }
 else {
  echo "Erro ao inserir dados na fila: " . $conn->error;
 }
}
else {
 echo 'Ocorreu um erro ao enviar o arquivo.';
}

$conn->close();

?>
