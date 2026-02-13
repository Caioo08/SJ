<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

class CompromissosController
{
    // Lista todos os compromissos do advogado logado
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("
            SELECT id, titulo, descricao, data_inicio, data_fim, local, criado_em 
            FROM compromissos 
            WHERE usuario_id = ? 
            ORDER BY data_inicio DESC
        ");
        $stmt->execute([$usuario_id]);
        $compromissos = $stmt->fetchAll();

        require_once '../views/compromissos/index.php';
    }

    // Exibe o formulário de novo compromisso
    public static function create()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        require_once '../views/compromissos/create.php';
    }

    // Armazena novo compromisso
    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $data_inicio = trim($_POST['data_inicio'] ?? '');
        $data_fim = trim($_POST['data_fim'] ?? '');
        $local = trim($_POST['local'] ?? '');

        // Validação
        if (empty($titulo) || empty($data_inicio)) {
            die("Título e data de início são obrigatórios.");
        }

        // Inserção no banco
        $stmt = $pdo->prepare("
            INSERT INTO compromissos (usuario_id, titulo, descricao, data_inicio, data_fim, local)
            VALUES (:usuario_id, :titulo, :descricao, :data_inicio, :data_fim, :local)
        ");

        try {
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':data_inicio' => $data_inicio,
                ':data_fim' => $data_fim ?: null,
                ':local' => $local
            ]);

            Audit::registrar('Compromisso criado', 'compromissos', (int) $pdo->lastInsertId(), 'Título: ' . $titulo);

            header('Location: /compromissos');
            exit;

        } catch (PDOException $e) {
            die("Erro ao cadastrar compromisso: " . $e->getMessage());
        }
    }

    // Exibe formulário de edição
    public static function edit($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("SELECT * FROM compromissos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $compromisso = $stmt->fetch();

        if (!$compromisso) {
            die("Compromisso não encontrado ou você não tem permissão.");
        }

        require_once '../views/compromissos/edit.php';
    }

    // Atualiza o compromisso
    public static function update($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $data_inicio = trim($_POST['data_inicio'] ?? '');
        $data_fim = trim($_POST['data_fim'] ?? '');
        $local = trim($_POST['local'] ?? '');

        // Validação
        if (empty($titulo) || empty($data_inicio)) {
            die("Título e data de início são obrigatórios.");
        }

        $stmt = $pdo->prepare("
            UPDATE compromissos 
            SET titulo = :titulo, 
                descricao = :descricao, 
                data_inicio = :data_inicio, 
                data_fim = :data_fim, 
                local = :local
            WHERE id = :id AND usuario_id = :usuario_id
        ");

        try {
            $stmt->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao,
                ':data_inicio' => $data_inicio,
                ':data_fim' => $data_fim ?: null,
                ':local' => $local,
                ':id' => $id,
                ':usuario_id' => $usuario_id
            ]);

            Audit::registrar('Compromisso atualizado', 'compromissos', (int) $id, 'Título: ' . $titulo);

            header('Location: /compromissos');
            exit;

        } catch (PDOException $e) {
            die("Erro ao atualizar compromisso: " . $e->getMessage());
        }
    }

    // Deleta um compromisso
    public static function delete($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("SELECT titulo FROM compromissos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $compromisso = $stmt->fetch();

        if (!$compromisso) {
            die("Compromisso não encontrado ou você não tem permissão.");
        }

        $stmt = $pdo->prepare("DELETE FROM compromissos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);

        Audit::registrar('Compromisso excluído', 'compromissos', (int) $id, 'Título: ' . ($compromisso['titulo'] ?? ''));

        header('Location: /compromissos');
        exit;
    }
}