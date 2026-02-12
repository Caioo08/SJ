<?php

require_once '../config/database.php';

class DocumentosController
{
    // Lista todos os documentos do usuário logado
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar documentos
        $stmt = $pdo->prepare("
            SELECT id, nome_original, tipo, tamanho, categoria, descricao, criado_em 
            FROM documentos 
            WHERE usuario_id = ? 
            ORDER BY criado_em DESC
        ");
        $stmt->execute([$usuario_id]);
        $documentos = $stmt->fetchAll();

        require_once '../views/documentos/index.php';
    }

    // Exibe o formulário de upload
    public static function create()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        require_once '../views/documentos/create.php';
    }

    // Processa o upload do documento
    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Validar se arquivo foi enviado
        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
            die("Erro ao fazer upload do arquivo.");
        }

        $arquivo = $_FILES['arquivo'];
        $categoria = $_POST['categoria'] ?? 'outros';
        $descricao = trim($_POST['descricao'] ?? '');

        // Validar tamanho (máx 10MB)
        $tamanhoMax = 10 * 1024 * 1024; // 10MB em bytes
        if ($arquivo['size'] > $tamanhoMax) {
            die("Arquivo muito grande. Tamanho máximo: 10MB");
        }

        // Extensões permitidas
        $extensoesPermitidas = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xlsx', 'xls', 'txt', 'zip'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extensao, $extensoesPermitidas)) {
            die("Tipo de arquivo não permitido. Use: " . implode(', ', $extensoesPermitidas));
        }

        // Criar diretório de uploads se não existir
        $uploadDir = '../uploads/documentos/' . $usuario_id . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Gerar nome único para o arquivo
        $nomeArquivo = uniqid() . '_' . time() . '.' . $extensao;
        $caminhoCompleto = $uploadDir . $nomeArquivo;

        // Mover arquivo
        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            die("Erro ao salvar arquivo no servidor.");
        }

        // Salvar no banco de dados
        $stmt = $pdo->prepare("
            INSERT INTO documentos (
                usuario_id, nome_original, nome_arquivo, tipo, tamanho, 
                categoria, descricao, caminho
            ) VALUES (
                :usuario_id, :nome_original, :nome_arquivo, :tipo, :tamanho,
                :categoria, :descricao, :caminho
            )
        ");

        try {
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':nome_original' => $arquivo['name'],
                ':nome_arquivo' => $nomeArquivo,
                ':tipo' => $arquivo['type'],
                ':tamanho' => $arquivo['size'],
                ':categoria' => $categoria,
                ':descricao' => $descricao,
                ':caminho' => $caminhoCompleto
            ]);

            header('Location: /documentos');
            exit;

        } catch (PDOException $e) {
            // Se erro no banco, remover arquivo
            if (file_exists($caminhoCompleto)) {
                unlink($caminhoCompleto);
            }
            die("Erro ao cadastrar documento: " . $e->getMessage());
        }
    }

    // Faz download do documento
    public static function download($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar documento
        $stmt = $pdo->prepare("
            SELECT * FROM documentos 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $usuario_id]);
        $documento = $stmt->fetch();

        if (!$documento) {
            die("Documento não encontrado ou você não tem permissão.");
        }

        $caminho = $documento['caminho'];

        if (!file_exists($caminho)) {
            die("Arquivo não encontrado no servidor.");
        }

        // Forçar download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $documento['nome_original'] . '"');
        header('Content-Length: ' . filesize($caminho));
        readfile($caminho);
        exit;
    }

    // Deleta um documento
    public static function delete($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar documento
        $stmt = $pdo->prepare("
            SELECT * FROM documentos 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $usuario_id]);
        $documento = $stmt->fetch();

        if (!$documento) {
            die("Documento não encontrado ou você não tem permissão.");
        }

        // Remover arquivo físico
        if (file_exists($documento['caminho'])) {
            unlink($documento['caminho']);
        }

        // Remover do banco
        $stmt = $pdo->prepare("DELETE FROM documentos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);

        header('Location: /documentos');
        exit;
    }
}