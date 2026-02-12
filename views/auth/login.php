<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Sistema Jurídico</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {--bg:#0a0a0a;--card:#0b0b0b;--primary:#f6f4ef;--accent:#d4af37;--muted:#bfb39a;--border:rgba(255,255,255,0.06);--shadow:0 12px 40px rgba(0,0,0,0.7);}
    *{box-sizing:border-box;font-family:'Inter',sans-serif} html,body{height:100%}
    body{margin:0;background:linear-gradient(180deg,var(--bg),#000);display:flex;align-items:center;justify-content:center;color:var(--primary)}
    .container{width:100%;max-width:460px;padding:26px}.card{background:var(--card);border-radius:12px;padding:26px;border:1px solid var(--border);box-shadow:var(--shadow)}
    .brand{display:flex;gap:12px;align-items:center;margin-bottom:16px}.logo{width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,#b8860b,#f1c65b);display:flex;align-items:center;justify-content:center;color:#0b0b0b;font-weight:800}
    h1{margin:0;font-size:20px} p{margin:6px 0 0;color:var(--muted);font-size:13px}
    form{display:grid;gap:12px;margin-top:14px} label{font-size:13px;color:var(--muted);display:block;margin-bottom:6px}
    input,select{width:100%;padding:12px;border-radius:8px;border:1px solid var(--border);background:#121212;color:var(--primary)}
    input:focus,select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(212,175,55,.15)}
    .help{font-size:12px;color:var(--muted)}
    .actions{display:flex;justify-content:space-between;align-items:center;margin-top:6px;gap:10px}
    .btn{background:var(--accent);color:#0b0b0b;padding:11px 15px;border:none;border-radius:8px;font-weight:700;cursor:pointer}
    a{color:var(--muted);text-decoration:none;font-size:14px}.top-links{display:flex;justify-content:space-between;align-items:center}
  </style>
</head>
<body>
  <div class="container">
    <div class="card" role="main" aria-label="Formulário de login">
      <div class="top-links">
        <a href="/">← Início</a>
        <a href="/register">Cadastrar advogado</a>
      </div>

      <div class="brand">
        <div class="logo">SJ</div>
        <div>
          <h1>Login do Sistema</h1>
          <p>Selecione o perfil de acesso e entre com suas credenciais.</p>
        </div>
      </div>

      <form method="POST" action="/login" novalidate>
        <?= Csrf::field() ?>

        <div>
          <label for="perfil_acesso">Perfil de acesso</label>
          <select id="perfil_acesso" name="perfil_acesso" required>
            <option value="admin" <?= ($acessoSelecionado ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            <option value="advogado" <?= ($acessoSelecionado ?? '') === 'advogado' ? 'selected' : '' ?>>Advogado</option>
            <option value="cliente" <?= ($acessoSelecionado ?? '') === 'cliente' ? 'selected' : '' ?>>Cliente (em breve)</option>
          </select>
          <div class="help">Clientes terão portal dedicado em breve.</div>
        </div>

        <div>
          <label for="email">Email</label>
          <input id="email" type="email" name="email" required placeholder="seu@exemplo.com">
        </div>

        <div>
          <label for="senha">Senha</label>
          <input id="senha" type="password" name="senha" required placeholder="••••••••">
        </div>

        <div class="actions">
          <span class="help">Sistema Jurídico © <?= date('Y') ?></span>
          <button type="submit" class="btn">Entrar</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
