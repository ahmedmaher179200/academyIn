@extends('layouts.admin')

@section('title', 'materials')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>material</h1>

            <ol class="breadcrumb">
                <li> <a href="{{url('admins')}}"><i class="fa fa-dashboard"></i>dashboard</a>
                </li>
                <li> <a href="{{url('admins/curriculums')}}"><i class="fa fa-dashboard"></i>curriculum</a>
                </li>
                <li> <a href="{{url('admins/levels?curriculum='.$curriculum_id)}}"><i class="fa fa-dashboard"></i>levels</a>
                </li>
                <li> <a href="{{url('admins/years?curriculum='.$curriculum_id . '&&level=' . $level_id )}}"><i class="fa fa-dashboard"></i>years</a>
                </li>
                <li> <a href="{{url('admins/terms?curriculum='.$curriculum_id . '&&level=' . $level_id . '&&year=' . $year_id . '&&term=' . $term_id )}}"><i class="fa fa-dashboard"></i>terms</a>
                </li>
                <li> <a href="{{url('admins/subjects?curriculum='.$curriculum_id . '&&level=' . $level_id . '&&year=' . $year_id . '&&term=' . $term_id . '&&subject=' .  $subject_id)}}"><i class="fa fa-dashboard"></i>subjects</a>
                </li>
                <li class="active"><i class="fa fa-users"></i>materials</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <form action="#" method="get">

                        <div class="row">

                             

                            <div class="col-md-4">
                                 
                                @if (auth('admin')->user()->isAbleTo('create-curriculums'))
                                    <a href="{{url('admins/materials/create?' . $parms)}}"
                                    class="btn btn-primary"><i class="fa fa-plus"></i>add
                                    </a>
                                @else
                                    <button class="btn btn-primary"disabled><i class="fa fa-plus"></i>Add </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    <table class="table table-hover" id="myTable">

                        <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>name</th>
                                    <th>description</th>
                                    <th>status</th>
                                    <th>action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($materials as $material)
                                    <tr>
                                        <td>{{$material->id}}</td>
                                        <td>{{$material->translate(LaravelLocalization::getCurrentLocale())->name}}</td>
                                        <td>
                                            {{$material->Subject->Term->Year->Level->Curriculum->Country->translate(LaravelLocalization::getCurrentLocale())->name}} ->
                                            {{$material->Subject->Term->Year->Level->Curriculum->translate(LaravelLocalization::getCurrentLocale())->name}} ->
                                            {{$material->Subject->Term->Year->Level->translate(LaravelLocalization::getCurrentLocale())->name}} ->
                                            {{$material->Subject->Term->Year->translate(LaravelLocalization::getCurrentLocale())->name}} ->
                                            {{$material->Subject->Term->translate(LaravelLocalization::getCurrentLocale())->name}} ->
                                            {{$material->subject->Main_subject->translate(LaravelLocalization::getCurrentLocale())->name}}
                                        </td>
                                        <td>{{$material->getStatus()}}</td>
                                        <td>
                                            {{-- edit --}}
                                            @if (auth('admin')->user()->isAbleTo('update-curriculums'))
                                                <a href="{{url('admins/materials/edit/' . $material->id . '?' . $parms)}}" style="color: #fff;
                                                    background-color: #17a2b8;
                                                    border-color: #17a2b8;" rel="tooltip" title="" class="btn btn-info btn-sm "
                                                        data-original-title="edit">
                                                        <i class="fa fa-edit">edit</i>
                                                </a>
                                            @else
                                                <button class="btn btn-info btn-sm"type="submit" value="" disabled>
                                                    <i class="fa fa-edit">edit</i>
                                                </button>
                                            @endif

                                            {{-- delete --}}
                                            @if (auth('admin')->user()->isAbleTo('delete-curriculums'))
                                                <a href="{{url('admins/materials/delete/' . $material->id . '?' . $parms)}}" tyle="color:#fff!important;" rel="tooltip" title="" class="btn btn-danger  btn-sm">
                                                    <i class="fa fa-1x fa-trash">delete</i>
                                                </a> 
                                            @else
                                                <button class="btn btn-danger btn-sm"type="submit" value="" disabled>
                                                    <i class="fa fa-trash">delete</i>
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
