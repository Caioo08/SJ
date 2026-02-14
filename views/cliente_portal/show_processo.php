<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Processo - Portal do Cliente</title>
<style>
:root{--bg:#0b0b0b;--card:#1a1a1a;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08)}*{box-sizing:border-box;font-family:Inter,Arial,sans-serif}
body{margin:0;background:var(--bg);color:var(--txt);padding:24px}.wrap{max-width:900px;margin:0 auto}.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:18px;margin-bottom:12px}
.btn{background:var(--acc);color:#0b0b0b;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700}.mut{color:var(--mut)}
</style>
</head>
<body>
<div class="wrap">
  <a class="btn" href="/cliente">← Voltar</a>
  <div class="card">
    <h1 style="margin:0 0 8px;color:var(--acc)">Processo <?= htmlspecialchars($processo['numero_processo'] ?: ('#'.$processo['id'])) ?></h1>
    <p class="mut">Status: <?= ucfirst(htmlspecialchars($processo['status'])) ?> • Atualizado em <?= date('d/m/Y H:i', strtotime($processo['atualizado_em'] ?: $processo['criado_em'])) ?></p>
    <h3>Resumo</h3>
    <p><?= nl2br(htmlspecialchars($processo['descricao'] ?: 'Sem descrição cadastrada.')) ?></p>
  </div>
  <div class="card">
    <h3>Advogado responsável</h3>
    <p><strong><?= htmlspecialchars($processo['advogado_nome'] ?: 'Não informado') ?></strong></p>
    <p class="mut">Email: <?= htmlspecialchars($processo['advogado_email'] ?: '-') ?> • OAB: <?= htmlspecialchars($processo['oab'] ?: '-') ?></p>
  </div>

  <div class="card">
    <h3>Linha do tempo</h3>
    <?php if (empty($eventos)): ?>
      <p class="mut">Sem eventos registrados para este processo.</p>
    <?php else: ?>
      <?php foreach($eventos as $ev): ?>
        <div style="padding:10px 0;border-bottom:1px solid var(--bd);">
          <strong><?= htmlspecialchars($ev['titulo']) ?></strong>
          <?php if(!empty($ev['descricao'])): ?><div class="mut" style="margin-top:4px;"><?= htmlspecialchars($ev['descricao']) ?></div><?php endif; ?>
          <div class="mut" style="font-size:12px;"><?= date('d/m/Y H:i', strtotime($ev['criado_em'])) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
