<?php
require_once __DIR__ . '/../config/database.php';

try {
    $stmt = $pdo->prepare("SELECT id, usuario_id, acao, detalhes, ip_address, criado_em FROM logs_auditoria WHERE acao LIKE '%CSRF%' ORDER BY criado_em DESC LIMIT 50");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "Nenhum log de CSRF encontrado.\n";
        exit(0);
    }

    foreach ($rows as $r) {
        echo sprintf("%s | id=%s | usuario_id=%s | ip=%s | detalhes=%s\n",
            $r['criado_em'], $r['id'], $r['usuario_id'] ?? 'NULL', $r['ip_address'] ?? 'NULL', $r['detalhes'] ?? '');
    }
} catch (Exception $e) {
    echo "Erro ao consultar logs: " . $e->getMessage() . "\n";
    exit(1);
}
