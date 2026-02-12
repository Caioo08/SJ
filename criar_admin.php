<?php
/**
 * Script para criar usuário administrador
 * Execute este arquivo UMA VEZ após importar o banco de dados
 * Acesse: http://localhost/criar_admin.php
 */

// Este arquivo deve estar na pasta raiz do projeto (junto com as pastas app, config, etc)
require_once __DIR__ . '/config/database.php';

echo "<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Criar Admin - Sistema Jurídico</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 { color: #333; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; color: #721c24; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; color: #856404; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px; color: #0c5460; }
        button { padding: 10px 20px; cursor: pointer; border: none; border-radius: 5px; font-weight: bold; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        code { background: #f8f9fa; padding: 5px 10px; border-radius: 3px; font-family: monospace; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<h1>🛡️ Criar Usuário Administrador</h1>";

try {
    // Verificar se já existe um admin
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE perfil_id = 1");
    $result = $stmt->fetch();
    
    if ($result['total'] > 0) {
        echo "<p style='color: orange;'>⚠️ Já existe um administrador no sistema!</p>";
        echo "<p>Se esqueceu a senha, delete o admin atual e execute este script novamente.</p>";
        echo "<hr>";
        echo "<h3>Resetar Admin?</h3>";
        echo "<form method='POST'>";
        echo "<p>Isso vai deletar o admin atual e criar um novo.</p>";
        echo "<button type='submit' name='resetar' style='background: red; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Resetar Admin</button>";
        echo "</form>";
        
        if (isset($_POST['resetar'])) {
            $pdo->exec("DELETE FROM usuarios WHERE perfil_id = 1");
            echo "<p style='color: green;'>✓ Admin resetado! Recarregue a página.</p>";
        }
        
        exit;
    }
    
    // Criar o hash da senha
    $senha = 'admin123';
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir admin
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, email, senha_hash, perfil_id, ativo)
        VALUES (:nome, :email, :senha_hash, :perfil_id, :ativo)
    ");
    
    $stmt->execute([
        ':nome' => 'Administrador',
        ':email' => 'admin@sistema.com',
        ':senha_hash' => $senha_hash,
        ':perfil_id' => 1,
        ':ativo' => 1
    ]);
    
    echo "<div class='success'>";
    echo "<h2>✓ Administrador criado com sucesso!</h2>";
    echo "<p><strong>Email:</strong> admin@sistema.com</p>";
    echo "<p><strong>Senha:</strong> admin123</p>";
    echo "<hr>";
    echo "<p><strong>Hash gerado:</strong><br><code>" . htmlspecialchars($senha_hash) . "</code></p>";
    echo "<hr>";
    echo "<p><a href='/login' class='btn-success' style='display: inline-block; padding: 12px 24px; text-decoration: none; color: white; border-radius: 5px;'>➜ Ir para Login</a></p>";
    echo "</div>";
    
    echo "<div class='warning' style='margin-top: 20px;'>";
    echo "<h3>⚠️ IMPORTANTE - Segurança</h3>";
    echo "<ul>";
    echo "<li><strong>DELETE este arquivo (criar_admin.php)</strong> imediatamente!</li>";
    echo "<li>Altere a senha do admin após o primeiro login</li>";
    echo "<li>Este arquivo não deve existir em produção</li>";
    echo "<li>Acesse o painel admin em: <a href='/admin'>/admin</a></li>";
    echo "</ul>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<h2>❌ Erro ao criar administrador</h2>";
    echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<hr>";
    echo "<h3>Possíveis causas:</h3>";
    echo "<ul>";
    echo "<li>Banco de dados não foi importado corretamente</li>";
    echo "<li>Tabela 'usuarios' ou 'perfis' não existe</li>";
    echo "<li>Credenciais de conexão incorretas em <code>config/database.php</code></li>";
    echo "<li>Servidor MySQL não está rodando</li>";
    echo "</ul>";
    echo "<hr>";
    echo "<h3>Como resolver:</h3>";
    echo "<ol>";
    echo "<li>Verifique se importou o arquivo <code>banco_reorganizado.sql</code></li>";
    echo "<li>Confirme as credenciais em <code>config/database.php</code></li>";
    echo "<li>Teste a conexão: verifique se consegue acessar o phpMyAdmin</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<hr>";
echo "<div class='info'>";
echo "<h3>🔍 Testar Senha</h3>";
echo "<p>Use este formulário para verificar se uma senha funciona com o admin:</p>";
echo "<form method='POST'>";
echo "<input type='text' name='testar_senha' placeholder='Digite a senha para testar' style='padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px;'> ";
echo "<button type='submit' name='testar' class='btn-primary'>Testar Senha</button>";
echo "</form>";

if (isset($_POST['testar']) && !empty($_POST['testar_senha'])) {
    $testar = $_POST['testar_senha'];
    
    // Buscar hash do admin
    $stmt = $pdo->query("SELECT senha_hash FROM usuarios WHERE perfil_id = 1 LIMIT 1");
    $admin = $stmt->fetch();
    
    if ($admin) {
        if (password_verify($testar, $admin['senha_hash'])) {
            echo "<div class='success' style='margin-top: 10px;'>";
            echo "<p><strong>✓ SENHA CORRETA!</strong> Esta senha funciona para fazer login como admin.</p>";
            echo "</div>";
        } else {
            echo "<div class='error' style='margin-top: 10px;'>";
            echo "<p><strong>✗ SENHA INCORRETA.</strong> Esta senha NÃO funciona para o admin.</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='warning' style='margin-top: 10px;'>";
        echo "<p>⚠️ Nenhum administrador encontrado no banco de dados.</p>";
        echo "</div>";
    }
}

echo "</div>";
echo "</body></html>";
?>