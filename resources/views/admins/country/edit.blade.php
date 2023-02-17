@extends('layouts.admin')

@section('title', "edit countries")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.edit') }}</h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>{{ trans('admin.countries') }}</a></li>
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
                                <label>{{ trans('admin.image') }}</label>
                                <input type="file" class="form-control"  name="image"
                                    autocomplete="off">
                                @error('image')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.dialing_code') }}</label>
                                <input type="text" class="form-control  @error('dialing_code') is-invalid @enderror" name="dialing_code"
                                    placeholder="{{ trans('admin.dialing_code') }}" value="{{$country->dialing_code}}" required autocomplete="off">
                                @error('dialing_code')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ trans('admin.name in') }} {{$properties['native']}}</label>
                                    <input type="text" class="form-control  @error('name') is-invalid @enderror" name="countries[{{$localeCode}}][name]"
                                        placeholder="{{ trans('admin.name') }}" value="{{$country->translate($localeCode)->name}}" required autocomplete="off">
                                    @error('name')
                                        <small class=" text text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        {{-- status --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="checkbox" id="switcherySize" value="1" class="switchery" name="status" data-size="lg" @if ($country->status == 1) checked @endif/>
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
