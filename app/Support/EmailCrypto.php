<?php

namespace App\Support;

class EmailCrypto
{
    private static function key(): string
    {
        return base64_decode(str_replace('base64:', '', config('mail.email_secret_key')));
    }

    private static function iv(): string
    {
        // IV fixo derivado da chave (mesmo nas duas apps)
        return substr(hash('sha256', self::key()), 0, 16);
    }

    public static function encrypt(string $value): string
    {
        return base64_encode(
            openssl_encrypt(
                $value,
                'AES-256-CBC',
                self::key(),
                OPENSSL_RAW_DATA,
                self::iv()
            )
        );
    }

    public static function decrypt(string $value): string
    {
        return openssl_decrypt(
            base64_decode($value),
            'AES-256-CBC',
            self::key(),
            OPENSSL_RAW_DATA,
            self::iv()
        );
    }
}
