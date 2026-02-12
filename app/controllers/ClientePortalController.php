<?php

require_once '../config/database.php';

class ClientePortalController
{
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['cliente_id'])) {
            header('Location: /login?acesso=cliente');
            exit;
        }

        $cliente_id = $_SESSION['cliente_id'];

        $stmt = $pdo->prepare("SELECT id, nome, email, celular, cidade, uf FROM clientes WHERE id = ?");
        $stmt->execute([$cliente_id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            session_destroy();
            header('Location: /login?acesso=cliente');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, numero_processo, status, descricao, criado_em, atualizado_em FROM processos WHERE cliente_id = ? ORDER BY criado_em DESC");
        $stmt->execute([$cliente_id]);
        $processos = $stmt->fetchAll();

        try {
            $stmt = $pdo->prepare("SELECT id, nome_original, categoria, criado_em FROM documentos WHERE cliente_id = ? AND visivel_cliente = 1 ORDER BY criado_em DESC LIMIT 20");
            $stmt->execute([$cliente_id]);
            $documentos = $stmt->fetchAll();
        } catch (PDOException $e) {
            $documentos = [];
        }

        require_once '../views/cliente_portal/index.php';
    }

    public static function showProcesso($id)
    {
        global $pdo;

        if (!isset($_SESSION['cliente_id'])) {
            header('Location: /login?acesso=cliente');
            exit;
        }

        $cliente_id = $_SESSION['cliente_id'];

        $stmt = $pdo->prepare("SELECT p.*, u.nome AS advogado_nome, u.email AS advogado_email, u.oab
            FROM processos p
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.id = ? AND p.cliente_id = ?");
        $stmt->execute([$id, $cliente_id]);
        $processo = $stmt->fetch();

        if (!$processo) {
            die('Processo não encontrado para este cliente.');
        }

        try {
            $stmt = $pdo->prepare("SELECT titulo, descricao, tipo, criado_em FROM processo_eventos WHERE processo_id = ? ORDER BY criado_em DESC");
            $stmt->execute([$id]);
            $eventos = $stmt->fetchAll();
        } catch (PDOException $e) {
            $eventos = [];
        }

        require_once '../views/cliente_portal/show_processo.php';
    }
}
