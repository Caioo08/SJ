<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerenciar Usuários - Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1a1a1a;
    --bg-secondary: #121212;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --muted: #bfb39a;
    --border: rgba(255,255,255,0.08);
    --shadow: 0 4px 20px rgba(0,0,0,0.6);
    --success: #4ade80;
    --danger: #ef4444;
}

* { box-sizing: border-box; font-family: 'Inter', sans-serif; }

body {
    margin:0;
    background: var(--bg);
    color: var(--primary);
    min-height:100vh;
}

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100vh;
    background: var(--card);
    border-right: 1px solid var(--border);
    padding: 24px 0;
    overflow-y: auto;
    z-index: 100;
}

.logo-section {
    padding: 0 24px 24px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 24px;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 18px;
}

.logo-text {
    font-size: 16px;
    font-weight: 700;
    color: var(--danger);
}

.admin-badge {
    background: var(--danger);
    color: white;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 700;
    margin-left: auto;
}

.nav-menu {
    list-style: none;
    padding: 0 12px;
}

.nav-item {
    margin-bottom: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: var(--muted);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 500;
}

.nav-link:hover, .nav-link.active {
    background: var(--bg-secondary);
    color: var(--danger);
}

.nav-link.active {
    background: rgba(239, 68, 68, 0.1);
}

.main-content {
    margin-left: 260px;
    padding: 24px;
}

h1 {
    color: var(--danger);
    margin: 0 0 24px 0;
    font-size: 28px;
}

.alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
}

.alert-success {
    background: rgba(74, 222, 128, 0.15);
    color: var(--success);
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.search-box {
    background: var(--card);
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid var(--border);
}

.search-box input {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--bg-secondary);
    color: var(--primary);
    font-size: 14px;
}

.table-wrapper {
    overflow-x: auto;
    background: var(--card);
    border-radius: 8px;
    box-shadow: var(--shadow);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 14px 12px;
    text-align: left;
}

th {
    color: var(--danger);
    font-weight: 600;
    border-bottom: 1px solid var(--border);
    background: var(--bg-secondary);
}

td {
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

tbody tr:hover {
    background: rgba(239, 68, 68, 0.08);
}

.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-ativo {
    background: rgba(74, 222, 128, 0.2);
    color: var(--success);
}

.status-inativo {
    background: rgba(128, 128, 128, 0.2);
    color: var(--muted);
}

.btn {
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-block;
    font-size: 13px;
    margin-right: 8px;
    cursor: pointer;
    border: none;
}

.btn-view {
    background: #4a9eff;
    color: white;
}

.btn-toggle {
    background: var(--accent);
    color: #0b0b0b;
}

.btn-delete {
    background: var(--danger);
    color: white;
}

.btn:hover {
    filter: brightness(0.95);
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .main-content {
        margin-left: 0;
    }
}
</style>
</head>
<body>

<aside class="sidebar">
    <div class="logo-section">
        <div class="logo-container">
            <div class="logo">🛡️</div>
            <div>
                <span class="logo-text">Admin Panel</span>
                <span class="admin-badge">ADMIN</span>
            </div>
        </div>
    </div>
    
    <nav>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="/admin" class="nav-link">
                    <span>📊</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/usuarios" class="nav-link active">
                    <span>👥</span> Usuários
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/logs" class="nav-link">
                    <span>📋</span> Logs de Auditoria
                </a>
            </li>
            <li class="nav-item" style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                <a href="/logout" class="nav-link">
                    <span>🚪</span> Sair
                </a>
            </li>
        </ul>
    </nav>
</aside>

<div class="main-content">
    <h1>👥 Gerenciar Usuários</h1>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?php if($_GET['msg'] == 'ativado'): ?>
                ✓ Usuário ativado com sucesso!
            <?php elseif($_GET['msg'] == 'desativado'): ?>
                ✓ Usuário desativado com sucesso!
            <?php elseif($_GET['msg'] == 'excluido'): ?>
                ✓ Usuário excluído com sucesso!
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Buscar usuário por nome, email ou OAB...">
    </div>

    <div class="table-wrapper">
        <table id="usuariosTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>OAB</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($usuarios)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--muted);">
                            Nenhum usuário cadastrado ainda.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($usuarios as $u): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($u['nome']) ?></strong></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= $u['oab'] ? htmlspecialchars($u['oab']) . '/' . $u['uf_sigla'] : '-' ?></td>
                            <td>
                                <span class="status-badge status-<?= $u['ativo'] ? 'ativo' : 'inativo' ?>">
                                    <?= $u['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($u['criado_em'])) ?></td>
                            <td>
                                <a href="/admin/usuarios/<?= $u['id'] ?>" class="btn btn-view">👁️ Ver</a>
                                <form action="/admin/usuarios/toggle/<?= $u['id'] ?>" method="POST" style="display:inline;">
                                    <?= Csrf::field() ?>
                                    <button type="submit" class="btn btn-toggle" onclick="return confirm('Tem certeza que deseja <?= $u['ativo'] ? 'desativar' : 'ativar' ?> este usuário?')">
                                        <?= $u['ativo'] ? '🚫 Desativar' : '✅ Ativar' ?>
                                    </button>
                                </form>
                                <a href="/admin/usuarios/confirm-delete/<?= $u['id'] ?>" 
                                   class="btn btn-delete">
                                    🗑️ Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Sistema de busca em tempo real
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#usuariosTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>

</body>
</html>