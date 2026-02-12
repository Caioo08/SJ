<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Detalhes do Cliente - Sistema Jurídico</title>
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
    max-width: 1200px;
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
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--accent);
    color: #0b0b0b;
}

.btn-secondary {
    background: #4a9eff;
    color: white;
}

.btn-success {
    background: #4ade80;
    color: #0b0b0b;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn:hover {
    filter: brightness(0.95);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--card);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-4px);
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

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.card {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.card.full-width {
    grid-column: 1 / -1;
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
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item.full-width {
    grid-column: 1 / -1;
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

.processo-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.processo-card {
    background: var(--bg-secondary);
    border-radius: 8px;
    padding: 16px;
    border-left: 4px solid var(--accent);
    transition: all 0.2s;
}

.processo-card:hover {
    background: #1c1c1c;
    transform: translateX(4px);
}

.processo-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.processo-numero {
    font-weight: 600;
    color: var(--primary);
    font-size: 14px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
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

.processo-desc {
    color: var(--muted);
    font-size: 13px;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.processo-date {
    color: var(--muted);
    font-size: 12px;
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

/* Seção de Documentos - NOVO */
.documentos-section {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
}

.documentos-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

@media (max-width: 968px) {
    .content-grid, .info-grid {
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
        <h1>👤 <?= htmlspecialchars($cliente['nome']) ?></h1>
        <div class="btn-group">
            <a href="/clientes" class="btn btn-primary">← Voltar</a>
            <a href="/clientes/edit/<?= $cliente['id'] ?>" class="btn btn-secondary">✏️ Editar</a>
            <a href="/clientes/confirm-delete/<?= $cliente['id'] ?>" class="btn btn-danger">🗑️ Excluir</a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['total_processos'] ?></div>
                    <div class="stat-label">Total de Processos</div>
                </div>
                <div class="stat-icon">📋</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['processos_abertos'] ?></div>
                    <div class="stat-label">Processos Abertos</div>
                </div>
                <div class="stat-icon">⚖️</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value"><?= $stats['processos_concluidos'] ?></div>
                    <div class="stat-label">Concluídos</div>
                </div>
                <div class="stat-icon">✅</div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Dados Pessoais -->
        <div class="card">
            <h2>📋 Dados Pessoais</h2>
            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Nome Completo</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['nome']) ?></span>
                </div>

                <?php if($cliente['cpf_cnpj']): ?>
                <div class="info-item">
                    <span class="info-label">CPF/CNPJ</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['cpf_cnpj']) ?></span>
                </div>
                <?php endif; ?>

                <?php if($cliente['estado_civil']): ?>
                <div class="info-item">
                    <span class="info-label">Estado Civil</span>
                    <span class="info-value">
                        <?php
                        $estados = [
                            'solteiro' => 'Solteiro(a)',
                            'casado' => 'Casado(a)',
                            'divorciado' => 'Divorciado(a)',
                            'viuvo' => 'Viúvo(a)',
                            'uniao_estavel' => 'União Estável'
                        ];
                        echo $estados[$cliente['estado_civil']] ?? ucfirst($cliente['estado_civil']);
                        ?>
                    </span>
                </div>
                <?php endif; ?>

                <?php if($cliente['email']): ?>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['email']) ?></span>
                </div>
                <?php endif; ?>

                <?php if($cliente['celular']): ?>
                <div class="info-item">
                    <span class="info-label">Celular</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['celular']) ?></span>
                </div>
                <?php endif; ?>

                <?php if($cliente['telefone']): ?>
                <div class="info-item">
                    <span class="info-label">Telefone</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['telefone']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- NOVO: Seção de Documentos -->
            <div class="documentos-section">
                <h3 style="color: var(--accent); font-size: 16px; margin-bottom: 12px;">📄 Documentos</h3>
                <div class="documentos-actions">
                    <a href="/clientes/<?= $cliente['id'] ?>/procuracao" 
                       class="btn btn-success" 
                       target="_blank"
                       style="flex: 1; justify-content: center;">
                        📜 Gerar Procuração
                    </a>
                    <!-- Você pode adicionar mais botões aqui no futuro -->
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="card">
            <h2>📍 Endereço</h2>
            <div class="info-grid">
                <?php if($cliente['cep']): ?>
                <div class="info-item">
                    <span class="info-label">CEP</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['cep']) ?></span>
                </div>
                <?php endif; ?>

                <?php if($cliente['endereco']): ?>
                <div class="info-item full-width">
                    <span class="info-label">Endereço</span>
                    <span class="info-value">
                        <?= htmlspecialchars($cliente['endereco']) ?>
                        <?= $cliente['numero'] ? ', ' . htmlspecialchars($cliente['numero']) : '' ?>
                    </span>
                </div>
                <?php endif; ?>

                <?php if($cliente['complemento']): ?>
                <div class="info-item">
                    <span class="info-label">Complemento</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['complemento']) ?></span>
                </div>
                <?php endif; ?>

                <?php if($cliente['bairro']): ?>
                <div class="info-item">
                    <span class="info-label">Bairro</span>
                    <span class="info-value"><?= htmlspecialchars($cliente['bairro']) ?></span>
                </div>
                <?php endif; ?>

                <?php if($cliente['cidade'] || $cliente['uf']): ?>
                <div class="info-item">
                    <span class="info-label">Cidade/UF</span>
                    <span class="info-value">
                        <?= htmlspecialchars($cliente['cidade']) ?><?= $cliente['uf'] ? '/' . htmlspecialchars($cliente['uf']) : '' ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Observações -->
        <?php if($cliente['observacoes']): ?>
        <div class="card full-width">
            <h2>📝 Observações</h2>
            <div style="color: var(--muted); line-height: 1.6;">
                <?= nl2br(htmlspecialchars($cliente['observacoes'])) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Processos Vinculados -->
        <div class="card full-width">
            <h2>⚖️ Processos Vinculados (<?= count($processos) ?>)</h2>
            
            <?php if(empty($processos)): ?>
                <div class="empty-state">
                    <div class="empty-icon">⚖️</div>
                    <p>Nenhum processo vinculado a este cliente.</p>
                    <a href="/processos/novo" class="btn btn-secondary" style="margin-top: 16px;">
                        Criar Processo
                    </a>
                </div>
            <?php else: ?>
                <div class="processo-list">
                    <?php foreach($processos as $p): ?>
                        <div class="processo-card">
                            <div class="processo-header">
                                <span class="processo-numero">
                                    <?= $p['numero_processo'] ? htmlspecialchars($p['numero_processo']) : 'Sem número' ?>
                                </span>
                                <span class="status-badge status-<?= $p['status'] ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </div>
                            
                            <?php if($p['descricao']): ?>
                                <div class="processo-desc">
                                    <?= nl2br(htmlspecialchars($p['descricao'])) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="processo-date">
                                Criado em: <?= date('d/m/Y', strtotime($p['criado_em'])) ?>
                            </div>
                            
                            <div style="margin-top: 12px;">
                                <a href="/processos/<?= $p['id'] ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
                                    Ver Detalhes →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Informações de Auditoria -->
    <div class="card" style="margin-top: 24px;">
        <h2>🕒 Informações do Sistema</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Cadastrado em</span>
                <span class="info-value"><?= date('d/m/Y H:i', strtotime($cliente['criado_em'])) ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Última Atualização</span>
                <span class="info-value"><?= date('d/m/Y H:i', strtotime($cliente['atualizado_em'])) ?></span>
            </div>
        </div>
    </div>
</div>

</body>
</html>