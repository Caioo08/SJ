<?php
$cancelarUrl = '/login';
if (isset($_SESSION['perfil_id']) && (int) $_SESSION['perfil_id'] === 1) {
    $cancelarUrl = '/admin';
} elseif (isset($_SESSION['perfil_id']) && (int) $_SESSION['perfil_id'] === 2) {
    $cancelarUrl = '/dashboard';
} elseif (isset($_SESSION['perfil_id']) && (int) $_SESSION['perfil_id'] === 3) {
    $cancelarUrl = '/cliente';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmar saída - Sistema Jurídico</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#0a0a0a;--card:#1a1a1a;--txt:#f6f4ef;--mut:#bfb39a;--acc:#d4af37;--bd:rgba(255,255,255,.08)}
*{box-sizing:border-box;font-family:'Inter',sans-serif}body{margin:0;min-height:100vh;display:grid;place-items:center;background:var(--bg);color:var(--txt);padding:20px}
.card{width:min(520px,100%);background:var(--card);border:1px solid var(--bd);border-radius:14px;padding:24px}
h1{margin:0 0 10px;color:var(--acc)}p{margin:0 0 16px;color:var(--mut)}
.actions{display:flex;gap:10px;flex-wrap:wrap}.btn{padding:10px 14px;border-radius:8px;border:1px solid var(--bd);text-decoration:none;font-weight:700;cursor:pointer}
.btn-primary{background:var(--acc);color:#0b0b0b;border:none}.btn-secondary{background:#222;color:var(--txt)}
</style>
</head>
<body>
    <div class="card">
        <h1>🚪 Confirmar saída</h1>
        <p>Tem certeza que deseja encerrar sua sessão?</p>
        <div class="actions">
            <form method="POST" action="/logout" style="display:inline;">
                <?= Csrf::field() ?>
                <button type="submit" class="btn btn-primary">Sair agora</button>
            </form>
            <a class="btn btn-secondary" href="<?= htmlspecialchars($cancelarUrl, ENT_QUOTES, 'UTF-8') ?>">Cancelar</a>
        </div>
    </div>
</body>
</html>
