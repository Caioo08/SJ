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

    public static function salvarModeloChecklist()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $nome = trim($_POST['nome'] ?? '');
        $tipoAcao = trim($_POST['tipo_acao'] ?? 'geral');
        $itensBrutos = trim($_POST['itens'] ?? '');
        $itensBrutos = trim($_POST['itens'] ?? '');

        $itens = array_values(array_filter(array_map('trim', preg_split('/\R+/', $itensBrutos ?: ''))));

        if ($nome === '' || empty($itens)) {
            header('Location: /processos?erro=modelo_invalido');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO checklist_modelos (usuario_id, nome, tipo_acao, itens_json, ativo)
            VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$usuarioId, $nome, $tipoAcao, json_encode($itens, JSON_UNESCAPED_UNICODE)]);

        Audit::registrar('Modelo checklist criado', 'checklist_modelos', (int) $pdo->lastInsertId(), 'Tipo: ' . $tipoAcao);

        header('Location: /processos?ok=modelo_criado');
        exit;
    }


    public static function modelos()
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];

        $stmt = $pdo->prepare("SELECT id, nome, tipo_acao, ativo, criado_em FROM checklist_modelos WHERE usuario_id = ? ORDER BY ativo DESC, nome ASC");
        $stmt->execute([$usuarioId]);
        $modelos = $stmt->fetchAll();

        require_once '../views/checklists/modelos_index.php';
    }

    public static function editarModelo($modeloId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $modeloId = (int) $modeloId;

        $stmt = $pdo->prepare("SELECT id, nome, tipo_acao, itens_json, ativo FROM checklist_modelos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$modeloId, $usuarioId]);
        $modelo = $stmt->fetch();

        if (!$modelo) {
            header('Location: /checklists/modelos?erro=modelo_invalido');
            exit;
        }

        $itens = json_decode($modelo['itens_json'] ?? '[]', true);
        if (!is_array($itens)) {
            $itens = [];
        }

        require_once '../views/checklists/modelos_edit.php';
    }

    public static function atualizarModelo($modeloId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $modeloId = (int) $modeloId;

        $nome = trim($_POST['nome'] ?? '');
        $tipoAcao = trim($_POST['tipo_acao'] ?? 'geral');
        $itensBrutos = trim($_POST['itens'] ?? '');
        $itens = array_values(array_filter(array_map('trim', preg_split('/\\R+/', $itensBrutos ?: ''))));

        if ($nome === '' || empty($itens)) {
            header('Location: /checklists/modelos/' . $modeloId . '/editar?erro=modelo_invalido');
            exit;
        }

        $stmt = $pdo->prepare("UPDATE checklist_modelos SET nome = ?, tipo_acao = ?, itens_json = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$nome, $tipoAcao, json_encode($itens, JSON_UNESCAPED_UNICODE), $modeloId, $usuarioId]);

        Audit::registrar('Modelo checklist atualizado', 'checklist_modelos', $modeloId, 'Tipo: ' . $tipoAcao);

        header('Location: /checklists/modelos');
        exit;
    }

    public static function toggleModeloChecklist($modeloId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $modeloId = (int) $modeloId;

        $stmt = $pdo->prepare("SELECT id, ativo FROM checklist_modelos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$modeloId, $usuarioId]);
        $modelo = $stmt->fetch();

        if (!$modelo) {
            header('Location: /checklists/modelos?erro=modelo_invalido');
            exit;
        }

        $novo = ((int) $modelo['ativo'] === 1) ? 0 : 1;
        $stmt = $pdo->prepare("UPDATE checklist_modelos SET ativo = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$novo, $modeloId, $usuarioId]);

        Audit::registrar('Modelo checklist alternado', 'checklist_modelos', $modeloId, 'Ativo: ' . $novo);

        $processoId = (int) ($_POST['processo_id'] ?? 0);
        if ($processoId > 0) {
            header('Location: /processos/' . $processoId);
        } else {
            header('Location: /checklists/modelos');
        }
        exit;
    }

    public static function excluirModeloChecklist($modeloId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $modeloId = (int) $modeloId;

        $stmt = $pdo->prepare("DELETE FROM checklist_modelos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$modeloId, $usuarioId]);

        Audit::registrar('Modelo checklist excluído', 'checklist_modelos', $modeloId, null);

        header('Location: /checklists/modelos');
        exit;
    }

    public static function aplicarModeloChecklistSelecionado($processoId)
    {
        $modeloId = (int) ($_POST['modelo_id'] ?? 0);
        self::aplicarModeloChecklist($processoId, $modeloId);
    }

    public static function aplicarModeloChecklist($processoId, $modeloId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $processoId = (int) $processoId;
        $modeloId = (int) $modeloId;

        $stmt = $pdo->prepare("SELECT id FROM processos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$processoId, $usuarioId]);
        if (!$stmt->fetch()) {
            header('Location: /processos?erro=processo_invalido');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id, itens_json, nome FROM checklist_modelos WHERE id = ? AND usuario_id = ? AND ativo = 1");
        $stmt->execute([$modeloId, $usuarioId]);
        $modelo = $stmt->fetch();

        if (!$modelo) {
            header('Location: /processos/' . $processoId . '?erro=modelo_invalido');
            exit;
        }

        $itens = json_decode($modelo['itens_json'] ?? '[]', true);
        if (!is_array($itens) || empty($itens)) {
            header('Location: /processos/' . $processoId . '?erro=modelo_vazio');
            exit;
        }

        $stmt = $pdo->prepare("INSERT IGNORE INTO processo_checklist_itens (processo_id, usuario_id, titulo, concluido)
            VALUES (?, ?, ?, 0)");
        $inseridos = 0;
        foreach ($itens as $titulo) {
            $titulo = trim((string) $titulo);
            if ($titulo === '') {
                continue;
            }
            $stmt->execute([$processoId, $usuarioId, $titulo]);
            $inseridos += (int) $stmt->rowCount();
        }

        Audit::registrar('Modelo checklist aplicado', 'processo_checklist_itens', $processoId, 'Modelo: ' . $modelo['nome'] . '; Itens novos: ' . $inseridos);

        header('Location: /processos/' . $processoId);
        exit;
    }

    public static function desativarModeloChecklist($modeloId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $modeloId = (int) $modeloId;
        $processoId = (int) ($_POST['processo_id'] ?? 0);

        $stmt = $pdo->prepare("UPDATE checklist_modelos SET ativo = 0 WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$modeloId, $usuarioId]);

        Audit::registrar('Modelo checklist desativado', 'checklist_modelos', $modeloId, null);

        if ($processoId > 0) {
            header('Location: /processos/' . $processoId);
        } else {
            header('Location: /processos');
        }
        exit;
    }

    public static function removerChecklistItem($itemId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $itemId = (int) $itemId;

        $stmt = $pdo->prepare("SELECT id, processo_id, titulo FROM processo_checklist_itens WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$itemId, $usuarioId]);
        $item = $stmt->fetch();

        if (!$item) {
            header('Location: /processos?erro=checklist_item_invalido');
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM processo_checklist_itens WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$itemId, $usuarioId]);

        Audit::registrar('Checklist item removido', 'processo_checklist_itens', $itemId, 'Título: ' . ($item['titulo'] ?? ''));

        header('Location: /processos/' . (int) $item['processo_id']);
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

        $arquivoOriginal = null;
        $arquivoCaminho = null;

        if (!empty($_FILES['arquivo_peticao']['name']) && ($_FILES['arquivo_peticao']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $nomeTmp = $_FILES['arquivo_peticao']['tmp_name'];
            $nomeOriginal = basename((string) $_FILES['arquivo_peticao']['name']);
            $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            $permitidas = ['pdf', 'doc', 'docx', 'txt'];

            if (!in_array($ext, $permitidas, true)) {
                header('Location: /processos/' . $processoId . '?erro=arquivo_invalido');
                exit;
            }

            $dir = __DIR__ . '/../../public/uploads/peticoes';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }

            $arquivoSeguro = uniqid('peticao_', true) . '.' . $ext;
            $destino = $dir . '/' . $arquivoSeguro;
            if (@move_uploaded_file($nomeTmp, $destino)) {
                $arquivoOriginal = $nomeOriginal;
                $arquivoCaminho = '/uploads/peticoes/' . $arquivoSeguro;
            }
        }

        if ($titulo === '' || ($conteudo === '' && $arquivoCaminho === null)) {
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

            $stmt = $pdo->prepare("INSERT INTO peticao_versoes (peticao_id, usuario_id, versao, observacao, conteudo, arquivo_original, arquivo_caminho)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$peticaoId, $usuarioId, $proximaVersao, $observacao, $conteudo !== '' ? $conteudo : null, $arquivoOriginal, $arquivoCaminho]);

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

        $stmt = $pdo->prepare("SELECT pv.id, pv.versao, pv.observacao, pv.conteudo, pv.arquivo_original, pv.arquivo_caminho, pv.criado_em, u.nome AS autor_nome
            FROM peticao_versoes pv
            LEFT JOIN usuarios u ON u.id = pv.usuario_id
            WHERE pv.peticao_id = ?
            ORDER BY pv.versao DESC");
        $stmt->execute([$peticaoId]);
        $versoes = $stmt->fetchAll();

        require_once '../views/peticoes/show.php';
    }

    public static function downloadArquivoVersao($versaoId)
    {
        global $pdo;

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];
        $versaoId = (int) $versaoId;

        $stmt = $pdo->prepare("SELECT pv.id, pv.arquivo_original, pv.arquivo_caminho, p.usuario_id
            FROM peticao_versoes pv
            INNER JOIN peticoes p ON p.id = pv.peticao_id
            WHERE pv.id = ?");
        $stmt->execute([$versaoId]);
        $versao = $stmt->fetch();

        if (!$versao || (int) $versao['usuario_id'] !== $usuarioId || empty($versao['arquivo_caminho'])) {
            http_response_code(404);
            echo 'Arquivo não encontrado.';
            exit;
        }

        $base = realpath(__DIR__ . '/../../public/uploads/peticoes');
        $basename = basename((string) $versao['arquivo_caminho']);
        $filePath = $base ? ($base . DIRECTORY_SEPARATOR . $basename) : '';

        if ($filePath === '' || !is_file($filePath)) {
            http_response_code(404);
            echo 'Arquivo não encontrado.';
            exit;
        }

        $fileName = $versao['arquivo_original'] ?: $basename;
        $mime = function_exists('mime_content_type') ? mime_content_type($filePath) : 'application/octet-stream';
        if (!$mime) {
            $mime = 'application/octet-stream';
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filePath));
        header('Content-Disposition: attachment; filename="' . str_replace('"', '', $fileName) . '"');
        readfile($filePath);
        exit;
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

        $stmt = $pdo->prepare("SELECT pv.id, pv.peticao_id, pv.conteudo, pv.arquivo_original, pv.arquivo_caminho, p.processo_id, p.usuario_id
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

        $stmt = $pdo->prepare("INSERT INTO peticao_versoes (peticao_id, usuario_id, versao, observacao, conteudo, arquivo_original, arquivo_caminho)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $peticaoId,
            $usuarioId,
            $proxima,
            'Versão derivada automaticamente da versão #' . $versaoId,
            !empty($base['conteudo']) ? (string) $base['conteudo'] : null,
            !empty($base['arquivo_original']) ? (string) $base['arquivo_original'] : null,
            !empty($base['arquivo_caminho']) ? (string) $base['arquivo_caminho'] : null,
        ]);

        Audit::registrar('Petição derivada', 'peticao_versoes', (int) $pdo->lastInsertId(), 'Base versão ID: ' . $versaoId);

        header('Location: /processos/' . (int) $base['processo_id'] . '/peticoes/' . $peticaoId);
        exit;
    }
}
