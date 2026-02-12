<?php

require_once '../config/database.php';

class AdminController
{
    // Verificar se usuário é admin
    private static function verificarAdmin()
    {
        require_once __DIR__ . '/../helpers/AuthMiddleware.php';
        AuthMiddleware::verificarAdmin();
    }

    // Registrar log de auditoria
    private static function registrarLog($acao, $tabela = null, $registro_id = null, $detalhes = null)
    {
        global $pdo;

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';

        $stmt = $pdo->prepare("
            INSERT INTO logs_auditoria (usuario_id, acao, tabela, registro_id, detalhes, ip_address)
            VALUES (:usuario_id, :acao, :tabela, :registro_id, :detalhes, :ip)
        ");

        $stmt->execute([
            ':usuario_id' => $_SESSION['usuario_id'],
            ':acao' => $acao,
            ':tabela' => $tabela,
            ':registro_id' => $registro_id,
            ':detalhes' => $detalhes,
            ':ip' => $ip
        ]);
    }

    // Dashboard admin
    public static function index()
    {
        self::verificarAdmin();
        global $pdo;

        // Estatísticas gerais
        $stats = [];

        // Total de usuários ativos
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE ativo = 1 AND perfil_id = 2");
        $stats['usuarios_ativos'] = $stmt->fetch()['total'];

        // Total de usuários inativos
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE ativo = 0 AND perfil_id = 2");
        $stats['usuarios_inativos'] = $stmt->fetch()['total'];

        // Total de processos no sistema
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM processos");
        $stats['total_processos'] = $stmt->fetch()['total'];

        // Total de clientes no sistema
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes");
        $stats['total_clientes'] = $stmt->fetch()['total'];

        // Total de documentos
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM documentos");
        $stats['total_documentos'] = $stmt->fetch()['total'];

        // Espaço usado
        $stmt = $pdo->query("SELECT COALESCE(SUM(tamanho), 0) as total FROM documentos");
        $stats['espaco_usado'] = $stmt->fetch()['total'];

        // Logs recentes
        $stmt = $pdo->prepare("
            SELECT l.*, u.nome as usuario_nome
            FROM logs_auditoria l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            ORDER BY l.criado_em DESC
            LIMIT 10
        ");
        $stmt->execute();
        $logs_recentes = $stmt->fetchAll();

        require_once '../views/admin/dashboard.php';
    }

    // Listar usuários
    public static function usuarios()
    {
        self::verificarAdmin();
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT u.*, uf.sigla as uf_sigla, p.nome as perfil_nome
            FROM usuarios u
            LEFT JOIN ufs uf ON u.uf_id = uf.id
            LEFT JOIN perfis p ON u.perfil_id = p.id
            WHERE u.perfil_id = 2
            ORDER BY u.nome ASC
        ");
        $stmt->execute();
        $usuarios = $stmt->fetchAll();

        require_once '../views/admin/usuarios.php';
    }

    // Ativar/Desativar usuário
    public static function toggleUsuario($id)
    {
        self::verificarAdmin();
        global $pdo;

        // Buscar usuário
        $stmt = $pdo->prepare("SELECT ativo, nome FROM usuarios WHERE id = ? AND perfil_id = 2");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            $_SESSION['erro_msg'] = "Usuário não encontrado.";
            header('Location: /admin/usuarios');
            exit;
        }

        // Inverter status
        $novo_status = $usuario['ativo'] ? 0 : 1;

        // Atualizar no banco
        $stmt = $pdo->prepare("UPDATE usuarios SET ativo = ? WHERE id = ?");
        $stmt->execute([$novo_status, $id]);

        // Registrar log
        $acao = $novo_status ? 'Usuário ativado' : 'Usuário desativado';
        self::registrarLog($acao, 'usuarios', $id, "Usuário: {$usuario['nome']}");

        // Redirecionar com mensagem
        $msg = $novo_status ? 'ativado' : 'desativado';
        header('Location: /admin/usuarios?msg=' . $msg);
        exit;
    }

    // Excluir usuário
    public static function deleteUsuario($id)
    {
        self::verificarAdmin();
        global $pdo;

        $senha = $_POST['senha'] ?? '';

        if (empty($senha)) {
            die("Senha é obrigatória para confirmar a exclusão.");
        }

        // Verificar senha do admin
        $stmt = $pdo->prepare("SELECT senha_hash FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        $admin = $stmt->fetch();

        if (!password_verify($senha, $admin['senha_hash'])) {
            die("Senha incorreta. A exclusão foi cancelada.");
        }

        // Buscar dados do usuário
        $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ? AND perfil_id = 2");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            die("Usuário não encontrado.");
        }

        // Deletar documentos físicos do usuário
        $stmt = $pdo->prepare("SELECT caminho FROM documentos WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $documentos = $stmt->fetchAll();

        foreach ($documentos as $doc) {
            if (file_exists($doc['caminho'])) {
                unlink($doc['caminho']);
            }
        }

        // Deletar diretório do usuário
        $uploadDir = '../uploads/documentos/' . $id;
        if (is_dir($uploadDir)) {
            rmdir($uploadDir);
        }

        // Deletar usuário (cascata deleta tudo relacionado)
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        self::registrarLog("Usuário excluído", 'usuarios', $id, "Usuário: {$usuario['nome']}");

        header('Location: /admin/usuarios?msg=excluido');
        exit;
    }

    // Visualizar detalhes do usuário
    public static function verUsuario($id)
    {
        self::verificarAdmin();
        global $pdo;

        // Dados do usuário
        $stmt = $pdo->prepare("
            SELECT u.*, uf.sigla as uf_sigla, p.nome as perfil_nome
            FROM usuarios u
            LEFT JOIN ufs uf ON u.uf_id = uf.id
            LEFT JOIN perfis p ON u.perfil_id = p.id
            WHERE u.id = ? AND u.perfil_id = 2
        ");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            die("Usuário não encontrado.");
        }

        // Estatísticas do usuário
        $stats = [];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM clientes WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $stats['clientes'] = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $stats['processos'] = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM compromissos WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $stats['compromissos'] = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM documentos WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $stats['documentos'] = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COALESCE(SUM(tamanho), 0) as total FROM documentos WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $stats['espaco_usado'] = $stmt->fetch()['total'];

        // Logs do usuário
        $stmt = $pdo->prepare("
            SELECT * FROM logs_auditoria
            WHERE usuario_id = ?
            ORDER BY criado_em DESC
            LIMIT 20
        ");
        $stmt->execute([$id]);
        $logs = $stmt->fetchAll();

        require_once '../views/admin/usuario_detalhes.php';
    }

    // Logs de auditoria
    public static function logs()
    {
        self::verificarAdmin();
        global $pdo;

        $acaoFiltro = trim($_GET['acao'] ?? '');
        $periodoFiltro = $_GET['periodo'] ?? '7d';

        $where = [];
        $params = [];

        if ($acaoFiltro !== '') {
            $where[] = 'l.acao LIKE :acao';
            $params[':acao'] = '%' . $acaoFiltro . '%';
        }

        if ($periodoFiltro === '24h') {
            $where[] = 'l.criado_em >= DATE_SUB(NOW(), INTERVAL 1 DAY)';
        } elseif ($periodoFiltro === '30d') {
            $where[] = 'l.criado_em >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
        } else {
            $where[] = 'l.criado_em >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
            $periodoFiltro = '7d';
        }

        $sqlWhere = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        $stmt = $pdo->prepare("
            SELECT l.*, u.nome as usuario_nome
            FROM logs_auditoria l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            {$sqlWhere}
            ORDER BY l.criado_em DESC
            LIMIT 200
        ");
        $stmt->execute($params);
        $logs = $stmt->fetchAll();

        require_once '../views/admin/logs.php';
    }

    // Confirmar exclusão de usuário
    public static function confirmDeleteUsuario($id)
    {
        self::verificarAdmin();
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT u.*, uf.sigla as uf_sigla
            FROM usuarios u
            LEFT JOIN ufs uf ON u.uf_id = uf.id
            WHERE u.id = ? AND u.perfil_id = 2
        ");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            die("Usuário não encontrado.");
        }

        // Contar dados vinculados
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM clientes WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $total_clientes = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $total_processos = $stmt->fetch()['total'];

        require_once '../views/admin/confirm_delete_usuario.php';
    }
}