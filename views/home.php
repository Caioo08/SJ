<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SJ — Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #0b0b0b;
  --bg-soft: #121212;
  --card: #1a1a1a;
  --text: #f6f4ef;
  --muted: #bfb39a;
  --accent: #d4af37;
  --accent-2: #c49f2c;
  --border: rgba(255,255,255,0.09);
  --shadow: 0 16px 50px rgba(0,0,0,.45);
}
*{box-sizing:border-box;font-family:'Inter',sans-serif}
body{margin:0;background:radial-gradient(circle at top,#1b1b1b,#0b0b0b 55%);color:var(--text)}
.wrap{max-width:1200px;margin:0 auto;padding:28px 20px 44px}
.topbar{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:22px}
.brand{display:flex;align-items:center;gap:10px}
.logo{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#b8860b,#f1c65b);display:flex;align-items:center;justify-content:center;color:#0b0b0b;font-weight:800}
.brand h1{font-size:20px;margin:0;color:var(--accent)}
.btn{display:inline-flex;align-items:center;justify-content:center;border-radius:10px;padding:12px 16px;font-weight:700;text-decoration:none;border:1px solid transparent;cursor:pointer}
.btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent-2));color:#0b0b0b}
.btn-ghost{background:#222;color:var(--text);border-color:var(--border)}
.hero{display:grid;grid-template-columns:1.2fr .8fr;gap:18px}
.card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:24px;box-shadow:var(--shadow)}
.title{font-size:40px;line-height:1.05;margin:0 0 12px;color:var(--accent)}
.desc{color:var(--muted);line-height:1.7;margin:0 0 18px}
.badges{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
.badge{border:1px solid var(--border);background:var(--bg-soft);padding:8px 12px;border-radius:999px;font-size:13px;color:var(--muted)}
.features{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px}
.feature{background:#131313;border:1px solid var(--border);border-radius:10px;padding:12px}
.feature strong{color:var(--text);font-size:14px}
.feature p{margin:5px 0 0;color:var(--muted);font-size:13px;line-height:1.5}
.aside-title{margin:0 0 8px;color:var(--accent)}
.aside p{color:var(--muted);line-height:1.6}
.role-list{display:grid;gap:8px;margin-top:14px}
.role{background:#141414;border:1px solid var(--border);padding:12px;border-radius:10px}
.role strong{display:block}
.role small{color:var(--muted)}
.role-actions{display:grid;gap:8px;margin-top:14px}
.footer-note{margin-top:14px;font-size:12px;color:var(--muted)}
@media (max-width:960px){.hero{grid-template-columns:1fr}.features{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <header class="topbar">
    <div class="brand">
      <div class="logo">SJ</div>
      <h1>Sistema Jurídico</h1>
    </div>
    <a href="/login" class="btn btn-primary">Entrar</a>
  </header>

  <section class="hero">
    <article class="card">
      <h2 class="title">Organize seu escritório com mais controle e menos retrabalho.</h2>
      <p class="desc">O SJ centraliza processos, clientes, compromissos, documentos e prazos em um fluxo único. A ideia é dar previsibilidade à rotina jurídica e melhorar a operação do dia a dia.</p>

      <div class="badges">
        <span class="badge">🔐 Segurança com CSRF</span>
        <span class="badge">⏳ Prazos críticos em destaque</span>
        <span class="badge">📁 Gestão de documentos</span>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn btn-primary" href="/login?acesso=advogado">Acessar como Advogado</a>
        <a class="btn btn-ghost" href="/login?acesso=admin">Acessar como Admin</a>
      </div>

      <div class="features">
        <div class="feature">
          <strong>Processos e Clientes</strong>
          <p>Visão consolidada da carteira, com histórico e dados essenciais.</p>
        </div>
        <div class="feature">
          <strong>Agenda e Compromissos</strong>
          <p>Controle das datas importantes para evitar perda de contexto.</p>
        </div>
        <div class="feature">
          <strong>Prazos Processuais</strong>
          <p>Filtro por urgência, conclusão e prioridade operacional.</p>
        </div>
      </div>
    </article>

    <aside class="card aside">
      <h3 class="aside-title">Escolha seu tipo de acesso</h3>
      <p>Na próxima tela você seleciona o perfil e entra com email/senha.</p>

      <div class="role-list">
        <div class="role">
          <strong>Administrador</strong>
          <small>Gestão de usuários, auditoria e visão global do sistema.</small>
        </div>
        <div class="role">
          <strong>Advogado</strong>
          <small>Operação diária com processos, clientes e prazos.</small>
        </div>
        <div class="role">
          <strong>Cliente (em breve)</strong>
          <small>Área planejada para acompanhamento e interação.</small>
        </div>
      </div>

      <div class="role-actions">
        <a class="btn btn-primary" href="/login">Continuar para o login</a>
      </div>
      <div class="footer-note">Sistema Jurídico © <?= date('Y') ?></div>
    </aside>
  </section>
</div>
</body>
</html>
