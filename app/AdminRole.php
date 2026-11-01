<?php

namespace App;

enum AdminRole: string
{
    case Member = 'member';
    case Admin = 'admin';
    case Registrar = 'registrar';
    case Treasurer = 'treasurer';

    public function label(): string
    {
        return match ($this) {
            self::Member => 'ผู้ใช้ทั่วไป',
            self::Admin => 'ผู้ดูแลระบบ',
            self::Registrar => 'นายทะเบียน',
            self::Treasurer => 'เหรัญญิก',
        };
    }
}
