@extends('layouts.admin')

@section('title', 'edit')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>user edit</h1>

            <ol class="breadcrumb">
                <li> <a href="#"><i class="fa fa-dashboard"></i>dashboard</a></li>
                <li> <a href="#"><i class="fa fa-users"></i>user</a></li>
                <li class="active"><i class="fa fa-edit"></i>edit</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title"> @lang('site.edit')</h1>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    {{-- @include('admins.partials._errors') --}}
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin: 0 !important;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>code</label>
                                <input type="text" class="form-control  @error('code') is-invalid @enderror" name="code"
                                    placeholder="code" value="{{ $promo_code->code }}" required autocomplete="off">
                                @error('code')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>percentage</label>
                                <input type="text" class="form-control  @error('percentage') is-invalid @enderror" name="percentage"
                                placeholder="percentage" value="{{$promo_code->percentage }}" required autocomplete="off">
                                @error('percentage')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>expiration</label>
                                <input type="date" placeholder="expire date" class="form-control  @error('expiration') is-invalid @enderror" name="expiration" value="{{date("Y-m-d", strtotime($promo_code->expiration))}}">
                                @error('expiration')
                                    <small class=" text text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </small>
                                @enderror
                            </div>
                        </div>

                        {{-- status --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="checkbox" id="switcherySize" value="1" class="switchery" name="status" data-size="lg" @if ($promo_code->status == 1) checked @endif/>
                                <label for="switcherySize" class="font-medium-2 text-bold-600 ml-1">status</label>
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
