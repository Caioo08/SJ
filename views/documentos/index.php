<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Meus Documentos - Sistema Jurídico</title>
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

.btn-download {
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

.filter-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--muted);
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.filter-tab:hover, .filter-tab.active {
    background: rgba(212, 175, 55, 0.1);
    color: var(--accent);
    border-color: var(--accent);
}

.documentos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.documento-card {
    background: var(--card);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--card-border);
    box-shadow: var(--shadow);
    transition: transform 0.2s, box-shadow 0.2s;
}

.documento-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
}

.documento-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}

.documento-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.documento-info {
    flex: 1;
    min-width: 0;
}

.documento-nome {
    font-size: 15px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 4px;
    word-break: break-word;
}

.documento-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 12px;
    color: var(--muted);
}

.categoria-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.categoria-processo { background: rgba(212, 175, 55, 0.2); color: var(--accent); }
.categoria-cliente { background: rgba(74, 158, 255, 0.2); color: #4a9eff; }
.categoria-contrato { background: rgba(139, 92, 246, 0.2); color: #8b5cf6; }
.categoria-outros { background: rgba(128, 128, 128, 0.2); color: var(--muted); }

.documento-descricao {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.5;
    margin: 12px 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.documento-footer {
    display: flex;
    gap: 8px;
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
    
    .documentos-grid {
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
                <a href="/clientes" class="nav-link">
                    <span>👥</span> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a href="/processos" class="nav-link">
                    <span>⚖️</span> Processos
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
                <a href="/documentos" class="nav-link active">
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
        <h1>Meus Documentos</h1>
        <a href="/documentos/novo" class="btn">➕ Novo Documento</a>
    </div>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Buscar documento por nome ou descrição...">
    </div>

    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="todos">Todos</button>
        <button class="filter-tab" data-filter="processo">Processos</button>
        <button class="filter-tab" data-filter="cliente">Clientes</button>
        <button class="filter-tab" data-filter="contrato">Contratos</button>
        <button class="filter-tab" data-filter="outros">Outros</button>
    </div>

    <div class="documentos-grid" id="documentosGrid">
        <?php if(empty($documentos)): ?>
            <div class="empty-state" style="grid-column: 1/-1;">
                <div class="empty-icon">📄</div>
                <p>Nenhum documento cadastrado ainda.</p>
                <p><a href="/documentos/novo" class="btn" style="margin-top: 16px;">Enviar primeiro documento</a></p>
            </div>
        <?php else: ?>
            <?php foreach($documentos as $d): ?>
                <div class="documento-card" data-categoria="<?= $d['categoria'] ?>">
                    <div class="documento-header">
                        <div class="documento-icon">
                            <?php
                            $icones = [
                                'pdf' => '📕',
                                'doc' => '📘',
                                'docx' => '📘',
                                'xls' => '📗',
                                'xlsx' => '📗',
                                'jpg' => '🖼️',
                                'jpeg' => '🖼️',
                                'png' => '🖼️',
                                'zip' => '📦'
                            ];
                            $ext = strtolower(pathinfo($d['nome_original'], PATHINFO_EXTENSION));
                            echo $icones[$ext] ?? '📄';
                            ?>
                        </div>
                        <div class="documento-info">
                            <div class="documento-nome"><?= htmlspecialchars($d['nome_original']) ?></div>
                            <div class="documento-meta">
                                <span class="categoria-badge categoria-<?= $d['categoria'] ?>">
                                    <?= ucfirst($d['categoria']) ?>
                                </span>
                                <span><?= number_format($d['tamanho'] / 1024, 1) ?> KB</span>
                                <span><?= date('d/m/Y', strtotime($d['criado_em'])) ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($d['cliente_nome'])): ?>
                        <div class="documento-descricao">👤 Cliente: <?= htmlspecialchars($d['cliente_nome']) ?></div>
                    <?php endif; ?>
                    <div class="documento-descricao">Portal cliente: <?= !empty($d['visivel_cliente']) ? '✅ Visível' : '🚫 Privado' ?></div>

                    <?php if($d['descricao']): ?>
                        <div class="documento-descricao">
                            <?= nl2br(htmlspecialchars($d['descricao'])) ?>
                        </div>
                    <?php endif; ?>

                    <div class="documento-footer">
                        <a href="/documentos/download/<?= $d['id'] ?>" class="btn btn-small btn-download">
                            ⬇️ Baixar
                        </a>
                        <form action="/documentos/delete/<?= $d['id'] ?>" method="POST" style="display:inline;">
                            <?= Csrf::field() ?>
                            <button type="submit" class="btn btn-small btn-delete" onclick="return confirm('Tem certeza que deseja excluir este documento?')">
                                🗑️ Excluir
                            </button>
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
    const cards = document.querySelectorAll('.documento-card');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// Sistema de filtros por categoria
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Atualizar aba ativa
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const cards = document.querySelectorAll('.documento-card');
        
        cards.forEach(card => {
            if (filter === 'todos' || card.dataset.categoria === filter) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

</body>
</html>