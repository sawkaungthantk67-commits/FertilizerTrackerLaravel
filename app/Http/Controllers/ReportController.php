<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use App\Models\UsageApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'fertilizer' => ['nullable', 'string', 'max:100'],
            'plot' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
        ]);
        $fertilizerFilter = trim($filters['fertilizer'] ?? '');
        $plotFilter = trim($filters['plot'] ?? '');
        $dateFrom = $filters['date_from'] ?? '';
        $dateTo = $filters['date_to'] ?? '';
        $all = UsageApplication::all()->map->toArray();
        $rows = $all->filter(function ($row) use ($fertilizerFilter, $plotFilter, $dateFrom, $dateTo) {
            return (! $fertilizerFilter || str_contains(mb_strtolower($row['fertilizer']), mb_strtolower($fertilizerFilter)))
                && (! $plotFilter || str_contains(mb_strtolower($row['plot']), mb_strtolower($plotFilter)))
                && (! $dateFrom || $row['applied_on'] >= $dateFrom)
                && (! $dateTo || $row['applied_on'] <= $dateTo);
        })->values();

        $fertilizers = Fertilizer::all()->map->toArray();
        $inventoryAlerts = $fertilizers->filter(function ($fertilizer) {
            $initialStock = (float) ($fertilizer['initial_stock'] ?? 0);

            $currentStock = (float) $fertilizer['stock'];

            return $currentStock <= 0 || ($initialStock > 0 && $currentStock <= $initialStock * 0.2);
        })->values();

        $spendByFertilizer = $rows->groupBy('fertilizer')->map(fn ($items) => [
            'quantity' => $items->sum('quantity'),
            'cost' => $items->sum(fn ($row) => $row['quantity'] * $row['unit_price']),
            'unit' => $items->first()['unit'],
        ]);
        $highestSpend = $spendByFertilizer->sortByDesc('cost')->first();
        $highestSpendName = $spendByFertilizer->sortByDesc('cost')->keys()->first();

        $weatherAdvisories = $rows
            ->whereIn('weather', ['Rainy', 'Windy'])
            ->map(fn ($row) => "{$row['applied_on']}: {$row['weather']} conditions recorded for {$row['plot']}. Review product-label weather precautions and local application rules.")
            ->unique()
            ->values();

        $seasonalPlans = $all
            ->groupBy('plot_id')
            ->map(function ($items) {
                $latest = $items->sortByDesc('applied_on')->first();

                return [
                    'plot' => $latest['plot'],
                    'crop' => $latest['crop'],
                    'last_applied' => $latest['applied_on'],
                    'review_on' => Carbon::parse($latest['applied_on'])->addDays(30)->toDateString(),
                ];
            })
            ->values();

        return view('tracker.reports', [
            'rows' => $rows,
            'totalQuantity' => $rows->sum('quantity'),
            'totalCost' => $rows->sum(fn ($row) => $row['quantity'] * $row['unit_price']),
            'fertilizerFilter' => $fertilizerFilter,
            'plotFilter' => $plotFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'inventoryAlerts' => $inventoryAlerts,
            'highestSpend' => $highestSpend,
            'highestSpendName' => $highestSpendName,
            'weatherAdvisories' => $weatherAdvisories,
            'seasonalPlans' => $seasonalPlans,
        ]);
    }
}
