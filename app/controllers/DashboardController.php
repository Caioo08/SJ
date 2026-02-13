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


        // Alertas de prazos (D-7, D-3, D-1 e vencidos)
        $prazos_criticos = [];
        $total_prazos_abertos = 0;
        $alertas_prazos = ['d7' => 0, 'd3' => 0, 'd1' => 0, 'vencidos' => 0];
        try {
            $stmt = $pdo->prepare("
                SELECT id, titulo, data_limite, prioridade,
                    CASE
                        WHEN data_limite < NOW() THEN 'VENCIDO'
                        WHEN data_limite <= DATE_ADD(NOW(), INTERVAL 1 DAY) THEN 'D-1'
                        WHEN data_limite <= DATE_ADD(NOW(), INTERVAL 3 DAY) THEN 'D-3'
                        WHEN data_limite <= DATE_ADD(NOW(), INTERVAL 7 DAY) THEN 'D-7'
                        ELSE 'EM_DIA'
                    END AS faixa_alerta
                FROM prazos
                WHERE usuario_id = ?
                  AND concluido = 0
                  AND data_limite <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                ORDER BY data_limite ASC
                LIMIT 12
            ");
            $stmt->execute([$usuario_id]);
            $prazos_criticos = $stmt->fetchAll();

            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM prazos WHERE usuario_id = ? AND concluido = 0");
            $stmt->execute([$usuario_id]);
            $total_prazos_abertos = (int) ($stmt->fetch()['total'] ?? 0);

            $stmt = $pdo->prepare("SELECT
                SUM(CASE WHEN data_limite < NOW() THEN 1 ELSE 0 END) AS vencidos,
                SUM(CASE WHEN data_limite >= NOW() AND data_limite <= DATE_ADD(NOW(), INTERVAL 1 DAY) THEN 1 ELSE 0 END) AS d1,
                SUM(CASE WHEN data_limite > DATE_ADD(NOW(), INTERVAL 1 DAY) AND data_limite <= DATE_ADD(NOW(), INTERVAL 3 DAY) THEN 1 ELSE 0 END) AS d3,
                SUM(CASE WHEN data_limite > DATE_ADD(NOW(), INTERVAL 3 DAY) AND data_limite <= DATE_ADD(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS d7
                FROM prazos
                WHERE usuario_id = ? AND concluido = 0");
            $stmt->execute([$usuario_id]);
            $alertas = $stmt->fetch() ?: [];
            $alertas_prazos = [
                'd7' => (int) ($alertas['d7'] ?? 0),
                'd3' => (int) ($alertas['d3'] ?? 0),
                'd1' => (int) ($alertas['d1'] ?? 0),
                'vencidos' => (int) ($alertas['vencidos'] ?? 0),
            ];
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
