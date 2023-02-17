@extends('layouts.admin')

@section('title', "edit countries")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>edit</h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>dashboard</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>countries</a></li>
                <li class="active"><i class="fa fa-plus"></i>edit</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title">edit</h1>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    {{-- @include('admins.partials._errors') --}}
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin: 0 !important;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>cost</label>
                                <input type="number" step="0.01" class="form-control  @error('cost') is-invalid @enderror" name="cost"
                                    placeholder="cost" value="{{ $year_cost->cost }}" required autocomplete="off">
                                @error('cost')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>year</label>
                                <select name="year_id" class="form-control">
                                    @foreach ($years as $year)
                                        <option value="{{$year->id}}" @if ($year_cost->year_id == $year->id) selected @endif>{{$year->Level->Curriculum->translate(LaravelLocalization::getCurrentLocale())->name}} => {{$year->Level->translate(LaravelLocalization::getCurrentLocale())->name}} => {{$year->translate(LaravelLocalization::getCurrentLocale())->name}}</option>
                                    @endforeach
                                </select>
                                @error('year_id')
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
                                        save</button>
                                </div>
                            </div>
                        </div>

                    </form> {{-- end of form --}}

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
