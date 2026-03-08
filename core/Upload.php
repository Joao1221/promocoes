<?php
class Upload
{
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public static function image(array $file, string $targetDir): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Falha no upload.');
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            throw new RuntimeException('Imagem excede 2MB.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $ext = self::ALLOWED[$mime] ?? null;

        if (!$ext) {
            throw new RuntimeException('Formato de imagem invalido.');
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Nao foi possivel salvar a imagem.');
        }

        return $filename;
    }
}
