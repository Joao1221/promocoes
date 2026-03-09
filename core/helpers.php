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
    $icons = [
        'Carrinho' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M3 4h2l2.4 10.2a1 1 0 0 0 1 .8h8.9a1 1 0 0 0 1-.8L20 8H7"/></svg>',
        'Saude' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M12 5v14M5 12h14"/><rect x="3" y="3" width="18" height="18" rx="4"/></svg>',
        'Prato' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M4 3v7a2 2 0 0 0 2 2h1v9"/><path d="M8 3v7"/><path d="M12 3v7"/><path d="M18 3c1.7 2 2.5 4.2 2.5 6.5S19.7 14 18 16v5"/></svg>',
        'Camisa' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M8 4 6 7 3 8.5 5 13l3-1v8h8v-8l3 1 2-4.5L18 7l-2-3z"/><path d="M9 4c.6 1.1 1.7 2 3 2s2.4-.9 3-2"/></svg>',
        'Plug' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M9 7V3M15 7V3"/><path d="M7 7h10v3a5 5 0 0 1-5 5 5 5 0 0 1-5-5z"/><path d="M12 15v6"/></svg>',
        'Ferramenta' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="m14 5 5 5"/><path d="M12 7 4 15l-1 5 5-1 8-8"/><path d="M15 4 20 9"/></svg>',
        'Agenda' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M8 2v4M16 2v4M3 9h18"/><path d="M8 13h3M8 17h8"/></svg>',
        'Acougue' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M4 6c2 1.5 3.5 3.8 4 6l-2 2c-2.2-.5-4.5-2.3-6-4.5z"/><path d="M12 6 19 13"/><path d="M13 3 21 11"/><path d="m9 15 6 6"/></svg>',
        'Frios' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M12 3v18"/><path d="M4.5 7.5 19.5 16.5"/><path d="M19.5 7.5 4.5 16.5"/><circle cx="12" cy="12" r="1.4"/></svg>',
        'Doces' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="7" y="8" width="10" height="8" rx="3"/><path d="m4 9 3 1v4l-3 1z"/><path d="m20 9-3 1v4l3 1z"/></svg>',
        'Frutas' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M12 8c-3.8 0-6 2.6-6 6 0 3.3 2.3 6 6 6s6-2.7 6-6c0-3.4-2.2-6-6-6z"/><path d="M12 8c0-1.8.9-3.2 2.8-4"/><path d="M11 6c-1.5 0-2.8-.6-3.8-1.8"/></svg>',
        'Verduras' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M5 13c0-5 4-8 9-8h5v5c0 5-4 9-9 9H5z"/><path d="M8 16c1-2 3-4 6-6"/></svg>',
        'Padaria' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="4" y="9" width="16" height="9" rx="3"/><path d="M7 9V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v2"/><path d="M9 13h.01M12 13h.01M15 13h.01"/></svg>',
        'Bebidas' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M7 5h8l-1 13H8z"/><path d="M15 7h3a2 2 0 0 1 0 4h-2"/><path d="M9 3v2M12 3v2"/></svg>',
        'Limpeza' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M10 4h4v3h-4z"/><path d="M9 7h6l2 4v7a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v-7z"/><path d="M8 11h4"/></svg>',
        'Higiene' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="4" y="9" width="16" height="10" rx="3"/><circle cx="8" cy="6" r="1"/><circle cx="12" cy="5" r="1.2"/><circle cx="16" cy="6" r="1"/></svg>',
        'PetShop' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="8" cy="8" r="1.5"/><circle cx="12" cy="6.5" r="1.5"/><circle cx="16" cy="8" r="1.5"/><path d="M12 12c-2.5 0-4 1.6-4 3.3 0 1.5 1.2 2.7 4 2.7s4-1.2 4-2.7c0-1.7-1.5-3.3-4-3.3z"/></svg>',
        'Papelaria' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="m4 20 4.5-1 10-10-3.5-3.5-10 10z"/><path d="m13 6 3.5 3.5"/><path d="M3 21h6"/></svg>',
        'Livraria' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M5 5h8a3 3 0 0 1 3 3v11H8a3 3 0 0 0-3 3z"/><path d="M16 5h3v14h-3"/></svg>',
        'Brinquedos' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="8.5" y="13" width="7" height="7" rx="1"/></svg>',
        'Esportes' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="12" cy="12" r="8"/><path d="M12 4a12 12 0 0 1 0 16"/><path d="M12 4a12 12 0 0 0 0 16"/><path d="M4 12h16"/></svg>',
        'Automotivo' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M4 14h16l-1.5-5a2 2 0 0 0-1.9-1.4H7.4A2 2 0 0 0 5.5 9z"/><circle cx="7.5" cy="16.5" r="1.5"/><circle cx="16.5" cy="16.5" r="1.5"/></svg>',
        'Ferramentas' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="m5 19 5-5"/><path d="m9 8 7 7"/><path d="M14 4h6v6"/><path d="M10 6 6 10l2 2"/></svg>',
        'Jardim' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="12" cy="12" r="2"/><circle cx="12" cy="7" r="2"/><circle cx="12" cy="17" r="2"/><circle cx="7" cy="12" r="2"/><circle cx="17" cy="12" r="2"/><path d="M12 14v7"/></svg>',
        'Moveis' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M7 10h10v5H7z"/><path d="M9 10V6h6v4"/><path d="M8 15v4M16 15v4"/></svg>',
        'Decoracao' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M8 10h8l-1.5-5h-5z"/><path d="M12 10v6"/><path d="M9 20h6"/><path d="M8 16h8"/></svg>',
        'Informatica' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="4" y="5" width="16" height="11" rx="2"/><path d="M10 19h4"/><path d="M12 16v3"/></svg>',
        'Telefonia' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><rect x="8" y="3" width="8" height="18" rx="2"/><path d="M11 6h2"/><circle cx="12" cy="18" r="1"/></svg>',
        'Otica' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="8" cy="13" r="3"/><circle cx="16" cy="13" r="3"/><path d="M11 13h2"/><path d="M5 13H3l1-4h16l1 4h-2"/></svg>',
        'Relojoaria' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>',
        'Festa' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="m5 19 8-8"/><path d="m13 11 6-6"/><circle cx="6" cy="6" r="1.5"/><circle cx="18" cy="14" r="1.5"/><path d="M9 20h.01M15 20h.01"/></svg>',
        'CamaMesaBanho' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><path d="M4 11h16v7H4z"/><path d="M6 11V8h4a2 2 0 0 1 2 2v1"/><path d="M4 18v2M20 18v2"/></svg>',
    ];

    return $icons[$icon] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-8 w-8"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>';
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
