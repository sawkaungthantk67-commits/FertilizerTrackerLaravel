@extends('tracker.layout')
@section('title','Usage & costs')
@section('content')
<div class="page-heading"><div><span class="eyebrow">PROCESS 03 · USAGE REVIEW</span><h1>Usage &amp; cost summary</h1><p>Review fertilizer usage, estimated costs, stock alerts, and planning notes.</p></div><span class="pill">{{ $rows->count() }} matching records</span></div>

<section class="metrics">
    <article class="metric"><span class="metric-icon green">▤</span><small>APPLICATIONS</small><strong>{{ $rows->count() }}</strong><span class="metric-foot">Matching your filters</span></article>
    <article class="metric"><span class="metric-icon blue">↗</span><small>TOTAL QUANTITY</small><strong>{{ number_format($totalQuantity,2) }}</strong><span class="metric-foot">Across matching records</span></article>
    <article class="metric"><span class="metric-icon amber">฿</span><small>ESTIMATED COST</small><strong>{{ number_format($totalCost,2) }}</strong><span class="metric-foot">Quantity × saved unit price</span></article>
</section>

<section class="panel">
    <div class="panel-head"><div><div><h2>Filter applications</h2><p>Use any combination of filters, including a date range.</p></div></div></div>
    <form method="GET" action="{{ route('reports.index') }}" class="filter-grid">
        <label>Fertilizer name<input name="fertilizer" value="{{ $fertilizerFilter }}" placeholder="e.g. Urea"></label>
        <label>Plot name<input name="plot" value="{{ $plotFilter }}" placeholder="e.g. North Field"></label>
        <label>From date<input type="date" name="date_from" value="{{ $dateFrom }}"></label>
        <label>To date<input type="date" name="date_to" value="{{ $dateTo }}"></label>
        <div class="filter-actions"><button class="button primary" type="submit">Apply filters</button><a class="button secondary" href="{{ route('reports.index') }}">Clear</a></div>
    </form>
</section>

<section class="panel">
    <div class="panel-head"><div><span class="step">01</span><div><h2>Inventory and stock alerts</h2><p>Low stock means 20% or less of the original recorded quantity.</p></div></div></div>
    @if($inventoryAlerts->count())
        <div class="insight-list">@foreach($inventoryAlerts as $fertilizer)<div class="insight warning-insight"><strong>{{ $fertilizer['name'] }} · Low stock</strong><span>{{ number_format((float) $fertilizer['stock'], 2) }} {{ $fertilizer['unit'] }} remaining. Review purchasing needs before the next application.</span></div>@endforeach</div>
    @else
        <p class="empty">No low-stock alerts. Add stock on the <a href="{{ route('catalog.index') }}">Fertilizer &amp; plots</a> page to begin tracking inventory.</p>
    @endif
</section>

<section class="panel">
    <div class="panel-head"><div><span class="step">02</span><div><h2>Cost calculation</h2><p>Estimated cost is quantity applied multiplied by the saved unit price.</p></div></div></div>
    @if($rows->count())
        <div class="table-wrap"><table><thead><tr><th>Date</th><th>Fertilizer</th><th>Plot</th><th>Quantity</th><th>Unit price</th><th>Estimated cost</th></tr></thead><tbody>@foreach($rows as $a)<tr><td>{{ $a['applied_on'] }}</td><td><strong>{{ $a['fertilizer'] }}</strong></td><td>{{ $a['plot'] }}</td><td>{{ number_format($a['quantity'],2) }} {{ $a['unit'] }}</td><td>{{ number_format($a['unit_price'],2) }}</td><td><strong>{{ number_format($a['quantity'] * $a['unit_price'],2) }}</strong></td></tr>@endforeach</tbody></table></div>
    @else
        <p class="empty">No matching records. Record an application or change your filters.</p>
    @endif
</section>

<div class="two-col insight-grid">
    <section class="panel">
        <div class="panel-head"><div><span class="step">03</span><div><h2>Cost-saving suggestions</h2><p>Based on recorded spend; no savings are guaranteed.</p></div></div></div>
        @if($highestSpend)
            <div class="insight-list">
                <div class="insight"><strong>Highest recorded spend: {{ $highestSpendName }}</strong><span>{{ number_format($highestSpend['quantity'], 2) }} {{ $highestSpend['unit'] }} were applied, with an estimated cost of {{ number_format($highestSpend['cost'], 2) }}. Compare supplier quotes and pack sizes before your next purchase.</span></div>
                <div class="insight"><strong>Reduce avoidable waste</strong><span>Match purchases to recorded plot needs and verify the product label and soil-test guidance before planning quantities.</span></div>
            </div>
        @else
            <p class="empty">Record an application to get suggestions based on actual usage and cost.</p>
        @endif
    </section>

    <section class="panel">
        <div class="panel-head"><div><span class="step">04</span><div><h2>Crop health and compliance</h2><p>General recordkeeping prompts, not agronomic prescriptions.</p></div></div></div>
        <div class="insight-list">
            @forelse($weatherAdvisories as $advisory)<div class="insight warning-insight"><span>{{ $advisory }}</span></div>@empty
                @if($rows->count())<div class="insight"><strong>Keep field records current</strong><span>No rainy or windy application conditions are recorded in this filtered period. Continue following product-label directions and local environmental requirements.</span></div>
                @else<p class="empty">Application and weather records will appear here after you record field use.</p>@endif
            @endforelse
            @if($rows->count())<div class="insight"><strong>Check before applying</strong><span>Use soil-test results, crop stage, product-label dosage limits, and local guidance when deciding whether or when to apply fertilizer.</span></div>@endif
        </div>
    </section>
</div>

<section class="panel">
    <div class="panel-head"><div><span class="step">05</span><div><h2>Seasonal planning assistant</h2><p>Review the latest recorded application for each plot before planning follow-up work.</p></div></div></div>
    @if($seasonalPlans->count())
        <div class="table-wrap"><table><thead><tr><th>Plot</th><th>Crop</th><th>Last recorded application</th><th>Planning checkpoint</th></tr></thead><tbody>@foreach($seasonalPlans as $plan)<tr><td><strong>{{ $plan['plot'] }}</strong></td><td>{{ $plan['crop'] }}</td><td>{{ $plan['last_applied'] }}</td><td>{{ $plan['review_on'] }} <small>30-day record review, not an application due date</small></td></tr>@endforeach</tbody></table></div>
        <p class="planner-note">Use crop stage, soil tests, product labels, and local expert guidance to decide the actual application schedule. The 30-day checkpoint is an administrative reminder only.</p>
    @else
        <p class="empty">Record applications against plots to build a seasonal history.</p>
    @endif
</section>

<div class="info-note"><strong>Demo data storage</strong><span>This learning project keeps fertilizer, plot, and application entries in the current browser session; they are not saved to a database. The advice panels use simple rules over your entered records and do not call an AI service or replace professional agronomic guidance.</span></div>
@endsection
