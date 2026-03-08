<?php
class AuthMiddleware
{
    public static function handle(array|string $roles): void
    {
        Auth::requireRole($roles);
    }
}
