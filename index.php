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

    <!-- BOTÕES DE ALINHAMENTO -->
    <button type="button" onclick="document.execCommand('justifyLeft', false, '');" title="Alinhar à esquerda" aria-label="Alinhar à esquerda">⇤</button>
    <button type="button" onclick="document.execCommand('justifyCenter', false, '');" title="Alinhar ao centro" aria-label="Alinhar ao centro">⇥</button>
    <button type="button" onclick="document.execCommand('justifyRight', false, '');" title="Alinhar à direita" aria-label="Alinhar à direita">⇥⇥</button>
    <button type="button" onclick="document.execCommand('justifyFull', false, '');" title="Justificar texto" aria-label="Justificar texto">≡</button>
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

<script src="editor.js"></script>

</body>
</html>
