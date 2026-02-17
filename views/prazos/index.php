<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prazos - Sistema Jurídico</title>
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
.btn{background:var(--acc);color:#0b0b0b;padding:10px 14px;border:none;border-radius:8px;text-decoration:none;font-weight:700;cursor:pointer}.btn-outline{background:#222;color:var(--txt);border:1px solid var(--bd)}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:16px 0}.stat{background:var(--card);border:1px solid var(--bd);padding:14px;border-radius:10px}
.filters{display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:8px;background:var(--card);border:1px solid var(--bd);padding:12px;border-radius:10px}
input,select{padding:10px;border-radius:8px;border:1px solid var(--bd);background:#141414;color:var(--txt);width:100%}
.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:16px;margin-top:12px}.meta{color:var(--mut);font-size:13px;margin-top:6px}
.badge{padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}.baixa{background:#1f2937;color:#cbd5e1}.media{background:#78350f;color:#fcd34d}.alta{background:#7f1d1d;color:#fecaca}
.actions{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap}form{display:inline}
.alert{background:rgba(74,222,128,.15);border:1px solid rgba(74,222,128,.4);color:#86efac;padding:10px;border-radius:8px;margin-bottom:10px}
@media (max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}.filters{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside class="sidebar">
    <div class="logo-section"><div class="logo-container"><div class="logo">SJ</div><span class="logo-text">Sistema Jurídico</span></div></div>
    <ul class="nav-menu">
        <li><a href="/dashboard" class="nav-link"><span>📊</span> Dashboard</a></li>
        <li><a href="/processos" class="nav-link"><span>⚖️</span> Processos</a></li>
        <li><a href="/clientes" class="nav-link"><span>👥</span> Clientes</a></li>
        <li><a href="/compromissos" class="nav-link"><span>📅</span> Compromissos</a></li>
        <li><a href="/prazos" class="nav-link active"><span>⏳</span> Prazos</a></li>
        <li><a href="/honorarios" class="nav-link"><span>💼</span> Honorários</a></li>
        <li><a href="/documentos" class="nav-link"><span>📄</span> Documentos</a></li>
        <li><a href="/configuracoes" class="nav-link"><span>⚙️</span> Configurações</a></li>
        <li><a href="/logout" class="nav-link"><span>🚪</span> Sair</a></li>
    </ul>
</aside>

<main class="main">
  <div class="header">
    <h1>⏳ Prazos Processuais</h1>
    <a class="btn" href="/prazos/novo">+ Novo Prazo</a>
  </div>

  <?php if (!empty($_GET['msg'])): ?><div class="alert">Alteração realizada com sucesso.</div><?php endif; ?>

  <div class="stats">
    <div class="stat"><strong><?= (int)($stats['abertos'] ?? 0) ?></strong><div class="meta">Abertos</div></div>
    <div class="stat"><strong><?= (int)($stats['atrasados'] ?? 0) ?></strong><div class="meta">Atrasados</div></div>
    <div class="stat"><strong><?= (int)($stats['concluidos'] ?? 0) ?></strong><div class="meta">Concluídos</div></div>
  </div>

  <form method="GET" class="filters">
    <input type="text" name="q" value="<?= htmlspecialchars($busca) ?>" placeholder="Buscar por título, processo ou cliente">
    <select name="status">
      <option value="abertos" <?= $statusFiltro==='abertos'?'selected':'' ?>>Abertos</option>
      <option value="atrasados" <?= $statusFiltro==='atrasados'?'selected':'' ?>>Atrasados</option>
      <option value="concluidos" <?= $statusFiltro==='concluidos'?'selected':'' ?>>Concluídos</option>
    </select>
    <select name="prioridade">
      <option value="">Todas prioridades</option>
      <option value="baixa" <?= $prioridadeFiltro==='baixa'?'selected':'' ?>>Baixa</option>
      <option value="media" <?= $prioridadeFiltro==='media'?'selected':'' ?>>Média</option>
      <option value="alta" <?= $prioridadeFiltro==='alta'?'selected':'' ?>>Alta</option>
    </select>
    <button class="btn" type="submit">Filtrar</button>
  </form>

  <?php if (empty($prazos)): ?>
    <div class="card">Nenhum prazo encontrado para os filtros selecionados.</div>
  <?php else: ?>
    <?php foreach($prazos as $p): $restante=(strtotime($p['data_limite'])-time())/86400; $urg=$restante<0&&!$p['concluido']?'🔴 Vencido':($restante<=1&&!$p['concluido']?'🟠 D-1':($restante<=3&&!$p['concluido']?'🟡 D-3':($restante<=7&&!$p['concluido']?'🔵 D-7':'🟢 Em dia'))); ?>
      <div class="card">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;">
          <strong><?= htmlspecialchars($p['titulo']) ?></strong>
          <span class="badge <?= htmlspecialchars($p['prioridade']) ?>"><?= strtoupper($p['prioridade']) ?></span>
        </div>
        <div class="meta">Prazo: <?= date('d/m/Y H:i', strtotime($p['data_limite'])) ?> · <?= $p['concluido'] ? '✅ Concluído' : $urg ?></div>
        <?php if(!empty($p['numero_processo'])): ?><div class="meta">Processo: <?= htmlspecialchars($p['numero_processo']) ?> · Cliente: <?= htmlspecialchars($p['cliente_nome']) ?></div><?php endif; ?>
        <?php if(!empty($p['descricao'])): ?><p><?= nl2br(htmlspecialchars($p['descricao'])) ?></p><?php endif; ?>
        <div class="actions">
          <a class="btn btn-outline" href="/prazos/edit/<?= $p['id'] ?>">Editar</a>
          <form action="/prazos/toggle/<?= $p['id'] ?>" method="POST"><?= Csrf::field() ?><button class="btn" type="submit"><?= $p['concluido'] ? 'Reabrir' : 'Concluir' ?></button></form>
          <form action="/prazos/delete/<?= $p['id'] ?>" method="POST" onsubmit="return confirm('Excluir este prazo?');"><?= Csrf::field() ?><button class="btn btn-outline" type="submit">Excluir</button></form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</main>
</body>
</html>
