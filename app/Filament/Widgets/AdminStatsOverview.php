<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Produk;
use App\Models\RekapHarian;
use App\Models\Testimoni;
use App\Models\StokMentah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Cache;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    // Polling lebih lambat, cek setiap 30 detik untuk hash changes
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Generate hash dari data penting untuk detect changes
        $currentHash = $this->generateDataHash();
        $cachedHash = Cache::get('admin_stats_hash');

        // Jika hash sama, gunakan cached stats
        if ($currentHash === $cachedHash && Cache::has('admin_stats_data')) {
            return Cache::get('admin_stats_data');
        }

        // Ada perubahan, hitung ulang stats
        $stats = $this->calculateStats();

        // Simpan hash dan stats baru
        Cache::put('admin_stats_hash', $currentHash, now()->addMinutes(60));
        Cache::put('admin_stats_data', $stats, now()->addMinutes(60));

        return $stats;
    }

    protected function generateDataHash(): string
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Gabungkan count/sum dari semua data penting
        $data = [
            User::count(),
            User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            Produk::where('aktif', true)->count(),
            RekapHarian::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('total_omzet'),
            RekapHarian::where('tanggal', $today)->sum('total_omzet'),
            Testimoni::where('status', 'Menunggu')->count(),
            StokMentah::sum('harga_total_stok'),
        ];

        return md5(json_encode($data));
    }

    protected function calculateStats(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Calculate statistics
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $totalProducts = Produk::where('aktif', true)->count();

        // Monthly revenue from RekapHarian
        $monthlyRevenue = RekapHarian::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('total_omzet');

        // Today's revenue
        $todayRevenue = RekapHarian::where('tanggal', $today)->sum('total_omzet');

        // Monthly expenses
        $monthlyExpenses = RekapHarian::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('total_pengeluaran');

        // Pending testimonials
        $pendingTestimonials = Testimoni::where('status', 'Menunggu')->count();

        // Stock value
        $totalStockValue = StokMentah::sum('harga_total_stok');

        return [
            Stat::make('Total Pengguna', $totalUsers)
                ->description("$newUsersThisMonth pengguna baru bulan ini")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->icon('heroicon-o-users'),

            Stat::make('Omzet Hari Ini', 'Rp ' . Number::format($todayRevenue, locale: 'id'))
                ->description('Update realtime')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Omzet Bulan Ini', 'Rp ' . Number::format($monthlyRevenue, locale: 'id'))
                ->description('Rp ' . Number::format($monthlyExpenses, locale: 'id') . ' pengeluaran')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($this->getMonthlyRevenueChart())
                ->icon('heroicon-o-chart-bar'),

            Stat::make('Produk Aktif', $totalProducts)
                ->description('Total produk yang tersedia')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->icon('heroicon-o-cube'),

            Stat::make('Testimoni Pending', $pendingTestimonials)
                ->description($pendingTestimonials > 0 ? 'Perlu ditinjau' : 'Semua sudah ditinjau')
                ->descriptionIcon($pendingTestimonials > 0 ? 'heroicon-m-exclamation-circle' : 'heroicon-m-check-circle')
                ->color($pendingTestimonials > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-star'),

            Stat::make('Nilai Stok', 'Rp ' . Number::format($totalStockValue, locale: 'id'))
                ->description('Total nilai stok mentah')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary')
                ->icon('heroicon-o-archive-box'),
        ];
    }

    protected function getMonthlyRevenueChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenue = RekapHarian::where('tanggal', $date)->sum('total_omzet');
            $data[] = $revenue / 1000; // Convert to thousands for better chart display
        }
        return $data;
    }
}
