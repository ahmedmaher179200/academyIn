@extends('layouts.admin')

@section('title', 'dashbourd')

@section('content')

    <div class="content-wrapper" style="min-height: 0">

        <section class="content-header">

            <h1>{{ trans('admin.dashboard') }}</h1>

            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                {{-- categories --}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{App\Models\Admin::count()}}</h3>

                            <p>{{ trans('admin.admins') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{App\Models\Country::active()->count()}}</h3>

                            <p>{{ trans('admin.countries') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{App\Models\Student::count()}}</h3>

                            <p>{{ trans('admin.students') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-black">
                        <div class="inner">
                            <h3>{{App\Models\Teacher::count()}}</h3>

                            <p>{{ trans('admin.teachers') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-black">
                        <div class="inner">
                            <h3>{{App\Models\Question::count()}}</h3>

                            <p>{{ trans('admin.questions') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{App\Models\Offer::count()}}</h3>

                            <p>{{ trans('admin.offers') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{App\Models\Role::count()}}</h3>

                            <p>{{ trans('admin.roles') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                    </div>
                </div>

            </div><!-- end of row -->
        </section><!-- end of content -->
        {{-- @include('admins.includes._char') --}}

    </div><!-- end of content wrapper -->


@endsection


@push('script')


@endpush
