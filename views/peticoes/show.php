<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Versões da Petição - Sistema Jurídico</title>
<style>
:root{--bg:#0b0b0b;--card:#1a1a1a;--txt:#f6f4ef;--mut:#bfb39a;--bd:rgba(255,255,255,.08);--acc:#d4af37}
*{box-sizing:border-box;font-family:Inter,Arial,sans-serif}
body{margin:0;background:var(--bg);color:var(--txt);padding:24px}
.wrap{max-width:1000px;margin:0 auto}
.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:18px;margin-bottom:14px}
.top{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap}
.btn{background:var(--acc);color:#0b0b0b;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700;border:none;cursor:pointer}
.meta{color:var(--mut);font-size:12px;margin-top:4px}
pre{white-space:pre-wrap;background:#121212;border:1px solid var(--bd);padding:12px;border-radius:8px;color:#e9e5db}
.inline{display:inline-block;margin-top:10px}
</style>
</head>
<body>
<div class="wrap">
  <div class="card top">
    <div>
      <h1 style="margin:0;color:var(--acc);">🧾 <?= htmlspecialchars($peticao['titulo']) ?></h1>
      <div class="meta">Histórico de versões</div>
    </div>
    <a class="btn" href="/processos/<?= (int)$peticao['processo_id'] ?>">← Voltar ao processo</a>
  </div>

  <?php if (empty($versoes)): ?>
    <div class="card">Nenhuma versão cadastrada.</div>
  <?php else: ?>
    <?php foreach($versoes as $v): ?>
      <div class="card">
        <strong>Versão <?= (int)$v['versao'] ?></strong>
        <div class="meta">Criada em <?= date('d/m/Y H:i', strtotime($v['criado_em'])) ?> • Autor: <?= htmlspecialchars($v['autor_nome'] ?? 'Usuário') ?></div>
        <?php if(!empty($v['observacao'])): ?><div class="meta">Obs: <?= htmlspecialchars($v['observacao']) ?></div><?php endif; ?>
        <?php if(!empty($v['arquivo_caminho'])): ?><div class="meta">Arquivo: <a href="<?= htmlspecialchars($v['arquivo_caminho']) ?>" target="_blank" style="color:#d4af37"><?= htmlspecialchars($v['arquivo_original'] ?: 'abrir') ?></a></div><?php endif; ?>
        <?php if(!empty($v['conteudo'])): ?><pre><?= htmlspecialchars($v['conteudo']) ?></pre><?php else: ?><div class="meta">Sem conteúdo textual nesta versão.</div><?php endif; ?>
        <form class="inline" method="POST" action="/peticoes/versoes/<?= (int)$v['id'] ?>/derivar">
          <?= Csrf::field() ?>
          <button class="btn" type="submit">Derivar nova versão</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
