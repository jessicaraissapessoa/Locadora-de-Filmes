<?php
include('conexao.php');
include('verificacao.php');
include('funcoes.php');

$login = $_SESSION['login'];

$idfilme = $_POST['idfilme'];
$idaluguel = $_POST['idaluguel'];
$nomeCliente = $_POST['nomecliente'];
$nomeFilme = $_POST['nomefilme'];
$dataalguel = $_POST['dataaluguel'];
$prazo_de_entrega = $_POST['dataentrega'];
verificarEAtualizarStatusPendente($conexao, $login);
verificarLocacaoPendenteEExibirAlerta($conexao, $login);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ediatar Locação</title>
</head>

<body>
    <form action="editar_locacao_scripting.php" method="post">
        <input type="hidden" name="dataEntregaAntiga" value="<?= $prazo_de_entrega ?>">
        <input type="hidden" name="idfilme" value="<?= $idfilme ?>"><br>
        <input type="hidden" name="idaluguel" value="<?= $idaluguel ?>"><br>

        <label>Nome Cliente: </label>
        <input type="text" name="nomeCliente" id="nome" value="<?= $nomeCliente ?>" readonly><br>

        <label>Nome Filme: </label>
        <?php criarCampoSelecaoFilmes($conexao, $idfilme); ?><br>

        <label>Data Aluguel: </label>
        <input type="text" name="data" id="data" value="<?= $dataalguel ?>" placeholder="YYYY-MM-DD HH:MM:SS"><br>

        <label>Data de Entrega: </label>
        <input type="text" name="data_de_entrega" id="dataEntrega" value="<?= $prazo_de_entrega ?>" placeholder="YYYY-MM-DD" onblur="formatarData(this)" required>


        <input type="submit" value="Editar" onclick="return editarComConfirmacao()">

    </form>
    <a href="lista_locacao.php" onclick="return confirmBack()">Voltar</a>
    <script src="funcoes.js"></script>
</body>

</html>