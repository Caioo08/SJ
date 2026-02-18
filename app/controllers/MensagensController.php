<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

class MensagensController
{
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = (int) $_SESSION['usuario_id'];
        $clienteSelecionado = isset($_GET['cliente_id']) ? (int) $_GET['cliente_id'] : 0;

        $stmt = $pdo->prepare("SELECT
                c.id,
                c.nome,
                c.email,
                (
                    SELECT m.criado_em
                    FROM mensagens_cliente m
                    WHERE m.cliente_id = c.id
                      AND m.usuario_id = c.usuario_id
                    ORDER BY m.criado_em DESC
                    LIMIT 1
                ) AS ultima_msg_em,
                (
                    SELECT COUNT(*)
                    FROM mensagens_cliente m2
                    WHERE m2.cliente_id = c.id
                      AND m2.usuario_id = c.usuario_id
                      AND m2.autor_tipo = 'cliente'
                      AND m2.lida = 0
                ) AS nao_lidas
            FROM clientes c
            WHERE c.usuario_id = ?
            ORDER BY COALESCE(ultima_msg_em, c.criado_em) DESC, c.nome ASC");
        $stmt->execute([$usuario_id]);
        $clientes = $stmt->fetchAll();

        if ($clienteSelecionado === 0 && !empty($clientes)) {
            $clienteSelecionado = (int) $clientes[0]['id'];
        }

        $mensagens = [];
        $clienteAtual = null;

        if ($clienteSelecionado > 0) {
            $stmt = $pdo->prepare("SELECT id, nome, email FROM clientes WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$clienteSelecionado, $usuario_id]);
            $clienteAtual = $stmt->fetch();

            if ($clienteAtual) {
                $stmt = $pdo->prepare("SELECT id, autor_tipo, mensagem, lida, criado_em
                    FROM mensagens_cliente
                    WHERE cliente_id = ? AND usuario_id = ?
                    ORDER BY criado_em ASC");
                $stmt->execute([$clienteSelecionado, $usuario_id]);
                $mensagens = $stmt->fetchAll();

                $stmt = $pdo->prepare("UPDATE mensagens_cliente
                    SET lida = 1
                    WHERE cliente_id = ?
                      AND usuario_id = ?
                      AND autor_tipo = 'cliente'");
                $stmt->execute([$clienteSelecionado, $usuario_id]);
            }
        }

        require_once '../views/mensagens/index.php';
    }

    public static function enviarAdvogado()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuario_id = (int) $_SESSION['usuario_id'];
        $cliente_id = isset($_POST['cliente_id']) ? (int) $_POST['cliente_id'] : 0;
        $mensagem = trim($_POST['mensagem'] ?? '');

        if ($cliente_id <= 0 || $mensagem === '') {
            header('Location: /mensagens?erro=mensagem_obrigatoria');
            exit;
        }

        $mensagem_len = function_exists('mb_strlen') ? mb_strlen($mensagem) : strlen($mensagem);
        if ($mensagem_len > 4000) {
            header('Location: /mensagens?cliente_id=' . $cliente_id . '&erro=mensagem_grande');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$cliente_id, $usuario_id]);
        if (!$stmt->fetch()) {
            header('Location: /mensagens?erro=cliente_invalido');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO mensagens_cliente (cliente_id, usuario_id, autor_tipo, mensagem, lida)
            VALUES (:cliente_id, :usuario_id, 'advogado', :mensagem, 0)");
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':usuario_id' => $usuario_id,
            ':mensagem' => $mensagem,
        ]);

        Audit::registrar('Mensagem enviada ao cliente', 'mensagens_cliente', (int) $pdo->lastInsertId(), 'Cliente ID: ' . $cliente_id);

        header('Location: /mensagens?cliente_id=' . $cliente_id);
        exit;
    }
}
