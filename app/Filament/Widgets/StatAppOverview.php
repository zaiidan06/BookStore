<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatAppOverview extends BaseWidget
{

    protected static bool $isLazy = true;
    protected ?string $heading = 'Analytics'; // Judul widget
    protected ?string $description = 'An overview of Book Store Analytics.'; // Deskripsi widget

    protected function getStats(): array
    {
        // Membuat statistik untuk Users, Products, dan Transactions
        return [
            $this->generateStat(
                'Users',
                User::class,
                'heroicon-m-arrow-trending-up',
                'heroicon-m-arrow-trending-down'
            ),
            $this->generateStat(
                'Books',
                Book::class,
                'heroicon-m-arrow-trending-up',
                'heroicon-m-arrow-trending-down'
            ),
            $this->generateStat(
                'Transactions',
                Transaction::class,
                'heroicon-m-arrow-trending-up',
                'heroicon-m-arrow-trending-down'
            ),
        ];
    }

    private function generateStat($title, $model, $iconUp, $iconDown)
    {
        // Hitung total jumlah data dalam tabel yang bersangkutan
        $totalCount = $model::count();

        // Ambil jumlah data yang dibuat dalam 7 hari terakhir
        $data = $model::where('created_at', '>=', Carbon::now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Menyiapkan data grafik untuk 7 hari terakhir, jika tidak ada data, isi dengan 0
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartData[] = $data[$date] ?? 0; // Gunakan nilai dari database atau 0 jika tidak ada data
        }

        // Hitung tren: bandingkan jumlah hari ini dengan kemarin
        $todayCount = $model::whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = $model::whereDate('created_at', Carbon::yesterday())->count();
        $trendIcon = $todayCount >= $yesterdayCount ? $iconUp : $iconDown; // Gunakan ikon tren naik/turun
        $trendColor = $todayCount >= $yesterdayCount ? 'success' : 'danger'; // Warna hijau untuk naik, merah untuk turun

        // Kembalikan data statistik yang akan ditampilkan di widget
        return Stat::make($title, $totalCount)
            ->description("All $title from the database")
            ->descriptionIcon($trendIcon)
            ->chart($chartData) // Data untuk grafik
            ->color($trendColor); // Warna sesuai tren
    }
}
