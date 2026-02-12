<?php

class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function field(): string
    {
        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    public static function verifyRequest(): bool
    {
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        $requestToken = $_POST['csrf_token'] ?? '';

        if (!is_string($sessionToken) || !is_string($requestToken)) {
            return false;
        }

        if ($sessionToken === '' || $requestToken === '') {
            return false;
        }

        return hash_equals($sessionToken, $requestToken);
    }

    public static function abortIfInvalid(): void
    {
        if (!self::verifyRequest()) {
            http_response_code(419);
            echo "Sessão expirada ou token CSRF inválido. Recarregue a página e tente novamente.";
            exit;
        }
    }
}
