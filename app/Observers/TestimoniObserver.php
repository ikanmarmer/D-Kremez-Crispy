<?php

namespace App\Observers;

use App\Models\Testimoni;

class TestimoniObserver
{
    public function updating(Testimoni $testimoni)
    {
        if ($testimoni->isDirty('status')) {
            // Reset notifikasi saat status berubah
            $testimoni->is_notified = false;
        }
    }
}
