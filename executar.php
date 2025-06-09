<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = $_POST['conteudo'] ?? '';

    // Caminho do arquivo para salvar (pode mudar conforme sua necessidade)
    $arquivo = 'salvo.html';

    // Salvar conteúdo (escreve HTML do editor)
    if (file_put_contents($arquivo, $conteudo) !== false) {
        echo "Salvo com sucesso";
    } else {
        http_response_code(500);
        echo "Erro ao salvar o arquivo";
    }
} else {
    http_response_code(405);
    echo "Método não permitido";
}
