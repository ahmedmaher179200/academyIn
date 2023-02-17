@extends('layouts.admin')

@section('title', 'classes')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>classes</h1>

            <ol class="breadcrumb">
                <li> <a href="{{url('admins/classes')}}"><i class="fa fa-dashboard"></i>dashboard</a>
                </li>
                <li class="active"><i class="fa fa-users"></i>classes</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <form action="#" method="get">

                        <div class="row">

                             

                            <div class="col-md-4">
                                 
                                {{-- @if (auth('admin')->user()->isAbleTo('create-questions'))
                                    <a href="{{url('admins/classes/create')}}"
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
                                    <th>teacher</th>
                                    <th>subject</th>
                                    <th>long</th>
                                    <th>student count</th>
                                    <th>start at</th>
                                    <th>action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($classes as $class)
                                    <tr>
                                        <td>{{$class->id}}</td>
                                        <td>{{$class->Teacher->username}}</td>
                                        <td>{{$class->Subject->Main_subject->translate(LaravelLocalization::getCurrentLocale())->name}}</td>
                                        <td>{{$class->Class_type->long}}</td>
                                        <td>{{count($class->Student_classes)}}</td>
                                        <td>{{$class->from}}</td>
                                        <td>
                                            @if ($class->join() == True)
                                                <a href="{{url('admins/agora/join/'. $class->id)}}"  target="_blank" style="color: #fff;
                                                    background-color: #17a2b8;
                                                    border-color: #17a2b8;" rel="tooltip" title="" class="btn btn-info btn-sm "
                                                        data-original-title="edit">
                                                        {{ trans('admin.join') }}</i>
                                                </a>
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
