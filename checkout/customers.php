<?php
// Configurações de conexão com o banco de dados
$servername = "localhost"; // endereço do servidor
$username = "cmo_checkout"; // nome de usuário
$password = "wovCorcyjx"; // senha
$dbname = "cmo_checkout"; // nome do banco de dados

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Seleciona todos os dados da tabela "nome_da_tabela"
$sql = "SELECT * FROM checkouts";
$result = $conn->query($sql);

// Verifica se há dados na tabela
if ($result->num_rows > 0) {
    // Inicia a tabela HTML
    echo "<table>";
    echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th></tr>";

    // Loop pelos resultados e adiciona-os à tabela HTML
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["nome"] . "</td><td>" . $row["email"] . "</td><td>" . $row["telefone"] . "</td></tr>";
    }

    // Fecha a tabela HTML
    echo "</table>";
} else {
    echo "Não há dados na tabela.";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
