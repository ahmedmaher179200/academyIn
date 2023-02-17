@extends('layouts.admin')

@section('title', 'answers')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.answers') }}</h1>

            <ol class="breadcrumb">
                <li> <a href="{{url('admins')}}"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a>
                </li>
                <li> <a href="{{url('admins/questions')}}"><i class="fa fa-dashboard"></i>{{ trans('admin.questions') }}</a>
                </li>
                <li class="active"><i class="fa fa-users"></i>{{ trans('admin.answers') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <form action="#" method="get">

                        <div class="row">

                             

                            <div class="col-md-4">
                                 
                                {{-- @if (auth('admin')->user()->isAbleTo('create-questions'))
                                    <a href="{{url('admins/answers/create')}}"
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
                                    <th>{{ trans('admin.student') }} </th>
                                    <th>{{ trans('admin.type') }} </th>
                                    <th>{{ trans('admin.answer') }} </th>
                                    <th>{{ trans('admin.action') }} </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($answers as $answer)
                                    <tr>
                                        <td>{{$answer->id}}</td>
                                        <td>{{$answer->getUser()->username}}</td>
                                        <td>{{$answer->getType()}}</td>
                                        <td>{{$answer->answer}}</td>
                                        <td>
                                            {{-- delete --}}
                                            @if (auth('admin')->user()->isAbleTo('delete-questions'))
                                                <a href="{{url('admins/answers/delete/' . $answer->id)}}" tyle="color:#fff!important;" rel="tooltip" title="" class="btn btn-danger  btn-sm">
                                                    <i class="fa fa-1x fa-trash">{{ trans('admin.delete') }}</i>
                                                </a> 
                                            @else
                                                <button class="btn btn-danger btn-sm"type="submit" value="" disabled>
                                                    <i class="fa fa-trash">{{ trans('admin.delete') }}</i>
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
