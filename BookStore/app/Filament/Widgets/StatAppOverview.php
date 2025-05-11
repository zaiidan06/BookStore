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
    protected ?string $heading = 'Analytics';
    protected ?string $description = 'An overview of Book Store Analytics.';

    protected function getStats(): array
    {
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
        $totalCount = $model::count();

        $data = $model::where('created_at', '>=', Carbon::now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartData[] = $data[$date] ?? 0;
        }

        $todayCount = $model::whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = $model::whereDate('created_at', Carbon::yesterday())->count();
        $trendIcon = $todayCount >= $yesterdayCount ? $iconUp : $iconDown;
        $trendColor = $todayCount >= $yesterdayCount ? 'success' : 'danger';

        return Stat::make($title, $totalCount)
            ->description("All $title from the database")
            ->descriptionIcon($trendIcon)
            ->chart($chartData)
            ->color($trendColor);
    }
}
