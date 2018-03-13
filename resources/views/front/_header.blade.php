<header>
    <div class="container">
        <a href="{{ route('front.home') }}" class="link-marianne">
            {{--@include('svg.marianne')--}}
            {{--<span class="title">DREAL <br>Nouvelle-Aquitaine</span>--}}
            <img src="{{ asset('images/logo_ministere.jpg') }}" alt="DREAL Nouvelle-Aquitaine" class="logo-image">
        </a>
        <a href="{{ route('front.home') }}" class="link-title">
            <h1>{{ ucfirst(config('app.name')) }}</h1>
            <p class="description">{{ config('app.subname') }}</p>
        </a>
    </div>
</header>