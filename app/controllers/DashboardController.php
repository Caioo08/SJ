<?php
require_once '../config/database.php';

class DashboardController
{
    public static function index()
    {
        global $pdo;

        $usuario_id = (int) $_SESSION['usuario_id'];

        [$filtro_periodo, $filtro_inicio, $filtro_fim, $filtro_rotulo] = self::resolverPeriodo();
        $inicioSql = $filtro_inicio->format('Y-m-d H:i:s');
        $fimSql = $filtro_fim->format('Y-m-d H:i:s');

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
        $total_processos = (int) ($stmt->fetch()['total'] ?? 0);

        // Processos abertos
        $stmt = $pdo->prepare("SELECT COUNT(*) AS abertos FROM processos WHERE usuario_id = ? AND status='aberto'");
        $stmt->execute([$usuario_id]);
        $processos_abertos = (int) ($stmt->fetch()['abertos'] ?? 0);

        // Cards gerenciais por período
        $processos_por_status = ['aberto' => 0, 'concluido' => 0, 'arquivado' => 0];
        $prazos_periodo = ['no_prazo' => 0, 'atrasados' => 0, 'total' => 0];
        $carga_advogados = [];

        $stmt = $pdo->prepare("SELECT status, COUNT(*) AS total
            FROM processos
            WHERE usuario_id = ? AND criado_em BETWEEN ? AND ?
            GROUP BY status");
        $stmt->execute([$usuario_id, $inicioSql, $fimSql]);
        foreach ($stmt->fetchAll() as $row) {
            $status = (string) ($row['status'] ?? '');
            if (array_key_exists($status, $processos_por_status)) {
                $processos_por_status[$status] = (int) ($row['total'] ?? 0);
            }
        }

        try {
            $stmt = $pdo->prepare("SELECT
                SUM(CASE WHEN data_limite >= NOW() THEN 1 ELSE 0 END) AS no_prazo,
                SUM(CASE WHEN data_limite < NOW() THEN 1 ELSE 0 END) AS atrasados,
                COUNT(*) AS total
                FROM prazos
                WHERE usuario_id = ?
                  AND concluido = 0
                  AND data_limite BETWEEN ? AND ?");
            $stmt->execute([$usuario_id, $inicioSql, $fimSql]);
            $row = $stmt->fetch() ?: [];
            $prazos_periodo = [
                'no_prazo' => (int) ($row['no_prazo'] ?? 0),
                'atrasados' => (int) ($row['atrasados'] ?? 0),
                'total' => (int) ($row['total'] ?? 0),
            ];

            $stmt = $pdo->prepare("SELECT
                u.id,
                u.nome,
                COALESCE(proc.total_processos, 0) AS total_processos,
                COALESCE(proc.processos_abertos, 0) AS processos_abertos,
                COALESCE(pz.prazos_abertos, 0) AS prazos_abertos,
                (COALESCE(proc.total_processos, 0) + COALESCE(pz.prazos_abertos, 0)) AS carga_total
                FROM usuarios u
                LEFT JOIN (
                    SELECT usuario_id,
                        COUNT(*) AS total_processos,
                        SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) AS processos_abertos
                    FROM processos
                    WHERE criado_em BETWEEN ? AND ?
                    GROUP BY usuario_id
                ) proc ON proc.usuario_id = u.id
                LEFT JOIN (
                    SELECT usuario_id,
                        COUNT(*) AS prazos_abertos
                    FROM prazos
                    WHERE concluido = 0
                      AND data_limite BETWEEN ? AND ?
                    GROUP BY usuario_id
                ) pz ON pz.usuario_id = u.id
                WHERE u.perfil_id = 2
                  AND u.ativo = 1
                ORDER BY carga_total DESC, proc.total_processos DESC, u.nome ASC
                LIMIT 10");
            $stmt->execute([$inicioSql, $fimSql, $inicioSql, $fimSql]);
            $carga_advogados = $stmt->fetchAll();
        } catch (PDOException $e) {
            $prazos_periodo = ['no_prazo' => 0, 'atrasados' => 0, 'total' => 0];
            $carga_advogados = [];
        }

        // Alertas de prazos (D-7, D-3, D-1 e vencidos)
        $prazos_criticos = [];
        $total_prazos_abertos = 0;
        $alertas_prazos = ['d7' => 0, 'd3' => 0, 'd1' => 0, 'vencidos' => 0, 'em_dia' => 0];
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
                ORDER BY data_limite ASC
                LIMIT 20
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
                'em_dia' => max(0, $total_prazos_abertos - ((int) ($alertas['d7'] ?? 0) + (int) ($alertas['d3'] ?? 0) + (int) ($alertas['d1'] ?? 0) + (int) ($alertas['vencidos'] ?? 0))),
            ];
        } catch (PDOException $e) {
            $prazos_criticos = [];
            $total_prazos_abertos = 0;
        }

        // Datas de prazo em aberto para marcação no calendário
        $prazos_calendario = [];
        try {
            $stmt = $pdo->prepare("SELECT data_limite FROM prazos WHERE usuario_id = ? AND concluido = 0");
            $stmt->execute([$usuario_id]);
            $prazos_calendario = $stmt->fetchAll();
        } catch (PDOException $e) {
            $prazos_calendario = [];
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

    private static function resolverPeriodo(): array
    {
        $periodo = $_GET['periodo'] ?? '30d';
        $agora = new DateTimeImmutable('now');
        $fim = $agora->setTime(23, 59, 59);

        switch ($periodo) {
            case '7d':
                $inicio = $agora->sub(new DateInterval('P7D'))->setTime(0, 0, 0);
                $rotulo = 'Últimos 7 dias';
                break;
            case '90d':
                $inicio = $agora->sub(new DateInterval('P90D'))->setTime(0, 0, 0);
                $rotulo = 'Últimos 90 dias';
                break;
            case 'mes':
                $inicio = $agora->modify('first day of this month')->setTime(0, 0, 0);
                $rotulo = 'Mês atual';
                break;
            case 'ano':
                $inicio = $agora->setDate((int) $agora->format('Y'), 1, 1)->setTime(0, 0, 0);
                $rotulo = 'Ano atual';
                break;
            case 'custom':
                $de = $_GET['de'] ?? '';
                $ate = $_GET['ate'] ?? '';
                $inicioCustom = DateTimeImmutable::createFromFormat('Y-m-d', $de);
                $fimCustom = DateTimeImmutable::createFromFormat('Y-m-d', $ate);

                if ($inicioCustom && $fimCustom && $inicioCustom <= $fimCustom) {
                    $inicio = $inicioCustom->setTime(0, 0, 0);
                    $fim = $fimCustom->setTime(23, 59, 59);
                    $rotulo = 'Período personalizado';
                } else {
                    $periodo = '30d';
                    $inicio = $agora->sub(new DateInterval('P30D'))->setTime(0, 0, 0);
                    $rotulo = 'Últimos 30 dias';
                }
                break;
            case '30d':
            default:
                $periodo = '30d';
                $inicio = $agora->sub(new DateInterval('P30D'))->setTime(0, 0, 0);
                $rotulo = 'Últimos 30 dias';
                break;
        }

        return [$periodo, $inicio, $fim, $rotulo];
    }
}
