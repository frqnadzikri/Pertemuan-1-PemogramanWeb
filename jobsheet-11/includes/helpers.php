<?php
// Escape output ke HTML (anti-XSS).
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Ambil string dari $_GET/$_POST dengan aman. Bila kosong atau berupa array
// (mis. ?q[]=x), hasilnya '' -- trim(array) akan memicu TypeError (HTTP 500).
function input_str(array $src, string $key): string
{
    $v = $src[$key] ?? '';
    return is_string($v) ? trim($v) : '';
}

// Ubah ke integer murni; null bila bukan integer ("1.5", "1e3", "abc" -> null).
function to_int($value)
{
    $r = filter_var($value, FILTER_VALIDATE_INT);
    return $r === false ? null : $r;
}

// Escape wildcard LIKE agar input '%' atau '_' dianggap karakter biasa.
function like_escape(string $s): string
{
    return addcslashes($s, '%_\\');
}

// Cetak flash message dengan escaping; tipe dibatasi ke success/error.
function flash_render($flash)
{
    if (!$flash) {
        return;
    }
    $type = ($flash['type'] ?? '') === 'success' ? 'success' : 'error';
    echo '<p class="flash flash-' . $type . '">' . e($flash['pesan'] ?? '') . '</p>';
}
