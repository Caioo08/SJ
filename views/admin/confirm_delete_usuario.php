<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Confirmar Exclusão - Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1c1c1c;
    --primary: #f6f4ef;
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
    max-width: 600px;
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
    padding: 20px;
    margin-bottom: 20px;
}

.info-item {
    margin-bottom: 12px;
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

.warning-box {
    background: rgba(239, 68, 68, 0.15);
    border: 2px solid rgba(239, 68, 68, 0.4);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.warning-box p {
    color: var(--primary);
    font-size: 14px;
    margin: 0 0 12px 0;
    line-height: 1.6;
}

.warning-box ul {
    margin: 0;
    padding-left: 20px;
    color: var(--muted);
}

.warning-box li {
    margin-bottom: 6px;
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
        
        <h1>Confirmar Exclusão de Usuário</h1>
        <p class="subtitle">Esta ação é permanente e irreversível</p>

        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Nome</span>
                <span class="info-value"><?= htmlspecialchars($usuario['nome']) ?></span>
            </div>

            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value"><?= htmlspecialchars($usuario['email']) ?></span>
            </div>

            <?php if($usuario['oab']): ?>
            <div class="info-item">
                <span class="info-label">OAB</span>
                <span class="info-value"><?= htmlspecialchars($usuario['oab']) ?>/<?= htmlspecialchars($usuario['uf_sigla']) ?></span>
            </div>
            <?php endif; ?>

            <div class="info-item">
                <span class="info-label">Cadastrado em</span>
                <span class="info-value"><?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?></span>
            </div>
        </div>

        <div class="warning-box">
            <p><strong>⚠️ ATENÇÃO:</strong> Ao excluir este usuário, os seguintes dados serão permanentemente removidos:</p>
            <ul>
                <li><strong><?= $total_clientes ?></strong> clientes cadastrados</li>
                <li><strong><?= $total_processos ?></strong> processos jurídicos</li>
                <li>Todos os compromissos agendados</li>
                <li>Todos os documentos armazenados</li>
                <li>Configurações e preferências</li>
            </ul>
            <p style="margin-top: 16px;"><strong>Esta ação NÃO pode ser desfeita!</strong></p>
        </div>

        <form action="/admin/usuarios/delete/<?= $usuario['id'] ?>" method="POST">
            <?= Csrf::field() ?>
            <div class="form-group">
                <label for="senha">Digite sua senha de administrador para confirmar:</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required autofocus>
            </div>

            <div class="buttons">
                <a href="/admin/usuarios" class="btn btn-cancel">Cancelar</a>
                <button type="submit" class="btn-danger">🗑️ Confirmar Exclusão Permanente</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    if (senha.length < 3) {
        e.preventDefault();
        alert('Digite sua senha para confirmar.');
        return false;
    }
    
    if (!confirm('Tem ABSOLUTA CERTEZA que deseja excluir este usuário e todos os seus dados? Esta ação é IRREVERSÍVEL!')) {
        e.preventDefault();
        return false;
    }
});
</script>

</body>
</html>