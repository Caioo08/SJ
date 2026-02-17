<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';


class AuthController
{
    
    public static function loginForm()
    {
        $acessoSelecionado = $_GET['acesso'] ?? 'advogado';
        $acessosPermitidos = ['admin', 'advogado', 'cliente'];
        if (!in_array($acessoSelecionado, $acessosPermitidos, true)) {
            $acessoSelecionado = 'advogado';
        }

        require_once '../views/auth/login.php';
    }

    public static function registerForm()
    {
        global $pdo;

        // Buscar UFs do banco
        $stmt = $pdo->query("SELECT id, sigla FROM ufs ORDER BY sigla");
        $ufs = $stmt->fetchAll();

        // Carregar a view
        require __DIR__ . '/../../views/auth/register.php';
    }

    public static function register()
    {
        global $pdo;

        $nome = trim((string) ($_POST['nome'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $senha = (string) ($_POST['senha'] ?? '');
        $oab = trim((string) ($_POST['oab'] ?? ''));
        $ufOab = (int) ($_POST['uf_id'] ?? 0);

        if ($nome === '' || $email === '' || $senha === '' || $oab === '' || $ufOab <= 0) {
            self::showError('Erro de validação', 'Preencha todos os campos para concluir o cadastro.', '/register', 'warning');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM ufs WHERE id = ? LIMIT 1");
        $stmt->execute([$ufOab]);
        if (!$stmt->fetch()) {
            self::showError('UF inválida', 'Selecione uma UF válida para a OAB.', '/register', 'warning');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::showError('Email inválido', 'Informe um endereço de email válido.', '/register', 'warning');
            exit;
        }

        if (strlen($senha) < 8) {
            self::showError('Senha fraca', 'A senha deve ter pelo menos 8 caracteres.', '/register', 'warning');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            self::showError('Email já cadastrado', 'Já existe um usuário com este email.', '/register', 'warning');
            exit;
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nome, email, senha_hash, oab, uf_id)
                VALUES (:nome, :email, :senha, :oab, :uf)
            ");

            $stmt->execute([
                ':nome'  => $nome,
                ':email' => $email,
                ':senha' => $senhaHash,
                ':oab'   => $oab,
                ':uf'    => $ufOab
            ]);

            Audit::registrar('Cadastro usuário', 'usuarios', (int) $pdo->lastInsertId(), 'Email: ' . $email);

            header('Location: /login');
            exit;

        } catch (PDOException $e) {
            self::showError('Falha no cadastro', 'Não foi possível concluir o cadastro no momento.', '/register');
            exit;
        }
    }

    public static function login()
    {
        global $pdo;

        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $senha = (string) ($_POST['senha'] ?? '');


        $perfilAcesso = $_POST['perfil_acesso'] ?? '';
        $mapaPerfis = [
            'admin' => 1,
            'advogado' => 2,
            'cliente' => 3,
        ];

        if (!array_key_exists($perfilAcesso, $mapaPerfis)) {
            self::showError('Perfil de acesso inválido', 'Selecione o tipo de acesso para continuar.', '/login');
            exit;
        }



        $perfilAcesso = $_POST['perfil_acesso'] ?? '';
        $mapaPerfis = [
            'admin' => 1,
            'advogado' => 2,
            'cliente' => 3,
        ];

        if (!array_key_exists($perfilAcesso, $mapaPerfis)) {
            self::showError('Perfil de acesso inválido', 'Selecione o tipo de acesso para continuar.', '/login');
            exit;
        }


        if (empty($email) || empty($senha)) {
            self::showError('Erro de validação', 'Preencha todos os campos para continuar.', '/login');
            exit;
        }

        if ($perfilAcesso === 'cliente') {
            $stmt = $pdo->prepare("SELECT c.*, u.nome AS advogado_nome FROM clientes c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.email = ? LIMIT 2");
            $stmt->execute([$email]);
            $clientes = $stmt->fetchAll();

            if (count($clientes) !== 1) {
                self::showError('Acesso de cliente indisponível', 'Não foi possível identificar uma conta de cliente única com este email. Contate seu advogado.', '/login?acesso=cliente', 'warning');
                exit;
            }

            $cliente = $clientes[0];
            if (empty($cliente['senha_hash']) || !password_verify($senha, $cliente['senha_hash'])) {
                self::showError('Credenciais inválidas', 'Email ou senha do cliente incorretos.', '/login?acesso=cliente');
                exit;
            }

            session_regenerate_id(true);
            unset($_SESSION['usuario_id'], $_SESSION['usuario_nome']);

            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nome'] = $cliente['nome'];
            $_SESSION['cliente_advogado'] = $cliente['advogado_nome'] ?? null;
            $_SESSION['perfil_id'] = 3;

            Audit::registrar('Login cliente', 'clientes', (int) $cliente['id'], 'Email: ' . $email);

            header('Location: /cliente');
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            self::showError(
                'Credenciais inválidas', 
                'Email ou senha incorretos. Verifique seus dados e tente novamente.',
                '/login'
            );
            exit;
        }


        if ((int)$usuario['perfil_id'] !== (int)$mapaPerfis[$perfilAcesso]) {
            self::showError(
                'Perfil incorreto',
                'O perfil selecionado não corresponde a este usuário. Verifique se você escolheu Admin ou Advogado corretamente.',
                '/login?acesso=' . urlencode($perfilAcesso),
                'warning'
            );
            exit;
        }

        // Verificar se o usuário está ativo
        if (!$usuario['ativo']) {
            self::showError(
                'Conta desativada',
                'Sua conta foi desativada pelo administrador. Entre em contato com o suporte para mais informações.',
                '/login',
                'warning'
            );
            exit;
        }

        // ✅ CORREÇÃO: Armazenar perfil_id na sessão
        session_regenerate_id(true);
        unset($_SESSION['cliente_id'], $_SESSION['cliente_nome'], $_SESSION['cliente_advogado']);

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['perfil_id'] = $usuario['perfil_id']; // ← ADICIONADO

        Audit::registrar('Login usuário', 'usuarios', (int) $usuario['id'], 'Perfil: ' . (int) $usuario['perfil_id']);

        // Redirecionar baseado no perfil
        if ($usuario['perfil_id'] == 1) {
            // Admin
            header('Location: /admin');
        } else {
            // Advogado
            header('Location: /dashboard');
        }
        exit;
    }

    /**
     * Exibe página de erro estilizada
     */
    private static function showError($titulo, $mensagem, $voltarPara = '/login', $tipo = 'error')
    {
        $icone = $tipo === 'warning' ? '⚠️' : '🔒';
        $cor = $tipo === 'warning' ? '#f59e0b' : '#ef4444';
        
        echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$titulo} - Sistema Jurídico</title>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap' rel='stylesheet'>
    <style>
        :root {
            --bg: #0a0a0a;
            --card: #1a1a1a;
            --primary: #f6f4ef;
            --accent: #d4af37;
            --muted: #bfb39a;
            --border: rgba(255,255,255,0.08);
            --shadow: 0 10px 40px rgba(0,0,0,0.6);
            --error: {$cor};
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-card {
            background: var(--card);
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            text-align: center;
        }

        .error-icon {
            font-size: 64px;
            margin-bottom: 24px;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .error-title {
            color: var(--error);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .error-message {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .error-actions {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #c49f2c);
            color: #0b0b0b;
        }

        .btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--primary);
        }

        .footer-text {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 32px 24px;
            }
            
            .error-icon {
                font-size: 48px;
            }
            
            .error-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class='error-container'>
        <div class='error-card'>
            <span class='error-icon'>{$icone}</span>
            <h1 class='error-title'>{$titulo}</h1>
            <p class='error-message'>{$mensagem}</p>
            
            <div class='error-actions'>
                <a href='{$voltarPara}' class='btn btn-primary'>
                    ← Tentar novamente
                </a>
                <button onclick='history.back()' class='btn btn-secondary'>
                    Voltar
                </button>
            </div>

            <div class='footer-text'>
                Sistema Jurídico © " . date('Y') . "
            </div>
        </div>
    </div>

    <script>
        // Auto-focus no botão principal após 100ms
        setTimeout(() => {
            document.querySelector('.btn-primary').focus();
        }, 100);

        // Permitir pressionar Enter para voltar
        document.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                window.location.href = '{$voltarPara}';
            }
        });
    </script>
</body>
</html>";
    }
}