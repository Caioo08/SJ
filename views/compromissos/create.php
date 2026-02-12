<?php  ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Novo Compromisso - Sistema Jurídico</title>
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
    max-width: 700px;
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

input, textarea {
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

input:focus, textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 4px 15px rgba(212,175,55,0.2);
}

textarea {
    resize: vertical;
    min-height: 100px;
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

.help-text {
    font-size: 12px;
    color: var(--muted);
    margin-top: 4px;
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
        <h1>📅 Novo Compromisso</h1>
        <form action="/compromissos/store" method="POST">
            
            <!-- Informações Básicas -->
            <div class="form-section">
                <h3>📋 Informações Básicas</h3>
                
                <div class="form-group full-width">
                    <label for="titulo">Título *</label>
                    <input type="text" id="titulo" name="titulo" placeholder="Ex: Audiência no Fórum..." required>
                </div>

                <div class="form-group full-width">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" placeholder="Detalhes do compromisso..."></textarea>
                </div>
            </div>

            <!-- Data e Hora -->
            <div class="form-section">
                <h3>🕐 Data e Hora</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="data_inicio">Data e Hora de Início *</label>
                        <input type="datetime-local" id="data_inicio" name="data_inicio" required>
                        <span class="help-text">Quando o compromisso começa</span>
                    </div>

                    <div class="form-group">
                        <label for="data_fim">Data e Hora de Término</label>
                        <input type="datetime-local" id="data_fim" name="data_fim">
                        <span class="help-text">Opcional</span>
                    </div>
                </div>
            </div>

            <!-- Local -->
            <div class="form-section">
                <h3>📍 Local</h3>
                
                <div class="form-group full-width">
                    <label for="local">Endereço ou Local</label>
                    <input type="text" id="local" name="local" placeholder="Ex: Fórum Central - Rua ABC, 123...">
                </div>
            </div>

            <div class="buttons">
                <a href="/compromissos" class="btn">Cancelar</a>
                <button type="submit">💾 Cadastrar Compromisso</button>
            </div>
        </form>
    </div>
</div>

<script>
// Define data mínima como agora
const now = new Date();
const offset = now.getTimezoneOffset() * 60000;
const localISOTime = (new Date(now - offset)).toISOString().slice(0, 16);
document.getElementById('data_inicio').min = localISOTime;
document.getElementById('data_fim').min = localISOTime;

// Quando mudar data_inicio, atualiza o mínimo de data_fim
document.getElementById('data_inicio').addEventListener('change', function() {
    document.getElementById('data_fim').min = this.value;
});
</script>

</body>
</html>