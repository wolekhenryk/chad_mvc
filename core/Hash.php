<?php

namespace app\core;

class Hash
{
    const HASH_ALGORITHM = 'sha512';

    public static function Make(string $plainText) {
        return hash(self::HASH_ALGORITHM, $plainText);
    }

    public static function Verify(string $plainText, string $hashedPass): bool {
        return self::Make($plainText) === $hashedPass;
    }
}