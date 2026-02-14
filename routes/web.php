<?php

require_once '../app/controllers/AuthController.php';
require_once '../app/helpers/AuthMiddleware.php';
require_once '../app/helpers/Audit.php';
require_once '../app/controllers/FaseCController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// ==================== AUTENTICAÇÃO ====================

if ($uri === '/login' && $method === 'GET') {
    AuthController::loginForm();
}
elseif ($uri === '/login' && $method === 'POST') {
    AuthController::login();
}
elseif ($uri === '/register' && $method === 'GET') {
    AuthController::registerForm();
}
elseif ($uri === '/register' && $method === 'POST') {
    AuthController::register();
}

// ==================== ADMIN ====================

elseif ($uri === '/admin') {
    AuthMiddleware::verificarAdmin();
    require_once '../app/controllers/AdminController.php';
    AdminController::index();
}

elseif ($uri === '/admin/usuarios') {
    AuthMiddleware::verificarAdmin();
    require_once '../app/controllers/AdminController.php';
    AdminController::usuarios();
}

elseif (preg_match('#^/admin/usuarios/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdmin();
    $id = $matches[1];
    require_once '../app/controllers/AdminController.php';
    AdminController::verUsuario($id);
}

elseif (preg_match('#^/admin/usuarios/toggle/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdmin();
    $id = $matches[1];
    require_once '../app/controllers/AdminController.php';
    AdminController::toggleUsuario($id);
}

elseif (preg_match('#^/admin/usuarios/confirm-delete/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdmin();
    $id = $matches[1];
    require_once '../app/controllers/AdminController.php';
    AdminController::confirmDeleteUsuario($id);
}

elseif (preg_match('#^/admin/usuarios/delete/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdmin();
    $id = $matches[1];
    require_once '../app/controllers/AdminController.php';
    AdminController::deleteUsuario($id);
}

elseif ($uri === '/admin/logs') {
    AuthMiddleware::verificarAdmin();
    require_once '../app/controllers/AdminController.php';
    AdminController::logs();
}

// ==================== DASHBOARD (APENAS ADVOGADOS) ====================

elseif ($uri === '/dashboard') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/DashboardController.php';
    DashboardController::index();
}

// ==================== PROCESSOS (APENAS ADVOGADOS) ====================

elseif ($uri === '/processos') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::index();
}

elseif ($uri === '/processos/novo') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::create();
}

elseif ($uri === '/processos/store' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::store();
}

elseif (preg_match('#^/processos/edit/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::edit($id);
}

elseif (preg_match('#^/processos/update/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::update($id);
}

elseif (preg_match('#^/processos/confirm-delete/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::confirmDelete($id);
}

elseif (preg_match('#^/processos/delete/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::delete($id);
}

elseif (preg_match('#^/processos/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ProcessosController.php';
    ProcessosController::show($id);
}


elseif (preg_match('#^/processos/(\d+)/checklist/adicionar$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    FaseCController::adicionarChecklistItem($id);
}

elseif (preg_match('#^/processos/checklist/(\d+)/toggle$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $itemId = $matches[1];
    FaseCController::toggleChecklistItem($itemId);
}

elseif (preg_match('#^/processos/(\d+)/peticoes/versao$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    FaseCController::adicionarPeticaoVersao($id);
}

elseif (preg_match('#^/processos/(\d+)/checklist/padrao$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    FaseCController::criarChecklistPadrao($id);
}

elseif (preg_match('#^/processos/(\d+)/peticoes/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $processoId = $matches[1];
    $peticaoId = $matches[2];
    FaseCController::verPeticao($processoId, $peticaoId);
}

elseif (preg_match('#^/peticoes/versoes/(\d+)/derivar$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $versaoId = $matches[1];
    FaseCController::derivarVersao($versaoId);
}

// ==================== CLIENTES (APENAS ADVOGADOS) ====================

elseif ($uri === '/clientes') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ClientesController.php';
    ClientesController::index();
}

elseif ($uri === '/clientes/novo') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ClientesController.php';
    ClientesController::create();
}

elseif ($uri === '/clientes/store' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ClientesController.php';
    ClientesController::store();
}

elseif (preg_match('#^/clientes/edit/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ClientesController.php';
    ClientesController::edit($id);
}

elseif (preg_match('#^/clientes/update/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ClientesController.php';
    ClientesController::update($id);
}

elseif (preg_match('#^/clientes/confirm-delete/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ClientesController.php';
    ClientesController::confirmDelete($id);
}

elseif (preg_match('#^/clientes/delete/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ClientesController.php';
    ClientesController::delete($id);
}

elseif (preg_match('#^/clientes/(\d+)/procuracao$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ClientesController.php';
    ClientesController::gerarProcuracao($id);
}

// ✅ NOVA ROTA: Visualizar cliente
elseif (preg_match('#^/clientes/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/ClientesController.php';
    ClientesController::show($id);
}

// ==================== COMPROMISSOS (APENAS ADVOGADOS) ====================

elseif ($uri === '/compromissos') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/CompromissosController.php';
    CompromissosController::index();
}

elseif ($uri === '/compromissos/novo') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/CompromissosController.php';
    CompromissosController::create();
}

elseif ($uri === '/compromissos/store' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/CompromissosController.php';
    CompromissosController::store();
}

elseif (preg_match('#^/compromissos/edit/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/CompromissosController.php';
    CompromissosController::edit($id);
}

elseif (preg_match('#^/compromissos/update/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/CompromissosController.php';
    CompromissosController::update($id);
}

elseif (preg_match('#^/compromissos/delete/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/CompromissosController.php';
    CompromissosController::delete($id);
}

// ==================== MENSAGENS (APENAS ADVOGADOS) ====================

elseif ($uri === '/mensagens' && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/MensagensController.php';
    MensagensController::index();
}

elseif (preg_match('#^/mensagens/enviar/?$#', $uri) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/MensagensController.php';
    MensagensController::enviarAdvogado();
}

// ==================== DOCUMENTOS (APENAS ADVOGADOS) ====================

elseif ($uri === '/documentos') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/DocumentosController.php';
    DocumentosController::index();
}

elseif ($uri === '/documentos/novo') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/DocumentosController.php';
    DocumentosController::create();
}

elseif ($uri === '/documentos/store' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/DocumentosController.php';
    DocumentosController::store();
}

elseif (preg_match('#^/documentos/download/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/DocumentosController.php';
    DocumentosController::download($id);
}

elseif (preg_match('#^/documentos/delete/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/DocumentosController.php';
    DocumentosController::delete($id);
}


// ==================== PRAZOS (APENAS ADVOGADOS) ====================

elseif ($uri === '/prazos') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/PrazosController.php';
    PrazosController::index();
}

elseif ($uri === '/prazos/novo' && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/PrazosController.php';
    PrazosController::create();
}


elseif (preg_match('#^/prazos/edit/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/PrazosController.php';
    PrazosController::edit($id);
}

elseif (preg_match('#^/prazos/update/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/PrazosController.php';
    PrazosController::update($id);
}

elseif ($uri === '/prazos/store' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/PrazosController.php';
    PrazosController::store();
}

elseif (preg_match('#^/prazos/toggle/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/PrazosController.php';
    PrazosController::toggleConclusao($id);
}

elseif (preg_match('#^/prazos/delete/(\d+)$#', $uri, $matches) && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    $id = $matches[1];
    require_once '../app/controllers/PrazosController.php';
    PrazosController::delete($id);
}

// ==================== CONFIGURAÇÕES (APENAS ADVOGADOS) ====================

elseif ($uri === '/configuracoes') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ConfiguracoesController.php';
    ConfiguracoesController::index();
}

elseif ($uri === '/configuracoes/atualizar-perfil' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ConfiguracoesController.php';
    ConfiguracoesController::updateProfile();
}

elseif ($uri === '/configuracoes/alterar-senha' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ConfiguracoesController.php';
    ConfiguracoesController::updatePassword();
}

elseif ($uri === '/configuracoes/excluir-conta' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ConfiguracoesController.php';
    ConfiguracoesController::deleteAccount();
}

elseif ($uri === '/configuracoes/atualizar-escritorio' && $method === 'POST') {
    AuthMiddleware::verificarAdvogado();
    require_once '../app/controllers/ConfiguracoesController.php';
    ConfiguracoesController::updateEscritorio();
}


// ==================== PORTAL DO CLIENTE ====================

elseif ($uri === '/cliente') {
    AuthMiddleware::verificarCliente();
    require_once '../app/controllers/ClientePortalController.php';
    ClientePortalController::index();
}

elseif (preg_match('#^/cliente/processos/(\d+)$#', $uri, $matches) && $method === 'GET') {
    AuthMiddleware::verificarCliente();
    $id = $matches[1];
    require_once '../app/controllers/ClientePortalController.php';
    ClientePortalController::showProcesso($id);
}

elseif ($uri === '/cliente/mensagens/enviar' && $method === 'POST') {
    AuthMiddleware::verificarCliente();
    require_once '../app/controllers/ClientePortalController.php';
    ClientePortalController::enviarMensagem();
}

// ==================== LOGOUT ====================

elseif ($uri === '/logout') {
    if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
        Audit::registrar('Logout usuário', 'usuarios', (int) $_SESSION['usuario_id'], null);
    }

    $_SESSION = [];
    session_destroy();
    header('Location: /login');
    exit;
}

// ==================== ROTA PADRÃO ====================

elseif ($uri === '/') {
    // Se já estiver logado, redireciona por perfil; senão exibe página inicial pública.
    if (isset($_SESSION['usuario_id']) && isset($_SESSION['perfil_id'])) {
        if ($_SESSION['perfil_id'] == 1) {
            header('Location: /admin');
        } elseif ($_SESSION['perfil_id'] == 2) {
            header('Location: /dashboard');
        } else {
            header('Location: /cliente');
        }
        exit;
    }

    require_once '../views/home.php';
    exit;
}
else {
    http_response_code(404);
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>404 - Página não encontrada</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #0b0b0b;
                color: #f6f4ef;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                background: #1a1a1a;
                padding: 40px;
                border-radius: 12px;
                text-align: center;
                border: 1px solid rgba(255,255,255,0.08);
            }
            h1 {
                color: #d4af37;
                font-size: 72px;
                margin: 0;
            }
            p {
                color: #bfb39a;
                margin: 20px 0;
            }
            a {
                background: #d4af37;
                color: #0b0b0b;
                padding: 12px 24px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <h1>404</h1>
            <p>Página não encontrada</p>
            <a href='/'>← Voltar para Home</a>
        </div>
    </body>
    </html>";
}