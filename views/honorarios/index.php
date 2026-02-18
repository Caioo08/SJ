<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Honorários e Contratos - Sistema Jurídico</title>
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

* { box-sizing: border-box; margin: 0; padding: 0; }

body{
    font-family: 'Inter', sans-serif;
    margin:0;
    background:var(--bg);
    color:var(--primary)
}

.main{
    max-width:1100px;
    margin:24px auto;
    padding:0 16px
}

.card{
    background:var(--card);
    border:1px solid var(--bd);
    border-radius:12px;
    padding:16px;
    margin-top:12px
}

.btn{
    background:var(--accent);
    color:#0b0b0b;
    padding:10px 14px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    font-weight:700;cursor:pointer
}

.btn-outline{
    background:#222;
    color:var(--txt);
    border:1px solid var(--bd)
}

.badge{
    padding:4px 8px;
    border-radius:999px;
    font-size:12px;
    font-weight:700
}

.pendente{
    background:#78350f;
    color:#fcd34d
}

.parcial{
    background:#1f2937;
    color:#cbd5e1
}

.pago{
    background:#14532d;
    color:#bbf7d0
}

.cancelado{
    background:#7f1d1d;
    color:#fecaca
}

.meta{
    color:var(--mut);
    font-size:13px
}
    
.actions{
        display:flex;
        gap:8px;
        align-items:center;
        flex-wrap:wrap
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
    min-height: 100vh;
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
<main class="main-content">
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
                    <a href="/honorarios" class="nav-link active">
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


        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
            <h1 style="margin:0;color:var(--acc);">💼 Honorários e Contratos</h1>
            <div style="display:flex;gap:8px;">
                <a class="btn" href="/honorarios/novo">+ Novo contrato</a>
            </div>
        </div>

        <form method="GET" class="card" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <label for="status" class="meta">Status:</label>
            <select id="status" name="status" style="padding:8px;border-radius:8px;border:1px solid var(--bd);background:#111;color:var(--txt);">
                <option value="">Todos</option>
                <option value="pendente" <?= $statusFiltro === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="parcial" <?= $statusFiltro === 'parcial' ? 'selected' : '' ?>>Parcial</option>
                <option value="pago" <?= $statusFiltro === 'pago' ? 'selected' : '' ?>>Pago</option>
                <option value="cancelado" <?= $statusFiltro === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
            <button class="btn" type="submit">Filtrar</button>
        </form>

        <?php if (empty($contratos)): ?>
            <div class="card">Nenhum contrato encontrado.</div>
        <?php else: ?>
            <?php foreach ($contratos as $c): ?>
                <div class="card">
                    <div style="display:flex;justify-content:space-between;gap:10px;align-items:flex-start;flex-wrap:wrap;">
                        <div>
                            <strong><?= htmlspecialchars($c['descricao']) ?></strong>
                            <div class="meta">Cliente: <?= htmlspecialchars($c['cliente_nome'] ?? '-') ?><?php if (!empty($c['numero_processo'])): ?> · Processo: <?= htmlspecialchars($c['numero_processo']) ?><?php endif; ?></div>
                            <div class="meta">Tipo: <?= htmlspecialchars($c['tipo_honorario']) ?> · Valor: R$ <?= number_format((float) $c['valor'], 2, ',', '.') ?> · Criado em <?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></div>
                            <?php if (!empty($c['observacoes'])): ?><div class="meta">Obs: <?= nl2br(htmlspecialchars($c['observacoes'])) ?></div><?php endif; ?>
                        </div>
                        <div class="actions">
                            <span class="badge <?= htmlspecialchars($c['status_pagamento']) ?>"><?= strtoupper(htmlspecialchars($c['status_pagamento'])) ?></span>
                            <form method="POST" action="/honorarios/toggle/<?= (int) $c['id'] ?>">
                                <?= Csrf::field() ?>
                                <button type="submit" class="btn btn-outline"><?= $c['status_pagamento'] === 'pago' ? 'Marcar pendente' : 'Marcar pago' ?></button>
                            </form>
                            <form method="POST" action="/honorarios/delete/<?= (int) $c['id'] ?>" onsubmit="return confirm('Excluir este contrato?');">
                                <?= Csrf::field() ?>
                                <button type="submit" class="btn btn-outline">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
</main>
</body>
</html>
