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

        $stmt = $pdo->prepare("\n            SELECT pr.*, p.numero_processo, p.cliente_nome\n            FROM prazos pr\n            LEFT JOIN processos p ON pr.processo_id = p.id\n            WHERE pr.usuario_id = ?\n            ORDER BY pr.data_limite ASC\n        ");
        $stmt->execute([$usuario_id]);
        $prazos = $stmt->fetchAll();

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

        $stmt = $pdo->prepare("SELECT id, numero_processo, cliente_nome FROM processos WHERE usuario_id = ? ORDER BY criado_em DESC");
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

        if ($processo_id !== null) {
            $stmt = $pdo->prepare("SELECT id FROM processos WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$processo_id, $usuario_id]);
            if (!$stmt->fetch()) {
                die('Processo inválido para este usuário.');
            }
        }

        $stmt = $pdo->prepare("\n            INSERT INTO prazos (usuario_id, processo_id, titulo, descricao, data_limite, prioridade)\n            VALUES (:usuario_id, :processo_id, :titulo, :descricao, :data_limite, :prioridade)\n        ");

        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':processo_id' => $processo_id,
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data_limite' => $data_limite,
            ':prioridade' => $prioridade,
        ]);

        header('Location: /prazos');
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

        $stmt = $pdo->prepare("SELECT concluido FROM prazos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $prazo = $stmt->fetch();

        if (!$prazo) {
            die('Prazo não encontrado.');
        }

        $novoStatus = $prazo['concluido'] ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE prazos SET concluido = ?, atualizado_em = CURRENT_TIMESTAMP WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$novoStatus, $id, $usuario_id]);

        header('Location: /prazos');
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
        $stmt = $pdo->prepare("DELETE FROM prazos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);

        header('Location: /prazos');
        exit;
    }
}
