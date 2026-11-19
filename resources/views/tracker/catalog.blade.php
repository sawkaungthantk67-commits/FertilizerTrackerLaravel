@extends('tracker.layout')
@section('title', 'Fertilizer & plots')
@section('content')
<div class="page-heading">
    <div><span class="eyebrow">PROCESS 01 · INVENTORY SETUP</span><h1>Fertilizer &amp; plot management</h1><p>Create, view, edit, search, and delete fertilizer and farm plot records.</p></div>
    <span class="pill">{{ $totalFertilizers + $totalPlots }} items</span>
</div>

<div class="two-col">
    <section class="panel">
        <div class="panel-head"><div><span class="step">01</span><div><h2>Add fertilizer</h2><p>Product, supplier and available stock</p></div></div></div>
        <form method="POST" action="{{ route('catalog.fertilizers.store') }}" class="form-grid">@csrf
            <label class="span-2">Fertilizer name<input name="name" value="{{ old('name') }}" placeholder="e.g. Urea 46%" required maxlength="100"></label>
            <label>Fertilizer type<select name="type" required><option value="">Choose type</option>@foreach(['Organic','Inorganic','Compound','Other'] as $v)<option @selected(old('type') === $v)>{{ $v }}</option>@endforeach</select></label>
            <label>Unit<select name="unit" required><option value="">Choose unit</option>@foreach(['kg','L','bag','bottle'] as $v)<option @selected(old('unit') === $v)>{{ $v }}</option>@endforeach</select></label>
            <label>Unit price<input name="unit_price" type="number" min="0" step="0.01" value="{{ old('unit_price') }}" placeholder="0.00" required></label>
            <label>Stock quantity<input name="stock" type="number" min="0" step="0.01" value="{{ old('stock') }}" placeholder="e.g. 500" required></label>
            <label class="span-2">Supplier name<input name="supplier" value="{{ old('supplier') }}" placeholder="e.g. AgriSupply Co." required maxlength="120"></label>
            <div class="span-2 form-end"><button class="button primary" type="submit">Add fertilizer <span>→</span></button></div>
        </form>
    </section>

    <section class="panel">
        <div class="panel-head"><div><span class="step">02</span><div><h2>Add a plot</h2><p>Record the field details for planning</p></div></div></div>
        <form method="POST" action="{{ route('catalog.plots.store') }}" class="form-grid">@csrf
            <label class="span-2">Plot name<input name="name" value="{{ old('name') }}" placeholder="e.g. North Field A" required maxlength="100"></label>
            <label>Crop<input name="crop" value="{{ old('crop') }}" placeholder="e.g. Corn" required maxlength="100"></label>
            <label>Soil type<input name="soil_type" value="{{ old('soil_type') }}" placeholder="e.g. Loamy" required maxlength="80"></label>
            <label class="span-2">Plot size (acres)<input name="size" type="number" min="0.01" step="0.01" value="{{ old('size') }}" placeholder="e.g. 2.5" required></label>
            <div class="span-2 form-end"><button class="button primary" type="submit">Add plot <span>→</span></button></div>
        </form>
    </section>
</div>

<div class="two-col lists">
    <section class="panel">
        <div class="panel-head"><div><div><h2>Fertilizer catalog</h2><p>Showing {{ $fertilizers->count() }} of {{ $totalFertilizers }} products in this session.</p></div></div></div>
        <form method="GET" action="{{ route('catalog.index') }}" class="search-form">
            <input type="search" name="fertilizer_search" value="{{ $fertilizerSearch }}" placeholder="Search name, supplier, or type" aria-label="Search fertilizers">
            @if($plotSearch)<input type="hidden" name="plot_search" value="{{ $plotSearch }}">@endif
            <button class="button primary" type="submit">Search</button>
            @if($fertilizerSearch)<a class="button secondary" href="{{ route('catalog.index', ['plot_search' => $plotSearch]) }}">Clear</a>@endif
        </form>
        @if($fertilizers->count())
            <div class="table-wrap"><table><thead><tr><th>Product</th><th>Supplier</th><th>Stock</th><th>Price / unit</th><th>Actions</th></tr></thead><tbody>
                @foreach($fertilizers as $f)
                    <tr>
                        <td><strong>{{ $f['name'] }}</strong><small>{{ $f['type'] }} · {{ $f['unit'] }}</small></td>
                        <td>{{ $f['supplier'] }}</td><td>{{ $f['stock'] }} {{ $f['unit'] }}</td><td>{{ number_format((float) $f['unit_price'], 2) }}</td>
                        <td><div class="filter-actions"><a class="button secondary" href="{{ route('catalog.fertilizers.edit', $f['id']) }}">Edit</a><form method="POST" action="{{ route('catalog.fertilizers.delete', $f['id']) }}" onsubmit="return confirm('Delete this fertilizer? Existing application history will remain.');">@csrf @method('DELETE')<button class="button danger" type="submit">Delete</button></form></div></td>
                    </tr>
                @endforeach
            </tbody></table></div>
        @else
            <p class="empty">{{ $fertilizerSearch ? 'No fertilizers match your search.' : 'No fertilizer yet. Add your first product above.' }}</p>
        @endif
    </section>

    <section class="panel">
        <div class="panel-head"><div><div><h2>Growing plots</h2><p>Showing {{ $plots->count() }} of {{ $totalPlots }} plots in this session.</p></div></div></div>
        <form method="GET" action="{{ route('catalog.index') }}" class="search-form">
            <input type="search" name="plot_search" value="{{ $plotSearch }}" placeholder="Search plot, crop, or soil" aria-label="Search plots">
            @if($fertilizerSearch)<input type="hidden" name="fertilizer_search" value="{{ $fertilizerSearch }}">@endif
            <button class="button primary" type="submit">Search</button>
            @if($plotSearch)<a class="button secondary" href="{{ route('catalog.index', ['fertilizer_search' => $fertilizerSearch]) }}">Clear</a>@endif
        </form>
        @if($plots->count())
            <div class="table-wrap"><table><thead><tr><th>Plot</th><th>Crop</th><th>Soil</th><th>Size</th><th>Actions</th></tr></thead><tbody>
                @foreach($plots as $plot)
                    <tr>
                        <td><strong>{{ $plot['name'] }}</strong></td><td>{{ $plot['crop'] }}</td><td>{{ $plot['soil_type'] }}</td><td>{{ $plot['size'] }} acres</td>
                        <td><div class="filter-actions"><a class="button secondary" href="{{ route('catalog.plots.edit', $plot['id']) }}">Edit</a><form method="POST" action="{{ route('catalog.plots.delete', $plot['id']) }}" onsubmit="return confirm('Delete this plot? Existing application history will remain.');">@csrf @method('DELETE')<button class="button danger" type="submit">Delete</button></form></div></td>
                    </tr>
                @endforeach
            </tbody></table></div>
        @else
            <p class="empty">{{ $plotSearch ? 'No plots match your search.' : 'No plots yet. Add your first plot above.' }}</p>
        @endif
    </section>
</div>
@endsection
