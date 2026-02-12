<?php
// Salve como: public/test_routes.php
// Acesse: http://localhost/test_routes.php

echo "<h1>🔍 Verificação de Rotas</h1>";
echo "<pre>";

$routes_file = '../routes/web.php';

if (!file_exists($routes_file)) {
    echo "❌ Arquivo routes/web.php não encontrado!\n";
    exit;
}

$content = file_get_contents($routes_file);

// Verificar se as rotas de compromissos existem
$checks = [
    "'/compromissos'" => "Rota principal /compromissos",
    "'/compromissos/novo'" => "Rota /compromissos/novo",
    "'/compromissos/store'" => "Rota /compromissos/store",
    "'/compromissos/edit/'" => "Rota /compromissos/edit/{id}",
    "'/compromissos/update/'" => "Rota /compromissos/update/{id}",
    "'/compromissos/delete/'" => "Rota /compromissos/delete/{id}",
    "CompromissosController" => "Referência ao CompromissosController"
];

echo "=== VERIFICAÇÃO DAS ROTAS ===\n\n";

$all_ok = true;
foreach ($checks as $search => $description) {
    if (strpos($content, $search) !== false) {
        echo "✅ $description\n";
    } else {
        echo "❌ $description - NÃO ENCONTRADA!\n";
        $all_ok = false;
    }
}

echo "\n";

if ($all_ok) {
    echo "🎉 TODAS AS ROTAS ESTÃO CONFIGURADAS!\n\n";
    echo "Agora você pode acessar:\n";
    echo "- <a href='/compromissos'>http://localhost/compromissos</a>\n";
    echo "- <a href='/compromissos/novo'>http://localhost/compromissos/novo</a>\n";
} else {
    echo "⚠️ FALTA ATUALIZAR O ARQUIVO routes/web.php\n\n";
    echo "Copie o conteúdo do artifact 'routes/web.php - Atualizado com Compromissos'\n";
    echo "e substitua o arquivo atual em: routes/web.php\n";
}

echo "</pre>";
?>