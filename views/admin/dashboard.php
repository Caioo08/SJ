<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel Administrativo - Sistema Jurídico</title>
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

.header {
    margin-bottom: 32px;
}

h1 {
    color: var(--danger);
    margin: 0 0 8px 0;
    font-size: 32px;
}

.subtitle {
    color: var(--muted);
    font-size: 14px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--muted);
    font-weight: 500;
}

.section {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--danger);
}

.logs-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.log-item {
    background: var(--bg-secondary);
    padding: 14px;
    border-radius: 8px;
    border-left: 3px solid var(--danger);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.log-info {
    flex: 1;
}

.log-acao {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 4px;
}

.log-detalhes {
    font-size: 13px;
    color: var(--muted);
}

.log-time {
    font-size: 12px;
    color: var(--muted);
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
                <a href="/admin" class="nav-link active">
                    <span>📊</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/admin/usuarios" class="nav-link">
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
    <div class="header">
        <h1>🛡️ Painel Administrativo</h1>
        <p class="subtitle">Visão geral do sistema e controle de acesso</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['usuarios_ativos'] ?></div>
                    <div class="stat-label">Usuários Ativos</div>
                </div>
                <div class="stat-icon">✅</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['usuarios_inativos'] ?></div>
                    <div class="stat-label">Usuários Inativos</div>
                </div>
                <div class="stat-icon">🚫</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['total_processos'] ?></div>
                    <div class="stat-label">Total de Processos</div>
                </div>
                <div class="stat-icon">⚖️</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['total_clientes'] ?></div>
                    <div class="stat-label">Total de Clientes</div>
                </div>
                <div class="stat-icon">👥</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['total_documentos'] ?></div>
                    <div class="stat-label">Documentos</div>
                </div>
                <div class="stat-icon">📄</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= number_format($stats['espaco_usado'] / 1024 / 1024, 2) ?> MB</div>
                    <div class="stat-label">Espaço Usado</div>
                </div>
                <div class="stat-icon">💾</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">📋 Atividades Recentes</h2>
            <a href="/admin/logs" style="color: var(--danger); text-decoration: none; font-size: 14px;">Ver todos →</a>
        </div>

        <div class="logs-list">
            <?php if(empty($logs_recentes)): ?>
                <p style="color: var(--muted); text-align: center; padding: 20px;">Nenhuma atividade registrada ainda.</p>
            <?php else: ?>
                <?php foreach($logs_recentes as $log): ?>
                    <div class="log-item">
                        <div class="log-info">
                            <div class="log-acao">
                                <?= htmlspecialchars($log['acao']) ?>
                                <?php if($log['usuario_nome']): ?>
                                    - por <?= htmlspecialchars($log['usuario_nome']) ?>
                                <?php endif; ?>
                            </div>
                            <?php if($log['detalhes']): ?>
                                <div class="log-detalhes"><?= htmlspecialchars($log['detalhes']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="log-time">
                            <?= date('d/m/Y H:i', strtotime($log['criado_em'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>