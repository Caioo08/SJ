<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Logs de Auditoria - Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1a1a1a;
    --bg-secondary: #121212;
    --primary: #f6f4ef;
    --muted: #bfb39a;
    --border: rgba(255,255,255,0.08);
    --shadow: 0 4px 20px rgba(0,0,0,0.6);
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

.search-box {
    background: var(--card);
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid var(--border);
}

.search-box input, .search-box select {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--bg-secondary);
    color: var(--primary);
    font-size: 14px;
}

.logs-container {
    background: var(--card);
    border-radius: 8px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.logs-list {
    display: flex;
    flex-direction: column;
}

.log-item {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: grid;
    grid-template-columns: 150px 1fr 180px;
    gap: 20px;
    align-items: center;
    transition: background 0.2s;
}

.log-item:hover {
    background: var(--bg-secondary);
}

.log-item:last-child {
    border-bottom: none;
}

.log-time {
    color: var(--muted);
    font-size: 13px;
}

.log-time-date {
    font-weight: 600;
}

.log-content {
    flex: 1;
}

.log-acao {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 4px;
}

.log-usuario {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 4px;
}

.log-detalhes {
    font-size: 13px;
    color: var(--muted);
}

.log-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.log-ip {
    font-size: 12px;
    color: var(--muted);
    font-family: monospace;
}

.log-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-criar {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
}

.badge-editar {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
}

.badge-excluir {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.badge-ativar {
    background: rgba(74, 158, 255, 0.2);
    color: #4a9eff;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}

@media (max-width: 968px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .log-item {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .log-meta {
        align-items: flex-start;
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
                <a href="/admin/usuarios" class="nav-link">
                    <span>👥</span> Usuários
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/logs" class="nav-link active">
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
    <h1>📋 Logs de Auditoria</h1>

    <div class="search-box">
        <form method="GET" style="display:grid;grid-template-columns:2fr 1fr auto;gap:10px;align-items:center;">
            <input type="text" name="acao" id="searchInput" value="<?= htmlspecialchars($acaoFiltro ?? '') ?>" placeholder="🔍 Filtrar ação, usuário ou detalhes...">
            <select name="periodo">
                <option value="24h" <?= ($periodoFiltro ?? '7d') === '24h' ? 'selected' : '' ?>>Últimas 24h</option>
                <option value="7d" <?= ($periodoFiltro ?? '7d') === '7d' ? 'selected' : '' ?>>Últimos 7 dias</option>
                <option value="30d" <?= ($periodoFiltro ?? '7d') === '30d' ? 'selected' : '' ?>>Últimos 30 dias</option>
            </select>
            <button type="submit" style="padding:12px 16px;border:none;border-radius:6px;background:var(--danger);color:white;font-weight:600;cursor:pointer;">Aplicar</button>
        </form>
    </div>

    <div class="logs-container">
        <div class="logs-list" id="logsList">
            <?php if(empty($logs)): ?>
                <div class="empty-state">
                    <p>Nenhum log registrado ainda.</p>
                </div>
            <?php else: ?>
                <?php foreach($logs as $log): ?>
                    <div class="log-item">
                        <div class="log-time">
                            <div class="log-time-date"><?= date('d/m/Y', strtotime($log['criado_em'])) ?></div>
                            <div><?= date('H:i:s', strtotime($log['criado_em'])) ?></div>
                        </div>

                        <div class="log-content">
                            <div class="log-acao"><?= htmlspecialchars($log['acao']) ?></div>
                            <?php if($log['usuario_nome']): ?>
                                <div class="log-usuario">Por: <?= htmlspecialchars($log['usuario_nome']) ?></div>
                            <?php endif; ?>
                            <?php if($log['detalhes']): ?>
                                <div class="log-detalhes"><?= htmlspecialchars($log['detalhes']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="log-meta">
                            <?php
                            $badge_class = 'badge-editar';
                            if (stripos($log['acao'], 'exclu') !== false) {
                                $badge_class = 'badge-excluir';
                            } elseif (stripos($log['acao'], 'ativ') !== false) {
                                $badge_class = 'badge-ativar';
                            } elseif (stripos($log['acao'], 'cri') !== false) {
                                $badge_class = 'badge-criar';
                            }
                            ?>
                            <span class="log-badge <?= $badge_class ?>">
                                <?= $log['tabela'] ?: 'Sistema' ?>
                            </span>
                            <?php if($log['ip_address']): ?>
                                <div class="log-ip"><?= htmlspecialchars($log['ip_address']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Sistema de busca em tempo real
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const logItems = document.querySelectorAll('.log-item');
    
    logItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>

</body>
</html>