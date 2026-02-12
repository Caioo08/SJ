<?php  ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Enviar Documento - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1c1c1c;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --accent-hover: #c49f2c;
    --muted: #bfb39a;
    --border: rgba(255,255,255,0.08);
    --shadow: 0 6px 25px rgba(0,0,0,0.6);
}

* { box-sizing: border-box; font-family: 'Inter', sans-serif; }

body {
    margin:0;
    background: var(--bg);
    color: var(--primary);
    min-height:100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 20px;
}

.container {
    width: 100%;
    max-width: 700px;
}

.card {
    background: var(--card);
    border-radius: 14px;
    box-shadow: var(--shadow);
    padding: 30px;
    overflow: hidden;
    border: 1px solid var(--border);
}

.card h1 {
    color: var(--accent);
    font-size: 28px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 10px;
}

form {
    display: grid;
    gap: 20px;
}

.form-section {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    background: rgba(255,255,255,0.02);
}

.form-section h3 {
    color: var(--accent);
    font-size: 16px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 6px;
    font-size: 13px;
}

input[type="file"] {
    padding: 12px 14px;
    border-radius: 8px;
    border: 2px dashed var(--border);
    background: #171717;
    color: var(--primary);
    font-size: 14px;
    outline: none;
    transition: 0.2s all;
    cursor: pointer;
}

input[type="file"]:hover {
    border-color: var(--accent);
}

select, textarea {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #171717;
    color: var(--primary);
    font-size: 14px;
    outline: none;
    transition: 0.2s all;
    width: 100%;
}

select option {
    background: var(--card);
    color: var(--primary);
}

textarea {
    resize: vertical;
    min-height: 100px;
}

textarea::placeholder {
    color: rgba(255,255,255,0.35);
}

select:focus, textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 4px 15px rgba(212,175,55,0.2);
}

.help-text {
    font-size: 12px;
    color: var(--muted);
    margin-top: 4px;
}

.file-info {
    background: rgba(212, 175, 55, 0.1);
    padding: 12px;
    border-radius: 8px;
    margin-top: 10px;
    display: none;
}

.file-info.show {
    display: block;
}

.file-details {
    font-size: 13px;
    color: var(--primary);
}

.buttons {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 10px;
}

button, a.btn {
    padding: 12px 22px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s all;
    min-width: 120px;
    text-align: center;
}

button[type="submit"] {
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    color: #0b0b0b;
    border: none;
}

button[type="submit"]:hover {
    filter: brightness(0.95);
}

button[type="submit"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

a.btn {
    background: #333333;
    color: var(--primary);
    border: 1px solid var(--border);
}

a.btn:hover {
    background: var(--accent-hover);
    color: #0b0b0b;
}

@media (max-width: 768px) {
    .buttons {
        flex-direction: column;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>📤 Enviar Documento</h1>
        <form action="/documentos/store" method="POST" enctype="multipart/form-data" id="uploadForm">
            <?= Csrf::field() ?>
            
            <!-- Upload do Arquivo -->
            <div class="form-section">
                <h3>📎 Selecionar Arquivo</h3>
                
                <div class="form-group">
                    <label for="arquivo">Escolha o arquivo *</label>
                    <input type="file" id="arquivo" name="arquivo" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls,.txt,.zip">
                    <span class="help-text">
                        Formatos aceitos: PDF, DOC, DOCX, JPG, PNG, XLSX, XLS, TXT, ZIP<br>
                        Tamanho máximo: 10MB
                    </span>
                </div>

                <div class="file-info" id="fileInfo">
                    <div class="file-details">
                        <strong>📄 Arquivo selecionado:</strong><br>
                        <span id="fileName"></span><br>
                        <span id="fileSize"></span>
                    </div>
                </div>
            </div>

            <!-- Categoria -->
            <div class="form-section">
                <h3>🏷️ Categoria</h3>
                
                <div class="form-group">
                    <label for="categoria">Tipo de documento *</label>
                    <select id="categoria" name="categoria" required>
                        <option value="processo">📋 Processo</option>
                        <option value="cliente">👥 Cliente</option>
                        <option value="contrato">📝 Contrato</option>
                        <option value="outros">📄 Outros</option>
                    </select>
                </div>
            </div>

            <!-- Descrição -->
            <div class="form-section">
                <h3>📝 Descrição</h3>
                

                <div class="form-group">
                    <label for="cliente_id">Cliente vinculado (opcional)</label>
                    <select id="cliente_id" name="cliente_id">
                        <option value="">Sem vínculo</option>
                        <?php foreach(($clientes ?? []) as $cli): ?>
                            <option value="<?= $cli['id'] ?>"><?= htmlspecialchars($cli['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" id="visivel_cliente" name="visivel_cliente" value="1" style="width:auto;">
                    <label for="visivel_cliente" style="margin:0;">Disponibilizar no portal do cliente</label>
                </div>
                <div class="form-group">
                    <label for="descricao">Observações sobre o documento</label>
                    <textarea id="descricao" name="descricao" placeholder="Ex: Contrato de prestação de serviços do cliente João Silva..."></textarea>
                    <span class="help-text">Opcional - Adicione informações úteis para identificar o documento</span>
                </div>
            </div>

            <div class="buttons">
                <a href="/documentos" class="btn">Cancelar</a>
                <button type="submit" id="submitBtn">📤 Enviar Documento</button>
            </div>
        </form>
    </div>
</div>

<script>
// Mostrar informações do arquivo selecionado
document.getElementById('arquivo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileInfo = document.getElementById('fileInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    if (file) {
        // Validar tamanho (10MB)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Arquivo muito grande! Tamanho máximo: 10MB');
            this.value = '';
            fileInfo.classList.remove('show');
            submitBtn.disabled = true;
            return;
        }
        
        // Mostrar informações
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = 'Tamanho: ' + (file.size / 1024).toFixed(2) + ' KB';
        fileInfo.classList.add('show');
        submitBtn.disabled = false;
    } else {
        fileInfo.classList.remove('show');
        submitBtn.disabled = true;
    }
});

// Prevenir submit sem arquivo
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const arquivo = document.getElementById('arquivo').files[0];
    if (!arquivo) {
        e.preventDefault();
        alert('Por favor, selecione um arquivo!');
        return false;
    }
    
    // Desabilitar botão para evitar duplo envio
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').textContent = '⏳ Enviando...';
});
</script>

</body>
</html>