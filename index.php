<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editor de texto</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

<h2>Editor de texto</h2>

<div class="toolbar" role="toolbar" aria-label="Barra de ferramentas de formatação">
    <button type="button" onclick="document.execCommand('bold', false, '');" title="Negrito (Ctrl+B)" aria-label="Negrito"><b>B</b></button>
    <button type="button" onclick="document.execCommand('italic', false, '');" title="Itálico (Ctrl+I)" aria-label="Itálico"><i>I</i></button>
    <button type="button" onclick="document.execCommand('underline', false, '');" title="Sublinhado (Ctrl+U)" aria-label="Sublinhado"><u>U</u></button>
    <button type="button" onclick="document.execCommand('strikeThrough', false, '');" title="Riscado" aria-label="Riscado"><s>S</s></button>
    <button type="button" onclick="document.execCommand('insertUnorderedList', false, '');" title="Lista com marcadores" aria-label="Lista com marcadores">• Lista</button>
    <button type="button" onclick="document.execCommand('insertOrderedList', false, '');" title="Lista numerada" aria-label="Lista numerada">1. Lista</button>
    <button type="button" onclick="document.execCommand('undo', false, '');" title="Desfazer (Ctrl+Z)" aria-label="Desfazer">↺ Desfazer</button>
    <button type="button" onclick="document.execCommand('redo', false, '');" title="Refazer (Ctrl+Y)" aria-label="Refazer">↻ Refazer</button>
</div>

<div class="editor-container">
    <div id="editor" contenteditable="true" spellcheck="true" role="textbox" aria-multiline="true" class="placeholder">
        Digite seu texto aqui...
    </div>

    <div class="actions-container">
        <div class="actions">
            <button id="btnSalvar">Salvar</button>

            <div class="export-dropdown">
                <button id="btnExportar">Exportar como ▼</button>
                <div class="export-options">
                    <button data-type="txt">.txt</button>
                    <button data-type="docx">.docx</button>
                    <button data-type="pdf">.pdf</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    const editor = document.getElementById('editor');
    const placeholderText = "Digite seu texto aqui...";
    const toast = document.getElementById('toast');

    // Placeholder logic
    editor.addEventListener('focus', () => {
        if (editor.classList.contains('placeholder')) {
            editor.innerHTML = "";
            editor.classList.remove('placeholder');
        }
    });

    editor.addEventListener('blur', () => {
        if (editor.textContent.trim() === "") {
            editor.innerHTML = placeholderText;
            editor.classList.add('placeholder');
        }
    });

    window.addEventListener('load', () => {
        if (editor.textContent.trim() === "") {
            editor.innerHTML = placeholderText;
            editor.classList.add('placeholder');
        }
    });

    // Atalhos de teclado para formatação
    editor.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key.toLowerCase()) {
                case 'b': e.preventDefault(); document.execCommand('bold'); break;
                case 'i': e.preventDefault(); document.execCommand('italic'); break;
                case 'u': e.preventDefault(); document.execCommand('underline'); break;
            }
        }
    });

    // Função para mostrar toast
    function showToast(msg) {
        toast.textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // Botão Salvar - salva no servidor via AJAX (fetch)
    document.getElementById('btnSalvar').addEventListener('click', () => {
        if (editor.classList.contains('placeholder')) {
            showToast('Nada para salvar');
            return;
        }

        const conteudoParaSalvar = editor.innerHTML;

        fetch('salvar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'conteudo=' + encodeURIComponent(conteudoParaSalvar)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                showToast('Arquivo salvo com sucesso!');
            } else {
                showToast('Erro ao salvar arquivo: ' + (data.mensagem || ''));
            }
        })
        .catch(() => {
            showToast('Erro ao salvar arquivo: falha na requisição');
        });
    });

    // Exportar arquivos
    document.querySelectorAll('.export-options button').forEach(btn => {
        btn.addEventListener('click', () => {
            const tipo = btn.getAttribute('data-type');
            exportar(tipo);
        });
    });

    // Função para exportar .txt, .docx, .pdf
    function exportar(tipo) {
        if (editor.classList.contains('placeholder')) {
            showToast("Conteúdo vazio para exportar");
            return;
        }
        const conteudo = editor.innerHTML;

        if (tipo === 'txt') {
            const text = editor.textContent || "";
            const blob = new Blob([text], { type: "text/plain;charset=utf-8" });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = "documento.txt";
            a.click();
            URL.revokeObjectURL(a.href);
            showToast("Arquivo .txt exportado");
        }
        else if (tipo === 'docx') {
            const preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                "xmlns='http://www.w3.org/TR/REC-html40'>" +
                "<head><meta charset='utf-8'><title>Documento</title></head><body>";
            const postHtml = "</body></html>";
            const html = preHtml + conteudo + postHtml;

            const blob = new Blob(['\ufeff', html], {
                type: 'application/msword'
            });

            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'documento.doc';
            a.click();
            URL.revokeObjectURL(url);
            showToast("Arquivo .docx exportado");
        }
        else if (tipo === 'pdf') {
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                const text = editor.textContent || "";

                // Quebra texto em linhas para o PDF (ajuste largura conforme necessário)
                const lines = doc.splitTextToSize(text, 180);

                doc.text(lines, 10, 10);
                doc.save("documento.pdf");
                showToast("Arquivo .pdf exportado");
            } catch(e) {
                showToast("Erro ao gerar PDF");
                console.error(e);
            }
        }
    }

    // Dropdown exportar toggle
    const btnExportar = document.getElementById('btnExportar');
    const exportOptions = document.querySelector('.export-options');

    btnExportar.addEventListener('click', () => {
        exportOptions.classList.toggle('show');
    });

    // Fecha dropdown ao clicar fora
    document.addEventListener('click', e => {
        if (!btnExportar.contains(e.target) && !exportOptions.contains(e.target)) {
            exportOptions.classList.remove('show');
        }
    });

</script>

</body>
</html>