<x-filament-infolists::entry-wrapper :entry="$entry">
    @php
        $record = $entry->getRecord();
        $testimonial = $record->testimonial;
    @endphp

    @if(!$testimonial)
        <p class="text-gray-500">Tidak ada testimonial</p>
    @else
        <div class="space-y-4">
            <div class="flex items-center space-x-2">
                <span class="font-semibold">Rating:</span>
                <span class="filament-infolists-badge text-sm bg-yellow-100 text-yellow-800 rounded-full px-2 py-0.5">
                    {{ $testimonial->rating }}
                </span>
            </div>

            <div>
                <span class="font-semibold">Isi Testimoni:</span>
                <p class="mt-1">{{ $testimonial->content }}</p>
            </div>

            @if ($testimonial->product_photo)
                <div>
                    <span class="font-semibold">Foto Produk:</span>
                    <img
                        src="{{ Storage::url($testimonial->product_photo) }}"
                        alt="Foto Produk"
                        class="h-32 w-auto object-cover rounded-lg mt-2"
                    />
                </div>
            @endif

            <div class="flex items-center space-x-2">
                <span class="font-semibold">Status:</span>
                @php
                    $statusColor = 'warning';
                    switch ($testimonial->status) {
                        case \App\Enums\Status::Disetujui->value:
                            $statusColor = 'success';
                            break;
                        case \App\Enums\Status::Ditolak->value:
                            $statusColor = 'danger';
                            break;
                    }
                @endphp
                <span class="filament-infolists-badge text-sm bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full px-2 py-0.5">
                    {{ $testimonial->status }}
                </span>
            </div>
            
            @if ($testimonial->status === \App\Enums\Status::Menunggu->value)
                <div class="flex space-x-2 mt-4">
                    <x-filament::button
                        color="success"
                        wire:click="approveTestimoni({{ $testimonial->id }})"
                        icon="heroicon-o-check-circle"
                    >
                        Setujui Testimoni
                    </x-filament::button>
                    <x-filament::button
                        color="danger"
                        wire:click="rejectTestimoni({{ $testimonial->id }})"
                        icon="heroicon-o-x-circle"
                    >
                        Tolak Testimoni
                    </x-filament::button>
                </div>
            @endif
        </div>
    @endif
</x-filament-infolists::entry-wrapper>