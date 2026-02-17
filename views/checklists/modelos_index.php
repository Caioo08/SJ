<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modelos de Checklist - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {--bg:#0b0b0b;--card:#1a1a1a;--bg2:#121212;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08);}
*{box-sizing:border-box;font-family:'Inter',sans-serif} body{margin:0;background:var(--bg);color:var(--txt)}
.sidebar{position:fixed;left:0;top:0;width:260px;height:100vh;background:var(--card);border-right:1px solid var(--bd);padding:24px 0;overflow-y:auto}
.logo-section{padding:0 24px 24px;border-bottom:1px solid var(--bd);margin-bottom:24px}.logo-container{display:flex;align-items:center;gap:12px}
.logo{width:42px;height:42px;border-radius:8px;background:linear-gradient(135deg,#b8860b,#f1c65b);display:flex;align-items:center;justify-content:center;color:#0b0b0b;font-weight:800}
.logo-text{font-size:18px;font-weight:700;color:var(--acc)}
.nav-menu{list-style:none;padding:0 12px}.nav-link{display:flex;gap:12px;padding:12px 16px;color:var(--mut);text-decoration:none;border-radius:8px}.nav-link:hover,.nav-link.active{background:var(--bg2);color:var(--acc)}
.main{margin-left:260px;padding:24px}.header{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px}
h1{margin:0;color:var(--acc)}
.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:16px;margin-top:12px}
.meta{color:var(--mut);font-size:13px}
.actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.btn{background:var(--acc);color:#0b0b0b;padding:9px 12px;border:none;border-radius:8px;text-decoration:none;font-weight:700;cursor:pointer}
.btn-outline{background:#222;color:var(--txt);border:1px solid var(--bd)}
.btn-danger{background:#7f1d1d;color:#fecaca;border:1px solid rgba(254,202,202,.2)}
@media (max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}}
</style>
</head>
<body>
<aside class="sidebar">
    <div class="logo-section"><div class="logo-container"><div class="logo">SJ</div><span class="logo-text">Sistema Jurídico</span></div></div>
    <ul class="nav-menu">
        <li><a href="/dashboard" class="nav-link"><span>📊</span> Dashboard</a></li>
        <li><a href="/processos" class="nav-link"><span>⚖️</span> Processos</a></li>
        <li><a href="/checklists/modelos" class="nav-link active"><span>✅</span> Modelos checklist</a></li>
        <li><a href="/logout" class="nav-link"><span>🚪</span> Sair</a></li>
    </ul>
</aside>

<main class="main">
    <div class="header">
        <h1>✅ Modelos de Checklist</h1>
        <a class="btn btn-outline" href="/processos">Voltar para processos</a>
    </div>

    <?php if (empty($modelos)): ?>
        <div class="card">Nenhum modelo cadastrado.</div>
    <?php else: ?>
        <?php foreach ($modelos as $modelo): ?>
            <div class="card">
                <div style="display:flex;justify-content:space-between;gap:10px;align-items:flex-start;flex-wrap:wrap;">
                    <div>
                        <strong><?= htmlspecialchars($modelo['nome']) ?></strong>
                        <div class="meta">Tipo: <?= htmlspecialchars($modelo['tipo_acao'] ?: 'geral') ?></div>
                        <div class="meta">Status: <?= (int)$modelo['ativo'] === 1 ? 'Ativo' : 'Inativo' ?> · Criado em <?= !empty($modelo['criado_em']) ? date('d/m/Y H:i', strtotime($modelo['criado_em'])) : '-' ?></div>
                    </div>
                    <div class="actions">
                        <a class="btn btn-outline" href="/checklists/modelos/<?= (int)$modelo['id'] ?>/editar">Editar</a>
                        <form method="POST" action="/checklists/modelos/<?= (int)$modelo['id'] ?>/toggle" style="display:inline;">
                            <?= Csrf::field() ?>
                            <button class="btn" type="submit"><?= (int)$modelo['ativo'] === 1 ? 'Desativar' : 'Ativar' ?></button>
                        </form>
                        <form method="POST" action="/checklists/modelos/<?= (int)$modelo['id'] ?>/excluir" style="display:inline;" onsubmit="return confirm('Excluir este modelo?');">
                            <?= Csrf::field() ?>
                            <button class="btn btn-danger" type="submit">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
</body>
</html>
