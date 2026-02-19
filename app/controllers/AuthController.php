<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';
require_once '../app/helpers/Csrf.php';


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

        $stmt = $pdo->query("SELECT id, sigla FROM ufs ORDER BY sigla");
        $ufs = $stmt->fetchAll();

        require __DIR__ . '/../../views/auth/register.php';
    }

    public static function register()
    {
        global $pdo;

        // ── Leitura dos campos ────────────────────────────────────────────────
        $nome          = trim((string) ($_POST['nome_completo']   ?? ''));
        $email         = strtolower(trim((string) ($_POST['email']          ?? '')));
        $senha         = (string) ($_POST['senha']         ?? '');
        $senhaConfirm  = (string) ($_POST['senha_confirm']  ?? '');
        $oabNumero     = trim((string) ($_POST['oab_numero']      ?? ''));
        $oabSeccional  = strtoupper(trim((string) ($_POST['oab_seccional']   ?? '')));

        // Campos extras
        $cpf           = trim((string) ($_POST['cpf']             ?? ''));
        $dataNasc      = trim((string) ($_POST['data_nascimento'] ?? ''));
        $telefone      = trim((string) ($_POST['telefone']        ?? ''));
        $genero        = trim((string) ($_POST['genero']          ?? ''));
        $areaAtuacao   = trim((string) ($_POST['area_atuacao']    ?? ''));
        $nomeEscritorio = trim((string) ($_POST['escritorio']     ?? ''));

        // ── Validações obrigatórias ───────────────────────────────────────────
        if ($nome === '' || $email === '' || $senha === '' || $oabNumero === '' || $oabSeccional === '') {
            self::showError(
                'Campos obrigatórios',
                'Preencha todos os campos obrigatórios: nome, e-mail, número OAB, seccional e senha.',
                '/register',
                'warning'
            );
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::showError('E-mail inválido', 'Informe um endereço de e-mail válido.', '/register', 'warning');
            exit;
        }

        if (strlen($senha) < 8) {
            self::showError('Senha fraca', 'A senha deve ter pelo menos 8 caracteres.', '/register', 'warning');
            exit;
        }

        if ($senha !== $senhaConfirm) {
            self::showError('Senhas diferentes', 'A senha e a confirmação não coincidem.', '/register', 'warning');
            exit;
        }

        // ── E-mail duplicado ──────────────────────────────────────────────────
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE LOWER(email) = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            self::showError('E-mail já cadastrado', 'Já existe um usuário com este e-mail.', '/register', 'warning');
            exit;
        }

        // ── Resolver uf_id pela sigla ─────────────────────────────────────────
        $stmt = $pdo->prepare("SELECT id FROM ufs WHERE sigla = ? LIMIT 1");
        $stmt->execute([$oabSeccional]);
        $uf = $stmt->fetch();

        if (!$uf) {
            self::showError('Seccional inválida', 'Selecione uma seccional (UF) válida para a OAB.', '/register', 'warning');
            exit;
        }

        $ufId = (int) $uf['id'];

        // ── OAB composta: "123456/SP" ─────────────────────────────────────────
        $oab = $oabNumero . '/' . $oabSeccional;

        // ── Data de nascimento: só salva se válida ────────────────────────────
        $dataNascSql = null;
        if ($dataNasc !== '') {
            $dtObj = DateTime::createFromFormat('Y-m-d', $dataNasc);
            if ($dtObj) {
                $dataNascSql = $dtObj->format('Y-m-d');
            }
        }

        // ── Gênero: só valores permitidos ─────────────────────────────────────
        $generoPermitidos = ['M', 'F', 'O', ''];
        if (!in_array($genero, $generoPermitidos, true)) {
            $genero = '';
        }

        // ── Hash da senha ─────────────────────────────────────────────────────
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // ── Inserção ──────────────────────────────────────────────────────────
        try {
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (
                    nome, cpf, data_nascimento, telefone, genero,
                    area_atuacao, nome_escritorio,
                    email, senha_hash, oab, uf_id
                ) VALUES (
                    :nome, :cpf, :data_nascimento, :telefone, :genero,
                    :area_atuacao, :nome_escritorio,
                    :email, :senha, :oab, :uf
                )
            ");

            $stmt->execute([
                ':nome'            => $nome,
                ':cpf'             => $cpf !== '' ? $cpf : null,
                ':data_nascimento' => $dataNascSql,
                ':telefone'        => $telefone !== '' ? $telefone : null,
                ':genero'          => $genero !== '' ? $genero : null,
                ':area_atuacao'    => $areaAtuacao !== '' ? $areaAtuacao : null,
                ':nome_escritorio' => $nomeEscritorio !== '' ? $nomeEscritorio : null,
                ':email'           => $email,
                ':senha'           => $senhaHash,
                ':oab'             => $oab,
                ':uf'              => $ufId,
            ]);

            Audit::registrar('Cadastro usuário', 'usuarios', (int) $pdo->lastInsertId(), 'Email: ' . $email);

            header('Location: /login?cadastro=ok');
            exit;

        } catch (PDOException $e) {
            // Se colunas ainda não existem no banco, tenta inserção mínima
            if ($e->getCode() === '42S22' || strpos($e->getMessage(), "Unknown column") !== false) {
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
                        ':uf'    => $ufId,
                    ]);
                    Audit::registrar('Cadastro usuário (básico)', 'usuarios', (int) $pdo->lastInsertId(), 'Email: ' . $email);
                    header('Location: /login?cadastro=ok');
                    exit;
                } catch (PDOException $e2) {
                    self::showError('Falha no cadastro', 'Não foi possível concluir o cadastro. Tente novamente.', '/register');
                    exit;
                }
            }
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
            'admin'    => 1,
            'advogado' => 2,
            'cliente'  => 3,
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
            $stmt = $pdo->prepare("SELECT c.*, u.nome AS advogado_nome FROM clientes c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE LOWER(c.email) = ? LIMIT 2");
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
            Csrf::rotateToken();
            unset($_SESSION['usuario_id'], $_SESSION['usuario_nome']);

            $_SESSION['cliente_id']       = $cliente['id'];
            $_SESSION['cliente_nome']     = $cliente['nome'];
            $_SESSION['cliente_advogado'] = $cliente['advogado_nome'] ?? null;
            $_SESSION['perfil_id']        = 3;

            Audit::registrar('Login cliente', 'clientes', (int) $cliente['id'], 'Email: ' . $email);
            header('Location: /cliente');
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE LOWER(email) = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            self::showError('Credenciais inválidas', 'Email ou senha incorretos. Verifique seus dados e tente novamente.', '/login');
            exit;
        }

        if ((int) $usuario['perfil_id'] !== (int) $mapaPerfis[$perfilAcesso]) {
            self::showError(
                'Perfil incorreto',
                'O perfil selecionado não corresponde a este usuário. Verifique se você escolheu Admin ou Advogado corretamente.',
                '/login?acesso=' . urlencode($perfilAcesso),
                'warning'
            );
            exit;
        }

        if (!$usuario['ativo']) {
            self::showError('Conta desativada', 'Sua conta foi desativada pelo administrador. Entre em contato com o suporte.', '/login', 'warning');
            exit;
        }

        session_regenerate_id(true);
        Csrf::rotateToken();
        unset($_SESSION['cliente_id'], $_SESSION['cliente_nome'], $_SESSION['cliente_advogado']);

        $_SESSION['usuario_id']   = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['perfil_id']    = $usuario['perfil_id'];

        Audit::registrar('Login usuário', 'usuarios', (int) $usuario['id'], 'Perfil: ' . (int) $usuario['perfil_id']);

        if ($usuario['perfil_id'] == 1) {
            header('Location: /admin');
        } else {
            header('Location: /dashboard');
        }
        exit;
    }

    private static function showError($titulo, $mensagem, $voltarPara = '/login', $tipo = 'error')
    {
        $icone = $tipo === 'warning' ? '⚠️' : '🔒';
        $cor   = $tipo === 'warning' ? '#c9a84c' : '#ef4444';

        echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>{$titulo} — Sistema Jurídico</title>
<link href='https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap' rel='stylesheet'>
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:'DM Sans',sans-serif}
body{background:#080808;color:#f0ece3;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.wrap{max-width:460px;width:100%;animation:up .35s cubic-bezier(.22,1,.36,1) both}
@keyframes up{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.card{background:#131313;border:1px solid rgba(201,168,76,.15);border-radius:4px;padding:52px 40px;text-align:center;position:relative;overflow:hidden}
.card::before{content:'';position:absolute;top:-80px;left:50%;transform:translateX(-50%);width:320px;height:320px;border-radius:50%;background:radial-gradient(circle,rgba(201,168,76,.06),transparent 65%);pointer-events:none}
.icon{font-size:52px;margin-bottom:28px;display:block}
.title{font-family:'Cormorant Garamond',serif;font-size:28px;font-weight:400;color:{$cor};margin-bottom:12px;letter-spacing:-.3px}
.msg{color:#b8b0a4;font-size:14px;line-height:1.7;margin-bottom:36px}
.actions{display:flex;flex-direction:column;gap:10px}
.btn{padding:14px 24px;border-radius:2px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;font-size:13px;border:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;letter-spacing:.04em}
.btn-p{background:#c9a84c;color:#080808}
.btn-p:hover{background:#e2c97e;transform:translateY(-1px)}
.btn-s{background:transparent;color:#b8b0a4;border:1px solid rgba(255,255,255,.08)}
.btn-s:hover{border-color:rgba(201,168,76,.3);color:#f0ece3}
.foot{margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,.05);color:#666;font-size:12px}
</style>
</head>
<body>
<div class='wrap'>
  <div class='card'>
    <span class='icon'>{$icone}</span>
    <h1 class='title'>{$titulo}</h1>
    <p class='msg'>{$mensagem}</p>
    <div class='actions'>
      <a href='{$voltarPara}' class='btn btn-p'>← Tentar novamente</a>
      <button onclick='history.back()' class='btn btn-s'>Voltar</button>
    </div>
    <div class='foot'>Sistema Jurídico © " . date('Y') . "</div>
  </div>
</div>
<script>
  setTimeout(()=>document.querySelector('.btn-p').focus(),100);
  document.addEventListener('keypress',e=>{if(e.key==='Enter')location.href='{$voltarPara}'});
</script>
</body>
</html>";
    }
}