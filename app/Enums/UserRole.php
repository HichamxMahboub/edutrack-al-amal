<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Enseignant = 'enseignant';
    case Encadrant = 'encadrant';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Enseignant => 'Enseignant',
            self::Encadrant => 'Encadrant',
        };
    }

    public static function options(): array
    {
        return [
            self::Admin->value => self::Admin->label(),
            self::Enseignant->value => self::Enseignant->label(),
            self::Encadrant->value => self::Encadrant->label(),
        ];
    }
}
