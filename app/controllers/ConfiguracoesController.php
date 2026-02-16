<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

class ConfiguracoesController
{
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar dados do usuário
        $stmt = $pdo->prepare("
            SELECT u.*, uf.sigla as uf_sigla
            FROM usuarios u
            LEFT JOIN ufs uf ON u.uf_id = uf.id
            WHERE u.id = ?
        ");
        $stmt->execute([$usuario_id]);
        $usuario = $stmt->fetch();

        // Buscar todas as UFs para o select
        $stmt = $pdo->query("SELECT id, sigla, nome FROM ufs ORDER BY sigla");
        $ufs = $stmt->fetchAll();

        // Estatísticas gerais
        $stats = [];
        
        // Total de clientes
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM clientes WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $stats['clientes'] = $stmt->fetch()['total'];
        
        // Total de processos
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $stats['processos'] = $stmt->fetch()['total'];
        
        // Total de compromissos
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM compromissos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $stats['compromissos'] = $stmt->fetch()['total'];
        
        // Total de documentos
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM documentos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $stats['documentos'] = $stmt->fetch()['total'];
        
        // Espaço usado em documentos
        $stmt = $pdo->prepare("SELECT COALESCE(SUM(tamanho), 0) as total FROM documentos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $stats['espaco_usado'] = $stmt->fetch()['total'];

        require_once '../views/configuracoes/index.php';
    }

    public static function updateProfile()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $oab = trim($_POST['oab'] ?? '');
        $uf_id = $_POST['uf_id'] ?? '';

        if (empty($nome) || empty($email) || empty($oab) || empty($uf_id)) {
            die("Todos os campos são obrigatórios.");
        }

        // Verificar se o email já está em uso por outro usuário
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $usuario_id]);
        if ($stmt->fetch()) {
            die("Este email já está em uso por outro usuário.");
        }

        try {
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nome = :nome, 
                    email = :email, 
                    oab = :oab, 
                    uf_id = :uf_id,
                    atualizado_em = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':oab' => $oab,
                ':uf_id' => $uf_id,
                ':id' => $usuario_id
            ]);

            // Atualizar nome na sessão
            $_SESSION['usuario_nome'] = $nome;
            Audit::registrar('Perfil atualizado', 'usuarios', (int) $usuario_id, 'Nome: ' . $nome);

            header('Location: /configuracoes?sucesso=perfil');
            exit;

        } catch (PDOException $e) {
            die("Erro ao atualizar perfil: " . $e->getMessage());
        }
    }

    public static function updatePassword()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $senha_atual = $_POST['senha_atual'] ?? '';
        $senha_nova = $_POST['senha_nova'] ?? '';
        $senha_confirmacao = $_POST['senha_confirmacao'] ?? '';

        if (empty($senha_atual) || empty($senha_nova) || empty($senha_confirmacao)) {
            die("Todos os campos de senha são obrigatórios.");
        }

        if ($senha_nova !== $senha_confirmacao) {
            die("A nova senha e a confirmação não coincidem.");
        }

        if (strlen($senha_nova) < 6) {
            die("A nova senha deve ter pelo menos 6 caracteres.");
        }

        // Verificar senha atual
        $stmt = $pdo->prepare("SELECT senha_hash FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $usuario = $stmt->fetch();

        if (!password_verify($senha_atual, $usuario['senha_hash'])) {
            die("Senha atual incorreta.");
        }

        // Atualizar senha
        $nova_senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET senha_hash = :senha_hash,
                    atualizado_em = CURRENT_TIMESTAMP
                WHERE id = :id
            ");

            $stmt->execute([
                ':senha_hash' => $nova_senha_hash,
                ':id' => $usuario_id
            ]);

            Audit::registrar('Senha atualizada', 'usuarios', (int) $usuario_id, null);

            header('Location: /configuracoes?sucesso=senha');
            exit;

        } catch (PDOException $e) {
            die("Erro ao atualizar senha: " . $e->getMessage());
        }
    }

    public static function deleteAccount()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $confirmacao = $_POST['confirmacao'] ?? '';

        if ($confirmacao !== 'EXCLUIR') {
            die("Confirmação inválida. Digite 'EXCLUIR' para confirmar.");
        }

        try {
            // Deletar documentos físicos
            $stmt = $pdo->prepare("SELECT caminho FROM documentos WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $documentos = $stmt->fetchAll();

            foreach ($documentos as $doc) {
                if (file_exists($doc['caminho'])) {
                    unlink($doc['caminho']);
                }
            }

            // Deletar diretório do usuário
            $uploadDir = '../uploads/documentos/' . $usuario_id;
            if (is_dir($uploadDir)) {
                rmdir($uploadDir);
            }

            Audit::registrar('Conta excluída', 'usuarios', (int) $usuario_id, null);

            // Deletar usuário (cascata deleta tudo relacionado)
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$usuario_id]);

            // Destruir sessão
            $_SESSION = [];
            session_destroy();

            header('Location: /login?conta_excluida=1');
            exit;

        } catch (PDOException $e) {
            die("Erro ao excluir conta: " . $e->getMessage());
        }
    }

    public static function updateEscritorio()
{
    global $pdo;

    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /login');
        exit;
    }

    $usuario_id = $_SESSION['usuario_id'];

    $escritorio_cep = trim($_POST['escritorio_cep'] ?? '');
    $escritorio_endereco = trim($_POST['escritorio_endereco'] ?? '');
    $escritorio_numero = trim($_POST['escritorio_numero'] ?? '');
    $escritorio_complemento = trim($_POST['escritorio_complemento'] ?? '');
    $escritorio_bairro = trim($_POST['escritorio_bairro'] ?? '');
    $escritorio_cidade = trim($_POST['escritorio_cidade'] ?? '');
    $escritorio_uf = trim($_POST['escritorio_uf'] ?? '');

    try {
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET escritorio_cep = :cep, 
                escritorio_endereco = :endereco, 
                escritorio_numero = :numero, 
                escritorio_complemento = :complemento,
                escritorio_bairro = :bairro,
                escritorio_cidade = :cidade,
                escritorio_uf = :uf,
                atualizado_em = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            ':cep' => $escritorio_cep,
            ':endereco' => $escritorio_endereco,
            ':numero' => $escritorio_numero,
            ':complemento' => $escritorio_complemento,
            ':bairro' => $escritorio_bairro,
            ':cidade' => $escritorio_cidade,
            ':uf' => $escritorio_uf,
            ':id' => $usuario_id
        ]);

        Audit::registrar('Endereço do escritório atualizado', 'usuarios', (int) $usuario_id, null);

        header('Location: /configuracoes?sucesso=escritorio');
        exit;

    } catch (PDOException $e) {
        die("Erro ao atualizar endereço do escritório: " . $e->getMessage());
    }
}
}