<?php

namespace App\Enums;

enum Status : string
{
    case Menunggu  = 'Menunggu';
    case Disetujui = 'Disetujui';
    case Ditolak   = 'Ditolak';

}
