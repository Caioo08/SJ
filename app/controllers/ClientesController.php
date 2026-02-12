<?php

require_once '../config/database.php';

class ClientesController
{
    // Lista todos os clientes do advogado logado
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("
            SELECT id, nome, cpf_cnpj, estado_civil, email, celular, cidade, uf, criado_em 
            FROM clientes 
            WHERE usuario_id = ? 
            ORDER BY nome ASC
        ");
        $stmt->execute([$usuario_id]);
        $clientes = $stmt->fetchAll();

        require_once '../views/clientes/index.php';
    }

    // Visualizar detalhes completos do cliente
    public static function show($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar cliente com todos os detalhes
        $stmt = $pdo->prepare("
            SELECT * FROM clientes 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $usuario_id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            die("Cliente não encontrado ou você não tem permissão.");
        }

        // Buscar processos vinculados ao cliente
        $stmt = $pdo->prepare("
            SELECT id, numero_processo, status, descricao, criado_em 
            FROM processos 
            WHERE cliente_id = ? 
            ORDER BY criado_em DESC
        ");
        $stmt->execute([$id]);
        $processos = $stmt->fetchAll();

        // Estatísticas do cliente
        $stats = [];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE cliente_id = ?");
        $stmt->execute([$id]);
        $stats['total_processos'] = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE cliente_id = ? AND status = 'aberto'");
        $stmt->execute([$id]);
        $stats['processos_abertos'] = $stmt->fetch()['total'];

        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE cliente_id = ? AND status = 'concluido'");
        $stmt->execute([$id]);
        $stats['processos_concluidos'] = $stmt->fetch()['total'];

        require_once '../views/clientes/show.php';
    }

    // Exibe o formulário de novo cliente
    public static function create()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        require_once '../views/clientes/create.php';
    }

    // Armazena novo cliente
    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

         // Recebe os dados do formulário
        $nome = trim($_POST['nome'] ?? '');
        $cpf_cnpj = trim($_POST['cpf_cnpj'] ?? '');
        $rg = trim($_POST['rg'] ?? '');
        $nacionalidade = trim($_POST['nacionalidade'] ?? '');
        $estado_civil = trim($_POST['estado_civil'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $celular = trim($_POST['celular'] ?? '');
        $cep = trim($_POST['cep'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $complemento = trim($_POST['complemento'] ?? '');
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $uf = trim($_POST['uf'] ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');

        // Validação simples
        if (empty($nome)) {
            die("O nome do cliente é obrigatório.");
        }

        // Inserção segura no banco
        $stmt = $pdo->prepare("
            INSERT INTO clientes (
                usuario_id, nome, cpf_cnpj, rg, nacionalidade, estado_civil, email, telefone, celular,
                cep, endereco, numero, complemento, bairro, cidade, uf, observacoes
            ) VALUES (
                :usuario_id, :nome, :cpf_cnpj, :rg, :nacionalidade, :estado_civil, :email, :telefone, :celular,
                :cep, :endereco, :numero, :complemento, :bairro, :cidade, :uf, :observacoes
            )
        ");

        try {
            $stmt->execute([
                ':usuario_id'   => $usuario_id,
                ':nome'         => $nome,
                ':cpf_cnpj'     => $cpf_cnpj,
                ':rg'           => $rg,
                ':nacionalidade'=> $nacionalidade,
                ':estado_civil' => $estado_civil ?: null,
                ':email'        => $email,
                ':telefone'     => $telefone,
                ':celular'      => $celular,
                ':cep'          => $cep,
                ':endereco'     => $endereco,
                ':numero'       => $numero,
                ':complemento'  => $complemento,
                ':bairro'       => $bairro,
                ':cidade'       => $cidade,
                ':uf'           => $uf,
                ':observacoes'  => $observacoes
            ]);

            header('Location: /clientes');
            exit;

        } catch (PDOException $e) {
            die("Erro ao cadastrar cliente: " . $e->getMessage());
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

        // Buscar cliente do usuário logado
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            die("Cliente não encontrado ou você não tem permissão.");
        }

        require_once '../views/clientes/edit.php';
    }

    // Atualiza o cliente
       // Atualiza o cliente
    public static function update($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Recebe os dados do formulário
        $nome = trim($_POST['nome'] ?? '');
        $cpf_cnpj = trim($_POST['cpf_cnpj'] ?? '');
        $rg = trim($_POST['rg'] ?? '');
        $nacionalidade = trim($_POST['nacionalidade'] ?? '');
        $estado_civil = trim($_POST['estado_civil'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $celular = trim($_POST['celular'] ?? '');
        $cep = trim($_POST['cep'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $complemento = trim($_POST['complemento'] ?? '');
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $uf = trim($_POST['uf'] ?? '');
        $observacoes = trim($_POST['observacoes'] ?? '');

        // Validação simples
        if (empty($nome)) {
            die("O nome do cliente é obrigatório.");
        }

        // Atualização segura no banco
        $stmt = $pdo->prepare("
            UPDATE clientes SET
                nome = :nome,
                cpf_cnpj = :cpf_cnpj,
                rg = :rg,
                nacionalidade = :nacionalidade,
                estado_civil = :estado_civil,
                email = :email,
                telefone = :telefone,
                celular = :celular,
                cep = :cep,
                endereco = :endereco,
                numero = :numero,
                complemento = :complemento,
                bairro = :bairro,
                cidade = :cidade,
                uf = :uf,
                observacoes = :observacoes,
                atualizado_em = CURRENT_TIMESTAMP
            WHERE id = :id AND usuario_id = :usuario_id
        ");

        try {
            $stmt->execute([
                ':nome'         => $nome,
                ':cpf_cnpj'     => $cpf_cnpj,
                ':rg'           => $rg,
                ':nacionalidade'=> $nacionalidade,
                ':estado_civil' => $estado_civil ?: null,
                ':email'        => $email,
                ':telefone'     => $telefone,
                ':celular'      => $celular,
                ':cep'          => $cep,
                ':endereco'     => $endereco,
                ':numero'       => $numero,
                ':complemento'  => $complemento,
                ':bairro'       => $bairro,
                ':cidade'       => $cidade,
                ':uf'           => $uf,
                ':observacoes'  => $observacoes,
                ':id'           => $id,
                ':usuario_id'   => $usuario_id
            ]);

            header('Location: /clientes/' . $id);
            exit;

        } catch (PDOException $e) {
            die("Erro ao atualizar cliente: " . $e->getMessage());
        }
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

        // Buscar cliente
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            die("Cliente não encontrado ou você não tem permissão.");
        }

        // Verificar se há processos vinculados
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM processos WHERE cliente_id = ?");
        $stmt->execute([$id]);
        $processos_vinculados = $stmt->fetch()['total'];

        require_once '../views/clientes/confirm_delete.php';
    }

    // Deleta um cliente após confirmação de senha
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

        // Verificar se o cliente pertence ao usuário
        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        if (!$stmt->fetch()) {
            die("Cliente não encontrado ou você não tem permissão.");
        }

        // Deletar cliente (processos vinculados terão cliente_id definido como NULL devido ao ON DELETE SET NULL)
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);

        header('Location: /clientes?deleted=1');
        exit;
    }

    public static function gerarProcuracao($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];

        // Buscar dados do cliente
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuario_id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            die("Cliente não encontrado ou você não tem permissão.");
        }

        // Buscar dados do advogado/escritório
        $stmt = $pdo->prepare("
            SELECT u.*, uf.sigla as uf_sigla, uf.nome as uf_nome
            FROM usuarios u
            LEFT JOIN ufs uf ON u.uf_id = uf.id
            WHERE u.id = ?
        ");
        $stmt->execute([$usuario_id]);
        $advogado = $stmt->fetch();

        if (!$advogado) {
            die("Erro ao carregar dados do advogado.");
        }

        require_once '../views/clientes/procuracao.php';
    }

}