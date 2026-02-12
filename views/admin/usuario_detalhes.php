<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Detalhes do Usuário - Admin</title>
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
    --success: #4ade80;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

h1 {
    color: var(--danger);
    margin: 0;
    font-size: 28px;
}

.btn-group {
    display: flex;
    gap: 12px;
}

.btn {
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.btn-back {
    background: #333;
    color: var(--primary);
}

.btn-toggle {
    background: var(--success);
    color: white;
}

.btn-delete {
    background: var(--danger);
    color: white;
}

.btn:hover {
    filter: brightness(0.9);
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
}

.card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.card.full-width {
    grid-column: 1 / -1;
}

.card h2 {
    color: var(--danger);
    font-size: 18px;
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-label {
    font-size: 12px;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 16px;
    color: var(--primary);
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-ativo {
    background: rgba(74, 222, 128, 0.2);
    color: var(--success);
}

.status-inativo {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.stat-box {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid var(--border);
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--danger);
    margin-bottom: 8px;
}

.stat-label {
    font-size: 13px;
    color: var(--muted);
}

.logs-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 400px;
    overflow-y: auto;
}

.log-item {
    background: var(--bg-secondary);
    padding: 14px;
    border-radius: 8px;
    border-left: 3px solid var(--danger);
}

.log-acao {
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 4px;
    font-size: 14px;
}

.log-detalhes {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 4px;
}

.log-time {
    font-size: 12px;
    color: var(--muted);
}

@media (max-width: 968px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .content-grid, .info-grid, .stats-grid {
        grid-template-columns: 1fr;
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
    <div class="header">
        <h1>👤 Detalhes do Usuário</h1>
        <div class="btn-group">
            <a href="/admin/usuarios" class="btn btn-back">← Voltar</a>
            <a href="/admin/usuarios/toggle/<?= $usuario['id'] ?>" 
               class="btn btn-toggle"
               onclick="return confirm('Deseja <?= $usuario['ativo'] ? 'desativar' : 'ativar' ?> este usuário?')">
                <?= $usuario['ativo'] ? '🚫 Desativar' : '✅ Ativar' ?>
            </a>
            <a href="/admin/usuarios/confirm-delete/<?= $usuario['id'] ?>" class="btn btn-delete">
                🗑️ Excluir
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Informações do Usuário -->
        <div class="card">
            <h2>📋 Informações Pessoais</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nome Completo</span>
                    <span class="info-value"><?= htmlspecialchars($usuario['nome']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="status-badge status-<?= $usuario['ativo'] ? 'ativo' : 'inativo' ?>">
                        <?= $usuario['ativo'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($usuario['email']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">OAB</span>
                    <span class="info-value">
                        <?= $usuario['oab'] ? htmlspecialchars($usuario['oab']) . '/' . $usuario['uf_sigla'] : '-' ?>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Perfil</span>
                    <span class="info-value"><?= htmlspecialchars($usuario['perfil_nome']) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Cadastrado em</span>
                    <span class="info-value"><?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?></span>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="card">
            <h2>📊 Estatísticas de Uso</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value"><?= $stats['clientes'] ?></div>
                    <div class="stat-label">Clientes</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value"><?= $stats['processos'] ?></div>
                    <div class="stat-label">Processos</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value"><?= $stats['compromissos'] ?></div>
                    <div class="stat-label">Compromissos</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value"><?= $stats['documentos'] ?></div>
                    <div class="stat-label">Documentos</div>
                </div>

                <div class="stat-box" style="grid-column: 1 / -1;">
                    <div class="stat-value"><?= number_format($stats['espaco_usado'] / 1024 / 1024, 2) ?> MB</div>
                    <div class="stat-label">Espaço Usado</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="card full-width">
        <h2>📋 Atividades Recentes (Últimas 20)</h2>
        <div class="logs-list">
            <?php if(empty($logs)): ?>
                <p style="text-align: center; color: var(--muted); padding: 20px;">
                    Nenhuma atividade registrada para este usuário.
                </p>
            <?php else: ?>
                <?php foreach($logs as $log): ?>
                    <div class="log-item">
                        <div class="log-acao"><?= htmlspecialchars($log['acao']) ?></div>
                        <?php if($log['detalhes']): ?>
                            <div class="log-detalhes"><?= htmlspecialchars($log['detalhes']) ?></div>
                        <?php endif; ?>
                        <div class="log-time">
                            <?= date('d/m/Y H:i:s', strtotime($log['criado_em'])) ?>
                            <?php if($log['ip_address']): ?>
                                | IP: <?= htmlspecialchars($log['ip_address']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>