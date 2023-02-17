
@include('admins.includes.header')

@include('admins.includes._navbar')


@include('admins.includes._aside')

@yield('content')

@include('admins.partials._session')

@stack('char')

@include('admins.includes._footer')
