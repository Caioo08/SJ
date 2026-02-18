<?php
require_once __DIR__ . '/../config/database.php';

try {
    $stmt = $pdo->prepare("SELECT id, usuario_id, acao, tabela, registro_id, detalhes, ip_address, criado_em FROM logs_auditoria ORDER BY criado_em DESC LIMIT 100");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "Nenhum log encontrado.\n";
        exit(0);
    }

    foreach ($rows as $r) {
        echo sprintf("%s | acao=%s | id=%s | usuario_id=%s | tabela=%s | registro_id=%s | ip=%s | detalhes=%s\n",
            $r['criado_em'], $r['acao'], $r['id'], $r['usuario_id'] ?? 'NULL', $r['tabela'] ?? 'NULL', $r['registro_id'] ?? 'NULL', $r['ip_address'] ?? 'NULL', $r['detalhes'] ?? '');
    }
} catch (Exception $e) {
    echo "Erro ao consultar logs: " . $e->getMessage() . "\n";
    exit(1);
}
