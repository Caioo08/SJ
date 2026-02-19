<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SJ — Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #080808;
  --bg-2: #0f0f0f;
  --bg-3: #151515;
  --gold: #c9a84c;
  --gold-light: #e2c97e;
  --gold-dim: rgba(201,168,76,0.15);
  --text: #f0ece3;
  --text-muted: #bcb4a8;
  --text-dim: #8c8478;
  --border: rgba(201,168,76,0.12);
  --border-soft: rgba(255,255,255,0.05);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  overflow-x: hidden;
}

/* ─── NOISE TEXTURE OVERLAY ─── */
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 0;
  opacity: 0.4;
}

/* ─── ANIMATED BACKGROUND GRADIENT ─── */
.bg-gradient {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 0;
  background:
    radial-gradient(ellipse 60% 50% at 20% 20%, rgba(201,168,76,0.07) 0%, transparent 70%),
    radial-gradient(ellipse 40% 60% at 80% 80%, rgba(201,168,76,0.04) 0%, transparent 70%),
    radial-gradient(ellipse 80% 40% at 50% 100%, rgba(15,10,5,0.9) 0%, transparent 60%);
}

/* ─── LAYOUT ─── */
.page { position: relative; z-index: 1; }

/* ─── NAV ─── */
nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 28px 60px;
  border-bottom: 1px solid var(--border-soft);
  backdrop-filter: blur(12px);
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(8,8,8,0.85);
  animation: fadeDown 0.8s ease both;
}

@keyframes fadeDown {
  from { opacity: 0; transform: translateY(-16px); }
  to { opacity: 1; transform: translateY(0); }
}

.nav-brand {
  display: flex;
  align-items: center;
  gap: 14px;
}

.logo-mark {
  width: 38px;
  height: 38px;
  border: 1px solid var(--gold);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.logo-mark::before {
  content: '';
  position: absolute;
  inset: 3px;
  border: 1px solid rgba(201,168,76,0.3);
}

.logo-mark span {
  font-family: 'Cormorant Garamond', serif;
  font-size: 16px;
  font-weight: 700;
  color: var(--gold);
  letter-spacing: 1px;
}

.brand-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 18px;
  font-weight: 600;
  color: var(--text);
  letter-spacing: 0.05em;
}

.brand-name span {
  color: var(--gold);
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-ghost {
  padding: 9px 20px;
  color: var(--text-muted);
  text-decoration: none;
  font-size: 13px;
  letter-spacing: 0.05em;
  font-weight: 500;
  transition: color 0.2s;
}

.btn-ghost:hover { color: var(--text); }

.btn-primary {
  padding: 10px 24px;
  background: transparent;
  border: 1px solid var(--gold);
  color: var(--gold);
  text-decoration: none;
  font-size: 13px;
  letter-spacing: 0.08em;
  font-weight: 500;
  text-transform: uppercase;
  transition: all 0.25s;
  position: relative;
  overflow: hidden;
}

.btn-primary::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--gold);
  transform: translateX(-100%);
  transition: transform 0.3s ease;
  z-index: -1;
}

.btn-primary:hover {
  color: #080808;
}

.btn-primary:hover::before {
  transform: translateX(0);
}

/* ─── HERO ─── */
.hero {
  padding: 100px 60px 80px;
  display: grid;
  grid-template-columns: 1fr 420px;
  gap: 80px;
  align-items: center;
  max-width: 1300px;
  margin: 0 auto;
}

.hero-label {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 11px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--gold);
  font-weight: 500;
  margin-bottom: 28px;
  animation: fadeUp 0.8s 0.2s ease both;
}

.hero-label::before {
  content: '';
  width: 28px;
  height: 1px;
  background: var(--gold);
}

.hero-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(48px, 6vw, 78px);
  line-height: 1.05;
  font-weight: 300;
  color: var(--text);
  margin-bottom: 28px;
  animation: fadeUp 0.8s 0.3s ease both;
}

.hero-title em {
  font-style: italic;
  color: var(--gold-light);
}

.hero-title strong {
  font-weight: 600;
  display: block;
}

.hero-desc {
  font-size: 15px;
  line-height: 1.8;
  color: var(--text-muted);
  max-width: 500px;
  margin-bottom: 44px;
  font-weight: 400;
  animation: fadeUp 0.8s 0.4s ease both;
}

.hero-actions {
  display: flex;
  gap: 16px;
  align-items: center;
  animation: fadeUp 0.8s 0.5s ease both;
}

.btn-cta {
  padding: 14px 32px;
  background: var(--gold);
  color: #080808;
  text-decoration: none;
  font-size: 13px;
  letter-spacing: 0.1em;
  font-weight: 600;
  text-transform: uppercase;
  transition: all 0.25s;
  position: relative;
}

.btn-cta:hover {
  background: var(--gold-light);
  transform: translateY(-1px);
  box-shadow: 0 12px 40px rgba(201,168,76,0.25);
}

.btn-outline {
  padding: 14px 28px;
  border: 1px solid var(--border-soft);
  color: var(--text-muted);
  text-decoration: none;
  font-size: 13px;
  letter-spacing: 0.08em;
  font-weight: 400;
  text-transform: uppercase;
  transition: all 0.25s;
}

.btn-outline:hover {
  border-color: var(--border);
  color: var(--text);
}

/* ─── HERO CARD ─── */
.hero-card {
  background: var(--bg-2);
  border: 1px solid var(--border);
  padding: 36px;
  animation: fadeUp 0.8s 0.5s ease both;
  position: relative;
}

.hero-card::before {
  content: '';
  position: absolute;
  top: -1px;
  left: 40px;
  right: 40px;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold), transparent);
}

.card-eyebrow {
  font-size: 10px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card-eyebrow::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

.access-list {
  display: flex;
  flex-direction: column;
  gap: 4px;
  margin-bottom: 28px;
}

.access-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 14px 16px;
  border: 1px solid transparent;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  color: inherit;
}

.access-item:hover {
  background: var(--gold-dim);
  border-color: var(--border);
}

.access-icon {
  width: 36px;
  height: 36px;
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 15px;
  margin-top: 2px;
}

.access-name {
  font-size: 14px;
  font-weight: 500;
  color: var(--text);
  margin-bottom: 3px;
}

.access-desc {
  font-size: 12px;
  color: var(--text-muted);
  line-height: 1.5;
}

.card-divider {
  height: 1px;
  background: var(--border-soft);
  margin: 20px 0;
}

.btn-full {
  display: block;
  width: 100%;
  padding: 13px;
  background: var(--gold);
  color: #080808;
  text-decoration: none;
  font-size: 12px;
  letter-spacing: 0.12em;
  font-weight: 600;
  text-transform: uppercase;
  text-align: center;
  transition: all 0.25s;
}

.btn-full:hover {
  background: var(--gold-light);
}

/* ─── DIVIDER ─── */
.section-divider {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 0 60px;
  max-width: 1300px;
  margin: 0 auto 60px;
  opacity: 0.4;
}

.section-divider::before,
.section-divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

.divider-symbol {
  font-family: 'Cormorant Garamond', serif;
  color: var(--gold);
  font-size: 18px;
}

/* ─── FEATURES STRIP ─── */
.features-section {
  padding: 0 60px 80px;
  max-width: 1300px;
  margin: 0 auto;
}

.features-eyebrow {
  font-size: 11px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--text-dim);
  margin-bottom: 40px;
  text-align: center;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1px;
  background: var(--border-soft);
  border: 1px solid var(--border-soft);
}

.feature-cell {
  background: var(--bg);
  padding: 36px 32px;
  transition: background 0.3s;
  position: relative;
  overflow: hidden;
}

.feature-cell::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gold);
  transform: scaleX(0);
  transition: transform 0.3s ease;
}

.feature-cell:hover {
  background: var(--bg-2);
}

.feature-cell:hover::after {
  transform: scaleX(1);
}

.feature-num {
  font-family: 'Cormorant Garamond', serif;
  font-size: 13px;
  color: var(--gold);
  margin-bottom: 20px;
  letter-spacing: 0.1em;
}

.feature-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 12px;
  line-height: 1.2;
}

.feature-desc {
  font-size: 13px;
  line-height: 1.7;
  color: var(--text-muted);
  font-weight: 400;
}

/* ─── STATS ─── */
.stats-section {
  border-top: 1px solid var(--border-soft);
  border-bottom: 1px solid var(--border-soft);
  padding: 60px;
  margin-bottom: 80px;
  background: var(--bg-2);
}

.stats-inner {
  max-width: 1300px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 40px;
}

.stat-item {
  text-align: center;
}

.stat-num {
  font-family: 'Cormorant Garamond', serif;
  font-size: 48px;
  font-weight: 300;
  color: var(--gold);
  line-height: 1;
  margin-bottom: 8px;
}

.stat-label {
  font-size: 12px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--text-muted);
}

/* ─── TRUST ─── */
.trust-section {
  padding: 0 60px 100px;
  max-width: 1300px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2px;
  background: var(--border-soft);
}

.trust-card {
  background: var(--bg);
  padding: 48px 40px;
  position: relative;
}

.trust-card:hover {
  background: var(--bg-2);
}

.trust-quote {
  font-family: 'Cormorant Garamond', serif;
  font-size: 28px;
  font-weight: 300;
  font-style: italic;
  color: var(--text);
  line-height: 1.4;
  margin-bottom: 24px;
}

.trust-quote em {
  color: var(--gold-light);
  font-style: italic;
}

.trust-detail {
  font-size: 13px;
  color: var(--text-muted);
  line-height: 1.6;
}

.trust-icon {
  font-size: 32px;
  margin-bottom: 20px;
  display: block;
  filter: grayscale(0.3);
}

/* ─── FOOTER ─── */
footer {
  border-top: 1px solid var(--border-soft);
  padding: 32px 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.footer-copy {
  font-size: 12px;
  color: var(--text-dim);
  letter-spacing: 0.05em;
}

.footer-links {
  display: flex;
  gap: 28px;
}

.footer-links a {
  font-size: 12px;
  color: var(--text-dim);
  text-decoration: none;
  letter-spacing: 0.05em;
  transition: color 0.2s;
}

.footer-links a:hover { color: var(--gold); }

/* ─── ANIMATIONS ─── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-cell {
  animation: fadeUp 0.7s ease both;
}

.animate-cell:nth-child(1) { animation-delay: 0.1s; }
.animate-cell:nth-child(2) { animation-delay: 0.2s; }
.animate-cell:nth-child(3) { animation-delay: 0.3s; }

/* ─── GOLD LINE DECORATION ─── */
.gold-line {
  width: 1px;
  height: 80px;
  background: linear-gradient(to bottom, transparent, var(--gold), transparent);
  margin: 0 auto 40px;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 1100px) {
  .hero {
    grid-template-columns: 1fr;
    padding: 60px 40px;
    gap: 48px;
  }
  nav { padding: 24px 40px; }
  .hero-card { max-width: 500px; }
}

@media (max-width: 900px) {
  .features-grid { grid-template-columns: 1fr; }
  .stats-inner { grid-template-columns: repeat(2, 1fr); }
  .trust-section { grid-template-columns: 1fr; }
  nav { padding: 20px 24px; }
  .hero { padding: 48px 24px; }
  .features-section { padding: 0 24px 60px; }
  .stats-section { padding: 48px 24px; }
  .trust-section { padding: 0 24px 60px; }
  footer { padding: 24px; flex-direction: column; gap: 16px; text-align: center; }
  .section-divider { padding: 0 24px; }
}
</style>
</head>
<body>

<div class="bg-gradient"></div>

<div class="page">

  <!-- NAV -->
  <nav>
    <div class="nav-brand">
      <div class="logo-mark"><span>SJ</span></div>
      <span class="brand-name">Sistema <span>Jurídico</span></span>
    </div>
    <div class="nav-actions">
      <a href="/register" class="btn-ghost">Cadastrar</a>
      <a href="/login" class="btn-primary">Entrar</a>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-label">Plataforma jurídica profissional</div>

      <h1 class="hero-title">
        <em>Controle total</em><br>
        <strong>do seu escritório</strong>
      </h1>

      <p class="hero-desc">
        Centralize processos, clientes, prazos e documentos em um único sistema. 
        Reduza retrabalho, aumente a previsibilidade e foque no que realmente importa — 
        a advocacia.
      </p>

      <div class="hero-actions">
        <a href="/login?acesso=advogado" class="btn-cta">Acessar Sistema</a>
        <a href="/register" class="btn-outline">Criar conta</a>
      </div>
    </div>

    <div class="hero-card">
      <div class="card-eyebrow">Acesso rápido</div>

      <div class="access-list">
        <a href="/login?acesso=advogado" class="access-item">
          <div class="access-icon">⚖️</div>
          <div>
            <div class="access-name">Advogado</div>
            <div class="access-desc">Gerencie processos, clientes, prazos e agenda completa do escritório.</div>
          </div>
        </a>
        <a href="/login?acesso=admin" class="access-item">
          <div class="access-icon">🛡️</div>
          <div>
            <div class="access-name">Administrador</div>
            <div class="access-desc">Controle de usuários, auditoria e visão global do sistema.</div>
          </div>
        </a>
        <a href="/login?acesso=cliente" class="access-item">
          <div class="access-icon">👤</div>
          <div>
            <div class="access-name">Portal do Cliente</div>
            <div class="access-desc">Acompanhe seus processos e mantenha contato com o escritório.</div>
          </div>
        </a>
      </div>

      <div class="card-divider"></div>
      <a href="/login" class="btn-full">Entrar na plataforma →</a>
    </div>
  </section>

  <!-- DIVIDER -->
  <div class="section-divider">
    <div class="divider-symbol">❧</div>
  </div>

  <!-- FEATURES -->
  <section class="features-section">
    <p class="features-eyebrow">Funcionalidades</p>
    <div class="features-grid">
      <div class="feature-cell animate-cell">
        <div class="feature-num">01</div>
        <div class="feature-title">Gestão de Processos</div>
        <div class="feature-desc">Visão consolidada da carteira com histórico completo, status em tempo real e dados essenciais de cada caso.</div>
      </div>
      <div class="feature-cell animate-cell">
        <div class="feature-num">02</div>
        <div class="feature-title">Controle de Prazos</div>
        <div class="feature-desc">Alertas automáticos com D-7, D-3 e D-1. Nunca perca um prazo processual crítico. Cálculo automático em dias corridos ou úteis.</div>
      </div>
      <div class="feature-cell animate-cell">
        <div class="feature-num">03</div>
        <div class="feature-title">Agenda Inteligente</div>
        <div class="feature-desc">Compromissos, audiências e reuniões integrados ao calendário. Visualização por dia, semana e mês com alertas de conflito.</div>
      </div>
      <div class="feature-cell animate-cell">
        <div class="feature-num">04</div>
        <div class="feature-title">Gestão de Clientes</div>
        <div class="feature-desc">Cadastro completo com CPF/CNPJ, endereço, histórico de processos e geração automática de procuração.</div>
      </div>
      <div class="feature-cell animate-cell">
        <div class="feature-num">05</div>
        <div class="feature-title">Documentos & Petições</div>
        <div class="feature-desc">Armazene, organize e versione documentos e petições. Histórico completo de alterações com sistema de derivação.</div>
      </div>
      <div class="feature-cell animate-cell">
        <div class="feature-num">06</div>
        <div class="feature-title">Honorários & Contratos</div>
        <div class="feature-desc">Controle financeiro completo com contratos fixos e de êxito, status de pagamento e vinculação por processo.</div>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <div class="stats-section">
    <div class="stats-inner">
      <div class="stat-item">
        <div class="stat-num">100%</div>
        <div class="stat-label">Seguro e privado</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">3</div>
        <div class="stat-label">Níveis de acesso</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">∞</div>
        <div class="stat-label">Processos & clientes</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24/7</div>
        <div class="stat-label">Disponibilidade</div>
      </div>
    </div>
  </div>

  <!-- TRUST -->
  <section class="trust-section">
    <div class="trust-card">
      <span class="trust-icon">🔐</span>
      <div class="trust-quote">
        Segurança é <em>inegociável</em>.<br>
        Proteção em cada operação.
      </div>
      <div class="trust-detail">
        Proteção CSRF em todos os formulários, senhas criptografadas com bcrypt, auditoria completa de todas as ações realizadas no sistema e controle granular de acesso por perfil.
      </div>
    </div>
    <div class="trust-card">
      <span class="trust-icon">📋</span>
      <div class="trust-quote">
        Auditoria <em>completa</em><br>de todas as ações.
      </div>
      <div class="trust-detail">
        Cada login, criação, edição e exclusão fica registrada com data, hora e IP. O administrador tem visão total da atividade do sistema e dos usuários em tempo real.
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-copy">© <?= date('Y') ?> Sistema Jurídico — Todos os direitos reservados</div>
    <div class="footer-links">
      <a href="/login">Entrar</a>
      <a href="/register">Cadastrar</a>
      <a href="/login?acesso=admin">Administração</a>
    </div>
  </footer>

</div>

<script>
// Intersection Observer para animar elementos ao scroll
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.feature-cell, .trust-card, .stat-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  observer.observe(el);
});
</script>

</body>
</html>