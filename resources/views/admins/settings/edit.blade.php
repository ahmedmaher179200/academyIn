@extends('layouts.admin')

@section('title', "edit settings")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.edit') }} </h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>{{ trans('admin.settings') }}</a></li>
                <li class="active"><i class="fa fa-plus"></i>{{ trans('admin.edit') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title">{{ trans('admin.edit') }} </h1>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    {{-- @include('admins.partials._errors') --}}
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin: 0 !important;">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.students number cost') }}</label>
                                <input type="number" step="0.01" class="form-control  @error('cost_students_number') is-invalid @enderror" name="cost_students_number"
                                    placeholder="{{ trans('admin.students number cost') }}" value="{{ $settings->cost_students_number }}" required autocomplete="off">
                                @error('cost_students_number')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.level cost') }}</label>
                                <input type="number" step="0.01" class="form-control  @error('cost_level') is-invalid @enderror" name="cost_level"
                                    placeholder="{{ trans('admin.level cost') }}" value="{{ $settings->cost_level }}" required autocomplete="off">
                                @error('cost_level')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.year cost') }}</label>
                                <input type="number" step="0.01" class="form-control  @error('cost_year') is-invalid @enderror" name="cost_year"
                                    placeholder="{{ trans('admin.year cost') }}" value="{{ $settings->cost_year }}" required autocomplete="off">
                                @error('cost_year')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.country cost') }}</label>
                                <input type="number" step="0.01" class="form-control  @error('cost_country') is-invalid @enderror" name="cost_country"
                                    placeholder="{{ trans('admin.country cost') }}" value="{{ $settings->cost_country }}" required autocomplete="off">
                                @error('cost_country')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.company percentage cost') }}</label>
                                <input type="number" class="form-control  @error('cost_company_percentage') is-invalid @enderror" name="cost_company_percentage"
                                    placeholder="{{ trans('admin.company percentage cost') }}" value="{{ $settings->cost_company_percentage }}" required autocomplete="off">
                                @error('cost_company_percentage')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="row" style="margin: 0 !important;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                        {{ trans('admin.save') }}</button>
                                </div>
                            </div>
                        </div>

                    </form> {{-- end of form --}}

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
