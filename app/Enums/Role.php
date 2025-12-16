<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case USER = 'user';

    /**
     * Get all role values as array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get human-readable label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::EDITOR => 'Éditeur',
            self::USER => 'Utilisateur',
        };
    }

    /**
     * Check if role has admin privileges.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if role has editor privileges.
     */
    public function isEditor(): bool
    {
        return $this === self::EDITOR || $this->isAdmin();
    }
}
