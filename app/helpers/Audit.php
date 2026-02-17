<?php

class Audit
{
    public static function registrar(string $acao, ?string $tabela = null, ?int $registroId = null, ?string $detalhes = null): void
    {
        global $pdo;

        if (!isset($pdo)) {
            return;
        }

        $usuarioId = isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])
            ? (int) $_SESSION['usuario_id']
            : null;

        $clienteId = isset($_SESSION['cliente_id']) && !empty($_SESSION['cliente_id'])
            ? (int) $_SESSION['cliente_id']
            : null;

        if ($usuarioId === null && $clienteId === null) {
            return;
        }

        if ($usuarioId === null && $clienteId !== null) {
            $prefixoCliente = '[cliente_id: ' . $clienteId . ']';
            $detalhes = $detalhes !== null && $detalhes !== ''
                ? $prefixoCliente . ' ' . $detalhes
                : $prefixoCliente;
        }

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'desconhecido');
        if (is_string($ip) && strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO logs_auditoria (usuario_id, acao, tabela, registro_id, detalhes, ip_address)
                VALUES (:usuario_id, :acao, :tabela, :registro_id, :detalhes, :ip)
            ");

            $stmt->execute([
                ':usuario_id' => $usuarioId,
                ':acao' => $acao,
                ':tabela' => $tabela,
                ':registro_id' => $registroId,
                ':detalhes' => $detalhes,
                ':ip' => is_string($ip) ? $ip : 'desconhecido',
            ]);
        } catch (PDOException $e) {
            // Mantém fluxo principal mesmo se logs não estiverem disponíveis.
        }
    }
}
