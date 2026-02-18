<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mensagens - Sistema Jurídico</title>
<style>
:root {
  --bg: #0b0b0b;
  --card: #1a1a1a;
  --txt: #f6f4ef;
  --mut: #bfb39a;
  --bd: rgba(255, 255, 255, .08);
  --acc: #d4af37;
}

* {
  box-sizing: border-box;
  font-family: Inter, Arial, sans-serif;
}

body {
  margin: 0;
  background: var(--bg);
  color: var(--txt);
}

.layout {
  display: grid;
  grid-template-columns: 320px 1fr;
  min-height: 100vh;
}

.panel {
  border-right: 1px solid var(--bd);
  padding: 16px;
  background: var(--card);
}

.chat {
  padding: 16px;
  display: flex;
  flex-direction: column;
}

.cliente {
  padding: 10px;
  border: 1px solid var(--bd);
  border-radius: 10px;
  margin-bottom: 8px;
  display: block;
  color: var(--txt);
  text-decoration: none;
}

.cliente.active {
  border-color: var(--acc);
  background: rgba(212, 175, 55, .1);
}

.msgs {
  flex: 1;
  overflow: auto;
  border: 1px solid var(--bd);
  border-radius: 10px;
  padding: 12px;
  background: #121212;
}

.msg {
  max-width: 75%;
  padding: 10px;
  border-radius: 10px;
  margin-bottom: 8px;
}

.msg.adv {
  margin-left: auto;
  background: rgba(212, 175, 55, .2);
}

.msg.cli {
  background: rgba(255, 255, 255, .07);
}

.meta {
  font-size: 12px;
  color: var(--mut);
  margin-top: 4px;
}

.badge {
  display: inline-block;
  background: #ef4444;
  color: white;
  border-radius: 999px;
  padding: 2px 8px;
  font-size: 11px;
  font-weight: 700;
  margin-left: 6px;
}

.alert {
  background: rgba(239, 68, 68, .15);
  border: 1px solid rgba(239, 68, 68, .4);
  color: #fecaca;
  padding: 10px;
  border-radius: 8px;
  margin: 10px 0;
}

form {
  display: flex;
  gap: 8px;
  margin-top: 10px;
}

textarea {
  flex: 1;
  background: #141414;
  border: 1px solid var(--bd);
  color: var(--txt);
  padding: 10px;
  border-radius: 10px;
}

button {
  background: var(--acc);
  border: none;
  padding: 10px 14px;
  border-radius: 10px;
  font-weight: 700;
}

.top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.btn {
  color: #0b0b0b;
  background: var(--acc);
  padding: 8px 12px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 700;
}

@media (max-width: 980px) {
  .layout {
    grid-template-columns: 1fr;
  }

  .panel {
    border-right: none;
    border-bottom: 1px solid var(--bd);
  }
}

</style>
</head>
<body>
<div class="layout">
  <aside class="panel">
    <div class="top"><h2 style="margin:0">Mensagens</h2><a href="/dashboard" class="btn">Dashboard</a></div>
    <p style="color:var(--mut)">Conversa com clientes</p>
    <?php if (!empty($_GET['erro'])): ?>
      <div class="alert">Não foi possível enviar a mensagem. Verifique os dados e tente novamente.</div>
    <?php endif; ?>
    <?php if (empty($clientes)): ?>
      <p style="color:var(--mut)">Nenhum cliente cadastrado.</p>
    <?php else: ?>
      <?php foreach($clientes as $c): ?>
        <a class="cliente <?= (int)$clienteSelecionado === (int)$c['id'] ? 'active' : '' ?>" href="/mensagens?cliente_id=<?= $c['id'] ?>">
          <strong><?= htmlspecialchars($c['nome']) ?></strong>
          <div class="meta">
            <?= htmlspecialchars($c['email'] ?: 'sem email') ?>
            <?php if ((int)($c['nao_lidas'] ?? 0) > 0): ?>
              <span class="badge"><?= (int)$c['nao_lidas'] ?></span>
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </aside>

  <section class="chat">
    <?php if (!$clienteAtual): ?>
      <p style="color:var(--mut)">Selecione um cliente para visualizar a conversa.</p>
    <?php else: ?>
      <h3 style="margin-top:0">Conversa com <?= htmlspecialchars($clienteAtual['nome']) ?></h3>
      <div class="msgs">
        <?php if (empty($mensagens)): ?>
          <p style="color:var(--mut)">Sem mensagens ainda.</p>
        <?php else: ?>
          <?php foreach($mensagens as $m): ?>
            <div class="msg <?= $m['autor_tipo'] === 'advogado' ? 'adv' : 'cli' ?>">
              <div><?= nl2br(htmlspecialchars($m['mensagem'])) ?></div>
              <div class="meta"><?= $m['autor_tipo'] === 'advogado' ? 'Você' : htmlspecialchars($clienteAtual['nome']) ?> · <?= date('d/m/Y H:i', strtotime($m['criado_em'])) ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <form method="POST" action="/mensagens/enviar">
        <?= Csrf::field() ?>
        <input type="hidden" name="cliente_id" value="<?= (int)$clienteAtual['id'] ?>">
        <textarea name="mensagem" rows="3" placeholder="Digite a resposta para o cliente..." required></textarea>
        <button type="submit">Enviar</button>
      </form>
    <?php endif; ?>
  </section>
</div>
</body>
</html>
