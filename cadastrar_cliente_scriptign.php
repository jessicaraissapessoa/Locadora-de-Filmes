<?php
include('conexao.php');

// Recupere os dados do formulário
$nome = $_POST["nome"];
$login = $_POST["login"];
$senha = $_POST["senha"];
$estado = $_POST["estado"];
$cidade = $_POST["cidade"];
$logradouro = $_POST["logradouro"];
$numero = $_POST["numero"];
$bairro = $_POST["bairro"];

// Verifique se o login já existe
$verificar_login = "SELECT login FROM login WHERE login = '$login'";
$query_ver_login = mysqli_query($conexao, $verificar_login);
$quant_login = mysqli_num_rows($query_ver_login);

if ($quant_login > 0) {
    echo "<script>alert('O login já existe, por favor escolha outro')</script>";
    echo "<script>window.location.href='cadastrar_cliente.php'</script>";
} else {
    if (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        echo "<script>alert('O login deve conter apenas letras e números.')</script>";
        echo "<script>window.location.href='cadastrar_cliente.php'</script>";
    } else {
        // Inicie uma transação para garantir a consistência dos dados
        mysqli_begin_transaction($conexao);

        // Verifique se é o primeiro usuário cadastrado
        $verificar_primeiro_usuario = "SELECT idcliente FROM login";
        $query_ver_primeiro_usuario = mysqli_query($conexao, $verificar_primeiro_usuario);

        if (mysqli_num_rows($query_ver_primeiro_usuario) == 0) {
            // Insira os dados na tabela "cliente"
            $inserirCliente = "INSERT INTO cliente (nomecliente, logradouro, numlogradouro, bairro, cidade, estado) 
            VALUES ('$nome', '$logradouro', '$numero', '$bairro', '$cidade', '$estado')";
            $queryInserirCliente = mysqli_query($conexao, $inserirCliente);

            // Obtenha o ID do cliente recém-inserido
            $idCliente = mysqli_insert_id($conexao);

            // Se for o primeiro usuário, insira-o como administrador (id_tipo_usuario = 1)
            $inserirLogin = "INSERT INTO login (login, senha, idcliente, id_tipo_usuario) VALUES ('$login', '$senha', '$idCliente', 1)";
        } else {
            // Caso contrário, insira-o como cliente (id_tipo_usuario = 2)
            $inserirCliente = "INSERT INTO cliente (nomecliente, logradouro, numlogradouro, bairro, cidade, estado) 
                VALUES ('$nome', '$logradouro', '$numero', '$bairro', '$cidade', '$estado')";
            $queryInserirCliente = mysqli_query($conexao, $inserirCliente);

            if (!$queryInserirCliente) {
                // Rollback em caso de erro na inserção de cliente
                mysqli_rollback($conexao);

                echo "<script>alert('Erro ao cadastrar cliente')</script>";
                echo "<script>window.location.href='cadastrar_cliente.php'</script>";
                exit;
            }

             // Obtenha o ID do cliente recém-inserido
             $idCliente = mysqli_insert_id($conexao);

             // Insira os dados na tabela "login"
             $inserirLogin = "INSERT INTO login (login, senha, idcliente, id_tipo_usuario) VALUES ('$login', '$senha', $idCliente, 2)";
        }

        $queryInserirLogin = mysqli_query($conexao, $inserirLogin);

        if ($queryInserirLogin) {
            // Commit a transação se tudo estiver correto
            mysqli_commit($conexao);

            echo "<script>alert('Dados inseridos com sucesso, por favor faça o login para ter acesso ao sistema')</script>";
            echo "<script>window.location.href='index.php'</script>";
        } else {
            // Rollback em caso de erro na inserção de login
            mysqli_rollback($conexao);

            echo "<script>alert('Erro ao cadastrar login')</script>";
            echo "<script>window.location.href='cadastrar_cliente.php'</script>";
        }
    }
}

// Feche a conexão com o banco de dados
mysqli_close($conexao);
?>
