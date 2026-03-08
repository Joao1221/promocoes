<?php
class Auth
{
    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function login(array $user): void
    {
        Session::set('user', $user);
    }

    public static function logout(): void
    {
        Session::forget('user');
        Session::forget('cart');
    }

    public static function requireRole(array|string $roles): void
    {
        $roles = (array) $roles;
        $user = self::user();

        if (!$user) {
            Session::flash('error', 'Para continuar, faça login ou crie sua conta.');
            header('Location: ' . url('login'));
            exit;
        }

        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            exit('Voce nao tem permissao para acessar esta area.');
        }
    }
}
