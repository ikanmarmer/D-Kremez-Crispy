<?php

namespace App\Enums;

enum Role : string
{
    case User = 'User';
    case Karyawan = 'Karyawan';
    case Admin = 'Admin';
    case Owner = 'Owner';
}
