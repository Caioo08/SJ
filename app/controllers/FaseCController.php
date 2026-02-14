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
}
