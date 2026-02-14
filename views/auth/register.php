<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Advogado</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    /* Paleta: preto profundo com toques dourados */
    :root {
      /* tons claros caso user force light */
      --bg: #0a0a0a; /* fallback escuro */
      --card: #0b0b0b;
      --primary: #f6f4ef; /* texto principal claro */
      --accent: #d4af37; /* dourado clássico */
      --muted: #bfb39a; /* dourado pálido para textos secundários */
      --card-border: rgba(255,255,255,0.03);
      --shadow: 0 10px 30px rgba(0,0,0,0.6);
      --logo-grad: linear-gradient(135deg,#b8860b,#f1c65b);
      --btn-text: #0b0b0b;
    }

    /* Se o sistema preferir dark, mantém a paleta preta/dourada (consistente) */
    @media (prefers-color-scheme: dark) {
      :root {
        --bg: #000000;
        --card: #071014;
        --primary: #f6f4ef;
        --accent: #d4af37;
        --muted: #bfb39a;
        --card-border: rgba(255,255,255,0.04);
        --shadow: 0 12px 40px rgba(0,0,0,0.7);
        --logo-grad: linear-gradient(135deg,#b8860b,#f1c65b);
      }
    }

    /* Classe para forçar tema escuro (preto/dourado) */
    .dark {
      --bg: #000000;
      --card: #071014;
      --primary: #f6f4ef;
      --accent: #d4af37;
      --muted: #bfb39a;
      --card-border: rgba(255,255,255,0.04);
      --shadow: 0 12px 40px rgba(0,0,0,0.7);
      --logo-grad: linear-gradient(135deg,#b8860b,#f1c65b);
      --btn-text: #0b0b0b;
    }

    *{box-sizing:border-box;font-family:'Inter',system-ui,'Segoe UI',Roboto,Helvetica,Arial,sans-serif}
    html,body{height:100%}
    body{
      margin:0;
      background:linear-gradient(180deg, var(--bg) 0%, rgba(0,0,0,0) 100%);
      display:flex;align-items:center;justify-content:center;min-height:100vh;color:var(--primary);
      transition:background-color .18s ease,color .18s ease;
    }
    .container{width:100%;max-width:420px;padding:32px}
    .card{background:var(--card);border-radius:12px;padding:28px;box-shadow:var(--shadow);border:1px solid var(--card-border);transition:background-color .18s ease,box-shadow .18s ease}
    .brand{display:flex;align-items:center;gap:12px;margin-bottom:18px}
    .logo{width:44px;height:44px;border-radius:8px;background:var(--logo-grad);display:flex;align-items:center;justify-content:center;color:#0b0b0b;font-weight:800}
    h1{margin:0;font-size:20px;color:var(--primary)}
    p.lead{margin:6px 0 18px;color:var(--muted);font-size:13px}
    form{display:grid;gap:12px}
    label{font-size:13px;color:var(--muted);display:block;margin-bottom:6px}

    /* Inputs discretos sobre fundo preto */
    input[type="email"],input[type="password"], input[type="text"]{width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(255,255,255,0.03);background:transparent;color:var(--primary);font-size:14px;outline:none;transition:box-shadow .15s,border-color .15s,background-color .15s}
    input::placeholder{color:rgba(255,255,255,0.35)}
    input:focus{border-color:var(--accent);box-shadow:0 6px 20px rgba(212,175,55,0.12)}

    .actions{display:flex;align-items:center;justify-content:space-between;margin-top:6px}

    select{width:100%;padding:12px 14px;border-radius:8px;border:1px solid rgba(255,255,255,0.03);background:transparent;color:var(--primary);font-size:14px;outline:none;transition:box-shadow .15s,border-color .15s,background-color .15s}
    select option{background:var(--card);color:var(--primary)}
    /* Botão dourado com texto escuro para contraste forte */
    .btn{background:var(--accent);color:var(--btn-text);padding:11px 16px;border-radius:8px;border:none;font-weight:700;cursor:pointer;box-shadow:0 6px 18px rgba(212,175,55,0.12)}
    .btn:hover{filter:brightness(.98)}

    a.register{color:var(--muted);text-decoration:none;font-size:14px;border-bottom:1px dashed rgba(255,255,255,0.04);padding-bottom:2px}
    a.register:hover{color:var(--accent)}

    .footer{margin-top:14px;text-align:center;color:var(--muted);font-size:13px}

    /* Botão alternador de tema: destacando com dourado quando ativo */
    .theme-toggle{margin-left:auto;background:transparent;border:1px solid rgba(255,255,255,0.04);padding:8px;border-radius:8px;cursor:pointer;font-size:16px;display:inline-flex;align-items:center;justify-content:center;min-width:44px;color:var(--muted)}
    .theme-toggle[aria-pressed="true"]{background:linear-gradient(90deg,rgba(212,175,55,0.12),rgba(212,175,55,0.06));color:var(--accent);border-color:rgba(212,175,55,0.12)}
    .theme-toggle:focus{outline:3px solid rgba(212,175,55,0.12);outline-offset:2px}

    @media (max-width:480px){.container{padding:20px}.card{padding:20px}}
  </style>
</head>

<body>
  <div class="container">
    <div class="card" role="main" aria-label="Formulário de cadastro">
      
      <div class="brand">
        <div class="logo">SJ</div>

        <div>
          <h1>Cadastro de Advogado</h1>
          <p class="lead">Crie sua conta para acessar o sistema jurídico</p>
        </div>

        <div style="margin-left:auto">
          <button id="themeToggle"
                  class="theme-toggle"
                  aria-pressed="false"
                  aria-label="Alternar tema"
                  title="Alternar tema">
            🌙
          </button>
        </div>
      </div>

      <form method="POST" action="/register" novalidate>
            <?= Csrf::field() ?>

        <div>
          <label for="nome">Nome</label>
          <input id="nome" type="text" name="nome" required placeholder="Nome completo">
        </div>

        <div>
          <label for="email">Email</label>
          <input id="email" type="email" name="email" required placeholder="seu@exemplo.com">
        </div>

        <div>
          <label for="senha">Senha</label>
          <input id="senha" type="password" name="senha" required placeholder="••••••••">
        </div>

        <div>
          <label for="oab">OAB</label>
          <input id="oab" type="text" name="oab" required placeholder="Número da OAB">
        </div>

        <div>
          <label for="uf_id">UF da OAB</label>
          <select id="uf_id" name="uf_id" required>
            <option value="">Selecione</option>
            <?php foreach ($ufs as $uf): ?>
              <option value="<?= $uf['id'] ?>">
                <?= htmlspecialchars($uf['sigla']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="actions">
          <a href="/login" class="register">Voltar para login</a>
          <button type="submit" class="btn">Cadastrar</button>
        </div>

      </form>

      <div class="footer">
        <small>Não compartilhe suas credenciais. Sistema Jurídico © <?= date('Y') ?></small>
      </div>

    </div>
  </div>
</body>

