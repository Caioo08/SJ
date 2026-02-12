<?php
require_once '../config/database.php';

class DashboardController
{
    public static function index()
    {
        global $pdo;

        $usuario_id = $_SESSION['usuario_id'];

        // Dados pessoais
        $stmt = $pdo->prepare("
            SELECT u.nome, u.email, u.oab, f.sigla AS uf
            FROM usuarios u
            LEFT JOIN ufs f ON u.uf_id = f.id
            WHERE u.id = ?
        ");
        $stmt->execute([$usuario_id]);
        $usuario = $stmt->fetch();

        // Total de processos
        $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM processos WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        $total_processos = $stmt->fetch()['total'];

        // Processos abertos
        $stmt = $pdo->prepare("SELECT COUNT(*) AS abertos FROM processos WHERE usuario_id = ? AND status='aberto'");
        $stmt->execute([$usuario_id]);
        $processos_abertos = $stmt->fetch()['abertos'];


        // Prazos críticos (próximas 48h) - módulo de prazos processuais
        $prazos_criticos = [];
        $total_prazos_abertos = 0;
        try {
            $stmt = $pdo->prepare("
                SELECT id, titulo, data_limite, prioridade
                FROM prazos
                WHERE usuario_id = ?
                  AND concluido = 0
                  AND data_limite BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 48 HOUR)
                ORDER BY data_limite ASC
                LIMIT 5
            ");
            $stmt->execute([$usuario_id]);
            $prazos_criticos = $stmt->fetchAll();

            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM prazos WHERE usuario_id = ? AND concluido = 0");
            $stmt->execute([$usuario_id]);
            $total_prazos_abertos = (int) ($stmt->fetch()['total'] ?? 0);
        } catch (PDOException $e) {
            // Mantém dashboard funcional caso tabela ainda não tenha sido aplicada no banco.
            $prazos_criticos = [];
            $total_prazos_abertos = 0;
        }

        // Próximos compromissos (7 dias)
        $stmt = $pdo->prepare("
            SELECT id, titulo, descricao, data_inicio, data_fim, local
            FROM compromissos
            WHERE usuario_id = ? AND data_inicio BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
            ORDER BY data_inicio ASC
        ");
        $stmt->execute([$usuario_id]);
        $compromissos = $stmt->fetchAll();

        require_once '../views/dashboard/index.php';
    }
}
