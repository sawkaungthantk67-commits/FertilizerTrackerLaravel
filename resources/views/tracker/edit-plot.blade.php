@extends('tracker.layout')
@section('title', 'Edit plot')
@section('content')
<div class="page-heading"><div><span class="eyebrow">PROCESS 01 · INVENTORY SETUP</span><h1>Edit plot</h1><p>Update the farm plot record. Existing application history stays unchanged.</p></div></div>
<section class="panel form-panel">
    <form method="POST" action="{{ route('catalog.plots.update', $plot['id']) }}" class="form-grid">@csrf @method('PUT')
        <label class="span-2">Plot name<input name="name" value="{{ old('name', $plot['name']) }}" required maxlength="100"></label>
        <label>Crop<input name="crop" value="{{ old('crop', $plot['crop']) }}" required maxlength="100"></label>
        <label>Soil type<input name="soil_type" value="{{ old('soil_type', $plot['soil_type']) }}" required maxlength="80"></label>
        <label class="span-2">Plot size (acres)<input name="size" type="number" min="0.01" step="0.01" value="{{ old('size', $plot['size']) }}" required></label>
        <div class="span-2 form-end"><a class="button secondary" href="{{ route('catalog.index') }}">Cancel</a><button class="button primary" type="submit">Save changes</button></div>
    </form>
</section>
@endsection
