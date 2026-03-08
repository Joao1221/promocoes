<?php
function config(string $key): mixed
{
    static $configs = [];

    if (!isset($configs[$key])) {
        $configs[$key] = require __DIR__ . '/../config/' . $key . '.php';
    }

    return $configs[$key];
}

function url(string $path = ''): string
{
    $base = rtrim(config('app')['base_url'], '/');
    $path = ltrim($path, '/');
    return $path ? $base . '/' . $path : $base;
}

function asset(string $path): string
{
    return url('public/assets/' . ltrim($path, '/'));
}

function upload_url(string $folder, ?string $file): string
{
    if (!$file) {
        return asset('img/placeholder.svg');
    }

    return rtrim(config('app')['uploads_url'], '/') . '/' . trim($folder, '/') . '/' . $file;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function format_price(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function discount_percent(float $original, ?float $promotional): int
{
    if (!$promotional || $promotional >= $original || $original <= 0) {
        return 0;
    }

    return (int) round((($original - $promotional) / $original) * 100);
}

function active_price(array $product): float
{
    $promo = isset($product['preco_promocional']) ? (float) $product['preco_promocional'] : 0;
    return $promo > 0 ? $promo : (float) $product['preco_original'];
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    $text = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($text));
    return trim((string) $text, '-');
}

function category_icon_svg(?string $icon): string
{
    return match ($icon) {
        'Carrinho' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 8H7"/></svg>',
        'Saude' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M12 5v14M5 12h14"/><rect x="3" y="3" width="18" height="18" rx="4"/></svg>',
        'Prato' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M4 3v7a2 2 0 0 0 2 2h1v9"/><path d="M8 3v7"/><path d="M12 3v7"/><path d="M18 3c1.7 2 2.5 4.2 2.5 6.5S19.7 14 18 16v5"/></svg>',
        'Camisa' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M8 4 6 7 3 8.5 5 13l3-1v8h8v-8l3 1 2-4.5L18 7l-2-3z"/><path d="M9 4c.6 1.1 1.7 2 3 2s2.4-.9 3-2"/></svg>',
        'Plug' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M9 7V3M15 7V3"/><path d="M7 7h10v3a5 5 0 0 1-5 5 5 5 0 0 1-5-5z"/><path d="M12 15v6"/></svg>',
        'Ferramenta' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="m14 5 5 5"/><path d="M12 7 4 15l-1 5 5-1 8-8"/><path d="M15 4 20 9"/></svg>',
        'Agenda' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M8 2v4M16 2v4M3 9h18"/><path d="M8 13h3M8 17h8"/></svg>',
        default => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>',
    };
}

function category_image(string $slug): string
{
    return match ($slug) {
        'construcao' => asset('img/categories/construcao.png'),
        'eletronicos' => asset('img/categories/eletronicos.png'),
        'farmacia' => asset('img/categories/farmacia.png'),
        'moda' => asset('img/categories/moda.png'),
        default => asset('img/placeholder.svg'),
    };
}
