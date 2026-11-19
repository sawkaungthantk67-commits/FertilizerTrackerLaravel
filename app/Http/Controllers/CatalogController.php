<?php

namespace App\Http\Controllers;

use App\Models\FarmPlot;
use App\Models\Fertilizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'fertilizer_search' => ['nullable', 'string', 'max:100'],
            'plot_search' => ['nullable', 'string', 'max:100'],
        ]);
        $fertilizerSearch = trim($filters['fertilizer_search'] ?? '');
        $plotSearch = trim($filters['plot_search'] ?? '');
        $allFertilizers = Fertilizer::all()->map->toArray();
        $allPlots = FarmPlot::all()->map->toArray();
        $fertilizers = $allFertilizers->filter(fn ($fertilizer) => $this->matchesSearch(
            $fertilizerSearch,
            [$fertilizer['name'], $fertilizer['type'], $fertilizer['supplier']]
        ))->values();
        $plots = $allPlots->filter(fn ($plot) => $this->matchesSearch(
            $plotSearch,
            [$plot['name'], $plot['crop'], $plot['soil_type']]
        ))->values();

        return view('tracker.catalog', [
            'fertilizers' => $fertilizers,
            'plots' => $plots,
            'totalFertilizers' => $allFertilizers->count(),
            'totalPlots' => $allPlots->count(),
            'fertilizerSearch' => $fertilizerSearch,
            'plotSearch' => $plotSearch,
        ]);
    }

    public function storeFertilizer(Request $request)
    {
        $data = $request->validate($this->fertilizerRules());
        Fertilizer::create([
            'id' => (string) Str::uuid(),
            ...$data,
            'initial_stock' => (float) $data['stock'],
        ]);

        return to_route('catalog.index')->with('success', 'Fertilizer added to your catalog.');
    }

    public function storePlot(Request $request)
    {
        $data = $request->validate($this->plotRules());
        FarmPlot::create(['id' => (string) Str::uuid(), ...$data]);

        return to_route('catalog.index')->with('success', 'Plot added to your catalog.');
    }

    public function editFertilizer(Request $request, string $id)
    {
        $fertilizer = Fertilizer::findOrFail($id)->toArray();

        return view('tracker.edit-fertilizer', compact('fertilizer'));
    }

    public function updateFertilizer(Request $request, string $id)
    {
        $data = $request->validate($this->fertilizerRules());
        $fertilizer = Fertilizer::findOrFail($id);
        $fertilizer->fill([
            ...$data,
            'initial_stock' => (float) $data['stock'],
        ])->save();

        return to_route('catalog.index')->with('success', 'Fertilizer details updated.');
    }

    public function deleteFertilizer(Request $request, string $id)
    {
        Fertilizer::findOrFail($id)->delete();

        return to_route('catalog.index')->with('success', 'Fertilizer deleted. Existing application history is retained.');
    }

    public function editPlot(Request $request, string $id)
    {
        $plot = FarmPlot::findOrFail($id)->toArray();

        return view('tracker.edit-plot', compact('plot'));
    }

    public function updatePlot(Request $request, string $id)
    {
        $data = $request->validate($this->plotRules());
        FarmPlot::findOrFail($id)->update($data);

        return to_route('catalog.index')->with('success', 'Plot details updated.');
    }

    public function deletePlot(Request $request, string $id)
    {
        FarmPlot::findOrFail($id)->delete();

        return to_route('catalog.index')->with('success', 'Plot deleted. Existing application history is retained.');
    }

    private function fertilizerRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:Organic,Inorganic,Compound,Other'],
            'unit' => ['required', 'in:kg,L,bag,bottle'],
            'unit_price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'supplier' => ['required', 'string', 'max:120'],
            'stock' => ['required', 'numeric', 'min:0', 'max:999999'],
        ];
    }

    private function plotRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'crop' => ['required', 'string', 'max:100'],
            'soil_type' => ['required', 'string', 'max:80'],
            'size' => ['required', 'numeric', 'gt:0', 'max:999999'],
        ];
    }

    private function matchesSearch(string $search, array $values): bool
    {
        if ($search === '') {
            return true;
        }

        foreach ($values as $value) {
            if (str_contains(mb_strtolower((string) $value), mb_strtolower($search))) {
                return true;
            }
        }

        return false;
    }
}
