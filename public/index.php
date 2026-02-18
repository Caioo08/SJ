<?php
$httpsAtivo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', $httpsAtivo ? '1' : '0');
ini_set('session.cookie_samesite', 'Lax');

session_start();

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: frame-ancestors 'self'");

require_once '../app/helpers/Csrf.php';
require_once '../app/helpers/Audit.php';

// Transformar erros em exceções e registrar exceções fatais para diagnóstico
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function($e) {
    Audit::registrar('Unhandled Exception', null, null, $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    echo "Erro interno do servidor. Tente novamente mais tarde.";
    exit;
});

register_shutdown_function(function() {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        Audit::registrar('Fatal error', null, null, $err['message'] . ' in ' . $err['file'] . ':' . $err['line']);
        http_response_code(500);
        echo "Erro interno do servidor. Tente novamente mais tarde.";
        exit;
    }
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::verifyRequest()) {
        $uriPost = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        // Registrar diagnóstico para ajudar debug (não inclui tokens)
        $sessTokenExists = isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] !== '' ? 1 : 0;
        $postTokenExists = isset($_POST['csrf_token']) && $_POST['csrf_token'] !== '' ? 1 : 0;
        $hdrTokenExists = (!empty($_SERVER['HTTP_X_CSRF_TOKEN']) || !empty($_SERVER['HTTP_X_CSRF']) || !empty($_SERVER['HTTP_X_XSRF_TOKEN'])) ? 1 : 0;
        Audit::registrar('CSRF falhou', 'public_index', null, 'uri=' . $uriPost . ' sess=' . $sessTokenExists . ' post=' . $postTokenExists . ' hdr=' . $hdrTokenExists);

        if (preg_match('#^/mensagens/enviar/?$#', $uriPost)) {
            header('Location: /mensagens?erro=csrf');
            exit;
        }

        if (preg_match('#^/cliente/mensagens/enviar/?$#', $uriPost)) {
            header('Location: /cliente?erro=csrf');
            exit;
        }
    }

    Csrf::abortIfInvalid();
}

require_once '../config/database.php';

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($metodo !== 'GET') {
    require_once '../routes/web.php';
    exit;
}

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
