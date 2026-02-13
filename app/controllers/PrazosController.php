<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

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
        $dias_prazo = isset($_POST['dias_prazo']) && $_POST['dias_prazo'] !== '' ? (int) $_POST['dias_prazo'] : null;
        $tipo_contagem = $_POST['tipo_contagem'] ?? 'corridos';
        $data_base = trim($_POST['data_base'] ?? '');
        $prioridade = $_POST['prioridade'] ?? 'media';

        if ($titulo === '') {
            die('Título é obrigatório.');
        }

        if (!in_array($prioridade, ['baixa', 'media', 'alta'], true)) {
            $prioridade = 'media';
        }

        if (!in_array($tipo_contagem, ['corridos', 'uteis'], true)) {
            $tipo_contagem = 'corridos';
        }

        $data_limite = self::resolverDataLimite($data_limite, $dias_prazo, $tipo_contagem, $data_base);

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

        $prazoId = (int) $pdo->lastInsertId();
        self::registrarHistorico($prazoId, $usuario_id, 'Prazo criado', null, [
            'titulo' => $titulo,
            'data_limite' => $data_limite,
            'prioridade' => $prioridade,
            'processo_id' => $processo_id,
        ]);
        Audit::registrar('Prazo criado', 'prazos', $prazoId, 'Título: ' . $titulo);

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

        try {
            $stmt = $pdo->prepare('SELECT ph.*, u.nome as usuario_nome FROM prazo_historico ph LEFT JOIN usuarios u ON ph.usuario_id = u.id WHERE ph.prazo_id = ? ORDER BY ph.criado_em DESC LIMIT 15');
            $stmt->execute([$id]);
            $historico = $stmt->fetchAll();
        } catch (PDOException $e) {
            $historico = [];
        }

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
        $dias_prazo = isset($_POST['dias_prazo']) && $_POST['dias_prazo'] !== '' ? (int) $_POST['dias_prazo'] : null;
        $tipo_contagem = $_POST['tipo_contagem'] ?? 'corridos';
        $data_base = trim($_POST['data_base'] ?? '');
        $prioridade = $_POST['prioridade'] ?? 'media';

        if ($titulo === '') {
            die('Título é obrigatório.');
        }

        if (!in_array($prioridade, ['baixa', 'media', 'alta'], true)) {
            $prioridade = 'media';
        }

        if (!in_array($tipo_contagem, ['corridos', 'uteis'], true)) {
            $tipo_contagem = 'corridos';
        }

        $data_limite = self::resolverDataLimite($data_limite, $dias_prazo, $tipo_contagem, $data_base);

        self::validarProcessoDoUsuario($processo_id, $usuario_id, $pdo);

        $stmt = $pdo->prepare('SELECT titulo, descricao, data_limite, prioridade, processo_id FROM prazos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuario_id]);
        $prazoAntes = $stmt->fetch();

        if (!$prazoAntes) {
            die('Prazo não encontrado.');
        }

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

        self::registrarHistorico($id, $usuario_id, 'Prazo atualizado', $prazoAntes, [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data_limite' => $data_limite,
            'prioridade' => $prioridade,
            'processo_id' => $processo_id,
        ]);
        Audit::registrar('Prazo atualizado', 'prazos', (int) $id, 'Título: ' . $titulo);

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

        self::registrarHistorico((int) $id, $usuario_id, $novoStatus ? 'Prazo concluído' : 'Prazo reaberto', ['concluido' => (int) $prazo['concluido']], ['concluido' => $novoStatus]);
        Audit::registrar($novoStatus ? 'Prazo concluído' : 'Prazo reaberto', 'prazos', (int) $id, null);

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

        $stmt = $pdo->prepare('SELECT titulo FROM prazos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuario_id]);
        $prazo = $stmt->fetch();

        if (!$prazo) {
            die('Prazo não encontrado.');
        }

        $stmt = $pdo->prepare('DELETE FROM prazos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuario_id]);

        Audit::registrar('Prazo excluído', 'prazos', (int) $id, 'Título: ' . ($prazo['titulo'] ?? ''));

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

    private static function resolverDataLimite(string $dataLimiteRaw, ?int $diasPrazo, string $tipoContagem, string $dataBaseRaw): string
    {
        if ($diasPrazo !== null) {
            if ($diasPrazo <= 0) {
                die('Quantidade de dias para cálculo deve ser maior que zero.');
            }

            $dataBase = $dataBaseRaw !== '' ? self::parseDataHora($dataBaseRaw) : new DateTime();
            $dataLimite = self::calcularPorDias($dataBase, $diasPrazo, $tipoContagem);
            return $dataLimite->format('Y-m-d H:i:s');
        }

        if ($dataLimiteRaw === '') {
            die('Informe uma data limite manual ou a quantidade de dias para cálculo automático.');
        }

        return self::parseDataHora($dataLimiteRaw)->format('Y-m-d H:i:s');
    }

    private static function parseDataHora(string $valor): DateTime
    {
        $dt = DateTime::createFromFormat('Y-m-d\TH:i', $valor);
        if ($dt instanceof DateTime) {
            return $dt;
        }

        try {
            return new DateTime($valor);
        } catch (Exception $e) {
            die('Data inválida. Verifique os campos de data e hora.');
        }
    }

    private static function calcularPorDias(DateTime $dataBase, int $diasPrazo, string $tipoContagem): DateTime
    {
        $resultado = clone $dataBase;

        if ($tipoContagem === 'uteis') {
            $diasSomados = 0;
            while ($diasSomados < $diasPrazo) {
                $resultado->modify('+1 day');
                $diaSemana = (int) $resultado->format('N');
                if ($diaSemana <= 5) {
                    $diasSomados++;
                }
            }
            return $resultado;
        }

        $resultado->modify('+' . $diasPrazo . ' days');
        return $resultado;
    }

    private static function registrarHistorico(int $prazoId, int $usuarioId, string $alteracao, ?array $antes, ?array $depois): void
    {
        global $pdo;

        try {
            $stmt = $pdo->prepare('INSERT INTO prazo_historico (prazo_id, usuario_id, alteracao, antes_json, depois_json) VALUES (:prazo_id, :usuario_id, :alteracao, :antes_json, :depois_json)');
            $stmt->execute([
                ':prazo_id' => $prazoId,
                ':usuario_id' => $usuarioId,
                ':alteracao' => $alteracao,
                ':antes_json' => $antes ? json_encode($antes, JSON_UNESCAPED_UNICODE) : null,
                ':depois_json' => $depois ? json_encode($depois, JSON_UNESCAPED_UNICODE) : null,
            ]);
        } catch (PDOException $e) {
            // Mantém fluxo principal caso tabela de histórico não exista.
        }
    }
}
