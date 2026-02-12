<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

class DocumentosController
{
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("\n            SELECT d.id, d.nome_original, d.tipo, d.tamanho, d.categoria, d.descricao, d.criado_em, d.visivel_cliente, c.nome AS cliente_nome\n            FROM documentos d\n            LEFT JOIN clientes c ON d.cliente_id = c.id\n            WHERE d.usuario_id = ? \n            ORDER BY d.criado_em DESC\n        ");
        $stmt->execute([$usuario_id]);
        $documentos = $stmt->fetchAll();

        require_once '../views/documentos/index.php';
    }

    public static function create()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $stmt = $pdo->prepare("SELECT id, nome FROM clientes WHERE usuario_id = ? ORDER BY nome ASC");
        $stmt->execute([$usuario_id]);
        $clientes = $stmt->fetchAll();

        require_once '../views/documentos/create.php';
    }

    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
            die("Erro ao fazer upload do arquivo.");
        }

        $arquivo = $_FILES['arquivo'];
        $categoria = $_POST['categoria'] ?? 'outros';
        $descricao = trim($_POST['descricao'] ?? '');
        $cliente_id = !empty($_POST['cliente_id']) ? (int) $_POST['cliente_id'] : null;
        $visivel_cliente = isset($_POST['visivel_cliente']) ? 1 : 0;

        if ($cliente_id) {
            $stmt = $pdo->prepare("SELECT id FROM clientes WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$cliente_id, $usuario_id]);
            if (!$stmt->fetch()) {
                die("Cliente inválido para vinculação do documento.");
            }
        }

        $tamanhoMax = 10 * 1024 * 1024;
        if ($arquivo['size'] > $tamanhoMax) {
            die("Arquivo muito grande. Tamanho máximo: 10MB");
        }

        $extensoesPermitidas = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xlsx', 'xls', 'txt', 'zip'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, $extensoesPermitidas)) {
            die("Tipo de arquivo não permitido. Use: " . implode(', ', $extensoesPermitidas));
        }

        $uploadDir = '../uploads/documentos/' . $usuario_id . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $nomeArquivo = uniqid() . '_' . time() . '.' . $extensao;
        $caminhoCompleto = $uploadDir . $nomeArquivo;

        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            die("Erro ao salvar arquivo no servidor.");
        }

        $stmt = $pdo->prepare("\n            INSERT INTO documentos (\n                usuario_id, cliente_id, visivel_cliente, nome_original, nome_arquivo, tipo, tamanho,\n                categoria, descricao, caminho\n            ) VALUES (\n                :usuario_id, :cliente_id, :visivel_cliente, :nome_original, :nome_arquivo, :tipo, :tamanho,\n                :categoria, :descricao, :caminho\n            )\n        ");

        try {
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':cliente_id' => $cliente_id,
                ':visivel_cliente' => $visivel_cliente,
                ':nome_original' => $arquivo['name'],
                ':nome_arquivo' => $nomeArquivo,
                ':tipo' => $arquivo['type'],
                ':tamanho' => $arquivo['size'],
                ':categoria' => $categoria,
                ':descricao' => $descricao,
                ':caminho' => $caminhoCompleto
            ]);

            Audit::registrar('Documento enviado', 'documentos', (int) $pdo->lastInsertId(), 'Arquivo: ' . $arquivo['name']);
            header('Location: /documentos');
            exit;

        } catch (PDOException $e) {
            if (file_exists($caminhoCompleto)) {
                unlink($caminhoCompleto);
            }
            die("Erro ao cadastrar documento: " . $e->getMessage());
        }
    }

    public static function download($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("SELECT * FROM documentos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $documento = $stmt->fetch();

        if (!$documento) {
            die("Documento não encontrado ou você não tem permissão.");
        }

        $caminho = $documento['caminho'];

        if (!file_exists($caminho)) {
            die("Arquivo não encontrado no servidor.");
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $documento['nome_original'] . '"');
        header('Content-Length: ' . filesize($caminho));
        readfile($caminho);
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

        $stmt = $pdo->prepare("SELECT * FROM documentos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $documento = $stmt->fetch();

        if (!$documento) {
            die("Documento não encontrado ou você não tem permissão.");
        }

        if (file_exists($documento['caminho'])) {
            unlink($documento['caminho']);
        }

        $stmt = $pdo->prepare("DELETE FROM documentos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);

        Audit::registrar('Documento excluído', 'documentos', (int) $id, 'Arquivo: ' . ($documento['nome_original'] ?? ''));

        header('Location: /documentos');
        exit;
    }
}
