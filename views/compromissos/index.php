<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meus Compromissos - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1a1a1a;
    --bg-secondary: #121212;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --muted: #bfb39a;
    --card-border: rgba(255,255,255,0.08);
    --shadow: 0 4px 20px rgba(0,0,0,0.6);
    --border: rgba(255,255,255,0.08);
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

.btn-edit {
    background: #4a9eff;
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

.compromissos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.compromisso-card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--card-border);
    box-shadow: var(--shadow);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
}

.compromisso-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent);
    border-radius: 12px 12px 0 0;
}

.compromisso-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
}

.compromisso-header {
    margin-bottom: 16px;
}

.compromisso-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 8px;
}

.compromisso-date {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--accent);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
}

.compromisso-time {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--muted);
    font-size: 13px;
}

.compromisso-body {
    margin: 16px 0;
    padding-top: 16px;
    border-top: 1px solid var(--card-border);
}

.compromisso-desc {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 12px;
}

.compromisso-location {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    font-size: 14px;
}

.compromisso-actions {
    display: flex;
    gap: 10px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--card-border);
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
    
    .compromissos-grid {
        grid-template-columns: 1fr;
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
                <a href="/processos" class="nav-link">
                    <span>⚖️</span> Processos
                </a>
            </li>
            <li class="nav-item">
                <a href="/clientes" class="nav-link">
                    <span>👥</span> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a href="/compromissos" class="nav-link active">
                    <span>📅</span> Compromissos
                </a>
            </li>
            <li class="nav-item">
                <a href="/prazos" class="nav-link">
                    <span>⏳</span> Prazos
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
        <h1>Meus Compromissos</h1>
        <a href="/compromissos/novo" class="btn">➕ Novo Compromisso</a>
    </div>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Buscar compromisso por título, descrição ou local...">
    </div>

    <div class="compromissos-grid" id="compromissosGrid">
        <?php if(empty($compromissos)): ?>
            <div class="empty-state" style="grid-column: 1/-1;">
                <div class="empty-icon">📅</div>
                <p>Nenhum compromisso cadastrado ainda.</p>
                <p><a href="/compromissos/novo" class="btn" style="margin-top: 16px;">Cadastrar primeiro compromisso</a></p>
            </div>
        <?php else: ?>
            <?php foreach($compromissos as $c): ?>
                <div class="compromisso-card">
                    <div class="compromisso-header">
                        <h3 class="compromisso-title"><?= htmlspecialchars($c['titulo']) ?></h3>
                        <div class="compromisso-date">
                            <span>📅</span>
                            <?= date('d/m/Y', strtotime($c['data_inicio'])) ?>
                        </div>
                        <div class="compromisso-time">
                            <span>🕐</span>
                            <?= date('H:i', strtotime($c['data_inicio'])) ?>
                            <?php if($c['data_fim']): ?>
                                - <?= date('H:i', strtotime($c['data_fim'])) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($c['descricao'] || $c['local']): ?>
                        <div class="compromisso-body">
                            <?php if($c['descricao']): ?>
                                <p class="compromisso-desc"><?= nl2br(htmlspecialchars($c['descricao'])) ?></p>
                            <?php endif; ?>
                            
                            <?php if($c['local']): ?>
                                <div class="compromisso-location">
                                    <span>📍</span>
                                    <?= htmlspecialchars($c['local']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="compromisso-actions">
                        <a href="/compromissos/edit/<?= $c['id'] ?>" class="btn btn-small btn-edit">✏️ Editar</a>
                        <form action="/compromissos/delete/<?= $c['id'] ?>" method="POST" style="display:inline;">
                            <?= Csrf::field() ?>
                            <button type="submit" class="btn btn-small btn-delete" onclick="return confirm('Tem certeza que deseja excluir este compromisso?')">🗑️ Excluir</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Sistema de busca em tempo real
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const cards = document.querySelectorAll('.compromisso-card');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>

</body>
</html>