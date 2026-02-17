<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meus Processos - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0a0a0a;
    --bg-secondary: #121212;
    --card: #1a1a1a;
    --card-hover: #222222;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --accent-hover: #e5c04c;
    --muted: #bfb39a;
    --muted-dark: #8a8577;
    --border: rgba(255,255,255,0.08);
    --shadow: 0 4px 20px rgba(0,0,0,0.4);
    --success: #4ade80;
    --info: #60a5fa;
    --danger: #f87171;
}

* { box-sizing: border-box; font-family: 'Inter', sans-serif; }

body {
    margin:0;
    background: var(--bg);
    color: var(--primary);
    min-height:100vh;
}

/* Sidebar */
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
    background: linear-gradient(135deg, #b8860b, #f1c65b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0b0b0b;
    font-weight: 800;
    font-size: 18px;
}

.logo-text {
    font-size: 18px;
    font-weight: 700;
    color: var(--accent);
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
    color: var(--accent);
}

.nav-link.active {
    background: rgba(212, 175, 55, 0.1);
}

/* Main Content */
.main-content {
    margin-left: 260px;
    padding: 24px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

h1 { 
    color: var(--accent); 
    margin: 0;
    font-size: 28px;
}

.btn {
    background: var(--accent);
    color: #0b0b0b;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: filter 0.2s;
    display: inline-block;
}

.btn:hover { filter: brightness(0.95); }

.btn-small {
    padding: 8px 14px;
    font-size: 13px;
    margin-right: 8px;
}

.btn-view {
    background: #4a9eff;
}

.btn-edit {
    background: var(--accent);
}

.btn-delete {
    background: #ef4444;
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

.search-box input::placeholder {
    color: var(--muted);
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
    color: var(--accent);
    font-weight: 600;
    border-bottom: 1px solid var(--card-border);
    background: var(--bg-secondary);
}

td {
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

tbody tr:hover {
    background: rgba(212,175,55,0.08);
}

.status {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}

.status.aberto { 
    background: rgba(212,175,55,0.2); 
    color: var(--accent); 
}

.status.concluido { 
    background: rgba(74, 222, 128, 0.2); 
    color: #4ade80; 
}

.status.arquivado { 
    background: rgba(128,128,128,0.2); 
    color: var(--muted); 
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.alert-success {
    background: rgba(74, 222, 128, 0.15);
    color: #4ade80;
    border: 1px solid rgba(74, 222, 128, 0.3);
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    th, td { 
        padding: 10px 8px; 
        font-size: 13px; 
    }
}
</style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo-section">
        <div class="logo-container">
            <div class="logo">SJ</div>
            <span class="logo-text">Sistema Jurídico</span>
        </div>
    </div>
    
    <nav>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link">
                    <span>📊</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/processos" class="nav-link active">
                    <span>⚖️</span> Processos
                </a>
            </li>
            <li class="nav-item">
                <a href="/clientes" class="nav-link">
                    <span>👥</span> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a href="/compromissos" class="nav-link">
                    <span>📅</span> Compromissos
                </a>
            </li>
            <li class="nav-item">
                <a href="/prazos" class="nav-link">
                    <span>⏳</span> Prazos
                </a>
            </li>
            <li class="nav-item">
                <a href="/honorarios" class="nav-link">
                    <span>💼</span> Honorários
                </a>
            </li>
            <li class="nav-item">
                <a href="/documentos" class="nav-link">
                    <span>📄</span> Documentos
                </a>
            </li>
            <li class="nav-item">
                <a href="/configuracoes" class="nav-link">
                    <span>⚙️</span> Configurações
                </a>
            </li>
            <li class="nav-item">
                <a href="/logout" class="nav-link">
                    <span>🚪</span> Sair
                </a>
            </li>
        </ul>
    </nav>
</aside>

<div class="main-content">
    <div class="header">
        <h1>Meus Processos</h1>
        <a href="/processos/novo" class="btn">➕ Novo Processo</a>
    </div>

    <?php if(isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
        <div class="alert-success">
            ✓ Processo excluído com sucesso!
        </div>
    <?php endif; ?>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Buscar processo por cliente, número ou status...">
    </div>

    <div class="table-wrapper">
        <table id="processosTable">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Nº Processo</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($processos)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">⚖️</div>
                                <p>Nenhum processo cadastrado ainda.</p>
                                <p><a href="/processos/novo" class="btn" style="margin-top: 16px;">Cadastrar primeiro processo</a></p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($processos as $p): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($p['cliente_vinculado_nome'] ?: $p['cliente_nome']) ?></strong>
                                <?php if($p['cliente_id'] && $p['cpf_cnpj']): ?>
                                    <br><small style="color: var(--muted);">
                                        <?= htmlspecialchars($p['cpf_cnpj']) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td><?= $p['numero_processo'] ? htmlspecialchars($p['numero_processo']) : '-' ?></td>
                            <td>
                                <span class="status <?= $p['status'] ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($p['criado_em'])) ?></td>
                            <td>
                                <a href="/processos/<?= $p['id'] ?>" class="btn btn-small btn-view">👁️ Ver</a>
                                <a href="/processos/edit/<?= $p['id'] ?>" class="btn btn-small btn-edit">✏️ Editar</a>                                
                                <a href="/processos/confirm-delete/<?= $p['id'] ?>" class="btn btn-small btn-delete">
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
    const tableRows = document.querySelectorAll('#processosTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>

</body>
</html>