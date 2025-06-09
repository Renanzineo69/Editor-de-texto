<?php
// salvar.php - Recebe via POST o conteúdo do editor e salva em arquivo

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = $_POST['conteudo'] ?? '';

    if (trim($conteudo) === '') {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Conteúdo vazio']);
        exit;
    }

    // Aqui você pode modificar o caminho e nome do arquivo conforme necessário
    $arquivo = 'documento_salvo.html';

    // Salva o conteúdo em formato HTML
    $salvou = file_put_contents($arquivo, $conteudo);

    if ($salvou === false) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao salvar arquivo']);
    } else {
        echo json_encode(['status' => 'sucesso']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método inválido']);
}