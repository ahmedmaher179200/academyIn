@extends('layouts.admin')

@section('title', "edit class type")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.edit') }} </h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>{{ trans('admin.class type') }}</a></li>
                <li class="active"><i class="fa fa-plus"></i>{{ trans('admin.edit') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title">{{ trans('admin.edit') }}</h1>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    {{-- @include('admins.partials._errors') --}}
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin: 0 !important;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.long') }}</label>
                                <input type="number" class="form-control  @error('long') is-invalid @enderror" name="long"
                                    placeholder="{{ trans('admin.long') }}" value="{{$class_type->long }}" required autocomplete="off">
                                @error('long')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.minutes cost') }}	</label>
                                <input type="number" step="0.01" class="form-control  @error('long_cost') is-invalid @enderror" name="long_cost"
                                    placeholder="{{ trans('admin.minutes cost') }}" value="{{ $class_type->long_cost }}" required autocomplete="off">
                                @error('long_cost')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        {{-- status --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="checkbox" id="switcherySize" value="1" class="switchery" name="status" data-size="lg" @if ($class_type->status == 1) checked @endif/>
                                <label for="switcherySize" class="font-medium-2 text-bold-600 ml-1">{{ trans('admin.status') }}</label>
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
