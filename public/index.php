<?php
session_start();

require_once '../app/helpers/Csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::abortIfInvalid();
}

require_once '../config/database.php';

ob_start();
require_once '../routes/web.php';
$content = ob_get_clean();

$mostrarAcessoMensagens = isset($_SESSION['perfil_id'], $_SESSION['usuario_id'])
    && (int) $_SESSION['perfil_id'] === 2;


$uriAtual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$estaNaAreaMensagens = preg_match('#^/mensagens(?:/.*)?$#', $uriAtual) === 1;

if ($mostrarAcessoMensagens && !$estaNaAreaMensagens && stripos($content, '</body>') !== false) {
    $botaoMensagens = <<<HTML
<style>
.fab-mensagens {
    position: fixed;
    right: 24px;
    bottom: 24px;
    width: 60px;
    height: 60px;
    border-radius: 999px;
    border: 1px solid rgba(255,255,255,.16);
    background: linear-gradient(135deg, #d4af37, #f1c65b);
    color: #0b0b0b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 26px;
    box-shadow: 0 10px 30px rgba(0,0,0,.45);
    z-index: 9999;
    transition: transform .2s ease, box-shadow .2s ease;
}
.fab-mensagens:hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 14px 34px rgba(0,0,0,.5);
}
.fab-mensagens:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 3px;
}
</style>
<a class="fab-mensagens" href="/mensagens" title="Abrir mensagens" aria-label="Abrir mensagens">💬</a>
HTML;

    $content = preg_replace('/<\/body>/i', $botaoMensagens . PHP_EOL . '</body>', $content, 1) ?? $content;
}

echo $content;
