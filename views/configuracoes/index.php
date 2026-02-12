<?php?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Configurações - Sistema Jurídico</title>
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
    --success: #4ade80;
    --danger: #ef4444;
}

* { box-sizing: border-box; font-family: 'Inter', sans-serif; }

body {
    margin:0;
    background: var(--bg);
    color: var(--primary);
    min-height:100vh;
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
}

.header {
    margin-bottom: 24px;
}

h1 { 
    color: var(--accent); 
    margin: 0 0 8px 0;
    font-size: 28px;
}

.subtitle {
    color: var(--muted);
    font-size: 14px;
}

.alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
}

.alert-success {
    background: rgba(74, 222, 128, 0.15);
    color: var(--success);
    border: 1px solid rgba(74, 222, 128, 0.3);
}

.config-sections {
    display: grid;
    gap: 24px;
}

.config-section {
    background: var(--card);
    border-radius: 12px;
    padding: 24px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
}

.section-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(212, 175, 55, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.section-title {
    flex: 1;
}

.section-title h2 {
    margin: 0;
    color: var(--accent);
    font-size: 18px;
}

.section-title p {
    margin: 4px 0 0 0;
    color: var(--muted);
    font-size: 13px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

label {
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 6px;
    font-size: 13px;
}

input, select {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #171717;
    color: var(--primary);
    font-size: 14px;
    outline: none;
    transition: 0.2s all;
    width: 100%;
}

input::placeholder {
    color: rgba(255,255,255,0.35);
}

input:focus, select:focus {
    border-color: var(--accent);
    box-shadow: 0 4px 15px rgba(212,175,55,0.2);
}

select option {
    background: var(--card);
    color: var(--primary);
}

.btn {
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-block;
    text-decoration: none;
    text-align: center;
}

.btn-primary {
    background: var(--accent);
    color: #0b0b0b;
}

.btn-primary:hover {
    filter: brightness(0.95);
}

.btn-danger {
    background: var(--danger);
    color: white;
}

.btn-danger:hover {
    filter: brightness(0.9);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.stat-item {
    background: var(--bg-secondary);
    padding: 16px;
    border-radius: 8px;
    border: 1px solid var(--border);
}

.stat-label {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 6px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--accent);
}

.danger-zone {
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 20px;
    border-radius: 8px;
    margin-top: 16px;
}

.danger-zone h3 {
    color: var(--danger);
    margin: 0 0 12px 0;
    font-size: 16px;
}

.danger-zone p {
    color: var(--muted);
    font-size: 13px;
    margin-bottom: 16px;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: var(--card);
    padding: 30px;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    border: 1px solid var(--border);
}

.modal-content h3 {
    color: var(--danger);
    margin: 0 0 16px 0;
}

.modal-buttons {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
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
                <a href="/dashboard" class="nav-link">
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
                <a href="/documentos" class="nav-link">
                    <span>📄</span> Documentos
                </a>
            </li>
            <li class="nav-item">
                <a href="/configuracoes" class="nav-link active">
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

<div class="main-content">
    <div class="header">
        <h1>Configurações</h1>
        <p class="subtitle">Gerencie suas informações e preferências do sistema</p>
    </div>

    <?php if(isset($_GET['sucesso'])): ?>
        <?php if($_GET['sucesso'] == 'perfil'): ?>
            <div class="alert alert-success">
                ✓ Perfil atualizado com sucesso!
            </div>
        <?php elseif($_GET['sucesso'] == 'senha'): ?>
            <div class="alert alert-success">
                ✓ Senha alterada com sucesso!
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="config-sections">
        
        <!-- Estatísticas -->
        <div class="config-section">
            <div class="section-header">
                <div class="section-icon">📊</div>
                <div class="section-title">
                    <h2>Estatísticas da Conta</h2>
                    <p>Visão geral dos seus dados no sistema</p>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Clientes Cadastrados</div>
                    <div class="stat-value"><?= $stats['clientes'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Processos Ativos</div>
                    <div class="stat-value"><?= $stats['processos'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Compromissos</div>
                    <div class="stat-value"><?= $stats['compromissos'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Documentos</div>
                    <div class="stat-value"><?= $stats['documentos'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Espaço Usado</div>
                    <div class="stat-value"><?= number_format($stats['espaco_usado'] / 1024 / 1024, 2) ?> MB</div>
                </div>
            </div>
        </div>

        <!-- Informações do Perfil -->
        <div class="config-section">
            <div class="section-header">
                <div class="section-icon">👤</div>
                <div class="section-title">
                    <h2>Informações do Perfil</h2>
                    <p>Atualize seus dados cadastrais</p>
                </div>
            </div>

            <form action="/configuracoes/atualizar-perfil" method="POST">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="nome">Nome Completo</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="oab">Número da OAB</label>
                        <input type="text" id="oab" name="oab" value="<?= htmlspecialchars($usuario['oab']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="uf_id">UF da OAB</label>
                        <select id="uf_id" name="uf_id" required>
                            <?php foreach($ufs as $uf): ?>
                                <option value="<?= $uf['id'] ?>" <?= $usuario['uf_id'] == $uf['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($uf['sigla']) ?> - <?= htmlspecialchars($uf['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Salvar Alterações</button>
                </div>
            </form>
        </div>

        <!-- Informações do Perfil -->
        <div class="config-section">
    <div class="section-header">
        <div class="section-icon">🏢</div>
        <div class="section-title">
            <h2>Endereço do Escritório</h2>
            <p>Dados que aparecerão na procuração</p>
        </div>
    </div>

    <form action="/configuracoes/atualizar-escritorio" method="POST">
        <div class="form-grid">
            <div class="form-group cep-group" style="position: relative;">
                <label for="escritorio_cep">CEP</label>
                <input type="text" id="escritorio_cep" name="escritorio_cep" 
                       value="<?= htmlspecialchars($usuario['escritorio_cep'] ?? '') ?>"
                       maxlength="9" placeholder="00000-000">
                <span class="cep-loading" id="escritorioCepLoading" style="display: none; position: absolute; right: 12px; top: 38px; color: var(--accent);">🔄 Buscando...</span>
            </div>

            <div class="form-group full-width">
                <label for="escritorio_endereco">Endereço</label>
                <input type="text" id="escritorio_endereco" name="escritorio_endereco" 
                       value="<?= htmlspecialchars($usuario['escritorio_endereco'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="escritorio_numero">Número</label>
                <input type="text" id="escritorio_numero" name="escritorio_numero" 
                       value="<?= htmlspecialchars($usuario['escritorio_numero'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="escritorio_complemento">Complemento</label>
                <input type="text" id="escritorio_complemento" name="escritorio_complemento" 
                       value="<?= htmlspecialchars($usuario['escritorio_complemento'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="escritorio_bairro">Bairro</label>
                <input type="text" id="escritorio_bairro" name="escritorio_bairro" 
                       value="<?= htmlspecialchars($usuario['escritorio_bairro'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="escritorio_cidade">Cidade</label>
                <input type="text" id="escritorio_cidade" name="escritorio_cidade" 
                       value="<?= htmlspecialchars($usuario['escritorio_cidade'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="escritorio_uf">UF</label>
                <select id="escritorio_uf" name="escritorio_uf">
                    <option value="">Selecione</option>
                    <?php
                    $ufs_br = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG',
                              'PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
                    foreach($ufs_br as $uf_option):
                    ?>
                        <option value="<?= $uf_option ?>" 
                            <?= ($usuario['escritorio_uf'] ?? '') == $uf_option ? 'selected' : '' ?>>
                            <?= $uf_option ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">💾 Salvar Endereço</button>
        </div>
    </form>
</div>

        <!-- Segurança -->
        <div class="config-section">
            <div class="section-header">
                <div class="section-icon">🔒</div>
                <div class="section-title">
                    <h2>Segurança</h2>
                    <p>Altere sua senha de acesso</p>
                </div>
            </div>

            <form action="/configuracoes/alterar-senha" method="POST">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" placeholder="••••••••" required>
                    </div>

                    <div class="form-group">
                        <label for="senha_nova">Nova Senha</label>
                        <input type="password" id="senha_nova" name="senha_nova" placeholder="••••••••" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="senha_confirmacao">Confirmar Nova Senha</label>
                        <input type="password" id="senha_confirmacao" name="senha_confirmacao" placeholder="••••••••" required minlength="6">
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">🔐 Alterar Senha</button>
                </div>
            </form>
        </div>

        <!-- Zona de Perigo -->
        <div class="config-section">
            <div class="section-header">
                <div class="section-icon">⚠️</div>
                <div class="section-title">
                    <h2>Zona de Perigo</h2>
                    <p>Ações irreversíveis</p>
                </div>
            </div>

            <div class="danger-zone">
                <h3>⚠️ Excluir Conta</h3>
                <p>
                    Esta ação é <strong>permanente e irreversível</strong>. Todos os seus dados serão excluídos, incluindo:
                    clientes, processos, compromissos e documentos.
                </p>
                <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                    🗑️ Excluir Minha Conta
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>⚠️ Confirmar Exclusão da Conta</h3>
        <p style="color: var(--muted); margin-bottom: 20px;">
            Esta ação não pode ser desfeita. Todos os seus dados serão permanentemente excluídos.
        </p>
        
        <form action="/configuracoes/excluir-conta" method="POST">
            <div class="form-group">
                <label for="confirmacao">Digite <strong>EXCLUIR</strong> para confirmar:</label>
                <input type="text" id="confirmacao" name="confirmacao" placeholder="EXCLUIR" required>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn btn-primary" onclick="closeDeleteModal()">Cancelar</button>
                <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    document.getElementById('confirmacao').value = '';
}

// Fechar modal ao clicar fora
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Validação de senha
document.querySelector('form[action="/configuracoes/alterar-senha"]').addEventListener('submit', function(e) {
    const novaSenha = document.getElementById('senha_nova').value;
    const confirmacao = document.getElementById('senha_confirmacao').value;
    
    if (novaSenha !== confirmacao) {
        e.preventDefault();
        alert('A nova senha e a confirmação não coincidem!');
        return false;
    }
});

// Máscara para CEP
document.getElementById('escritorio_cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});

// Busca CEP via ViaCEP
document.getElementById('escritorio_cep').addEventListener('blur', function() {
    const cep = this.value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        // Mostrar loading (opcional)
        this.style.borderColor = 'var(--accent)';
        
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                this.style.borderColor = '';
                
                if (!data.erro) {
                    document.getElementById('escritorio_endereco').value = data.logradouro || '';
                    document.getElementById('escritorio_bairro').value = data.bairro || '';
                    document.getElementById('escritorio_cidade').value = data.localidade || '';
                    document.getElementById('escritorio_uf').value = data.uf || '';
                    document.getElementById('escritorio_numero').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                this.style.borderColor = '';
                alert('Erro ao buscar CEP. Verifique sua conexão.');
                console.error('Erro:', error);
            });
    }
});
</script>

</body>
</html>