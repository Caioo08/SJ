<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Novo Prazo - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #0b0b0b;
  --card: #1a1a1a;
  --bg2: #121212;
  --txt: #f6f4ef;
  --acc: #d4af37;
  --mut: #bfb39a;
  --bd: rgba(255, 255, 255, .08);
}

* {
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  margin: 0;
  background: var(--bg);
  color: var(--txt);
}



/* Main Content */
.main {
  padding: 24px;
}

.wrap {
  max-width: 760px;
  width: 100%;
  margin: 0 auto; /* centraliza horizontalmente */
  background: var(--card);
  padding: 22px;
  border-radius: 12px;
  border: 1px solid var(--bd);
}


/* Form Elements */
label {
  display: block;
  font-size: 13px;
  color: var(--mut);
  margin: 12px 0 6px;
}

input,
select,
textarea {
  width: 100%;
  padding: 11px;
  border-radius: 8px;
  background: #141414;
  border: 1px solid var(--bd);
  color: var(--txt);
}

/* Buttons */
.actions {
  display: flex;
  gap: 8px;
  margin-top: 16px;
}

.btn {
  background: var(--acc);
  color: #0b0b0b;
  padding: 10px 14px;
  border-radius: 8px;
  border: none;
  text-decoration: none;
  font-weight: 700;
}

.btn-outline {
  background: #222;
  color: var(--txt);
}

.meta {
  color: var(--mut);
  font-size: 12px;
}

/* Responsive */
@media (max-width: 900px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .main {
    margin-left: 0;
  }
}

</style>
</head>
<body>
<main class="main"><div class="wrap">
  <h1>➕ Novo Prazo</h1>
  <form action="/prazos/store" method="POST">
    <?= Csrf::field() ?>
    <label>Título*</label><input type="text" name="titulo" required>
    <label>Processo (opcional)</label>
    <select name="processo_id"><option value="">Sem vínculo</option><?php foreach($processos as $proc): ?><option value="<?= $proc['id'] ?>"><?= htmlspecialchars($proc['numero_processo'] ?: ('#'.$proc['id'])) ?> - <?= htmlspecialchars($proc['cliente_nome']) ?></option><?php endforeach; ?></select>
    <label>Data limite manual</label><input type="datetime-local" name="data_limite">
    <div class="meta" style="margin-top:6px;">Opcional quando usar cálculo automático abaixo.</div>

    <label>Cálculo automático (dias)</label><input type="number" min="1" name="dias_prazo" placeholder="Ex: 5">
    <label>Tipo de contagem</label>
    <select name="tipo_contagem">
      <option value="corridos" selected>Dias corridos</option>
      <option value="uteis">Dias úteis (seg-sex)</option>
    </select>
    <label>Data base do cálculo</label><input type="datetime-local" name="data_base">

    <label>Prioridade</label><select name="prioridade"><option value="baixa">Baixa</option><option value="media" selected>Média</option><option value="alta">Alta</option></select>
    <label>Descrição</label><textarea name="descricao" rows="4"></textarea>
    <div class="actions"><button class="btn" type="submit">Salvar</button><a class="btn btn-outline" href="/prazos">Cancelar</a></div>
  </form>
</div></main>
</body>
</html>
