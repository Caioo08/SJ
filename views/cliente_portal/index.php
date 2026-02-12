<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Portal do Cliente - SJ</title>
<style>
:root{--bg:#0b0b0b;--card:#1a1a1a;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08)}
*{box-sizing:border-box;font-family:Inter,Arial,sans-serif}body{margin:0;background:var(--bg);color:var(--txt);padding:24px}
.wrap{max-width:1100px;margin:0 auto}.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:18px;margin-bottom:14px}
h1{margin:0 0 8px;color:var(--acc)}.mut{color:var(--mut)}
.table{width:100%;border-collapse:collapse}.table th,.table td{padding:10px;border-bottom:1px solid var(--bd);text-align:left}
.badge{padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}.aberto{background:#14532d;color:#86efac}.concluido{background:#1e3a8a;color:#bfdbfe}.arquivado{background:#3f3f46;color:#d4d4d8}
.btn{background:var(--acc);color:#0b0b0b;padding:8px 12px;border-radius:8px;text-decoration:none;font-weight:700}
.top{display:flex;justify-content:space-between;gap:10px;align-items:center;flex-wrap:wrap}
</style>
</head>
<body>
<div class="wrap">
  <div class="top card">
    <div>
      <h1>Olá, <?= htmlspecialchars($cliente['nome']) ?></h1>
      <div class="mut">Portal do Cliente • Advogado responsável: <?= htmlspecialchars($_SESSION['cliente_advogado'] ?? 'Não informado') ?></div>
    </div>
    <a class="btn" href="/logout">Sair</a>
  </div>

  <div class="card">
    <h2 style="margin-top:0">Seus processos</h2>
    <?php if (empty($processos)): ?>
      <p class="mut">Nenhum processo vinculado no momento.</p>
    <?php else: ?>
      <table class="table">
        <thead><tr><th>Número</th><th>Status</th><th>Última atualização</th><th>Ação</th></tr></thead>
        <tbody>
        <?php foreach($processos as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['numero_processo'] ?: ('#'.$p['id'])) ?></td>
            <td><span class="badge <?= htmlspecialchars($p['status']) ?>"><?= ucfirst($p['status']) ?></span></td>
            <td><?= date('d/m/Y H:i', strtotime($p['atualizado_em'] ?: $p['criado_em'])) ?></td>
            <td><a class="btn" href="/cliente/processos/<?= $p['id'] ?>">Ver detalhes</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
