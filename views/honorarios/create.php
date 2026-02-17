<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Novo Contrato - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {--bg:#0b0b0b;--card:#1a1a1a;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08);}*{box-sizing:border-box;font-family:'Inter',sans-serif}
body{margin:0;background:var(--bg);color:var(--txt)}.wrap{max-width:860px;margin:24px auto;padding:0 16px}.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:20px}
.field{width:100%;padding:10px;border-radius:8px;border:1px solid var(--bd);background:#111;color:var(--txt);margin-top:6px}.row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.btn{background:var(--acc);color:#0b0b0b;padding:10px 14px;border:none;border-radius:8px;font-weight:700;cursor:pointer}.btn-outline{background:#222;color:var(--txt);border:1px solid var(--bd);text-decoration:none}
@media (max-width:760px){.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1 style="margin:0 0 12px;color:var(--acc);">➕ Novo contrato de honorários</h1>
        <form method="POST" action="/honorarios/store" style="display:grid;gap:12px;">
            <?= Csrf::field() ?>
            <div>
                <label for="descricao">Descrição</label>
                <input id="descricao" class="field" type="text" name="descricao" required>
            </div>
            <div class="row">
                <div>
                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" class="field" name="cliente_id" required>
                        <option value="">Selecione</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="processo_id">Processo (opcional)</label>
                    <select id="processo_id" class="field" name="processo_id">
                        <option value="">Sem vínculo</option>
                        <?php foreach ($processos as $p): ?>
                            <option value="<?= (int) $p['id'] ?>"><?= htmlspecialchars($p['numero_processo'] ?: ('Processo #' . $p['id'])) ?> · <?= htmlspecialchars($p['cliente_nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div>
                    <label for="tipo_honorario">Tipo</label>
                    <select id="tipo_honorario" class="field" name="tipo_honorario" required>
                        <option value="fixo">Fixo</option>
                        <option value="exito">Êxito</option>
                    </select>
                </div>
                <div>
                    <label for="valor">Valor (R$)</label>
                    <input id="valor" class="field" type="number" min="0.01" step="0.01" name="valor" required>
                </div>
            </div>
            <div class="row">
                <div>
                    <label for="status_pagamento">Status pagamento</label>
                    <select id="status_pagamento" class="field" name="status_pagamento" required>
                        <option value="pendente">Pendente</option>
                        <option value="parcial">Parcial</option>
                        <option value="pago">Pago</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div>
                    <label for="observacoes">Observações</label>
                    <input id="observacoes" class="field" type="text" name="observacoes">
                </div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn" type="submit">Salvar contrato</button>
                <a class="btn btn-outline" href="/honorarios">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
