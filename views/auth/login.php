<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Entrar — Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #080808;
  --bg-2: #131313;
  --bg-3: #151515;
  --gold: #c9a84c;
  --gold-light: #e2c97e;
  --gold-dim: rgba(201,168,76,0.10);
  --text: #f0ece3;
  --text-muted: #b8b0a4;
  --text-dim: #8a8278;
  --border: rgba(201,168,76,0.18);
  --border-soft: rgba(255,255,255,0.05);
  --error: #d95f5f;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body { height: 100%; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 100vh;
  overflow: hidden;
}

/* ─── NOISE ─── */
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 0;
  opacity: 0.4;
}

/* ─── LEFT PANEL ─── */
.panel-left {
  position: relative;
  background: var(--bg-2);
  border-right: 1px solid var(--border-soft);
  display: flex;
  flex-direction: column;
  padding: 44px 56px;
  z-index: 1;
  overflow: hidden;
  animation: slideLeft 0.8s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes slideLeft {
  from { opacity: 0; transform: translateX(-24px); }
  to   { opacity: 1; transform: translateX(0); }
}

/* Gold accent line on right edge */
.panel-left::after {
  content: '';
  position: absolute;
  right: -1px;
  top: 20%;
  width: 1px;
  height: 140px;
  background: linear-gradient(to bottom, transparent, var(--gold), transparent);
}

/* Radial glow bottom-left */
.panel-left::before {
  content: '';
  position: absolute;
  bottom: -120px;
  left: -80px;
  width: 480px;
  height: 480px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(201,168,76,0.07) 0%, transparent 65%);
  pointer-events: none;
}

.nav-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  color: var(--text-muted);
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  transition: color 0.2s;
  z-index: 1;
}

.nav-back:hover { color: var(--gold); }

.nav-back svg {
  width: 12px; height: 12px;
  stroke: currentColor; fill: none; stroke-width: 1.8;
}

.left-body {
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 1;
  z-index: 1;
  padding: 48px 0 24px;
}

.brand {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 64px;
}

.logo-box {
  width: 42px; height: 42px;
  border: 1px solid var(--gold);
  display: flex; align-items: center; justify-content: center;
  position: relative;
  flex-shrink: 0;
}

.logo-box::before {
  content: '';
  position: absolute;
  inset: 4px;
  border: 1px solid rgba(201,168,76,0.2);
}

.logo-box span {
  font-family: 'Cormorant Garamond', serif;
  font-size: 15px; font-weight: 700;
  color: var(--gold); letter-spacing: 1px;
  position: relative; z-index: 1;
}

.brand-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 17px; letter-spacing: 0.06em; color: var(--text); font-weight: 400;
}

.left-headline {
  font-family: 'Cormorant Garamond', serif;
  font-size: 46px;
  font-weight: 300;
  line-height: 1.12;
  color: var(--text);
  margin-bottom: 24px;
}

.left-headline em {
  font-style: italic;
  color: var(--gold-light);
}

.left-sub {
  font-size: 14px;
  color: var(--text-muted);
  line-height: 1.75;
  font-weight: 400;
  max-width: 340px;
  margin-bottom: 52px;
}

/* Role cards */
.role-label {
  font-size: 10px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 12px;
}

.roles {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.role-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px 14px;
  border: 1px solid rgba(255,255,255,0.10);
  cursor: pointer;
  transition: all 0.18s;
  user-select: none;
}

.role-card:hover { border-color: var(--border); background: var(--gold-dim); }
.role-card.active { border-color: var(--border); background: var(--gold-dim); }

.role-icon {
  width: 32px; height: 32px;
  border: 1px solid rgba(255,255,255,0.06);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; flex-shrink: 0;
  transition: border-color 0.18s;
}
.role-card.active .role-icon { border-color: var(--border); }

.role-info { flex: 1; }
.role-name { font-size: 13px; font-weight: 500; color: var(--text); margin-bottom: 2px; }
.role-desc { font-size: 11px; color: var(--text-muted); }

.role-pip {
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--gold);
  opacity: 0;
  transition: opacity 0.18s;
  flex-shrink: 0;
}
.role-card.active .role-pip { opacity: 1; }

/* ─── RIGHT PANEL ─── */
.panel-right {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 44px 60px;
  z-index: 1;
  animation: slideRight 0.8s 0.1s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes slideRight {
  from { opacity: 0; transform: translateX(24px); }
  to   { opacity: 1; transform: translateX(0); }
}

.panel-right::before {
  content: '';
  position: absolute;
  top: -180px; right: -180px;
  width: 500px; height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(201,168,76,0.04) 0%, transparent 65%);
  pointer-events: none;
}

.form-wrap {
  width: 100%;
  max-width: 390px;
}

.eyebrow {
  font-size: 10px;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.eyebrow::before {
  content: '';
  width: 22px; height: 1px;
  background: var(--gold);
}

.form-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 38px;
  font-weight: 400;
  line-height: 1.1;
  color: var(--text);
  margin-bottom: 8px;
}

.form-subtitle {
  font-size: 13px;
  color: var(--text-muted);
  font-weight: 400;
  margin-bottom: 42px;
}

/* Alert */
.alert {
  border-left: 2px solid var(--error);
  background: rgba(217,95,95,0.07);
  padding: 12px 16px;
  font-size: 13px;
  color: #f0a0a0;
  margin-bottom: 24px;
  line-height: 1.5;
}

/* Fields */
.field {
  margin-bottom: 18px;
}

.field-label {
  display: block;
  font-size: 10px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--text-muted);
  font-weight: 600;
  margin-bottom: 8px;
}

.field-input {
  width: 100%;
  padding: 14px 16px;
  background: #161616;
  border: 1px solid rgba(255,255,255,0.10);
  color: var(--text);
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 400;
  outline: none;
  transition: border-color 0.2s, background 0.2s;
  border-radius: 0;
  appearance: none;
}

.field-input::placeholder { color: #6a6460; }

.field-input:focus {
  border-color: rgba(201,168,76,0.4);
  background: rgba(201,168,76,0.03);
}

.field-hint {
  font-size: 11px;
  color: var(--text-muted);
  margin-top: 6px;
}

.field-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}

.field-row .field-label { margin-bottom: 0; }

.forgot-link {
  font-size: 11px;
  color: var(--text-muted);
  text-decoration: none;
  letter-spacing: 0.04em;
  transition: color 0.2s;
}
.forgot-link:hover { color: var(--gold); }

.field-row-wrap {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

/* Submit */
.btn-primary {
  width: 100%;
  padding: 15px;
  background: var(--gold);
  border: none;
  color: #060606;
  font-family: 'DM Sans', sans-serif;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  cursor: pointer;
  transition: all 0.25s;
  margin-top: 10px;
  display: block;
}

.btn-primary:hover {
  background: var(--gold-light);
  transform: translateY(-1px);
  box-shadow: 0 8px 32px rgba(201,168,76,0.22);
}

.form-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 28px;
  padding-top: 22px;
  border-top: 1px solid var(--border-soft);
}

.footer-text { font-size: 12px; color: var(--text-muted); }

.footer-link {
  font-size: 12px;
  color: var(--gold);
  text-decoration: none;
  letter-spacing: 0.04em;
  transition: color 0.2s;
}
.footer-link:hover { color: var(--gold-light); }

/* Responsive */
@media (max-width: 860px) {
  body { grid-template-columns: 1fr; overflow: auto; }
  .panel-left { padding: 32px 28px; }
  .panel-left::after { display: none; }
  .left-body { padding: 28px 0; flex: none; }
  .left-headline { font-size: 34px; }
  .panel-right { padding: 36px 28px 60px; align-items: flex-start; }
  .form-wrap { max-width: 100%; }
}
</style>
</head>
<body>

<!-- ─── LEFT ─── -->
<div class="panel-left">
  <a href="/" class="nav-back">
    <svg viewBox="0 0 16 16"><path d="M10 3L5 8l5 5"/></svg>
    Início
  </a>

  <div class="left-body">
    <div class="brand">
      <div class="logo-box"><span>SJ</span></div>
      <span class="brand-name">Sistema Jurídico</span>
    </div>

    <h1 class="left-headline">
      Bem-vindo<br>de <em>volta.</em>
    </h1>

    <p class="left-sub">
      Acesse sua conta e retome o controle completo do seu escritório.
      Processos, prazos e clientes — tudo no lugar certo.
    </p>

    <div class="role-label">Acessar como</div>
    <div class="roles" id="roleGrid">
      <div class="role-card active" data-role="advogado">
        <div class="role-icon">⚖️</div>
        <div class="role-info">
          <div class="role-name">Advogado</div>
          <div class="role-desc">Gestão completa do escritório</div>
        </div>
        <div class="role-pip"></div>
      </div>
      <div class="role-card" data-role="admin">
        <div class="role-icon">🛡️</div>
        <div class="role-info">
          <div class="role-name">Administrador</div>
          <div class="role-desc">Controle e auditoria do sistema</div>
        </div>
        <div class="role-pip"></div>
      </div>
      <div class="role-card" data-role="cliente">
        <div class="role-icon">👤</div>
        <div class="role-info">
          <div class="role-name">Cliente</div>
          <div class="role-desc">Portal de acompanhamento</div>
        </div>
        <div class="role-pip"></div>
      </div>
    </div>
  </div>
</div>

<!-- ─── RIGHT ─── -->
<div class="panel-right">
  <div class="form-wrap">

    <div class="eyebrow">Autenticação</div>
    <h2 class="form-title">Acessar<br>conta</h2>
    <p class="form-subtitle">Insira suas credenciais para continuar</p>

    <?php if(isset($erro)): ?>
      <div class="alert"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login" novalidate>
      <?= Csrf::field() ?>

      <input type="hidden" name="perfil_acesso" id="perfilInput" value="advogado">

      <div class="field">
        <label class="field-label" for="email">Endereço de e-mail</label>
        <input
          id="email" name="email" type="email"
          class="field-input"
          placeholder="seu@escritorio.com.br"
          autocomplete="email"
          required
        >
      </div>

      <div class="field">
        <div class="field-row-wrap">
          <label class="field-label" for="senha">Senha</label>
          <a href="/esqueci-senha" class="forgot-link">Esqueci minha senha</a>
        </div>
        <input
          id="senha" name="senha" type="password"
          class="field-input"
          placeholder="••••••••••"
          autocomplete="current-password"
          required
        >
        <p class="field-hint">Cliente: use e-mail e senha definidos pelo advogado.</p>
      </div>

      <button type="submit" class="btn-primary">Entrar na plataforma</button>
    </form>

    <div class="form-footer">
      <span class="footer-text">Novo no sistema?</span>
      <a href="/cadastrar-advogado" class="footer-link">Criar conta de advogado →</a>
    </div>

  </div>
</div>

<script>
(function () {
  const cards = document.querySelectorAll('.role-card');
  const input = document.getElementById('perfilInput');

  function activate(role) {
    cards.forEach(c => c.classList.toggle('active', c.dataset.role === role));
    input.value = role;
  }

  cards.forEach(c => c.addEventListener('click', () => activate(c.dataset.role)));
})();
</script>
</body>
</html>