@push('head')
    <link
        href="/favicon.ico"
        id="favicon"
        rel="icon"
    >
@endpush

@auth
    <img class="img-fluid mx-auto d-block" src="{{ asset('logo2.png') }}">
@endauth

@guest
    <img class="img-fluid" src="{{ asset('logo1.png') }}">
@endguest