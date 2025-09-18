<div>
    {{-- Render infolist schema from TestimoniInfolist --}}
    {{ $this->testimoniInfolist }}

    <div class="mt-4 flex space-x-2">
        {{-- Tampilkan tombol 'Approve' hanya jika status belum 'disetujui' --}}
        @if ($testimoni->status !== \App\Enums\Status::Disetujui)
            {{ $this->approveAction }}
        @endif

        {{-- Tampilkan tombol 'Reject' hanya jika status belum 'ditolak' --}}
        @if ($testimoni->status !== \App\Enums\Status::Ditolak)
            {{ $this->rejectAction }}
        @endif
    </div>

    {{-- Render modal untuk konfirmasi aksi --}}
    <x-filament-actions::modals />
</div>
