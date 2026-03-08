<?php
class Csrf
{
    public static function token(): string
    {
        $token = Session::get('_csrf_token');

        if (!$token) {
            $token = bin2hex(random_bytes(32));
            Session::set('_csrf_token', $token);
        }

        return $token;
    }

    public static function validate(?string $token): void
    {
        if (!$token || !hash_equals((string) Session::get('_csrf_token'), $token)) {
            http_response_code(419);
            exit('CSRF token invalido.');
        }
    }
}
