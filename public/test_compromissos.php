<?php
// Salve este arquivo como: public/test_compromissos.php
// Acesse: http://localhost/test_compromissos.php

session_start();
require_once '../config/database.php';

echo "<h1>🔍 Debug - Compromissos</h1>";
echo "<pre>";

// 1. Testar sessão
echo "=== SESSÃO ===\n";
echo "Usuario ID: " . ($_SESSION['usuario_id'] ?? 'NÃO DEFINIDO') . "\n";
echo "Usuario Nome: " . ($_SESSION['usuario_nome'] ?? 'NÃO DEFINIDO') . "\n\n";

// 2. Testar conexão com banco
echo "=== CONEXÃO COM BANCO ===\n";
try {
    $pdo->query("SELECT 1");
    echo "✅ Conexão OK\n\n";
} catch (PDOException $e) {
    echo "❌ Erro de conexão: " . $e->getMessage() . "\n\n";
    exit;
}

// 3. Verificar se a tabela existe
echo "=== TABELA COMPROMISSOS ===\n";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'compromissos'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabela 'compromissos' existe\n\n";
    } else {
        echo "❌ Tabela 'compromissos' NÃO existe\n\n";
        exit;
    }
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n\n";
    exit;
}

// 4. Verificar estrutura da tabela
echo "=== ESTRUTURA DA TABELA ===\n";
try {
    $stmt = $pdo->query("DESCRIBE compromissos");
    $columns = $stmt->fetchAll();
    foreach ($columns as $col) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n\n";
}

// 5. Contar registros
echo "=== REGISTROS ===\n";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM compromissos");
    $total = $stmt->fetch()['total'];
    echo "Total de compromissos: $total\n\n";
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n\n";
}

// 6. Verificar se o controller existe
echo "=== ARQUIVOS ===\n";
$controller_path = '../app/controllers/CompromissosController.php';
echo "CompromissosController.php: " . (file_exists($controller_path) ? "✅ Existe" : "❌ NÃO existe") . "\n";

$view_index = '../views/compromissos/index.php';
echo "views/compromissos/index.php: " . (file_exists($view_index) ? "✅ Existe" : "❌ NÃO existe") . "\n";

$view_create = '../views/compromissos/create.php';
echo "views/compromissos/create.php: " . (file_exists($view_create) ? "✅ Existe" : "❌ NÃO existe") . "\n\n";

// 7. Testar rota
echo "=== TESTE DE ROTA ===\n";
echo "Acesse: <a href='/compromissos'>http://localhost/compromissos</a>\n\n";

// 8. Listar compromissos (se logado)
if (isset($_SESSION['usuario_id'])) {
    echo "=== SEUS COMPROMISSOS ===\n";
    try {
        $stmt = $pdo->prepare("SELECT * FROM compromissos WHERE usuario_id = ? ORDER BY data_inicio DESC LIMIT 5");
        $stmt->execute([$_SESSION['usuario_id']]);
        $compromissos = $stmt->fetchAll();
        
        if (empty($compromissos)) {
            echo "Nenhum compromisso cadastrado.\n";
        } else {
            foreach ($compromissos as $c) {
                echo "ID: {$c['id']} | {$c['titulo']} | {$c['data_inicio']}\n";
            }
        }
    } catch (PDOException $e) {
        echo "❌ Erro: " . $e->getMessage() . "\n";
    }
} else {
    echo "⚠️ Você não está logado. Faça login primeiro em: <a href='/login'>/login</a>\n";
}

echo "</pre>";
?>