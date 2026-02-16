<?php

class Audit
{
    public static function registrar(string $acao, ?string $tabela = null, ?int $registroId = null, ?string $detalhes = null): void
    {
        global $pdo;

        if (!isset($pdo) || !isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
            return;
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO logs_auditoria (usuario_id, acao, tabela, registro_id, detalhes, ip_address)
                VALUES (:usuario_id, :acao, :tabela, :registro_id, :detalhes, :ip)
            ");

            $stmt->execute([
                ':usuario_id' => (int) $_SESSION['usuario_id'],
                ':acao' => $acao,
                ':tabela' => $tabela,
                ':registro_id' => $registroId,
                ':detalhes' => $detalhes,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'desconhecido',
            ]);
        } catch (PDOException $e) {
            // Mantém fluxo principal mesmo se logs não estiverem disponíveis.
        }
    }
}
