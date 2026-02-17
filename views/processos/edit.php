<?php  ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Processo - Sistema Jurídico</title>
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
    margin-bottom: 15px;
}

.form-group:last-child {
    margin-bottom: 0;
}

label {
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 6px;
    font-size: 13px;
}

input, textarea, select {
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

input::placeholder, textarea::placeholder {
    color: rgba(255,255,255,0.35);
}

input:focus, textarea:focus, select:focus {
    border-color: var(--accent);
    box-shadow: 0 4px 15px rgba(212,175,55,0.2);
}

select option {
    background: var(--card);
    color: var(--primary);
}

textarea {
    resize: vertical;
    min-height: 100px;
}

.help-text {
    font-size: 12px;
    color: var(--muted);
    margin-top: 4px;
}

.option-divider {
    display: flex;
    align-items: center;
    margin: 15px 0;
    gap: 10px;
}

.option-divider::before,
.option-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.option-divider span {
    color: var(--muted);
    font-size: 13px;
    font-weight: 600;
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
        <h1>✏️ Editar Processo</h1>
        <form action="/processos/update/<?= $processo['id'] ?>" method="POST">
            <?= Csrf::field() ?>
            
            <!-- Cliente -->
            <div class="form-section">
                <h3>👤 Cliente</h3>
                
                <div class="form-group">
                    <label for="cliente_id">Selecionar cliente cadastrado</label>
                    <select id="cliente_id" name="cliente_id">
                        <option value="">-- Selecione um cliente --</option>
                        <?php foreach($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($processo['cliente_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nome']) ?>
                                <?= $c['cpf_cnpj'] ? ' - ' . htmlspecialchars($c['cpf_cnpj']) : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="help-text">Cliente atualmente vinculado: <?= $processo['cliente_vinculado_nome'] ? htmlspecialchars($processo['cliente_vinculado_nome']) : 'Nenhum' ?></span>
                </div>

                <div class="option-divider">
                    <span>OU</span>
                </div>

                <div class="form-group">
                    <label for="cliente_nome">Nome do cliente</label>
                    <input type="text" id="cliente_nome" name="cliente_nome" 
                           value="<?= htmlspecialchars($processo['cliente_nome']) ?>" 
                           placeholder="Nome do cliente">
                </div>
            </div>

            <!-- Dados do Processo -->
            <div class="form-section">
                <h3>📋 Informações do Processo</h3>
                
                <div class="form-group">
                    <label for="numero_processo">Número do Processo</label>
                    <input type="text" id="numero_processo" name="numero_processo" 
                           value="<?= htmlspecialchars($processo['numero_processo']) ?>" 
                           placeholder="Ex: 0000000-00.0000.0.00.0000">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao"><?= htmlspecialchars($processo['descricao']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="aberto" <?= $processo['status'] == 'aberto' ? 'selected' : '' ?>>Aberto</option>
                        <option value="concluido" <?= $processo['status'] == 'concluido' ? 'selected' : '' ?>>Concluído</option>
                        <option value="arquivado" <?= $processo['status'] == 'arquivado' ? 'selected' : '' ?>>Arquivado</option>
                    </select>
                </div>
            </div>

            <div class="buttons">
                <a href="/processos" class="btn">Cancelar</a>
                <button type="submit">💾 Atualizar Processo</button>
            </div>
        </form>
    </div>
</div>

<script>
// Lógica de alternância entre select e input
const clienteSelect = document.getElementById('cliente_id');
const clienteInput = document.getElementById('cliente_nome');

// Estado inicial
if (clienteSelect.value) {
    clienteInput.disabled = true;
    clienteInput.style.opacity = '0.5';
}

clienteSelect.addEventListener('change', function() {
    if (this.value) {
        clienteInput.value = '';
        clienteInput.disabled = true;
        clienteInput.style.opacity = '0.5';
    } else {
        clienteInput.disabled = false;
        clienteInput.style.opacity = '1';
    }
});

clienteInput.addEventListener('input', function() {
    if (this.value.trim()) {
        clienteSelect.value = '';
        clienteSelect.disabled = true;
        clienteSelect.style.opacity = '0.5';
    } else {
        clienteSelect.disabled = false;
        clienteSelect.style.opacity = '1';
    }
});

// Validação
document.querySelector('form').addEventListener('submit', function(e) {
    const clienteId = clienteSelect.value;
    const clienteNome = clienteInput.value.trim();
    
    if (!clienteId && !clienteNome) {
        e.preventDefault();
        alert('Por favor, selecione um cliente cadastrado ou digite o nome do cliente.');
        return false;
    }
});
</script>

</body>
</html>