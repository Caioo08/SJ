<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Advogado — Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #080808;
  --bg-2: #131313;
  --bg-3: #141414;
  --gold: #c9a84c;
  --gold-light: #e2c97e;
  --gold-dim: rgba(201,168,76,0.10);
  --text: #f0ece3;
  --text-muted: #b8b0a4;
  --text-dim: #8a8278;
  --border: rgba(201,168,76,0.18);
  --border-soft: rgba(255,255,255,0.05);
  --error: #d95f5f;
  --success: #5aaa80;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  display: grid;
  grid-template-columns: 340px 1fr;
}

/* Noise */
body::before {
  content: '';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  pointer-events: none; z-index: 0; opacity: 0.4;
}

/* ─── SIDEBAR ─── */
.sidebar {
  position: sticky;
  top: 0;
  height: 100vh;
  background: var(--bg-2);
  border-right: 1px solid var(--border-soft);
  padding: 40px 36px;
  display: flex;
  flex-direction: column;
  z-index: 10;
  overflow: hidden;
  animation: sideIn 0.7s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes sideIn {
  from { opacity: 0; transform: translateX(-20px); }
  to   { opacity: 1; transform: translateX(0); }
}

.sidebar::before {
  content: '';
  position: absolute;
  bottom: -60px; left: -60px;
  width: 360px; height: 360px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(201,168,76,0.06) 0%, transparent 65%);
  pointer-events: none;
}

.sidebar::after {
  content: '';
  position: absolute;
  right: -1px; top: 25%;
  width: 1px; height: 100px;
  background: linear-gradient(to bottom, transparent, var(--gold), transparent);
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
  margin-bottom: auto;
}
.nav-back:hover { color: var(--gold); }
.nav-back svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 1.8; }

.sidebar-body {
  padding: 40px 0 0;
  z-index: 1;
}

.brand {
  display: flex; align-items: center; gap: 12px;
  margin-bottom: 52px;
}

.logo-box {
  width: 40px; height: 40px;
  border: 1px solid var(--gold);
  display: flex; align-items: center; justify-content: center;
  position: relative; flex-shrink: 0;
}
.logo-box::before {
  content: '';
  position: absolute; inset: 4px;
  border: 1px solid rgba(201,168,76,0.2);
}
.logo-box span {
  font-family: 'Cormorant Garamond', serif;
  font-size: 14px; font-weight: 700;
  color: var(--gold); letter-spacing: 1px;
  position: relative; z-index: 1;
}
.brand-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 16px; letter-spacing: 0.06em; color: var(--text);
}

.sidebar-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 30px; font-weight: 300;
  line-height: 1.2; color: var(--text);
  margin-bottom: 16px;
}
.sidebar-title em { font-style: italic; color: var(--gold-light); }

.sidebar-desc {
  font-size: 13px; font-weight: 400;
  color: var(--text-muted); line-height: 1.7;
  margin-bottom: 44px;
}

/* Progress steps */
.steps-label {
  font-size: 10px; letter-spacing: 0.16em;
  text-transform: uppercase; color: var(--text-muted);
  margin-bottom: 16px;
}

.steps {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.step {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 12px 0;
  position: relative;
}

.step:not(:last-child)::after {
  content: '';
  position: absolute;
  left: 12px; top: 38px;
  width: 1px; height: calc(100% - 12px);
  background: var(--border-soft);
}

.step.done:not(:last-child)::after { background: rgba(201,168,76,0.3); }
.step.active:not(:last-child)::after { background: var(--border-soft); }

.step-dot {
  width: 24px; height: 24px;
  border: 1px solid rgba(255,255,255,0.10);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  font-size: 10px;
  color: var(--text-muted);
  font-weight: 500;
  transition: all 0.25s;
  background: var(--bg);
  position: relative; z-index: 1;
}

.step.active .step-dot {
  border-color: var(--gold);
  color: var(--gold);
  box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
}

.step.done .step-dot {
  border-color: rgba(201,168,76,0.5);
  background: var(--gold-dim);
  color: var(--gold);
}

.step-text {}
.step-name {
  font-size: 13px; font-weight: 500;
  color: var(--text-muted);
  margin-bottom: 2px;
  transition: color 0.2s;
}
.step.active .step-name { color: var(--text); }
.step.done .step-name { color: var(--text-muted); }
.step-sub { font-size: 11px; color: var(--text-muted); }

/* Login footer */
.sidebar-footer {
  margin-top: auto;
  padding-top: 32px;
  border-top: 1px solid var(--border-soft);
  z-index: 1;
}

.sidebar-footer p { font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
.sidebar-footer a {
  font-size: 12px; color: var(--gold);
  text-decoration: none; letter-spacing: 0.04em;
  transition: color 0.2s;
}
.sidebar-footer a:hover { color: var(--gold-light); }

/* ─── MAIN CONTENT ─── */
.main {
  position: relative;
  padding: 60px 72px 80px;
  z-index: 1;
  animation: mainIn 0.7s 0.15s cubic-bezier(0.22,1,0.36,1) both;
  overflow-y: auto;
}

@keyframes mainIn {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

.main::before {
  content: '';
  position: fixed;
  top: -120px; right: -120px;
  width: 400px; height: 400px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(201,168,76,0.04) 0%, transparent 65%);
  pointer-events: none;
}

/* Section header */
.section-header {
  margin-bottom: 44px;
  padding-bottom: 28px;
  border-bottom: 1px solid var(--border-soft);
  position: relative;
}

.section-header::after {
  content: '';
  position: absolute;
  bottom: -1px; left: 0;
  width: 60px; height: 1px;
  background: var(--gold);
}

.eyebrow {
  font-size: 10px; letter-spacing: 0.22em;
  text-transform: uppercase; color: var(--gold);
  margin-bottom: 12px;
  display: flex; align-items: center; gap: 10px;
}
.eyebrow::before { content: ''; width: 20px; height: 1px; background: var(--gold); }

.section-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 40px; font-weight: 400;
  color: var(--text); line-height: 1.1;
}

/* Alert / success */
.alert {
  border-left: 2px solid var(--error);
  background: rgba(217,95,95,0.07);
  padding: 12px 16px;
  font-size: 13px; color: #f0a0a0;
  margin-bottom: 32px; line-height: 1.5;
}

.alert.success {
  border-left-color: var(--success);
  background: rgba(90,170,128,0.07);
  color: #90d4b0;
}

/* Form grid */
.form-section {
  margin-bottom: 48px;
}

.form-section-label {
  font-size: 10px; letter-spacing: 0.16em;
  text-transform: uppercase; color: var(--text-muted);
  margin-bottom: 20px;
  display: flex; align-items: center; gap: 14px;
}
.form-section-label::after {
  content: ''; flex: 1; height: 1px;
  background: var(--border-soft);
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px 20px;
}

.form-grid.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.form-grid.col-full { grid-template-columns: 1fr; }

.field { display: flex; flex-direction: column; }
.field.span-2 { grid-column: span 2; }
.field.span-3 { grid-column: span 3; }

.field-label {
  font-size: 10px; letter-spacing: 0.14em;
  text-transform: uppercase; color: var(--text-muted);
  font-weight: 600; margin-bottom: 8px;
}

.field-input {
  width: 100%;
  padding: 13px 16px;
  background: #161616;
  border: 1px solid rgba(255,255,255,0.10);
  color: var(--text);
  font-family: 'DM Sans', sans-serif;
  font-size: 14px; font-weight: 400;
  outline: none;
  transition: border-color 0.2s, background 0.2s;
  border-radius: 0; appearance: none;
}
.field-input::placeholder { color: #6a6460; }
.field-input:focus {
  border-color: rgba(201,168,76,0.4);
  background: rgba(201,168,76,0.03);
}

select.field-input {
  cursor: pointer;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23555047' fill='none' stroke-width='1.5'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
  padding-right: 34px;
}
select.field-input option { background: #181818; color: var(--text); }

.field-hint { font-size: 11px; color: var(--text-muted); margin-top: 6px; line-height: 1.5; }

/* Password strength */
.pwd-meter {
  display: flex; gap: 4px;
  margin-top: 10px;
}
.pwd-bar {
  flex: 1; height: 2px;
  background: var(--border-soft);
  border-radius: 1px;
  transition: background 0.3s;
}
.pwd-bar.weak   { background: #d95f5f; }
.pwd-bar.medium { background: #c9a84c; }
.pwd-bar.strong { background: #5aaa80; }

.pwd-text { font-size: 11px; color: var(--text-muted); margin-top: 6px; }

/* Terms */
.terms-box {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 16px;
  border: 1px solid rgba(255,255,255,0.10);
  margin-bottom: 28px;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
}
.terms-box:hover { border-color: var(--border); background: var(--gold-dim); }
.terms-box.checked { border-color: var(--border); background: var(--gold-dim); }

.terms-check {
  width: 18px; height: 18px; flex-shrink: 0;
  border: 1px solid rgba(255,255,255,0.10);
  display: flex; align-items: center; justify-content: center;
  margin-top: 1px;
  transition: all 0.18s;
}
.terms-box.checked .terms-check {
  border-color: var(--gold);
  background: var(--gold);
}
.terms-check svg { display: none; }
.terms-box.checked .terms-check svg { display: block; }

.terms-text { font-size: 13px; color: var(--text-muted); font-weight: 400; line-height: 1.6; }
.terms-text a { color: var(--gold); text-decoration: none; }
.terms-text a:hover { color: var(--gold-light); }

/* Submit */
.form-actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-primary {
  padding: 15px 48px;
  background: var(--gold);
  border: none;
  color: #060606;
  font-family: 'DM Sans', sans-serif;
  font-size: 11px; font-weight: 600;
  letter-spacing: 0.16em; text-transform: uppercase;
  cursor: pointer;
  transition: all 0.25s;
}
.btn-primary:hover {
  background: var(--gold-light);
  transform: translateY(-1px);
  box-shadow: 0 8px 32px rgba(201,168,76,0.22);
}
.btn-primary:disabled {
  opacity: 0.45; cursor: not-allowed; transform: none; box-shadow: none;
}

.btn-secondary {
  padding: 15px 28px;
  background: transparent;
  border: 1px solid rgba(255,255,255,0.10);
  color: var(--text-muted);
  font-family: 'DM Sans', sans-serif;
  font-size: 11px; font-weight: 500;
  letter-spacing: 0.1em; text-transform: uppercase;
  cursor: pointer; text-decoration: none;
  display: inline-flex; align-items: center;
  transition: all 0.2s;
}
.btn-secondary:hover { border-color: var(--border); color: var(--text); }

/* Responsive */
@media (max-width: 1100px) {
  body { grid-template-columns: 1fr; }
  .sidebar { position: relative; height: auto; }
  .sidebar-body { padding: 24px 0; }
  .main { padding: 40px 32px 60px; }
  .form-grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 640px) {
  .main { padding: 28px 20px 60px; }
  .form-grid { grid-template-columns: 1fr; }
  .field.span-2, .field.span-3 { grid-column: span 1; }
  .form-grid.cols-3 { grid-template-columns: 1fr; }
  .form-actions { flex-direction: column-reverse; align-items: stretch; text-align: center; }
  .btn-primary { padding: 15px; }
}
</style>
</head>
<body>

<!-- ─── SIDEBAR ─── -->
<aside class="sidebar">
  <a href="/login" class="nav-back">
    <svg viewBox="0 0 16 16"><path d="M10 3L5 8l5 5"/></svg>
    Entrar
  </a>

  <div class="sidebar-body">
    <div class="brand">
      <div class="logo-box"><span>SJ</span></div>
      <span class="brand-name">Sistema Jurídico</span>
    </div>

    <h2 class="sidebar-title">
      Crie sua<br><em>conta.</em>
    </h2>

    <p class="sidebar-desc">
      Preencha os dados para registrar seu escritório e começar a usar 
      a plataforma completa de gestão jurídica.
    </p>

    <div class="steps-label">Etapas do cadastro</div>
    <div class="steps" id="stepsNav">
      <div class="step active" data-step="1">
        <div class="step-dot">1</div>
        <div class="step-text">
          <div class="step-name">Dados pessoais</div>
          <div class="step-sub">Nome, CPF, contato</div>
        </div>
      </div>
      <div class="step" data-step="2">
        <div class="step-dot">2</div>
        <div class="step-text">
          <div class="step-name">Credenciais OAB</div>
          <div class="step-sub">Número e seccional</div>
        </div>
      </div>
      <div class="step" data-step="3">
        <div class="step-dot">3</div>
        <div class="step-text">
          <div class="step-name">Acesso ao sistema</div>
          <div class="step-sub">E-mail e senha</div>
        </div>
      </div>
      <div class="step" data-step="4">
        <div class="step-dot">4</div>
        <div class="step-text">
          <div class="step-name">Confirmação</div>
          <div class="step-sub">Revisar e finalizar</div>
        </div>
      </div>
    </div>
  </div>

  <div class="sidebar-footer">
    <p>Já tem uma conta?</p>
    <a href="/login">Entrar na plataforma →</a>
  </div>
</aside>

<!-- ─── MAIN ─── -->
<main class="main">

  <div class="section-header">
    <div class="eyebrow">Novo advogado</div>
    <h1 class="section-title">Cadastro</h1>
  </div>

  <?php if(isset($erro)): ?>
    <div class="alert"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <?php if(isset($sucesso)): ?>
    <div class="alert success"><?= htmlspecialchars($sucesso) ?></div>
  <?php endif; ?>

  <form method="POST" action="/register" id="regForm" novalidate>
    <?= Csrf::field() ?>

    <!-- DADOS PESSOAIS -->
    <div class="form-section">
      <div class="form-section-label">Dados pessoais</div>
      <div class="form-grid">

        <div class="field span-2">
          <label class="field-label" for="nome_completo">Nome completo</label>
          <input id="nome_completo" name="nome_completo" type="text" class="field-input"
            placeholder="Dr. João da Silva" autocomplete="name" required>
        </div>

        <div class="field">
          <label class="field-label" for="cpf">CPF</label>
          <input id="cpf" name="cpf" type="text" class="field-input"
            placeholder="000.000.000-00" maxlength="14" required>
        </div>

        <div class="field">
          <label class="field-label" for="data_nascimento">Data de nascimento</label>
          <input id="data_nascimento" name="data_nascimento" type="date" class="field-input" required>
        </div>

        <div class="field">
          <label class="field-label" for="telefone">Telefone / WhatsApp</label>
          <input id="telefone" name="telefone" type="tel" class="field-input"
            placeholder="(00) 00000-0000" maxlength="15">
        </div>

        <div class="field">
          <label class="field-label" for="genero">Gênero</label>
          <select id="genero" name="genero" class="field-input">
            <option value="">Prefiro não informar</option>
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
            <option value="O">Outro</option>
          </select>
        </div>

      </div>
    </div>

    <!-- OAB -->
    <div class="form-section">
      <div class="form-section-label">Credenciais OAB</div>
      <div class="form-grid cols-3">

        <div class="field">
          <label class="field-label" for="oab_numero">Número OAB</label>
          <input id="oab_numero" name="oab_numero" type="text" class="field-input"
            placeholder="000000" required>
        </div>

        <div class="field">
          <label class="field-label" for="oab_seccional">Seccional (UF)</label>
          <select id="oab_seccional" name="oab_seccional" class="field-input" required>
            <option value="">Selecione</option>
            <?php foreach(['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'] as $uf): ?>
              <option value="<?= $uf ?>"><?= $uf ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label class="field-label" for="area_atuacao">Área de atuação</label>
          <select id="area_atuacao" name="area_atuacao" class="field-input">
            <option value="">Selecione</option>
            <option value="civil">Direito Civil</option>
            <option value="trabalhista">Direito Trabalhista</option>
            <option value="criminal">Direito Criminal</option>
            <option value="empresarial">Direito Empresarial</option>
            <option value="tributario">Direito Tributário</option>
            <option value="familia">Direito de Família</option>
            <option value="previdenciario">Previdenciário</option>
            <option value="imobiliario">Direito Imobiliário</option>
            <option value="outro">Outro</option>
          </select>
        </div>

        <div class="field span-3">
          <label class="field-label" for="escritorio">Nome do escritório <span style="color:var(--text-muted);font-size:10px;text-transform:none;letter-spacing:0;">(opcional)</span></label>
          <input id="escritorio" name="escritorio" type="text" class="field-input"
            placeholder="Silva & Associados Advocacia">
        </div>

      </div>
    </div>

    <!-- ACESSO -->
    <div class="form-section">
      <div class="form-section-label">Acesso ao sistema</div>
      <div class="form-grid">

        <div class="field span-2">
          <label class="field-label" for="email">E-mail de acesso</label>
          <input id="email" name="email" type="email" class="field-input"
            placeholder="joao@silva.adv.br" autocomplete="email" required>
          <p class="field-hint">Será usado para login e notificações do sistema.</p>
        </div>

        <div class="field">
          <label class="field-label" for="senha">Senha</label>
          <input id="senha" name="senha" type="password" class="field-input"
            placeholder="••••••••••" autocomplete="new-password" required>
          <div class="pwd-meter" id="pwdMeter">
            <div class="pwd-bar" id="b1"></div>
            <div class="pwd-bar" id="b2"></div>
            <div class="pwd-bar" id="b3"></div>
            <div class="pwd-bar" id="b4"></div>
          </div>
          <p class="pwd-text" id="pwdText">Mínimo 8 caracteres</p>
        </div>

        <div class="field">
          <label class="field-label" for="senha_confirm">Confirmar senha</label>
          <input id="senha_confirm" name="senha_confirm" type="password" class="field-input"
            placeholder="••••••••••" autocomplete="new-password" required>
          <p class="field-hint" id="confirmHint" style="display:none;">As senhas não conferem.</p>
        </div>

      </div>
    </div>

    <!-- TERMS -->
    <div class="terms-box" id="termsBox">
      <div class="terms-check">
        <svg width="11" height="9" viewBox="0 0 11 9" fill="none">
          <path d="M1 4.5L4 7.5L10 1" stroke="#060606" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <p class="terms-text">
        Concordo com os <a href="/termos" target="_blank">Termos de Uso</a> e
        <a href="/privacidade" target="_blank">Política de Privacidade</a> do Sistema Jurídico.
        Confirmo que sou um advogado regularmente inscrito na OAB.
      </p>
      <input type="checkbox" name="termos" id="termosInput" style="display:none;" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-primary" id="submitBtn" disabled>
        Criar conta
      </button>
      <a href="/login" class="btn-secondary">Cancelar</a>
    </div>

  </form>
</main>

<script>
(function () {
  /* ── Password strength ── */
  const senhaEl = document.getElementById('senha');
  const confirmEl = document.getElementById('senha_confirm');
  const bars = [document.getElementById('b1'), document.getElementById('b2'),
                document.getElementById('b3'), document.getElementById('b4')];
  const pwdText = document.getElementById('pwdText');
  const confirmHint = document.getElementById('confirmHint');
  const submitBtn = document.getElementById('submitBtn');
  const termsBox = document.getElementById('termsBox');
  const termosInput = document.getElementById('termosInput');

  function getPwdStrength(v) {
    let score = 0;
    if (v.length >= 8) score++;
    if (v.length >= 12) score++;
    if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v) || /\d/.test(v)) score++;
    return Math.min(score, 4);
  }

  function updateStrength() {
    const v = senhaEl.value;
    const str = getPwdStrength(v);
    const labels = ['', 'Fraca', 'Razoável', 'Boa', 'Forte'];
    const classes = ['', 'weak', 'medium', 'strong', 'strong'];

    bars.forEach((b, i) => {
      b.className = 'pwd-bar';
      if (v.length > 0 && i < str) b.classList.add(classes[str]);
    });

    pwdText.textContent = v.length === 0 ? 'Mínimo 8 caracteres' : labels[str] || 'Muito fraca';
    validate();
  }

  function validate() {
    const pwdOk = senhaEl.value.length >= 8;
    const match = senhaEl.value === confirmEl.value;
    const termsOk = termosInput.checked;

    confirmHint.style.display = (confirmEl.value.length > 0 && !match) ? 'block' : 'none';
    submitBtn.disabled = !(pwdOk && match && termsOk);
  }

  senhaEl.addEventListener('input', updateStrength);
  confirmEl.addEventListener('input', validate);

  /* ── Terms toggle ── */
  termsBox.addEventListener('click', function () {
    const checked = !termosInput.checked;
    termosInput.checked = checked;
    termsBox.classList.toggle('checked', checked);
    validate();
  });

  /* ── CPF mask ── */
  document.getElementById('cpf').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').slice(0, 11);
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    this.value = v;
  });

  /* ── Phone mask ── */
  document.getElementById('telefone').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').slice(0, 11);
    if (v.length >= 7) v = '(' + v.slice(0,2) + ') ' + v.slice(2,7) + (v.length > 7 ? '-' + v.slice(7) : '');
    else if (v.length >= 3) v = '(' + v.slice(0,2) + ') ' + v.slice(2);
    this.value = v;
  });

  /* ── Sidebar step highlight on scroll ── */
  const sections = document.querySelectorAll('.form-section');
  const stepEls = document.querySelectorAll('.step');

  function markStep(idx) {
    stepEls.forEach((s, i) => {
      s.classList.toggle('active', i === idx);
      s.classList.toggle('done', i < idx);
    });
  }

  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const idx = Array.from(sections).indexOf(e.target);
        if (idx !== -1) markStep(idx);
      }
    });
  }, { threshold: 0.5 });

  sections.forEach(s => obs.observe(s));
})();
</script>
</body>
</html>