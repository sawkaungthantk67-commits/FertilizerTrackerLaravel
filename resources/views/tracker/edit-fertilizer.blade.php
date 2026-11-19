@extends('tracker.layout')
@section('title', 'Edit fertilizer')
@section('content')
<div class="page-heading"><div><span class="eyebrow">PROCESS 01 · INVENTORY SETUP</span><h1>Edit fertilizer</h1><p>Update the catalog record. Existing application history stays unchanged.</p></div></div>
<section class="panel form-panel">
    <form method="POST" action="{{ route('catalog.fertilizers.update', $fertilizer['id']) }}" class="form-grid">@csrf @method('PUT')
        <label class="span-2">Fertilizer name<input name="name" value="{{ old('name', $fertilizer['name']) }}" required maxlength="100"></label>
        <label>Fertilizer type<select name="type" required>@foreach(['Organic','Inorganic','Compound','Other'] as $v)<option value="{{ $v }}" @selected(old('type', $fertilizer['type']) === $v)>{{ $v }}</option>@endforeach</select></label>
        <label>Unit<select name="unit" required>@foreach(['kg','L','bag','bottle'] as $v)<option value="{{ $v }}" @selected(old('unit', $fertilizer['unit']) === $v)>{{ $v }}</option>@endforeach</select></label>
        <label>Unit price<input name="unit_price" type="number" min="0" step="0.01" value="{{ old('unit_price', $fertilizer['unit_price']) }}" required></label>
        <label>Current stock<input name="stock" type="number" min="0" step="0.01" value="{{ old('stock', $fertilizer['stock']) }}" required></label>
        <label class="span-2">Supplier name<input name="supplier" value="{{ old('supplier', $fertilizer['supplier']) }}" required maxlength="120"></label>
        <div class="span-2 form-end"><a class="button secondary" href="{{ route('catalog.index') }}">Cancel</a><button class="button primary" type="submit">Save changes</button></div>
    </form>
</section>
@endsection
