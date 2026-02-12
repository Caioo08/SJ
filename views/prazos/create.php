<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Novo Prazo - Sistema Jurídico</title>
<style>
body{font-family:Arial,sans-serif;background:#0b0b0b;color:#f6f4ef;margin:0;padding:24px}
.container{max-width:760px;margin:0 auto;background:#1a1a1a;padding:22px;border-radius:12px;border:1px solid rgba(255,255,255,.08)}
label{display:block;font-size:13px;color:#bfb39a;margin:12px 0 6px}
input,select,textarea{width:100%;padding:11px;border-radius:8px;background:#141414;border:1px solid rgba(255,255,255,.1);color:#f6f4ef}
.actions{display:flex;gap:8px;margin-top:16px}
.btn{background:#d4af37;color:#0b0b0b;padding:10px 14px;border-radius:8px;border:none;text-decoration:none;font-weight:700}
.btn-outline{background:#222;color:#f6f4ef}
</style>
</head>
<body>
<div class="container">
  <h1>➕ Novo Prazo</h1>
  <form action="/prazos/store" method="POST">
    <?= Csrf::field() ?>
    <label>Título*</label>
    <input type="text" name="titulo" required>

    <label>Processo (opcional)</label>
    <select name="processo_id">
      <option value="">Sem vínculo</option>
      <?php foreach($processos as $proc): ?>
      <option value="<?= $proc['id'] ?>"><?= htmlspecialchars($proc['numero_processo'] ?: ('#'.$proc['id'])) ?> - <?= htmlspecialchars($proc['cliente_nome']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Data limite*</label>
    <input type="datetime-local" name="data_limite" required>

    <label>Prioridade</label>
    <select name="prioridade">
      <option value="baixa">Baixa</option>
      <option value="media" selected>Média</option>
      <option value="alta">Alta</option>
    </select>

    <label>Descrição</label>
    <textarea name="descricao" rows="4"></textarea>

    <div class="actions">
      <button class="btn" type="submit">Salvar</button>
      <a class="btn btn-outline" href="/prazos">Cancelar</a>
    </div>
  </form>
</div>
</body>
</html>
