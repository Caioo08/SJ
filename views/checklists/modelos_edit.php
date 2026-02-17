<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Modelo - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {--bg:#0b0b0b;--card:#1a1a1a;--txt:#f6f4ef;--acc:#d4af37;--mut:#bfb39a;--bd:rgba(255,255,255,.08);}*{box-sizing:border-box;font-family:'Inter',sans-serif}
body{margin:0;background:var(--bg);color:var(--txt)}.wrap{max-width:760px;margin:30px auto;padding:0 16px}
.card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:20px}
h1{margin:0 0 12px;color:var(--acc)}.mut{color:var(--mut);font-size:14px;margin-bottom:14px}
.field{display:block;width:100%;padding:10px;border-radius:8px;border:1px solid var(--bd);background:#111;color:var(--txt);margin:8px 0 14px}
.btn{background:var(--acc);color:#0b0b0b;padding:10px 14px;border:none;border-radius:8px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}
.btn-outline{background:#222;color:var(--txt);border:1px solid var(--bd)}
</style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>✏️ Editar modelo de checklist</h1>
        <p class="mut">Atualize o nome, tipo da ação e os itens (um por linha).</p>

        <form method="POST" action="/checklists/modelos/<?= (int)$modelo['id'] ?>/atualizar">
            <?= Csrf::field() ?>
            <label for="nome">Nome</label>
            <input id="nome" class="field" type="text" name="nome" value="<?= htmlspecialchars($modelo['nome']) ?>" required>

            <label for="tipo_acao">Tipo de ação</label>
            <input id="tipo_acao" class="field" type="text" name="tipo_acao" value="<?= htmlspecialchars($modelo['tipo_acao'] ?: 'geral') ?>" required>

            <label for="itens">Itens</label>
            <textarea id="itens" class="field" name="itens" rows="10" required><?= htmlspecialchars(implode(PHP_EOL, $itens)) ?></textarea>

            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="btn" type="submit">Salvar alterações</button>
                <a class="btn btn-outline" href="/checklists/modelos">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
