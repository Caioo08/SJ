<?php  ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Novo Cliente - Sistema Jurídico</title>
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

.container {
    width: 100%;
    max-width: 800px;
}

.card {
    background: var(--card);
    border-radius: 14px;
    box-shadow: var(--shadow);
    padding: 30px;
    overflow: hidden;
    border: 1px solid var(--border);
}

.card h1 {
    color: var(--accent);
    font-size: 28px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border);
    padding-bottom: 10px;
}

form {
    display: grid;
    gap: 20px;
}

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
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
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

input, textarea, select {
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

textarea {
    resize: vertical;
    min-height: 80px;
}

.cep-group {
    position: relative;
}

.cep-loading {
    display: none;
    position: absolute;
    right: 12px;
    top: 38px;
    color: var(--accent);
}

.buttons {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 10px;
}

button, a.btn {
    padding: 12px 22px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: 0.2s all;
    min-width: 120px;
    text-align: center;
}

button[type="submit"] {
    background: linear-gradient(135deg, var(--accent), var(--accent-hover));
    color: #0b0b0b;
    border: none;
}

button[type="submit"]:hover {
    filter: brightness(0.95);
}

a.btn {
    background: #333333;
    color: var(--primary);
    border: 1px solid var(--border);
}

a.btn:hover {
    background: var(--accent-hover);
    color: #0b0b0b;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .buttons {
        flex-direction: column;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>📋 Novo Cliente</h1>
        <form action="/clientes/store" method="POST">
            <?= Csrf::field() ?>
            
            <!-- Dados Pessoais -->
            <div class="form-section">
                <h3>👤 Dados Pessoais</h3>
                
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cpf_cnpj">CPF/CNPJ</label>
                        <input type="text" id="cpf_cnpj" name="cpf_cnpj" placeholder="000.000.000-00">
                    </div>

                    <div class="form-group">
                        <label for="rg">RG</label>
                        <input type="text" id="rg" name="rg" placeholder="00.000.000-0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nacionalidade">Nacionalidade</label>
                        <input type="text" id="nacionalidade" name="nacionalidade" placeholder="Brasileiro(a)" value="Brasileiro(a)">
                    </div>

                    <div class="form-group">
                        <label for="estado_civil">Estado Civil</label>
                        <select id="estado_civil" name="estado_civil">
                            <option value="">Selecione</option>
                            <option value="solteiro">Solteiro(a)</option>
                            <option value="casado">Casado(a)</option>
                            <option value="divorciado">Divorciado(a)</option>
                            <option value="viuvo">Viúvo(a)</option>
                            <option value="uniao_estavel">União Estável</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="cliente@exemplo.com">
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="text" id="celular" name="celular" placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(00) 0000-0000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="senha_portal">Senha de acesso do cliente (opcional)</label>
                        <input type="password" id="senha_portal" name="senha_portal" placeholder="Defina para liberar o portal do cliente">
                        <div class="help-text">Para login do cliente, informe email e senha.</div>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="form-section">
                <h3>📍 Endereço</h3>
                
                <div class="form-row">
                    <div class="form-group cep-group">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9">
                        <span class="cep-loading" id="cepLoading">🔄 Buscando...</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" placeholder="Rua, Avenida...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" id="numero" name="numero" placeholder="123">
                    </div>

                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" id="complemento" name="complemento" placeholder="Apto, Bloco...">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" id="bairro" name="bairro" placeholder="Nome do bairro">
                    </div>

                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade" placeholder="Nome da cidade">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="uf">UF</label>
                        <select id="uf" name="uf">
                            <option value="">Selecione</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="form-section">
                <h3>📝 Observações</h3>
                <div class="form-group full-width">
                    <label for="observacoes">Observações adicionais</label>
                    <textarea id="observacoes" name="observacoes" placeholder="Informações relevantes sobre o cliente..."></textarea>
                </div>
            </div>

            <div class="buttons">
                <a href="/clientes" class="btn">Cancelar</a>
                <button type="submit">💾 Cadastrar Cliente</button>
            </div>
        </form>
    </div>
</div>

<script>
// Máscaras para os campos
document.getElementById('cpf_cnpj').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        // CPF: 000.000.000-00
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
        // CNPJ: 00.000.000/0000-00
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

// Busca CEP via ViaCEP
document.getElementById('cep').addEventListener('blur', function() {
    const cep = this.value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        const loading = document.getElementById('cepLoading');
        loading.style.display = 'block';
        
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                    document.getElementById('numero').focus();
                } else {
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                alert('Erro ao buscar CEP. Verifique sua conexão.');
                console.error('Erro:', error);
            });
    }
});
</script>

</body>
</html>