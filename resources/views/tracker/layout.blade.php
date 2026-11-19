<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Fieldwise') | Fertilizer Tracker</title>
    <link rel="stylesheet" href="{{ asset('css/tracker.css') }}">
</head>
<body>
<div class="shell">
    <aside class="sidebar"><a class="brand" href="{{ route('catalog.index') }}"><span class="brand-mark">F</span><span>Fieldwise<small>FERTILIZER TRACKER</small></span></a>
        <p class="nav-label">WORKSPACE</p>
        <nav>
            <a class="nav-item {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}"><span>▦</span> Fertilizer &amp; plots</a>
            <a class="nav-item {{ request()->routeIs('applications.*') ? 'active' : '' }}" href="{{ route('applications.index') }}"><span>＋</span> Record application</a>
            <a class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}"><span>▤</span> Usage &amp; costs</a>
        </nav>
        <div class="side-note"><div class="seed">✦</div><strong>Grow with clarity</strong><p>Keep fertilizer, field and application details together.</p></div>
        <div class="profile"><div class="avatar">FM</div><div><strong>Farm manager</strong><small>Local demo workspace</small></div></div>
    </aside>
    <main class="main"><header class="topbar"><div><span class="eyebrow">FARM OPERATIONS</span><strong>Fertilizer Usage &amp; Cost Tracker</strong></div><span class="status"><i></i> Workspace ready</span></header>
      <section class="content">
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert danger"><strong>Please fix these details:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
      </section>
    </main>
</div>
</body></html>
