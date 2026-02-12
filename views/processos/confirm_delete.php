<?php  ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Confirmar Exclusão - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1c1c1c;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --danger: #ef4444;
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
    align-items: center;
    padding: 40px 20px;
}

.container {
    width: 100%;
    max-width: 500px;
}

.card {
    background: var(--card);
    border-radius: 14px;
    box-shadow: var(--shadow);
    padding: 30px;
    border: 1px solid var(--border);
}

.alert-icon {
    font-size: 64px;
    text-align: center;
    margin-bottom: 20px;
}

h1 {
    color: var(--danger);
    font-size: 24px;
    margin: 0 0 10px 0;
    text-align: center;
}

.subtitle {
    color: var(--muted);
    font-size: 14px;
    text-align: center;
    margin-bottom: 24px;
}

.info-box {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 20px;
}

.info-item {
    margin-bottom: 8px;
    font-size: 14px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    color: var(--muted);
    font-weight: 600;
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.info-value {
    color: var(--primary);
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 4px;
}

.status-aberto {
    background: rgba(212, 175, 55, 0.2);
    color: var(--accent);
}

.status-concluido {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
}

.status-arquivado {
    background: rgba(128, 128, 128, 0.2);
    color: var(--muted);
}

.warning-box {
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 20px;
}

.warning-box p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
    line-height: 1.6;
}

.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 8px;
    display: block;
    font-size: 14px;
}

input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #171717;
    color: var(--primary);
    font-size: 14px;
    outline: none;
    transition: 0.2s all;
}

input[type="password"]:focus {
    border-color: var(--danger);
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
}

.buttons {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

button, a.btn {
    flex: 1;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s all;
    text-align: center;
    border: none;
    font-size: 14px;
}

.btn-cancel {
    background: #333333;
    color: var(--primary);
}

.btn-cancel:hover {
    background: #444444;
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    filter: brightness(0.9);
}

@media (max-width: 768px) {
    .buttons {
        flex-direction: column-reverse;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="alert-icon">⚠️</div>
        
        <h1>Confirmar Exclusão de Processo</h1>
        <p class="subtitle">Esta ação não pode ser desfeita</p>

        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Cliente</span>
                <span class="info-value"><?= htmlspecialchars($processo['cliente_vinculado_nome'] ?: $processo['cliente_nome']) ?></span>
            </div>

            <?php if($processo['numero_processo']): ?>
            <div class="info-item">
                <span class="info-label">Número do Processo</span>
                <span class="info-value"><?= htmlspecialchars($processo['numero_processo']) ?></span>
            </div>
            <?php endif; ?>

            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="status-badge status-<?= $processo['status'] ?>">
                    <?= ucfirst($processo['status']) ?>
                </span>
            </div>

            <?php if($processo['descricao']): ?>
            <div class="info-item" style="margin-top: 12px;">
                <span class="info-label">Descrição</span>
                <span class="info-value" style="display: block; margin-top: 4px;">
                    <?= nl2br(htmlspecialchars(mb_substr($processo['descricao'], 0, 150))) ?>
                    <?= strlen($processo['descricao']) > 150 ? '...' : '' ?>
                </span>
            </div>
            <?php endif; ?>
        </div>

        <div class="warning-box">
            <p>
                ⚠️ <strong>Atenção:</strong> Ao excluir este processo, todos os dados relacionados serão 
                permanentemente removidos do sistema. Esta ação é irreversível.
            </p>
        </div>

        <form action="/processos/delete/<?= $processo['id'] ?>" method="POST">
            <div class="form-group">
                <label for="senha">Digite sua senha para confirmar a exclusão:</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required autofocus>
            </div>

            <div class="buttons">
                <a href="/processos/<?= $processo['id'] ?>" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn-danger">🗑️ Confirmar Exclusão</button>
            </div>
        </form>
    </div>
</div>

<script>
// Prevenir envio acidental
document.querySelector('form').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    if (senha.length < 3) {
        e.preventDefault();
        alert('Digite sua senha para confirmar.');
        return false;
    }
});
</script>

</body>
</html>