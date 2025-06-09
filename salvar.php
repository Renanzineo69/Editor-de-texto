<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe o conteúdo enviado via Ajax (esperado em $_POST['conteudo'])
    $conteudo = $_POST['conteudo'] ?? '';

    // Grava o conteúdo no arquivo salvo.html (sobrescreve)
    $arquivo = 'salvo.html';
    if (file_put_contents($arquivo, $conteudo) !== false) {
        echo json_encode(['status' => 'sucesso']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao salvar arquivo']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Método não permitido']);
}
?>
