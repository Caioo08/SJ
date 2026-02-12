<?php  ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Cliente - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0b0b0b;
    --card: #1c1c1c;
    --primary: #f6f4ef;
    --accent: #d4af37;
    --accent-hover: #c49f2c;
    --muted: #bfb39a;
    --border: rgba(255,255,255,0.08);
    --shadow: 0 6px 25px rgba(0,0,0,0.6);
}

* { box-sizing: border-box; font-family: 'Inter', sans-serif; }

body {
    margin:0;
    background: var(--bg);
    color: var(--primary);
    min-height:100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 20px;
}

.container { width: 100%; max-width: 800px; }

.card {
    background: var(--card);
    border-radius: 14px;
    box-shadow: var(--shadow);
    padding: 30px;
    border: 1px solid var(--border);
}

.card h1 {
    color: var(--accent);
    font-size: 28px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 10px;
}

form { display: grid; gap: 20px; }

.form-section {
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    background: rgba(255,255,255,0.02);
}

.form-section h3 {
    color: var(--accent);
    font-size: 16px;
    margin-bottom: 15px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group { display: flex; flex-direction: column; }
.form-group.full-width { grid-column: 1 / -1; }

label {
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 6px;
    font-size: 13px;
}

input, textarea, select {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #171717;
    color: var(--primary);
    font-size: 14px;
    outline: none;
    transition: 0.2s all;
}

input::placeholder, textarea::placeholder {
    color: rgba(255,255,255,0.35);
}

input:focus, textarea:focus, select:focus {
    border-color: var(--accent);
    box-shadow: 0 4px 15px rgba(212,175,55,0.2);
}

select option {
    background: var(--card);
    color: var(--primary);
}

textarea { min-height: 80px; resize: vertical; }

.buttons {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
}

button {
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    color: #0b0b0b;
    border: none;
    padding: 12px 22px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: filter 0.2s;
}

button:hover {
    filter: brightness(0.95);
}

a.btn {
    background: #333;
    color: var(--primary);
    border: 1px solid var(--border);
    padding: 12px 22px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
}

a.btn:hover {
    background: var(--accent-hover);
    color: #0b0b0b;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>

<div class="container">
<div class="card">
<h1>✏️ Editar Cliente</h1>

<form action="/clientes/update/<?= $cliente['id'] ?>" method="POST">
            <?= Csrf::field() ?>

<!-- Dados Pessoais -->
<div class="form-section">
<h3>👤 Dados Pessoais</h3>

<div class="form-row">
<div class="form-group full-width">
<label>Nome Completo *</label>
<input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>" required>
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>CPF/CNPJ</label>
<input type="text" id="cpf_cnpj" name="cpf_cnpj" value="<?= htmlspecialchars($cliente['cpf_cnpj'] ?? '') ?>">
</div>

<div class="form-group">
<label>RG</label>
<input type="text" id="rg" name="rg" value="<?= htmlspecialchars($cliente['rg'] ?? '') ?>">
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>Nacionalidade</label>
<input type="text" name="nacionalidade" value="<?= htmlspecialchars($cliente['nacionalidade'] ?? 'Brasileiro(a)') ?>">
</div>

<div class="form-group">
<label>Estado Civil</label>
<select name="estado_civil">
<option value="">Selecione</option>
<option value="solteiro" <?= ($cliente['estado_civil'] ?? '') == 'solteiro' ? 'selected' : '' ?>>Solteiro(a)</option>
<option value="casado" <?= ($cliente['estado_civil'] ?? '') == 'casado' ? 'selected' : '' ?>>Casado(a)</option>
<option value="divorciado" <?= ($cliente['estado_civil'] ?? '') == 'divorciado' ? 'selected' : '' ?>>Divorciado(a)</option>
<option value="viuvo" <?= ($cliente['estado_civil'] ?? '') == 'viuvo' ? 'selected' : '' ?>>Viúvo(a)</option>
<option value="uniao_estavel" <?= ($cliente['estado_civil'] ?? '') == 'uniao_estavel' ? 'selected' : '' ?>>União Estável</option>
</select>
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>Email</label>
<input type="email" name="email" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>">
</div>

<div class="form-group">
<label>Celular</label>
<input type="text" id="celular" name="celular" value="<?= htmlspecialchars($cliente['celular'] ?? '') ?>">
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>Telefone</label>
<input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>">
</div>
</div>
</div>

<!-- Endereço -->
<div class="form-section">
<h3>📍 Endereço</h3>

<div class="form-row">
<div class="form-group">
<label>CEP</label>
<input type="text" id="cep" name="cep" value="<?= htmlspecialchars($cliente['cep'] ?? '') ?>" maxlength="9">
</div>
</div>

<div class="form-row">
<div class="form-group full-width">
<label>Endereço</label>
<input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco'] ?? '') ?>">
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>Número</label>
<input type="text" name="numero" value="<?= htmlspecialchars($cliente['numero'] ?? '') ?>">
</div>

<div class="form-group">
<label>Complemento</label>
<input type="text" name="complemento" value="<?= htmlspecialchars($cliente['complemento'] ?? '') ?>">
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>Bairro</label>
<input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($cliente['bairro'] ?? '') ?>">
</div>

<div class="form-group">
<label>Cidade</label>
<input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($cliente['cidade'] ?? '') ?>">
</div>
</div>

<div class="form-row">
<div class="form-group">
<label>UF</label>
<select id="uf" name="uf">
<option value="">Selecione</option>
<?php 
$ufs_br = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
foreach($ufs_br as $uf_option): 
?>
<option value="<?= $uf_option ?>" <?= ($cliente['uf'] ?? '') == $uf_option ? 'selected' : '' ?>><?= $uf_option ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
</div>

<!-- Observações -->
<div class="form-section">
<h3>📝 Observações</h3>
<textarea name="observacoes"><?= htmlspecialchars($cliente['observacoes'] ?? '') ?></textarea>
</div>

<div class="buttons">
<a href="/clientes" class="btn">Cancelar</a>
<button type="submit">💾 Atualizar Cliente</button>
</div>

</form>
</div>
</div>

<script>
// Máscaras
document.getElementById('cpf_cnpj').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }
    
    e.target.value = value;
});

document.getElementById('rg').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1})$/, '$1-$2');
    e.target.value = value;
});

document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
    e.target.value = value;
});

document.getElementById('celular').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});

document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});

// Busca CEP
document.getElementById('cep').addEventListener('blur', function() {
    const cep = this.value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                    document.querySelector('input[name="numero"]').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
    }
});
</script>

</body>
</html>