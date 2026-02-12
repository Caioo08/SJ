<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Detalhes do Processo - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1a1a1a;
    --bg-secondary: #121212;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --muted: #bfb39a;
    --card-border: rgba(255,255,255,0.08);
    --shadow: 0 4px 20px rgba(0,0,0,0.6);
    --border: rgba(255,255,255,0.08);
}

* { box-sizing: border-box; font-family: 'Inter', sans-serif; }

body {
    margin:0;
    background: var(--bg);
    color: var(--primary);
    min-height:100vh;
    padding: 40px 20px;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
}

.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

h1 {
    color: var(--accent);
    margin: 0;
    font-size: 28px;
}

.btn-group {
    display: flex;
    gap: 12px;
}

.btn {
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.btn-primary {
    background: var(--accent);
    color: #0b0b0b;
}

.btn-secondary {
    background: #4a9eff;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn:hover {
    filter: brightness(0.95);
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.card h2 {
    color: var(--accent);
    font-size: 18px;
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-grid {
    display: grid;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 12px;
    color: var(--muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 15px;
    color: var(--primary);
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 16px;
    font-size: 13px;
    font-weight: 600;
    margin-top: 4px;
}

.status-aberto {
    background: rgba(212, 175, 55, 0.2);
    color: var(--accent);
}

.status-concluido {
    background: rgba(74, 222, 128, 0.2);
    color: #4ade80;
}

.status-arquivado {
    background: rgba(128, 128, 128, 0.2);
    color: var(--muted);
}

.descricao-box {
    background: var(--bg-secondary);
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--border);
    line-height: 1.6;
    color: var(--muted);
    margin-top: 8px;
}

.cliente-card {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid var(--border);
}

.cliente-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 16px;
}

.cliente-info {
    display: grid;
    gap: 12px;
}

.cliente-field {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.cliente-icon {
    font-size: 16px;
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.timeline-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: var(--bg-secondary);
    border-radius: 8px;
    border: 1px solid var(--border);
}

.timeline-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(212, 175, 55, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.timeline-content {
    flex: 1;
}

.timeline-title {
    font-weight: 600;
    color: var(--primary);
    font-size: 14px;
    margin-bottom: 4px;
}

.timeline-date {
    font-size: 12px;
    color: var(--muted);
}

@media (max-width: 968px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .header-actions {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .btn-group {
        width: 100%;
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="header-actions">
        <h1>⚖️ Detalhes do Processo</h1>
        <div class="btn-group">
            <a href="/processos" class="btn btn-primary">← Voltar</a>
            <a href="/processos/edit/<?= $processo['id'] ?>" class="btn btn-secondary">✏️ Editar</a>
            <a href="/processos/confirm-delete/<?= $processo['id'] ?>" 
               class="btn btn-danger" 
               onclick="return confirm('Tem certeza que deseja excluir este processo?')">
                🗑️ Excluir
            </a>
        </div>
    </div>

    <div class="content-grid">
        <!-- Coluna Principal -->
        <div>
            <!-- Informações do Processo -->
            <div class="card">
                <h2>📋 Informações do Processo</h2>
                
                <div class="info-grid">
                    <?php if($processo['numero_processo']): ?>
                    <div class="info-item">
                        <span class="info-label">Número do Processo</span>
                        <span class="info-value"><?= htmlspecialchars($processo['numero_processo']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="status-badge status-<?= $processo['status'] ?>">
                            <?= ucfirst($processo['status']) ?>
                        </span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Data de Criação</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($processo['criado_em'])) ?></span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Última Atualização</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($processo['atualizado_em'])) ?></span>
                    </div>

                    <?php if($processo['descricao']): ?>
                    <div class="info-item">
                        <span class="info-label">Descrição</span>
                        <div class="descricao-box">
                            <?= nl2br(htmlspecialchars($processo['descricao'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Histórico do Processo -->
            <div class="card" style="margin-top: 24px;">
                <h2>📅 Linha do Tempo</h2>
                <div class="timeline">
                    <?php if (empty($eventos)): ?>
                        <div class="timeline-item">
                            <div class="timeline-icon">ℹ️</div>
                            <div class="timeline-content">
                                <div class="timeline-title">Sem eventos registrados</div>
                                <div class="timeline-date">Os próximos eventos deste processo aparecerão aqui.</div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($eventos as $ev): ?>
                            <div class="timeline-item">
                                <div class="timeline-icon">📝</div>
                                <div class="timeline-content">
                                    <div class="timeline-title"><?= htmlspecialchars($ev['titulo']) ?></div>
                                    <?php if(!empty($ev['descricao'])): ?>
                                        <div style="color: var(--muted); font-size: 13px; margin: 4px 0 6px;"><?= htmlspecialchars($ev['descricao']) ?></div>
                                    <?php endif; ?>
                                    <div class="timeline-date"><?= date('d/m/Y H:i', strtotime($ev['criado_em'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Coluna Lateral -->
        <div>
            <!-- Informações do Cliente -->
            <div class="card">
                <h2>👤 Cliente</h2>
                
                <div class="cliente-card">
                    <div class="cliente-name">
                        <?= htmlspecialchars($processo['cliente_vinculado_nome'] ?: $processo['cliente_nome']) ?>
                    </div>

                    <?php if($processo['cliente_id']): ?>
                    <div class="cliente-info">
                        <?php if($processo['cpf_cnpj']): ?>
                        <div class="cliente-field">
                            <span class="cliente-icon">🆔</span>
                            <span><?= htmlspecialchars($processo['cpf_cnpj']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($processo['cliente_email']): ?>
                        <div class="cliente-field">
                            <span class="cliente-icon">📧</span>
                            <span><?= htmlspecialchars($processo['cliente_email']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($processo['celular']): ?>
                        <div class="cliente-field">
                            <span class="cliente-icon">📱</span>
                            <span><?= htmlspecialchars($processo['celular']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($processo['telefone']): ?>
                        <div class="cliente-field">
                            <span class="cliente-icon">☎️</span>
                            <span><?= htmlspecialchars($processo['telefone']) ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($processo['cidade'] && $processo['uf']): ?>
                        <div class="cliente-field">
                            <span class="cliente-icon">📍</span>
                            <span><?= htmlspecialchars($processo['cidade']) ?>/<?= htmlspecialchars($processo['uf']) ?></span>
                        </div>
                        <?php endif; ?>

                        <div style="margin-top: 16px;">
                            <a href="/clientes/edit/<?= $processo['cliente_id'] ?>" class="btn btn-secondary" style="width: 100%; justify-content: center;">
                                Ver Perfil Completo
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <p style="color: var(--muted); font-size: 13px; margin: 0;">
                        Cliente não vinculado ao cadastro
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card" style="margin-top: 24px;">
                <h2>⚡ Ações Rápidas</h2>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="/compromissos/novo" class="btn btn-secondary" style="justify-content: center;">
                        📅 Agendar Compromisso
                    </a>
                    <a href="/documentos/novo" class="btn btn-secondary" style="justify-content: center;">
                        📄 Adicionar Documento
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>