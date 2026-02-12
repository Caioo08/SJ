<?php

class AuthMiddleware
{
    /**
     * Verifica se o usuário está logado
     */
    public static function verificarLogin()
    {
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['cliente_id'])) {
            header('Location: /login');
            exit;
        }
        
    }

    /**
     * Verifica se o usuário é administrador
     */
    public static function verificarAdmin()
    {
        self::verificarLogin();

        if (!isset($_SESSION['perfil_id']) || $_SESSION['perfil_id'] != 1) {
            die("
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Acesso Negado</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background: #0b0b0b;
                            color: #f6f4ef;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 100vh;
                            margin: 0;
                        }
                        .error-box {
                            background: #1a1a1a;
                            padding: 40px;
                            border-radius: 12px;
                            border: 1px solid rgba(239, 68, 68, 0.3);
                            text-align: center;
                            max-width: 500px;
                        }
                        .error-icon {
                            font-size: 64px;
                            margin-bottom: 20px;
                        }
                        h1 {
                            color: #ef4444;
                            margin: 0 0 10px 0;
                        }
                        p {
                            color: #bfb39a;
                            margin: 0 0 30px 0;
                        }
                        a {
                            background: #d4af37;
                            color: #0b0b0b;
                            padding: 12px 24px;
                            border-radius: 8px;
                            text-decoration: none;
                            font-weight: 600;
                            display: inline-block;
                        }
                        a:hover {
                            filter: brightness(0.9);
                        }
                    </style>
                </head>
                <body>
                    <div class='error-box'>
                        <div class='error-icon'>🚫</div>
                        <h1>Acesso Negado</h1>
                        <p>Você não tem permissão para acessar esta área.<br>Esta seção é exclusiva para administradores do sistema.</p>
                        <a href='/dashboard'>← Voltar para Dashboard</a>
                    </div>
                </body>
                </html>
            ");
        }
    }

    /**
     * Verifica se o usuário é advogado (não admin)
     */
    public static function verificarAdvogado()
    {
        self::verificarLogin();

        if (!isset($_SESSION['perfil_id']) || $_SESSION['perfil_id'] != 2) {
            die("
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Acesso Negado</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background: #0b0b0b;
                            color: #f6f4ef;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 100vh;
                            margin: 0;
                        }
                        .error-box {
                            background: #1a1a1a;
                            padding: 40px;
                            border-radius: 12px;
                            border: 1px solid rgba(239, 68, 68, 0.3);
                            text-align: center;
                            max-width: 500px;
                        }
                        .error-icon {
                            font-size: 64px;
                            margin-bottom: 20px;
                        }
                        h1 {
                            color: #ef4444;
                            margin: 0 0 10px 0;
                        }
                        p {
                            color: #bfb39a;
                            margin: 0 0 30px 0;
                        }
                        a {
                            background: #ef4444;
                            color: white;
                            padding: 12px 24px;
                            border-radius: 8px;
                            text-decoration: none;
                            font-weight: 600;
                            display: inline-block;
                        }
                        a:hover {
                            filter: brightness(0.9);
                        }
                    </style>
                </head>
                <body>
                    <div class='error-box'>
                        <div class='error-icon'>⚠️</div>
                        <h1>Área Restrita</h1>
                        <p>Esta área é exclusiva para usuários advogados.<br>Administradores devem usar o painel administrativo.</p>
                        <a href='/admin'>← Ir para Painel Admin</a>
                    </div>
                </body>
                </html>
            ");
        }
    }



    /**
     * Verifica se o usuário é cliente
     */
    public static function verificarCliente()
    {
        self::verificarLogin();

        if (!isset($_SESSION['perfil_id']) || $_SESSION['perfil_id'] != 3 || !isset($_SESSION['cliente_id'])) {
            header('Location: /login?acesso=cliente');
            exit;
        }
    }

    /**
     * Redireciona baseado no perfil do usuário
     */
    public static function redirecionarPorPerfil()
    {
        self::verificarLogin();

        if (isset($_SESSION['perfil_id'])) {
            if ($_SESSION['perfil_id'] == 1) {
                header('Location: /admin');
                exit;
            } elseif ($_SESSION['perfil_id'] == 2) {
                header('Location: /dashboard');
                exit;
            } else {
                header('Location: /cliente');
                exit;
            }
        }
    }
}