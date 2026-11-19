@extends('tracker.layout')
@section('title','Record application')
@section('content')
<div class="page-heading"><div><span class="eyebrow">PROCESS 02 · FIELD LOG</span><h1>Record an application</h1><p>Log each fertilizer use against a plot to keep field records current.</p></div><span class="pill">{{ count($applications) }} records</span></div>
@if(count($fertilizers) && count($plots))<section class="panel form-panel"><div class="panel-head"><div><span class="step">01</span><div><h2>Application details</h2><p>Fields marked required need a value.</p></div></div></div>
<form method="POST" action="{{ route('applications.store') }}" class="form-grid">@csrf
<label>Fertilizer<select name="fertilizer_id" required><option value="">Select fertilizer</option>@foreach($fertilizers as $f)<option value="{{ $f['id'] }}" @selected(old('fertilizer_id')===$f['id'])>{{ $f['name'] }} ({{ $f['unit'] }})</option>@endforeach</select></label>
<label>Plot<select name="plot_id" required><option value="">Select plot</option>@foreach($plots as $p)<option value="{{ $p['id'] }}" @selected(old('plot_id')===$p['id'])>{{ $p['name'] }} · {{ $p['crop'] }}</option>@endforeach</select></label>
<label>Application method<select name="method" required><option value="">Choose method</option>@foreach(['Broadcasting','Drip irrigation','Foliar spray','Side dressing'] as $v)<option @selected(old('method')===$v)>{{ $v }}</option>@endforeach</select></label>
<label>Application date<input type="date" name="applied_on" value="{{ old('applied_on', now()->toDateString()) }}" required></label>
<label>Quantity applied<input type="number" min="0.01" step="0.01" name="quantity" value="{{ old('quantity') }}" placeholder="e.g. 50" required></label>
<label>Weather condition<select name="weather" required><option value="">Choose weather</option>@foreach(['Sunny','Cloudy','Rainy','Windy'] as $v)<option @selected(old('weather')===$v)>{{ $v }}</option>@endforeach</select></label>
<label class="span-2">Application note <span class="muted">Optional</span><textarea name="note" rows="3" maxlength="500" placeholder="Add useful field notes">{{ old('note') }}</textarea></label>
<div class="span-2 form-end"><button class="button primary" type="submit">Save application <span>→</span></button></div>
</form></section>@else<div class="alert warning">First add at least one fertilizer and one plot in <a href="{{ route('catalog.index') }}">Fertilizer &amp; plots</a>, then return here to record use.</div>@endif
<section class="panel lists"><div class="panel-head"><div><div><h2>Recent applications</h2><p>Entries recorded in this browser session</p></div></div><a class="text-link" href="{{ route('reports.index') }}">View cost summary →</a></div>
@if(count($applications))<div class="table-wrap"><table><thead><tr><th>Date</th><th>Fertilizer</th><th>Plot / crop</th><th>Method</th><th>Quantity</th><th>Weather</th></tr></thead><tbody>@foreach($applications as $a)<tr><td>{{ $a['applied_on'] }}</td><td><strong>{{ $a['fertilizer'] }}</strong></td><td>{{ $a['plot'] }}<small>{{ $a['crop'] }}</small></td><td>{{ $a['method'] }}</td><td>{{ $a['quantity'] }} {{ $a['unit'] }}</td><td>{{ $a['weather'] }}</td></tr>@endforeach</tbody></table></div>@else<p class="empty">No applications recorded yet.</p>@endif</section>
@endsection
