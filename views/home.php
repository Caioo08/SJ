<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SJ - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#0b0b0b;--card:#171717;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.09)}
*{box-sizing:border-box;font-family:'Inter',sans-serif} body{margin:0;background:radial-gradient(circle at top,#1b1b1b, #0b0b0b 60%);color:var(--txt)}
.wrap{max-width:1100px;margin:0 auto;padding:48px 22px}
.hero{display:grid;grid-template-columns:1.2fr .8fr;gap:24px;align-items:stretch}
.card{background:var(--card);border:1px solid var(--bd);border-radius:14px;padding:26px}
h1{font-size:40px;margin:0 0 10px;color:var(--acc)}p{color:var(--mut);line-height:1.6}
.badges{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}.badge{padding:8px 12px;border-radius:999px;background:#212121;border:1px solid var(--bd);font-size:13px}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:20px}.btn{background:var(--acc);color:#0b0b0b;padding:12px 16px;border-radius:10px;font-weight:700;text-decoration:none}
.btn-outline{background:#222;color:var(--txt);border:1px solid var(--bd)}
.features{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}.feature{background:#121212;border:1px solid var(--bd);border-radius:10px;padding:14px}
small{color:var(--mut)}
@media (max-width:900px){.hero{grid-template-columns:1fr}.features{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <div class="hero">
    <section class="card">
      <h1>⚖️ Sistema Jurídico SJ</h1>
      <p>Plataforma para organizar rotinas jurídicas com foco em produtividade, segurança e controle de prazos. Centralize processos, clientes, documentos e agenda em um só lugar.</p>
      <div class="badges">
        <span class="badge">🔐 Segurança com CSRF</span>
        <span class="badge">⏳ Gestão de prazos</span>
        <span class="badge">📄 Documentos centralizados</span>
      </div>
      <div class="actions">
        <a class="btn" href="/login">Acessar sistema</a>
        <a class="btn btn-outline" href="/login?acesso=advogado">Entrar como Advogado</a>
      </div>
      <div class="features">
        <div class="feature"><strong>Processos e clientes</strong><br><small>Visão completa da carteira e histórico.</small></div>
        <div class="feature"><strong>Agenda e compromissos</strong><br><small>Planejamento de audiências e tarefas.</small></div>
        <div class="feature"><strong>Prazos críticos</strong><br><small>Acompanhamento de urgências em 48h.</small></div>
      </div>
    </section>

    <aside class="card">
      <h2 style="margin-top:0;color:var(--acc)">Tipo de acesso</h2>
      <p>Escolha seu perfil na próxima etapa de login:</p>
      <ul style="color:var(--mut);line-height:1.8;padding-left:18px;">
        <li><strong>Administrador</strong>: gestão de usuários, auditoria e visão global.</li>
        <li><strong>Advogado</strong>: operação diária de casos, clientes e prazos.</li>
        <li><strong>Cliente</strong>: área em desenvolvimento (em breve).</li>
      </ul>
      <a class="btn" href="/login?acesso=admin">Continuar para Login</a>
    </aside>
  </div>
</div>
</body>
</html>
