<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Honorários e Contratos - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {--bg:#0b0b0b;--card:#1a1a1a;--bg2:#121212;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08);}*{box-sizing:border-box;font-family:'Inter',sans-serif}
body{margin:0;background:var(--bg);color:var(--txt)}.main{max-width:1100px;margin:24px auto;padding:0 16px}
.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:16px;margin-top:12px}
.btn{background:var(--acc);color:#0b0b0b;padding:10px 14px;border:none;border-radius:8px;text-decoration:none;font-weight:700;cursor:pointer}
.btn-outline{background:#222;color:var(--txt);border:1px solid var(--bd)}
.badge{padding:4px 8px;border-radius:999px;font-size:12px;font-weight:700}.pendente{background:#78350f;color:#fcd34d}.parcial{background:#1f2937;color:#cbd5e1}.pago{background:#14532d;color:#bbf7d0}.cancelado{background:#7f1d1d;color:#fecaca}
.meta{color:var(--mut);font-size:13px}.actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
</style>
</head>
<body>
<main class="main">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <h1 style="margin:0;color:var(--acc);">💼 Honorários e Contratos</h1>
        <div style="display:flex;gap:8px;">
            <a class="btn btn-outline" href="/dashboard">Voltar ao dashboard</a>
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
