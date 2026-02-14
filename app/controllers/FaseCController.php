<?php

require_once '../config/database.php';
require_once '../app/helpers/Audit.php';

class FaseCController
{
    public static function adicionarChecklistItem($processoId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $processoId = (int) $processoId;
        $titulo = trim($_POST['titulo'] ?? '');

        if ($processoId <= 0 || $titulo === '') {
            header('Location: /processos/' . $processoId . '?erro=checklist_invalido');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM processos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$processoId, $usuarioId]);
        if (!$stmt->fetch()) {
            header('Location: /processos?erro=processo_invalido');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO processo_checklist_itens (processo_id, usuario_id, titulo, concluido)
            VALUES (?, ?, ?, 0)");
        $stmt->execute([$processoId, $usuarioId, $titulo]);

        Audit::registrar('Checklist item criado', 'processo_checklist_itens', (int) $pdo->lastInsertId(), 'Processo ID: ' . $processoId);

        header('Location: /processos/' . $processoId);
        exit;
    }

    public static function criarChecklistPadrao($processoId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $processoId = (int) $processoId;

        $stmt = $pdo->prepare("SELECT id FROM processos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$processoId, $usuarioId]);
        if (!$stmt->fetch()) {
            header('Location: /processos?erro=processo_invalido');
            exit;
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM processo_checklist_itens WHERE processo_id = ? AND usuario_id = ?");
        $stmt->execute([$processoId, $usuarioId]);
        $total = (int) ($stmt->fetch()['total'] ?? 0);

        if ($total > 0) {
            header('Location: /processos/' . $processoId . '?info=checklist_existente');
            exit;
        }

        $itensPadrao = [
            'Conferir procuração e documentos pessoais',
            'Mapear prazos processuais iniciais',
            'Definir estratégia processual',
            'Preparar minuta inicial/defesa',
            'Validar documentação com cliente',
        ];

        $stmt = $pdo->prepare("INSERT INTO processo_checklist_itens (processo_id, usuario_id, titulo, concluido) VALUES (?, ?, ?, 0)");
        foreach ($itensPadrao as $titulo) {
            $stmt->execute([$processoId, $usuarioId, $titulo]);
        }

        Audit::registrar('Checklist padrão aplicado', 'processo_checklist_itens', $processoId, 'Itens: ' . count($itensPadrao));

        header('Location: /processos/' . $processoId);
        exit;
    }

    public static function toggleChecklistItem($itemId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $itemId = (int) $itemId;

        $stmt = $pdo->prepare("SELECT id, processo_id, concluido FROM processo_checklist_itens WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$itemId, $usuarioId]);
        $item = $stmt->fetch();

        if (!$item) {
            header('Location: /processos?erro=checklist_item_invalido');
            exit;
        }

        $novoStatus = ((int) $item['concluido'] === 1) ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE processo_checklist_itens SET concluido = ?, atualizado_em = NOW() WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$novoStatus, $itemId, $usuarioId]);

        Audit::registrar('Checklist item atualizado', 'processo_checklist_itens', $itemId, 'Concluído: ' . $novoStatus);

        header('Location: /processos/' . (int) $item['processo_id']);
        exit;
    }

    public static function adicionarPeticaoVersao($processoId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $processoId = (int) $processoId;

        $titulo = trim($_POST['titulo'] ?? '');
        $conteudo = trim($_POST['conteudo'] ?? '');
        $observacao = trim($_POST['observacao'] ?? '');

        if ($titulo === '' || $conteudo === '') {
            header('Location: /processos/' . $processoId . '?erro=peticao_invalida');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM processos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$processoId, $usuarioId]);
        if (!$stmt->fetch()) {
            header('Location: /processos?erro=processo_invalido');
            exit;
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("SELECT id FROM peticoes WHERE processo_id = ? AND usuario_id = ? AND titulo = ? LIMIT 1");
            $stmt->execute([$processoId, $usuarioId, $titulo]);
            $peticao = $stmt->fetch();

            if (!$peticao) {
                $stmt = $pdo->prepare("INSERT INTO peticoes (processo_id, usuario_id, titulo) VALUES (?, ?, ?)");
                $stmt->execute([$processoId, $usuarioId, $titulo]);
                $peticaoId = (int) $pdo->lastInsertId();
            } else {
                $peticaoId = (int) $peticao['id'];
            }

            $stmt = $pdo->prepare("SELECT COALESCE(MAX(versao), 0) + 1 AS proxima FROM peticao_versoes WHERE peticao_id = ?");
            $stmt->execute([$peticaoId]);
            $proximaVersao = (int) ($stmt->fetch()['proxima'] ?? 1);

            $stmt = $pdo->prepare("INSERT INTO peticao_versoes (peticao_id, usuario_id, versao, observacao, conteudo)
                VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$peticaoId, $usuarioId, $proximaVersao, $observacao, $conteudo]);

            $pdo->commit();

            Audit::registrar('Petição versionada', 'peticao_versoes', (int) $pdo->lastInsertId(), 'Processo ID: ' . $processoId . '; Versão: ' . $proximaVersao);

            header('Location: /processos/' . $processoId);
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            header('Location: /processos/' . $processoId . '?erro=peticao_erro');
            exit;
        }
    }

    public static function verPeticao($processoId, $peticaoId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $processoId = (int) $processoId;
        $peticaoId = (int) $peticaoId;

        $stmt = $pdo->prepare("SELECT p.id, p.titulo, p.processo_id
            FROM peticoes p
            INNER JOIN processos pr ON pr.id = p.processo_id
            WHERE p.id = ? AND p.processo_id = ? AND p.usuario_id = ? AND pr.usuario_id = ?");
        $stmt->execute([$peticaoId, $processoId, $usuarioId, $usuarioId]);
        $peticao = $stmt->fetch();

        if (!$peticao) {
            header('Location: /processos/' . $processoId . '?erro=peticao_invalida');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, versao, observacao, conteudo, criado_em
            FROM peticao_versoes
            WHERE peticao_id = ?
            ORDER BY versao DESC");
        $stmt->execute([$peticaoId]);
        $versoes = $stmt->fetchAll();

        require_once '../views/peticoes/show.php';
    }

    public static function derivarVersao($versaoId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $versaoId = (int) $versaoId;

        $stmt = $pdo->prepare("SELECT pv.id, pv.peticao_id, pv.conteudo, p.processo_id, p.usuario_id
            FROM peticao_versoes pv
            INNER JOIN peticoes p ON p.id = pv.peticao_id
            WHERE pv.id = ?");
        $stmt->execute([$versaoId]);
        $base = $stmt->fetch();

        if (!$base || (int) $base['usuario_id'] !== $usuarioId) {
            header('Location: /processos?erro=peticao_invalida');
            exit;
        }

        $peticaoId = (int) $base['peticao_id'];

        $stmt = $pdo->prepare("SELECT COALESCE(MAX(versao), 0) + 1 AS proxima FROM peticao_versoes WHERE peticao_id = ?");
        $stmt->execute([$peticaoId]);
        $proxima = (int) ($stmt->fetch()['proxima'] ?? 1);

        $stmt = $pdo->prepare("INSERT INTO peticao_versoes (peticao_id, usuario_id, versao, observacao, conteudo)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $peticaoId,
            $usuarioId,
            $proxima,
            'Versão derivada automaticamente da versão #' . $versaoId,
            (string) $base['conteudo'],
        ]);

        Audit::registrar('Petição derivada', 'peticao_versoes', (int) $pdo->lastInsertId(), 'Base versão ID: ' . $versaoId);

        header('Location: /processos/' . (int) $base['processo_id'] . '/peticoes/' . $peticaoId);
        exit;
    }
}
