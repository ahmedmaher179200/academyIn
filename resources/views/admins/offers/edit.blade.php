@extends('layouts.admin')

@section('title', "edit offers")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.edit') }} </h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>{{ trans('admin.offers') }}</a></li>
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
                                <label>{{ trans('admin.price') }}</label>
                                <input type="number" step="0.01" class="form-control  @error('price') is-invalid @enderror" name="price"
                                    placeholder="{{ trans('admin.price') }}" value="{{$offer->price}}" required autocomplete="off">
                                @error('price')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.discount') }}</label>
                                <input type="number" min="0" max="100" class="form-control  @error('discount') is-invalid @enderror" name="discount"
                                    placeholder="{{ trans('admin.discount') }}" value="{{$offer->discount}}" required autocomplete="off">
                                @error('discount')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{ trans('admin.classes count') }}</label>
                                <input type="number" class="form-control  @error('classes_count') is-invalid @enderror" name="classes_count"
                                    placeholder="{{ trans('admin.classes count') }}" value="{{$offer->classes_count}}" required autocomplete="off">
                                @error('classes_count')
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
