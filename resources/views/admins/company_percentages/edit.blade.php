@extends('layouts.admin')

@section('title', "edit country percentage")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>edit</h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>dashboard</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>country percentage</a></li>
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
                                <label>percentage</label>
                                <input type="number" step="0.01" class="form-control  @error('percentage') is-invalid @enderror" name="percentage"
                                    placeholder="percentage" value="{{ $country_percentage->percentage }}" required autocomplete="off">
                                @error('percentage')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>country</label>
                                <select name="country_id" class="form-control">
                                    @foreach ($countries as $country)
                                        <option value="{{$country->id}}" @if ($country->country_id == $country->id) selected @endif>{{$country->translate(LaravelLocalization::getCurrentLocale())->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
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
