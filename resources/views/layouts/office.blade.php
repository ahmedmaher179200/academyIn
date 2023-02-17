
@include('offices.includes.header')

@include('offices.includes._navbar')


@include('offices.includes._aside')

@yield('content')

@include('offices.partials._session')

@stack('char')

@include('offices.includes._footer')
