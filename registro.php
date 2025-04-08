<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Corretor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f8f8f8;
            padding: 20px;
        }

        .container {
            width: 350px;
            background: white;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 15px;
        }

        .form-inline {
            display: flex;
            justify-content: space-between;
        }
        .form-inline input {
            width: 48%;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            opacity: 0.8;
        }

        input::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2c2f38;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1f222a;
        }

        #mensagem {
            margin-top: 10px;
            font-weight: bold;
        }

        .table-container {
            width: 80%;
            background: white;
            padding: 15px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .action-link {
            color: blue;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin-right: 10px;
        }

        .action-link:hover {
            color: darkblue;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Formulário de Cadastro -->
    <div class="container">
        <h2>Cadastro de Corretor</h2>
        <form id="registroForm">
            <input type="hidden" id="usuario_id" name="usuario_id">
            <div class="form-inline">
                <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" maxlength="11" required>
                <input type="text" id="creci" name="creci" placeholder="Digite seu Creci" required>
            </div>
            <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
            <button type="submit" id="botao">Enviar</button>
        </form>
        <p id="mensagem"></p>
    </div>

    <div class="table-container">
        <h2>Usuários Registrados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>CRECI</th>
                <th>Ações</th>
            </tr>
            <?php
            // Conectar ao banco de dados 
            $conn = new mysqli("localhost", "root", "", "registro_db");

            // Verificar conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            // Selecionar dados dos usuários cadastrados
            $sql = "SELECT id, nome, cpf, creci FROM corretores";
            $result = $conn->query($sql);

            // Exibir dados na tabela
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id"] . "</td>
                    <td>" . $row["nome"] . "</td>
                    <td>" . $row["cpf"] . "</td>
                    <td>" . $row["creci"] . "</td>
                    <td>
                        <a class='action-link' onclick='editarUsuario(" . $row["id"] . ", \"" . $row["nome"] . "\", \"" . $row["cpf"] . "\", \"" . $row["creci"] . "\")'>Editar</button>
                        <a class='action-link' onclick='excluirUsuario(" . $row["id"] . ")'>Excluir</button>
                    </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum registro encontrado</td></tr>";
            }

            // Fechar conexão
            $conn->close();
            ?>
        </table>
    </div>

    <script>

        function editarUsuario(id, nome, cpf, creci) {
            document.getElementById("usuario_id").value = id;
            document.getElementById("nome").value = nome;
            document.getElementById("cpf").value = cpf;
            document.getElementById("creci").value = creci;
            document.getElementById("botao").innerText = "Salvar";
        }

        function excluirUsuario(id) {
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                fetch("excluir.php?id=" + id, {
                    method: "GET"
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Atualiza a página após exclusão
                })
                .catch(error => {
                    alert("Erro ao excluir usuário.");
                });
            }
        }
        // Adiciona funcionalidade ao formulário
        document.getElementById("registroForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let url = document.getElementById("usuario_id").value ? "editar.php" : "processa_registro.php";

            fetch(url, { method: "POST", body: formData })
            .then(response => response.text())
            .then(data => {
                document.getElementById("mensagem").innerText = data;
                document.getElementById("registroForm").reset();
                document.getElementById("botao").innerText = "Enviar";
                setTimeout(() => { location.reload(); }, 1500);
            });
        });
    </script>

</body>
</html>
