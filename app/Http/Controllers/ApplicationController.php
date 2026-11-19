<?php

namespace App\Http\Controllers;

use App\Models\FarmPlot;
use App\Models\Fertilizer;
use App\Models\UsageApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        return view('tracker.applications', [
            'fertilizers' => Fertilizer::all()->toArray(),
            'plots' => FarmPlot::all()->toArray(),
            'applications' => UsageApplication::query()->latest()->get()->map->toArray(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fertilizer_id' => ['required', 'uuid', 'exists:fertilizers,id'],
            'plot_id' => ['required', 'uuid', 'exists:plots,id'],
            'method' => ['required', 'in:Broadcasting,Drip irrigation,Foliar spray,Side dressing'],
            'applied_on' => ['required', 'date'],
            'quantity' => ['required', 'numeric', 'gt:0', 'max:999999'],
            'weather' => ['required', 'in:Sunny,Cloudy,Rainy,Windy'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);
        $failure = DB::transaction(function () use ($data) {
            $fertilizer = Fertilizer::query()->lockForUpdate()->findOrFail($data['fertilizer_id']);
            $plot = FarmPlot::findOrFail($data['plot_id']);

            if ((float) $data['quantity'] > $fertilizer->stock) {
                return 'The quantity applied cannot be greater than the available fertilizer stock.';
            }

            $fertilizer->decrement('stock', (float) $data['quantity']);
            UsageApplication::create([
                'id' => (string) Str::uuid(),
                'fertilizer_id' => $fertilizer->id,
                'fertilizer' => $fertilizer->name,
                'unit' => $fertilizer->unit,
                'unit_price' => $fertilizer->unit_price,
                'plot_id' => $plot->id,
                'plot' => $plot->name,
                'crop' => $plot->crop,
                'soil_type' => $plot->soil_type,
                'plot_size' => $plot->size,
                'method' => $data['method'],
                'applied_on' => $data['applied_on'],
                'quantity' => (float) $data['quantity'],
                'weather' => $data['weather'],
                'note' => $data['note'] ?? '',
            ]);

            return null;
        });

        if ($failure !== null) {
            return back()->withErrors(['quantity' => $failure])->withInput();
        }

        return to_route('applications.index')->with('success', 'Application recorded successfully.');
    }
}
