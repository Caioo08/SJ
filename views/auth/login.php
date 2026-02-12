<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#0a0a0a; --card:#121212; --card-2:#1a1a1a; --txt:#f6f4ef; --mut:#bfb39a;
  --acc:#d4af37; --acc-2:#c49f2c; --bd:rgba(255,255,255,.08); --shadow:0 16px 40px rgba(0,0,0,.5);
}
*{box-sizing:border-box;font-family:'Inter',sans-serif}
html,body{height:100%}
body{margin:0;background:radial-gradient(circle at top,#191919,#0a0a0a 58%);display:flex;align-items:center;justify-content:center;color:var(--txt)}
.container{width:100%;max-width:980px;padding:24px}
.layout{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.card{background:var(--card);border:1px solid var(--bd);border-radius:14px;padding:22px;box-shadow:var(--shadow)}
.logo-row{display:flex;align-items:center;gap:12px;margin-bottom:14px}
.logo{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#b8860b,#f1c65b);display:flex;align-items:center;justify-content:center;color:#0b0b0b;font-weight:800}
h1{margin:0;font-size:24px;color:var(--acc)}
p{margin:6px 0 0;color:var(--mut);line-height:1.6}
.top-links{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.top-links a{color:var(--mut);text-decoration:none;font-size:14px}
.role-grid{display:grid;gap:8px;margin-top:12px}
.role-card{border:1px solid var(--bd);background:var(--card-2);border-radius:10px;padding:12px;cursor:pointer;transition:.15s}
.role-card:hover{border-color:rgba(212,175,55,.5)}
.role-card.active{border-color:var(--acc);box-shadow:0 0 0 2px rgba(212,175,55,.18) inset}
.role-title{font-weight:700}
.role-desc{font-size:13px;color:var(--mut);margin-top:4px}
form{display:grid;gap:12px;margin-top:8px}
label{font-size:13px;color:var(--mut);display:block;margin-bottom:6px}
input,select{width:100%;padding:12px;border-radius:8px;border:1px solid var(--bd);background:#0f0f0f;color:var(--txt)}
input:focus,select:focus{outline:none;border-color:var(--acc);box-shadow:0 0 0 3px rgba(212,175,55,.12)}
.help{font-size:12px;color:var(--mut)}
.btn{background:linear-gradient(135deg,var(--acc),var(--acc-2));color:#0b0b0b;padding:12px 14px;border:none;border-radius:9px;font-weight:800;cursor:pointer}
.footer{margin-top:12px;color:var(--mut);font-size:12px}
@media (max-width:900px){.layout{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="container">
  <div class="layout">
    <section class="card">
      <div class="logo-row">
        <div class="logo">SJ</div>
        <div>
          <h1>Acesso ao sistema</h1>
          <p>Selecione seu perfil e entre com suas credenciais.</p>
        </div>
      </div>

      <div class="role-grid" id="roleGrid">
        <div class="role-card" data-role="admin">
          <div class="role-title">Administrador</div>
          <div class="role-desc">Gerencia usuários, auditoria e configurações globais.</div>
        </div>
        <div class="role-card" data-role="advogado">
          <div class="role-title">Advogado</div>
          <div class="role-desc">Opera processos, clientes, compromissos e prazos.</div>
        </div>
        <div class="role-card" data-role="cliente">
          <div class="role-title">Cliente</div>
          <div class="role-desc">Acompanha processos vinculados e informações do caso.</div>
        </div>
      </div>
    </section>

    <section class="card">
      <div class="top-links">
        <a href="/">← Voltar para início</a>
        <a href="/register">Cadastrar advogado</a>
      </div>

      <form method="POST" action="/login" novalidate>
        <?= Csrf::field() ?>

        <div>
          <label for="perfil_acesso">Perfil de acesso</label>
          <select id="perfil_acesso" name="perfil_acesso" required>
            <option value="admin" <?= ($acessoSelecionado ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            <option value="advogado" <?= ($acessoSelecionado ?? '') === 'advogado' ? 'selected' : '' ?>>Advogado</option>
            <option value="cliente" <?= ($acessoSelecionado ?? '') === 'cliente' ? 'selected' : '' ?>>Cliente</option>
          </select>
          <div class="help">* Cliente: use email e senha definidos pelo advogado no cadastro do cliente.</div>
        </div>

        <div>
          <label for="email">Email</label>
          <input id="email" type="email" name="email" required placeholder="seu@exemplo.com">
        </div>

        <div>
          <label for="senha">Senha</label>
          <input id="senha" type="password" name="senha" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn">Entrar</button>
      </form>

      <div class="footer">Sistema Jurídico © <?= date('Y') ?></div>
    </section>
  </div>
</div>

<script>
(function(){
  const select = document.getElementById('perfil_acesso');
  const cards = Array.from(document.querySelectorAll('.role-card'));

  function paint() {
    cards.forEach(c => c.classList.toggle('active', c.dataset.role === select.value));
  }

  cards.forEach(card => {
    card.addEventListener('click', () => {
      select.value = card.dataset.role;
      paint();
    });
  });

  select.addEventListener('change', paint);
  paint();
})();
</script>
</body>
</html>
