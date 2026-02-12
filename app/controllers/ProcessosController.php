<?php

require_once '../config/database.php';

class ProcessosController
{
    // Lista os processos do advogado logado
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar processos com informações do cliente vinculado (se houver)
        $stmt = $pdo->prepare("
            SELECT 
                p.id, 
                p.cliente_nome, 
                p.cliente_id,
                c.nome as cliente_vinculado_nome,
                c.cpf_cnpj,
                c.email as cliente_email,
                p.numero_processo,
                p.descricao, 
                p.status, 
                p.criado_em 
            FROM processos p
            LEFT JOIN clientes c ON p.cliente_id = c.id
            WHERE p.usuario_id = ? 
            ORDER BY p.criado_em DESC
        ");
        $stmt->execute([$usuario_id]);
        $processos = $stmt->fetchAll();

        require_once '../views/processos/index.php';
    }

    // Exibe o formulário de novo processo
    public static function create()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar clientes do usuário para o select
        $stmt = $pdo->prepare("
            SELECT id, nome, cpf_cnpj, email 
            FROM clientes 
            WHERE usuario_id = ? 
            ORDER BY nome ASC
        ");
        $stmt->execute([$usuario_id]);
        $clientes = $stmt->fetchAll();

        require_once '../views/processos/create.php';
    }

    // Armazena novo processo
    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $cliente_id = !empty($_POST['cliente_id']) ? $_POST['cliente_id'] : null;
        $cliente_nome = trim($_POST['cliente_nome'] ?? '');
        $numero_processo = trim($_POST['numero_processo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'aberto';

        // Se cliente_id foi selecionado, buscar o nome
        if ($cliente_id) {
            $stmt = $pdo->prepare("SELECT nome FROM clientes WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$cliente_id, $usuario_id]);
            $cliente = $stmt->fetch();
            if ($cliente) {
                $cliente_nome = $cliente['nome'];
            }
        }

        // Validação: ou cliente_id ou cliente_nome deve estar preenchido
        if (empty($cliente_id) && empty($cliente_nome)) {
            die("Você deve selecionar um cliente cadastrado ou digitar o nome do cliente.");
        }

        // Garantir que o status é válido
        $statusPermitido = ['aberto', 'concluido', 'arquivado'];
        if (!in_array($status, $statusPermitido)) {
            $status = 'aberto';
        }

        // Inserção segura no banco
        $stmt = $pdo->prepare("
            INSERT INTO processos (usuario_id, cliente_id, cliente_nome, numero_processo, descricao, status)
            VALUES (:usuario_id, :cliente_id, :cliente_nome, :numero_processo, :descricao, :status)
        ");

        try {
            $stmt->execute([
                ':usuario_id'       => $usuario_id,
                ':cliente_id'       => $cliente_id,
                ':cliente_nome'     => $cliente_nome,
                ':numero_processo'  => $numero_processo,
                ':descricao'        => $descricao,
                ':status'           => $status
            ]);

            header('Location: /processos');
            exit;

        } catch (PDOException $e) {
            die("Erro ao cadastrar processo: " . $e->getMessage());
        }
    }

    public static function edit($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar processo com informações do cliente
        $stmt = $pdo->prepare("
            SELECT p.*, c.nome as cliente_vinculado_nome
            FROM processos p
            LEFT JOIN clientes c ON p.cliente_id = c.id
            WHERE p.id = ? AND p.usuario_id = ?
        ");
        $stmt->execute([$id, $usuario_id]);
        $processo = $stmt->fetch();

        if (!$processo) {
            die("Processo não encontrado ou você não tem permissão.");
        }

        // Buscar clientes para o select
        $stmt = $pdo->prepare("
            SELECT id, nome, cpf_cnpj, email 
            FROM clientes 
            WHERE usuario_id = ? 
            ORDER BY nome ASC
        ");
        $stmt->execute([$usuario_id]);
        $clientes = $stmt->fetchAll();

        require_once '../views/processos/edit.php';
    }

    // Atualiza o processo
    public static function update($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $cliente_id = !empty($_POST['cliente_id']) ? $_POST['cliente_id'] : null;
        $cliente_nome = trim($_POST['cliente_nome'] ?? '');
        $numero_processo = trim($_POST['numero_processo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $status = $_POST['status'] ?? 'aberto';

        // Se cliente_id foi selecionado, buscar o nome
        if ($cliente_id) {
            $stmt = $pdo->prepare("SELECT nome FROM clientes WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$cliente_id, $usuario_id]);
            $cliente = $stmt->fetch();
            if ($cliente) {
                $cliente_nome = $cliente['nome'];
            }
        }

        // Validação
        if (empty($cliente_id) && empty($cliente_nome)) {
            die("Você deve selecionar um cliente cadastrado ou digitar o nome do cliente.");
        }

        // Validar status
        $statusPermitido = ['aberto', 'concluido', 'arquivado'];
        if (!in_array($status, $statusPermitido)) {
            die("Status inválido.");
        }

        $stmt = $pdo->prepare("
            UPDATE processos 
            SET cliente_id = :cliente_id,
                cliente_nome = :cliente_nome,
                numero_processo = :numero_processo,
                descricao = :descricao,
                status = :status, 
                atualizado_em = CURRENT_TIMESTAMP 
            WHERE id = :id AND usuario_id = :usuario_id
        ");

        try {
            $stmt->execute([
                ':cliente_id'       => $cliente_id,
                ':cliente_nome'     => $cliente_nome,
                ':numero_processo'  => $numero_processo,
                ':descricao'        => $descricao,
                ':status'           => $status,
                ':id'               => $id,
                ':usuario_id'       => $usuario_id
            ]);

            header('Location: /processos');
            exit;

        } catch (PDOException $e) {
            die("Erro ao atualizar processo: " . $e->getMessage());
        }
    }

    // Visualizar detalhes do processo
    public static function show($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar processo com informações completas do cliente
        $stmt = $pdo->prepare("
            SELECT 
                p.*,
                c.nome as cliente_vinculado_nome,
                c.cpf_cnpj,
                c.email as cliente_email,
                c.celular,
                c.telefone,
                c.endereco,
                c.numero,
                c.bairro,
                c.cidade,
                c.uf
            FROM processos p
            LEFT JOIN clientes c ON p.cliente_id = c.id
            WHERE p.id = ? AND p.usuario_id = ?
        ");
        $stmt->execute([$id, $usuario_id]);
        $processo = $stmt->fetch();

        if (!$processo) {
            die("Processo não encontrado ou você não tem permissão.");
        }

        require_once '../views/processos/show.php';
    }

    // Exibe formulário de confirmação de exclusão
    public static function confirmDelete($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar processo
        $stmt = $pdo->prepare("
            SELECT p.*, c.nome as cliente_vinculado_nome
            FROM processos p
            LEFT JOIN clientes c ON p.cliente_id = c.id
            WHERE p.id = ? AND p.usuario_id = ?
        ");
        $stmt->execute([$id, $usuario_id]);
        $processo = $stmt->fetch();

        if (!$processo) {
            die("Processo não encontrado ou você não tem permissão.");
        }

        require_once '../views/processos/confirm_delete.php';
    }

    // Deleta um processo após confirmação de senha
    public static function delete($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $senha = $_POST['senha'] ?? '';

        if (empty($senha)) {
            die("Senha é obrigatória para confirmar a exclusão.");
        }

        // Verificar senha do usuário
        $stmt = $pdo->prepare("SELECT senha_hash FROM usuarios WHERE id = ?");
        $stmt->execute([$usuario_id]);
        $usuario = $stmt->fetch();

        if (!password_verify($senha, $usuario['senha_hash'])) {
            die("Senha incorreta. A exclusão foi cancelada.");
        }

        // Verificar se o processo pertence ao usuário
        $stmt = $pdo->prepare("SELECT id FROM processos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        if (!$stmt->fetch()) {
            die("Processo não encontrado ou você não tem permissão.");
        }

        // Deletar processo
        $stmt = $pdo->prepare("DELETE FROM processos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);

        header('Location: /processos?deleted=1');
        exit;
    }
}