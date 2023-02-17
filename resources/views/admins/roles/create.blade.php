@extends('layouts.admin')

@section('title', "roles-add")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.add') }}</h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>{{ trans('admin.users') }}</a></li>
                <li class="active"><i class="fa fa-plus"></i>{{ trans('admin.add') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title">{{ trans('admin.add') }}</h1>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    {{-- @include('admins.partials._errors') --}}
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin: 0 !important;">
                        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ trans('admin.name') }}</label>
                                    <input type="text" class="form-control  @error('name') is-invalid @enderror" name="name"
                                        placeholder="{{ trans('admin.name') }}" value="{{ old('name') }}" required autocomplete="off">
                                    @error('name')
                                        <small class=" text text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{ trans('admin.description') }}</label>
                                    <input type="text" class="form-control  @error('description') is-invalid @enderror" name="description"
                                    placeholder="{{ trans('admin.description') }}" value="{{ old('description') }}" required autocomplete="off">
                                    @error('description')
                                        <small class=" text text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                        @php
                        $models = [
                            "admins",
                            "roles",
                            'students',
                            'teachers',
                            "countries",
                            "curriculums",
                            "promo_codes",
                            "questions",
                            'class_types',
                            'settings',
                            'offers',
                            'classes',
                        ];
                        $maps = ['read', 'create','update', 'delete'];
                        @endphp

                        @foreach ($models as $model)
                            <div class="list-group col-md-3" style="padding-left: 15px !important;">
                                <a href="#" class="list-group-item active">
                                    {{$model}}
                                </a>
                                {{-- --}}
                                @foreach ($maps as $map)
                                    <label>
                                        <input type="checkbox" name="permissions[]" value="{{$map . '-' . $model}}">{{$map}}
                                    </label>
                                    <hr>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="row" style="margin: 0 !important;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                        {{ trans('admin.add') }}</button>
                                </div>
                            </div>
                        </div>

                    </form> {{-- end of form --}}

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
