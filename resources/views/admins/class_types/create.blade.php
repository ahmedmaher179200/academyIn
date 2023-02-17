@extends('layouts.admin')

@section('title', "add class types")


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>add</h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>dashboard</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>class types</a></li>
                <li class="active"><i class="fa fa-plus"></i>add</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title">add</h1>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    {{-- @include('admins.partials._errors') --}}
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin: 0 !important;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>percentage</label>
                                <input type="number" class="form-control  @error('long') is-invalid @enderror" name="long"
                                    placeholder="long" value="{{ old('long') }}" required autocomplete="off">
                                @error('long')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>minutes cost	</label>
                                <input type="number" step="0.01" class="form-control  @error('long_cost') is-invalid @enderror" name="long_cost"
                                    placeholder="long_cost" value="{{ old('long_cost') }}" required autocomplete="off">
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
                                <input type="checkbox" id="switcherySize" value="1" class="switchery" name="status" data-size="lg" checked/>
                                <label for="switcherySize" class="font-medium-2 text-bold-600 ml-1">status</label>
                            </div>
                        </div>

                        <div class="row" style="margin: 0 !important;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                        add</button>
                                </div>
                            </div>
                        </div>

                    </form> {{-- end of form --}}

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
