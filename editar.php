<?php
$conn = new mysqli("localhost", "root", "", "registro_db");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$id = $_POST["usuario_id"];
$nome = $_POST["nome"];
$cpf = $_POST["cpf"];
$creci = $_POST["creci"];

// Validação no servidor
if (strlen($nome) < 2) {
    echo "Erro: Nome deve ter pelo menos 2 caracteres.";
    exit;
}
if (strlen($cpf) !== 11 || !is_numeric($cpf)) {
    echo "Erro: CPF deve ter exatamente 11 números.";
    exit;
}
if (strlen($creci) < 2) {
    echo "Erro: CRECI deve ter pelo menos 2 caracteres.";
    exit;
}

// Verifica se o CPF já está cadastrado
$sql = "SELECT id FROM corretores WHERE cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Erro: Este CPF já está registrado.";
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$sql = "UPDATE corretores SET nome='$nome', cpf='$cpf', creci='$creci' WHERE id=$id";
echo ($conn->query($sql) === TRUE) ? "Usuário atualizado com sucesso!" : "Erro ao atualizar: " . $conn->error;
$conn->close();
?>