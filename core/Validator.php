<?php
class Validator
{
    public static function required(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            $value = $data[$field] ?? null;
            if ($value === null || trim((string) $value) === '') {
                $errors[$field] = 'Campo obrigatorio.';
            }
        }

        return $errors;
    }

    public static function email(?string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
