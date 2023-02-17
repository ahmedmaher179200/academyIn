@extends('layouts.admin')

@section('title', 'questions')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.questions') }}</h1>

            <ol class="breadcrumb">
                <li> <a href="{{url('admins/questions')}}"><i class="fa fa-dashboard"></i>{{ trans('admin.') }}dashboard</a>
                </li>
                <li class="active"><i class="fa fa-users"></i>{{ trans('admin.questions') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <form action="#" method="get">

                        <div class="row">

                             

                            <div class="col-md-4">
                                 
                                {{-- @if (auth('admin')->user()->isAbleTo('create-questions'))
                                    <a href="{{url('admins/questions/create')}}"
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
                                    <th>{{ trans('admin.subject') }}</th>
                                    <th>{{ trans('admin.question') }}</th>
                                    <th>{{ trans('admin.answer count') }}</th>
                                    <th>{{ trans('admin.action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($questions as $question)
                                    <tr>
                                        <td>{{$question->id}}</td>
                                        <td>{{$question->Student->username}}</td>
                                        <td>{{$question->subject->Main_subject->translate(LaravelLocalization::getCurrentLocale())->name}}</td>
                                        <td>{{$question->question}}</td>
                                        <td><a href="answers?question={{$question->id}}">{{count($question->Answers)}}</a></td>
                                        <td>
                                            {{-- delete --}}
                                            @if (auth('admin')->user()->isAbleTo('delete-questions'))
                                                <a href="{{url('admins/questions/delete/' . $question->id)}}" tyle="color:#fff!important;" rel="tooltip" title="" class="btn btn-danger  btn-sm">
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
