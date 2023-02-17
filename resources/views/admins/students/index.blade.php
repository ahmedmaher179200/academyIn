@extends('layouts.admin')

@section('title', 'students')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.students') }} </h1>

            <ol class="breadcrumb">
                <li> <a href="{{url('admins/students')}}"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a>
                </li>
                <li class="active"><i class="fa fa-users"></i>{{ trans('admin.students') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <form action="#" method="get">

                        <div class="row">

                             

                            <div class="col-md-4">
                                 
                                {{-- @if (auth('admin')->user()->isAbleTo('create-students'))
                                    <a href="{{url('admins/students/create')}}"
                                    class="btn btn-primary"><i class="fa fa-plus"></i>add
                                    </a>
                                @else
                                    <button class="btn btn-primary"disabled><i class="fa fa-plus"></i>Add </button>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    <table class="table table-hover" id="myTable">

                        <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('admin.student') }}</th>
                                    <th>{{ trans('admin.phone') }}</th>
                                    <th>{{ trans('admin.country') }}</th>
                                    <th>{{ trans('admin.gender') }}</th>
                                    <th>{{ trans('admin.balance') }}</th>
                                    <th>{{ trans('admin.status') }}</th>
                                    <th>{{ trans('admin.action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{$student->id}}</td>
                                        <td>{{$student->username}}</td>
                                        <td>{{$student->dialing_code}} => {{$student->phone}}</td>
                                        <td>{{$student->Country->translate(LaravelLocalization::getCurrentLocale())->name}}</td>
                                        <td>{{$student->getGender()}}</td>
                                        <td>{{$student->balance}}</td>
                                        <td>{{$student->getStatus()}}</td>
                                        <td>
                                            {{-- block --}}
                                            @if (auth('admin')->user()->isAbleTo('delete-students'))
                                                <a href="{{url('admins/students/block/' . $student->id)}}" tyle="color:#fff!important;" rel="tooltip" title="" @if ($student->status == 1) class="btn  btn-danger  btn-sm" @else class="btn  btn-success  btn-sm" @endif >
                                                    <i class="fa fa-1x fa-trash">{{$student->changeStatus()}}</i>
                                                </a>
                                            @else
                                                <button class="btn btn-danger btn-sm"type="submit" value="" disabled>
                                                    <i class="fa fa-1x fa-trash">{{$student->changeStatus()}}</i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody>

                        </table> {{-- end of table --}}

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
