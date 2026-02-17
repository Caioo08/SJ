<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

class HonorariosController
{
    public static function index()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $statusFiltro = trim((string) ($_GET['status'] ?? ''));
        $where = ['hc.usuario_id = :usuario_id'];
        $params = [':usuario_id' => $usuarioId];

        if (in_array($statusFiltro, ['pendente', 'parcial', 'pago', 'cancelado'], true)) {
            $where[] = 'hc.status_pagamento = :status';
            $params[':status'] = $statusFiltro;
        } else {
            $statusFiltro = '';
        }

        $sql = "SELECT hc.*, c.nome AS cliente_nome, p.numero_processo
                FROM honorarios_contratos hc
                LEFT JOIN clientes c ON c.id = hc.cliente_id
                LEFT JOIN processos p ON p.id = hc.processo_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY hc.criado_em DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $contratos = $stmt->fetchAll();

        require_once '../views/honorarios/index.php';
    }

    public static function create()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];

        $stmt = $pdo->prepare('SELECT id, nome FROM clientes WHERE usuario_id = ? ORDER BY nome ASC');
        $stmt->execute([$usuarioId]);
        $clientes = $stmt->fetchAll();

        $stmt = $pdo->prepare('SELECT id, numero_processo, cliente_nome FROM processos WHERE usuario_id = ? ORDER BY criado_em DESC');
        $stmt->execute([$usuarioId]);
        $processos = $stmt->fetchAll();

        require_once '../views/honorarios/create.php';
    }

    public static function store()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $processoId = (int) ($_POST['processo_id'] ?? 0);
        $descricao = trim((string) ($_POST['descricao'] ?? ''));
        $tipo = trim((string) ($_POST['tipo_honorario'] ?? 'fixo'));
        $valor = (float) ($_POST['valor'] ?? 0);
        $status = trim((string) ($_POST['status_pagamento'] ?? 'pendente'));
        $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

        if ($clienteId <= 0 || $descricao === '' || $valor <= 0) {
            header('Location: /honorarios/novo?erro=validacao');
            exit;
        }

        if (!in_array($tipo, ['fixo', 'exito'], true)) {
            $tipo = 'fixo';
        }
        if (!in_array($status, ['pendente', 'parcial', 'pago', 'cancelado'], true)) {
            $status = 'pendente';
        }

        $stmt = $pdo->prepare('SELECT id FROM clientes WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$clienteId, $usuarioId]);
        if (!$stmt->fetch()) {
            header('Location: /honorarios/novo?erro=cliente_invalido');
            exit;
        }

        if ($processoId > 0) {
            $stmt = $pdo->prepare('SELECT id FROM processos WHERE id = ? AND usuario_id = ?');
            $stmt->execute([$processoId, $usuarioId]);
            if (!$stmt->fetch()) {
                header('Location: /honorarios/novo?erro=processo_invalido');
                exit;
            }
        } else {
            $processoId = null;
        }

        $stmt = $pdo->prepare('INSERT INTO honorarios_contratos (usuario_id, cliente_id, processo_id, descricao, tipo_honorario, valor, status_pagamento, observacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$usuarioId, $clienteId, $processoId, $descricao, $tipo, $valor, $status, $observacoes !== '' ? $observacoes : null]);

        Audit::registrar('Contrato honorário criado', 'honorarios_contratos', (int) $pdo->lastInsertId(), 'Tipo: ' . $tipo . '; Status: ' . $status);

        header('Location: /honorarios?ok=1');
        exit;
    }

    public static function toggleStatus($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $id = (int) $id;

        $stmt = $pdo->prepare('SELECT id, status_pagamento FROM honorarios_contratos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuarioId]);
        $contrato = $stmt->fetch();

        if (!$contrato) {
            header('Location: /honorarios?erro=contrato_invalido');
            exit;
        }

        $novoStatus = ((string) $contrato['status_pagamento'] === 'pago') ? 'pendente' : 'pago';
        $stmt = $pdo->prepare('UPDATE honorarios_contratos SET status_pagamento = ? WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$novoStatus, $id, $usuarioId]);

        Audit::registrar('Contrato honorário atualizado', 'honorarios_contratos', $id, 'Status: ' . $novoStatus);

        header('Location: /honorarios');
        exit;
    }

    public static function delete($id)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $id = (int) $id;

        $stmt = $pdo->prepare('DELETE FROM honorarios_contratos WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuarioId]);

        Audit::registrar('Contrato honorário excluído', 'honorarios_contratos', $id, null);

        header('Location: /honorarios');
        exit;
    }
}
