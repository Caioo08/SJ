<?php

require_once '../config/database.php';

class PrazosController
{
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $statusFiltro = $_GET['status'] ?? 'abertos';
        $prioridadeFiltro = $_GET['prioridade'] ?? '';
        $busca = trim($_GET['q'] ?? '');

        $where = ['pr.usuario_id = :usuario_id'];
        $params = [':usuario_id' => $usuario_id];

        if ($statusFiltro === 'concluidos') {
            $where[] = 'pr.concluido = 1';
        } elseif ($statusFiltro === 'atrasados') {
            $where[] = 'pr.concluido = 0 AND pr.data_limite < NOW()';
        } else {
            $where[] = 'pr.concluido = 0';
            $statusFiltro = 'abertos';
        }

        if (in_array($prioridadeFiltro, ['baixa', 'media', 'alta'], true)) {
            $where[] = 'pr.prioridade = :prioridade';
            $params[':prioridade'] = $prioridadeFiltro;
        } else {
            $prioridadeFiltro = '';
        }

        if ($busca !== '') {
            $where[] = '(pr.titulo LIKE :busca OR COALESCE(p.numero_processo, "") LIKE :busca OR COALESCE(p.cliente_nome, "") LIKE :busca)';
            $params[':busca'] = '%' . $busca . '%';
        }

        $sqlWhere = implode(' AND ', $where);

        $stmt = $pdo->prepare("SELECT pr.*, p.numero_processo, p.cliente_nome
            FROM prazos pr
            LEFT JOIN processos p ON pr.processo_id = p.id
            WHERE {$sqlWhere}
            ORDER BY pr.concluido ASC, pr.data_limite ASC");
        $stmt->execute($params);
        $prazos = $stmt->fetchAll();

        $statsStmt = $pdo->prepare("SELECT
            SUM(CASE WHEN concluido = 0 THEN 1 ELSE 0 END) AS abertos,
            SUM(CASE WHEN concluido = 1 THEN 1 ELSE 0 END) AS concluidos,
            SUM(CASE WHEN concluido = 0 AND data_limite < NOW() THEN 1 ELSE 0 END) AS atrasados
            FROM prazos
            WHERE usuario_id = ?");
        $statsStmt->execute([$usuario_id]);
        $stats = $statsStmt->fetch() ?: ['abertos' => 0, 'concluidos' => 0, 'atrasados' => 0];

        require_once '../views/prazos/index.php';
    }

    public static function create()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare('SELECT id, numero_processo, cliente_nome FROM processos WHERE usuario_id = ? ORDER BY criado_em DESC');
        $stmt->execute([$usuario_id]);
        $processos = $stmt->fetchAll();

        require_once '../views/prazos/create.php';
    }

    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $processo_id = !empty($_POST['processo_id']) ? (int) $_POST['processo_id'] : null;
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $data_limite = trim($_POST['data_limite'] ?? '');
        $prioridade = $_POST['prioridade'] ?? 'media';

        if ($titulo === '' || $data_limite === '') {
            die('Título e data limite são obrigatórios.');
        }

        if (!in_array($prioridade, ['baixa', 'media', 'alta'], true)) {
            $prioridade = 'media';
        }

        self::validarProcessoDoUsuario($processo_id, $usuario_id, $pdo);

        $stmt = $pdo->prepare('INSERT INTO prazos (usuario_id, processo_id, titulo, descricao, data_limite, prioridade)
            VALUES (:usuario_id, :processo_id, :titulo, :descricao, :data_limite, :prioridade)');

        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':processo_id' => $processo_id,
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data_limite' => $data_limite,
            ':prioridade' => $prioridade,
        ]);

        header('Location: /prazos?msg=criado');
        exit;
    }

    public static function edit($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare('SELECT * FROM prazos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuario_id]);
        $prazo = $stmt->fetch();

        if (!$prazo) {
            die('Prazo não encontrado.');
        }

        $stmt = $pdo->prepare('SELECT id, numero_processo, cliente_nome FROM processos WHERE usuario_id = ? ORDER BY criado_em DESC');
        $stmt->execute([$usuario_id]);
        $processos = $stmt->fetchAll();

        require_once '../views/prazos/edit.php';
    }

    public static function update($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $processo_id = !empty($_POST['processo_id']) ? (int) $_POST['processo_id'] : null;
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $data_limite = trim($_POST['data_limite'] ?? '');
        $prioridade = $_POST['prioridade'] ?? 'media';

        if ($titulo === '' || $data_limite === '') {
            die('Título e data limite são obrigatórios.');
        }

        if (!in_array($prioridade, ['baixa', 'media', 'alta'], true)) {
            $prioridade = 'media';
        }

        self::validarProcessoDoUsuario($processo_id, $usuario_id, $pdo);

        $stmt = $pdo->prepare('UPDATE prazos
            SET processo_id = :processo_id,
                titulo = :titulo,
                descricao = :descricao,
                data_limite = :data_limite,
                prioridade = :prioridade,
                atualizado_em = CURRENT_TIMESTAMP
            WHERE id = :id AND usuario_id = :usuario_id');

        $stmt->execute([
            ':processo_id' => $processo_id,
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data_limite' => $data_limite,
            ':prioridade' => $prioridade,
            ':id' => $id,
            ':usuario_id' => $usuario_id,
        ]);

        header('Location: /prazos?msg=atualizado');
        exit;
    }

    public static function toggleConclusao($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare('SELECT concluido FROM prazos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuario_id]);
        $prazo = $stmt->fetch();

        if (!$prazo) {
            die('Prazo não encontrado.');
        }

        $novoStatus = $prazo['concluido'] ? 0 : 1;
        $stmt = $pdo->prepare('UPDATE prazos SET concluido = ?, atualizado_em = CURRENT_TIMESTAMP WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$novoStatus, $id, $usuario_id]);

        header('Location: /prazos?msg=status');
        exit;
    }

    public static function delete($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $stmt = $pdo->prepare('DELETE FROM prazos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuario_id]);

        header('Location: /prazos?msg=excluido');
        exit;
    }

    private static function validarProcessoDoUsuario($processo_id, $usuario_id, $pdo)
    {
        if ($processo_id !== null) {
            $stmt = $pdo->prepare('SELECT id FROM processos WHERE id = ? AND usuario_id = ?');
            $stmt->execute([$processo_id, $usuario_id]);
            if (!$stmt->fetch()) {
                die('Processo inválido para este usuário.');
            }
        }
    }
}
