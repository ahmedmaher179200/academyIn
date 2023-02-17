@extends('layouts.office')

@section('title', 'dashbourd')

@section('content')

    <div class="content-wrapper" style="min-height: 0">

        <section class="content-header">

            <h1>dashboard</h1>

            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i>dashboard</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                {{-- categories --}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>10</h3>

                            <p>admins</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                            {{-- @if (auth()->user()->hasPermission('read-admins')) --}}
                                <a href="{{url('offices')}}" class="small-box-footer">read<i class="fa fa-arrow-circle-right"></i></a>
                            {{-- @endif --}}
                    </div>
                </div>

            </div><!-- end of row -->
        </section><!-- end of content -->
        {{-- @include('admins.includes._char') --}}

    </div><!-- end of content wrapper -->


@endsection


@push('script')


@endpush
