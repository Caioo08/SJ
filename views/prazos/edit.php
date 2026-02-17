<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Editar Prazo - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {--bg:#0b0b0b;--card:#1a1a1a;--bg2:#121212;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08);}*{box-sizing:border-box;font-family:'Inter',sans-serif}
body{margin:0;background:var(--bg);color:var(--txt)}.sidebar{position:fixed;left:0;top:0;width:260px;height:100vh;background:var(--card);border-right:1px solid var(--bd);padding:24px 0;overflow-y:auto}
.logo-section{padding:0 24px 24px;border-bottom:1px solid var(--bd);margin-bottom:24px}.logo-container{display:flex;align-items:center;gap:12px}.logo{width:42px;height:42px;border-radius:8px;background:linear-gradient(135deg,#b8860b,#f1c65b);display:flex;align-items:center;justify-content:center;color:#0b0b0b;font-weight:800}.logo-text{font-size:18px;font-weight:700;color:var(--acc)}
.nav-menu{list-style:none;padding:0 12px}.nav-link{display:flex;gap:12px;padding:12px 16px;color:var(--mut);text-decoration:none;border-radius:8px}.nav-link:hover,.nav-link.active{background:var(--bg2);color:var(--acc)}
.main{margin-left:260px;padding:24px}.wrap{max-width:760px;background:var(--card);padding:22px;border-radius:12px;border:1px solid var(--bd)}label{display:block;font-size:13px;color:var(--mut);margin:12px 0 6px}
input,select,textarea{width:100%;padding:11px;border-radius:8px;background:#141414;border:1px solid var(--bd);color:var(--txt)}.actions{display:flex;gap:8px;margin-top:16px}.btn{background:var(--acc);color:#0b0b0b;padding:10px 14px;border-radius:8px;border:none;text-decoration:none;font-weight:700}.btn-outline{background:#222;color:var(--txt)}.meta{color:var(--mut);font-size:12px}
@media (max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}}
</style></head><body>
<aside class="sidebar"><div class="logo-section"><div class="logo-container"><div class="logo">SJ</div><span class="logo-text">Sistema Jurídico</span></div></div>
<ul class="nav-menu"><li><a href="/dashboard" class="nav-link"><span>📊</span> Dashboard</a></li><li><a href="/processos" class="nav-link"><span>⚖️</span> Processos</a></li><li><a href="/clientes" class="nav-link"><span>👥</span> Clientes</a></li><li><a href="/compromissos" class="nav-link"><span>📅</span> Compromissos</a></li><li><a href="/prazos" class="nav-link active"><span>⏳</span> Prazos</a></li><li><a href="/honorarios" class="nav-link"><span>💼</span> Honorários</a></li>
        <li><a href="/documentos" class="nav-link"><span>📄</span> Documentos</a></li><li><a href="/configuracoes" class="nav-link"><span>⚙️</span> Configurações</a></li><li><a href="/logout" class="nav-link"><span>🚪</span> Sair</a></li></ul>
</aside>
<main class="main"><div class="wrap"><h1>✏️ Editar Prazo</h1>
<form action="/prazos/update/<?= $prazo['id'] ?>" method="POST">
<?= Csrf::field() ?>
<label>Título*</label><input type="text" name="titulo" required value="<?= htmlspecialchars($prazo['titulo']) ?>">
<label>Processo (opcional)</label><select name="processo_id"><option value="">Sem vínculo</option><?php foreach($processos as $proc): ?><option value="<?= $proc['id'] ?>" <?= (int)$prazo['processo_id']===(int)$proc['id']?'selected':'' ?>><?= htmlspecialchars($proc['numero_processo'] ?: ('#'.$proc['id'])) ?> - <?= htmlspecialchars($proc['cliente_nome']) ?></option><?php endforeach; ?></select>
<label>Data limite manual</label><input type="datetime-local" name="data_limite" value="<?= date('Y-m-d\TH:i', strtotime($prazo['data_limite'])) ?>">
<div class="meta" style="margin-top:6px;">Opcional quando usar cálculo automático abaixo.</div>
<label>Recalcular prazo (dias)</label><input type="number" min="1" name="dias_prazo" placeholder="Ex: 5">
<label>Tipo de contagem</label><select name="tipo_contagem"><option value="corridos" selected>Dias corridos</option><option value="uteis">Dias úteis (seg-sex)</option></select>
<label>Data base do cálculo</label><input type="datetime-local" name="data_base">
<label>Prioridade</label><select name="prioridade"><option value="baixa" <?= $prazo['prioridade']==='baixa'?'selected':'' ?>>Baixa</option><option value="media" <?= $prazo['prioridade']==='media'?'selected':'' ?>>Média</option><option value="alta" <?= $prazo['prioridade']==='alta'?'selected':'' ?>>Alta</option></select>
<label>Descrição</label><textarea name="descricao" rows="4"><?= htmlspecialchars($prazo['descricao']) ?></textarea>
<div class="actions"><button class="btn" type="submit">Atualizar</button><a class="btn btn-outline" href="/prazos">Cancelar</a></div>
</form></div>
<?php if (!empty($historico ?? [])): ?>
<div class="wrap" style="margin-top:16px;">
  <h2 style="margin-top:0;">🕘 Histórico de Alterações</h2>
  <?php foreach(($historico ?? []) as $h): ?>
    <div style="border:1px solid var(--bd);border-radius:8px;padding:10px;margin-bottom:8px;">
      <div class="meta"><strong><?= htmlspecialchars($h['alteracao']) ?></strong> · <?= date('d/m/Y H:i', strtotime($h['criado_em'])) ?> · <?= htmlspecialchars($h['usuario_nome'] ?? 'Usuário') ?></div>
      <?php if(!empty($h['antes_json'])): ?><div class="meta">Antes: <?= htmlspecialchars($h['antes_json']) ?></div><?php endif; ?>
      <?php if(!empty($h['depois_json'])): ?><div class="meta">Depois: <?= htmlspecialchars($h['depois_json']) ?></div><?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
</main>
</body></html>
