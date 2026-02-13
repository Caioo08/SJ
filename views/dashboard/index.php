<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0a0a0a;
    --bg-secondary: #121212;
    --card: #1a1a1a;
    --card-hover: #222222;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --accent-hover: #e5c04c;
    --muted: #bfb39a;
    --muted-dark: #8a8577;
    --border: rgba(255,255,255,0.08);
    --shadow: 0 4px 20px rgba(0,0,0,0.4);
    --success: #4ade80;
    --info: #60a5fa;
    --danger: #f87171;
}


* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--primary);
    line-height: 1.6;
}

/* Sidebar */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100vh;
    background: var(--card);
    border-right: 1px solid var(--border);
    padding: 24px 0;
    overflow-y: auto;
    z-index: 100;
}

.logo-section {
    padding: 0 24px 24px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 24px;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo {
    width: 42px;
    height: 42px;
    border-radius: 8px;
    background: linear-gradient(135deg, #b8860b, #f1c65b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0b0b0b;
    font-weight: 800;
    font-size: 18px;
}

.logo-text {
    font-size: 18px;
    font-weight: 700;
    color: var(--accent);
}

.nav-menu {
    list-style: none;
    padding: 0 12px;
}

.nav-item {
    margin-bottom: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: var(--muted);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 500;
}

.nav-link:hover, .nav-link.active {
    background: var(--bg-secondary);
    color: var(--accent);
}

.nav-link.active {
    background: rgba(212, 175, 55, 0.1);
}

/* Main Content */
.main-content {
    margin-left: 260px;
    padding: 24px;
    min-height: 100vh;
}

/* Header */
.header {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.welcome {
    font-size: 28px;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 4px;
}

.date-time {
    color: var(--muted);
    font-size: 14px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #b8860b, #f1c65b);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0b0b0b;
    font-weight: 700;
    font-size: 18px;
}

.user-details h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
}

.user-details p {
    font-size: 12px;
    color: var(--muted);
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 13px;
    color: var(--muted);
    font-weight: 500;
}

/* Main Grid Layout */
.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
}

/* Calendar Section */
.calendar-section {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    height: fit-content;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--accent);
}

.calendar {
    width: 100%;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.calendar-nav {
    background: none;
    border: 1px solid var(--border);
    color: var(--muted);
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.calendar-nav:hover {
    background: var(--bg-secondary);
    color: var(--accent);
}

.calendar-month {
    font-size: 16px;
    font-weight: 600;
    color: var(--primary);
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-bottom: 8px;
}

.weekday {
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    padding: 8px 0;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: var(--muted);
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.calendar-day:hover {
    background: var(--bg-secondary);
}

.calendar-day.other-month {
    color: var(--muted-dark);
}

.calendar-day.today {
    background: rgba(212, 175, 55, 0.15);
    color: var(--accent);
    font-weight: 700;
}

.calendar-day.has-event {
    background: var(--accent);
    color: #0b0b0b;
    font-weight: 700;
}

.calendar-day.has-event::after {
    content: '';
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #0b0b0b;
}

.calendar-day.has-deadline {
    box-shadow: inset 0 0 0 2px rgba(239, 68, 68, 0.7);
}

.calendar-day.has-deadline::before {
    content: '⚠';
    position: absolute;
    top: 2px;
    right: 4px;
    color: #f87171;
    font-size: 10px;
}

/* Appointments Section */
.appointments-section {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.appointments-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.appointment-card {
    background: var(--bg-secondary);
    border-radius: 8px;
    padding: 16px;
    border-left: 4px solid var(--accent);
    transition: all 0.2s;
}

.appointment-card:hover {
    background: var(--card-hover);
    transform: translateX(4px);
}

.appointment-time {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--accent);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

.appointment-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 4px;
}

.appointment-location {
    font-size: 13px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--muted);
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
}

/* Personal Info Section */
.info-section {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    margin-bottom: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 12px;
    color: var(--muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 15px;
    color: var(--primary);
    font-weight: 600;
}

/* Responsive */
@media (max-width: 1200px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .calendar-section {
        max-width: 600px;
    }
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg);
}

::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--accent);
}
</style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo-section">
        <div class="logo-container">
            <div class="logo">SJ</div>
            <span class="logo-text">Sistema Jurídico</span>
        </div>
    </div>
    
    <nav>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link active">
                    <span>📊</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/processos" class="nav-link">
                    <span>⚖️</span> Processos
                </a>
            </li>
            <li class="nav-item">
                <a href="/clientes" class="nav-link">
                    <span>👥</span> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a href="/compromissos" class="nav-link">
                    <span>📅</span> Compromissos
                </a>
            </li>
            <li class="nav-item">
                <a href="/prazos" class="nav-link">
                    <span>⏳</span> Prazos
                </a>
            </li>
            <li class="nav-item">
                <a href="/documentos" class="nav-link">
                    <span>📄</span> Documentos
                </a>
            </li>
            <li class="nav-item">
                <a href="/configuracoes" class="nav-link">
                    <span>⚙️</span> Configurações
                </a>
            </li>
            <li class="nav-item">
                <a href="/logout" class="nav-link">
                    <span>🚪</span> Sair
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- Main Content -->
<main class="main-content">
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div>
                <h1 class="welcome">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h1>
                <p class="date-time" id="currentDateTime"></p>
            </div>
            <div class="user-info">
                <div class="user-avatar"><?= strtoupper(substr($_SESSION['usuario_nome'], 0, 2)) ?></div>
                <div class="user-details">
                    <h3><?= htmlspecialchars($usuario['nome']) ?></h3>
                    <p>OAB <?= htmlspecialchars($usuario['oab']) ?>/<?= htmlspecialchars($usuario['uf']) ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $total_processos ?></div>
                    <div class="stat-label">Total de Processos</div>
                </div>
                <div class="stat-icon">📋</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $processos_abertos ?></div>
                    <div class="stat-label">Processos Abertos</div>
                </div>
                <div class="stat-icon">⚖️</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= count($compromissos) ?></div>
                    <div class="stat-label">Próximos Compromissos</div>
                </div>
                <div class="stat-icon">📅</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $total_prazos_abertos ?></div>
                    <div class="stat-label">Prazos Abertos</div>
                </div>
                <div class="stat-icon">🚨</div>
            </div>
        </div>
    </div>

    <!-- Personal Info -->
    <section class="info-section">
        <div class="section-header">
            <h2 class="section-title">Informações Pessoais</h2>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nome Completo</span>
                <span class="info-value"><?= htmlspecialchars($usuario['nome']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value"><?= htmlspecialchars($usuario['email']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">OAB</span>
                <span class="info-value"><?= htmlspecialchars($usuario['oab']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">UF</span>
                <span class="info-value"><?= htmlspecialchars($usuario['uf']) ?></span>
            </div>
        </div>
    </section>

    <section class="info-section">
        <div class="section-header">
            <h2 class="section-title">Alertas de Prazo (D-7, D-3, D-1 e vencidos)</h2>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
            <span style="padding:4px 10px;border-radius:999px;background:rgba(74,158,255,.2);color:#4a9eff;font-size:12px;font-weight:700;">D-7: <?= (int)($alertas_prazos['d7'] ?? 0) ?></span>
            <span style="padding:4px 10px;border-radius:999px;background:rgba(212,175,55,.2);color:#d4af37;font-size:12px;font-weight:700;">D-3: <?= (int)($alertas_prazos['d3'] ?? 0) ?></span>
            <span style="padding:4px 10px;border-radius:999px;background:rgba(249,115,22,.2);color:#fb923c;font-size:12px;font-weight:700;">D-1: <?= (int)($alertas_prazos['d1'] ?? 0) ?></span>
            <span style="padding:4px 10px;border-radius:999px;background:rgba(239,68,68,.2);color:#ef4444;font-size:12px;font-weight:700;">Vencidos: <?= (int)($alertas_prazos['vencidos'] ?? 0) ?></span>
        </div>
        <?php if(empty($prazos_criticos)): ?>
            <div class="empty-state" style="padding:20px;">✅ Nenhum prazo crítico nos próximos 7 dias.</div>
        <?php else: ?>
            <div class="appointments-list">
                <?php foreach($prazos_criticos as $p): ?>
                    <div class="appointment-card" style="border-left-color: var(--danger);">
                        <div class="appointment-time">⏰ <?= date('d/m/Y H:i', strtotime($p['data_limite'])) ?></div>
                        <div class="appointment-title"><?= htmlspecialchars($p['titulo']) ?></div>
                        <div class="appointment-location">Prioridade: <?= strtoupper(htmlspecialchars($p['prioridade'])) ?> · Alerta: <?= htmlspecialchars($p['faixa_alerta']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
        <!-- Appointments -->
        <section class="appointments-section">
            <div class="section-header">
                <h2 class="section-title">Próximos Compromissos (7 dias)</h2>
            </div>
            <div class="appointments-list">
                <?php if(empty($compromissos)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📅</div>
                        <p>Nenhum compromisso nos próximos 7 dias</p>
                    </div>
                <?php else: ?>
                    <?php foreach($compromissos as $c): ?>
                        <div class="appointment-card">
                            <div class="appointment-time">
                                <span>🕐</span>
                                <?= date('d/m/Y', strtotime($c['data_inicio'])) ?> às <?= date('H:i', strtotime($c['data_inicio'])) ?>
                            </div>
                            <div class="appointment-title"><?= htmlspecialchars($c['titulo']) ?></div>
                            <?php if($c['local']): ?>
                                <div class="appointment-location">
                                    <span>📍</span>
                                    <?= htmlspecialchars($c['local']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Calendar -->
        <section class="calendar-section">
            <div class="section-header">
                <h2 class="section-title">Calendário</h2>
                <div style="display:flex;gap:10px;align-items:center;font-size:12px;color:var(--muted);">
                    <span style="display:inline-flex;align-items:center;gap:4px;"><span style="color:var(--accent);font-size:16px;">•</span>Compromissos</span>
                    <span style="display:inline-flex;align-items:center;gap:4px;"><span style="color:#f87171;">⚠</span>Prazos</span>
                </div>
            </div>
            <div class="calendar">
                <div class="calendar-header">
                    <button class="calendar-nav" onclick="previousMonth()">◀</button>
                    <span class="calendar-month" id="calendarMonth"></span>
                    <button class="calendar-nav" onclick="nextMonth()">▶</button>
                </div>
                <div class="calendar-weekdays">
                    <div class="weekday">Dom</div>
                    <div class="weekday">Seg</div>
                    <div class="weekday">Ter</div>
                    <div class="weekday">Qua</div>
                    <div class="weekday">Qui</div>
                    <div class="weekday">Sex</div>
                    <div class="weekday">Sáb</div>
                </div>
                <div class="calendar-days" id="calendarDays"></div>
            </div>
        </section>
    </div>
</main>

<script>
// Update date and time
function updateDateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    document.getElementById('currentDateTime').textContent = now.toLocaleDateString('pt-BR', options);
}
updateDateTime();
setInterval(updateDateTime, 60000);

// Calendar functionality
const compromissos = <?= json_encode(array_values(array_unique(array_map(function($c) {
    return date('Y-m-d', strtotime($c['data_inicio']));
}, $compromissos)))) ?>;
const prazos = <?= json_encode(array_values(array_unique(array_map(function($p) {
    return date('Y-m-d', strtotime($p['data_limite']));
}, $prazos_calendario)))) ?>;

let currentDate = new Date();

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                       'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    
    document.getElementById('calendarMonth').textContent = `${monthNames[month]} ${year}`;
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const calendarDays = document.getElementById('calendarDays');
    calendarDays.innerHTML = '';
    
    const today = new Date();
    const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    
    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day other-month';
        dayEl.textContent = day;
        calendarDays.appendChild(dayEl);
    }
    
    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day';
        dayEl.textContent = day;
        
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        if (dateStr === todayStr) {
            dayEl.classList.add('today');
        }
        
        if (compromissos.includes(dateStr)) {
            dayEl.classList.add('has-event');
        }

        if (prazos.includes(dateStr)) {
            dayEl.classList.add('has-deadline');
        }
        
        calendarDays.appendChild(dayEl);
    }
    
    // Next month days
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells; // 6 rows * 7 days
    for (let day = 1; day <= remainingCells; day++) {
        const dayEl = document.createElement('div');
        dayEl.className = 'calendar-day other-month';
        dayEl.textContent = day;
        calendarDays.appendChild(dayEl);
    }
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

renderCalendar();
</script>

</body>
</html>