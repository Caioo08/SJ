<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prazos - Sistema Jurídico</title>
<style>
body{font-family:Arial,sans-serif;background:#0b0b0b;color:#f6f4ef;margin:0;padding:24px}
.container{max-width:1100px;margin:0 auto}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;gap:12px;flex-wrap:wrap}
.btn{background:#d4af37;color:#0b0b0b;padding:10px 14px;border-radius:8px;text-decoration:none;border:none;font-weight:700;cursor:pointer}
.btn-outline{background:#1a1a1a;color:#f6f4ef;border:1px solid rgba(255,255,255,.1)}
.card{background:#1a1a1a;border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;margin-bottom:12px}
.badge{padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}
.baixa{background:#1f2937;color:#cbd5e1}.media{background:#78350f;color:#fcd34d}.alta{background:#7f1d1d;color:#fecaca}
.meta{color:#bfb39a;font-size:13px;margin-top:6px}
.actions{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap}
form{display:inline}
.empty{padding:30px;text-align:center;color:#bfb39a;background:#1a1a1a;border-radius:12px;border:1px solid rgba(255,255,255,.08)}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>⏳ Prazos Processuais</h1>
    <div style="display:flex;gap:8px;">
      <a class="btn btn-outline" href="/dashboard">← Dashboard</a>
      <a class="btn" href="/prazos/novo">+ Novo Prazo</a>
    </div>
  </div>

  <?php if (empty($prazos)): ?>
    <div class="empty">Nenhum prazo cadastrado ainda.</div>
  <?php else: ?>
    <?php foreach($prazos as $p):
      $restante = (strtotime($p['data_limite']) - time()) / 86400;
      $urgencia = $restante < 0 && !$p['concluido'] ? '🔴 Atrasado' : ($restante <= 2 && !$p['concluido'] ? '🟠 Crítico' : '🟢 Dentro do prazo');
    ?>
      <div class="card">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;">
          <strong><?= htmlspecialchars($p['titulo']) ?></strong>
          <span class="badge <?= htmlspecialchars($p['prioridade']) ?>"><?= strtoupper($p['prioridade']) ?></span>
        </div>
        <div class="meta">Prazo: <?= date('d/m/Y H:i', strtotime($p['data_limite'])) ?> · Status: <?= $p['concluido'] ? '✅ Concluído' : $urgencia ?></div>
        <?php if(!empty($p['numero_processo'])): ?>
          <div class="meta">Processo: <?= htmlspecialchars($p['numero_processo']) ?> · Cliente: <?= htmlspecialchars($p['cliente_nome']) ?></div>
        <?php endif; ?>
        <?php if(!empty($p['descricao'])): ?>
          <p><?= nl2br(htmlspecialchars($p['descricao'])) ?></p>
        <?php endif; ?>
        <div class="actions">
          <form action="/prazos/toggle/<?= $p['id'] ?>" method="POST">
            <?= Csrf::field() ?>
            <button class="btn" type="submit"><?= $p['concluido'] ? 'Reabrir' : 'Concluir' ?></button>
          </form>
          <form action="/prazos/delete/<?= $p['id'] ?>" method="POST" onsubmit="return confirm('Excluir este prazo?');">
            <?= Csrf::field() ?>
            <button class="btn btn-outline" type="submit">Excluir</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
