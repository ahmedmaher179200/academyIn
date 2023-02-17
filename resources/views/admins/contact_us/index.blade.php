@extends('layouts.admin')

@section('title', 'contact us')


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>{{ trans('admin.contact us') }}</h1>

            <ol class="breadcrumb">
                <li> <a href="{{url('admins/contact_us')}}"><i class="fa fa-dashboard"></i>{{ trans('admin.dashboard') }}</a>
                </li>
                <li class="active"><i class="fa fa-users"></i>{{ trans('admin.contact_us') }}</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <form action="#" method="get">

                        <div class="row">
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    <table class="table table-hover" id="myTable">

                        <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('admin.email') }}</th>
                                    <th>{{ trans('admin.title') }}</th>
                                    <th>{{ trans('admin.content') }}</th>
                                    <th>{{ trans('admin.action') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($contactUsMessages as $contactUsMessage)
                                    <tr>
                                        <td>{{$contactUsMessage->id}}</td>
                                        <td>{{$contactUsMessage->title}}</td>
                                        <td>{{$contactUsMessage->content}}</td>
                                        <td>{{$contactUsMessage->message}}</td>
                                        <td>
                                            <a href="{{url('/admins/contact_us/delete/' . $contactUsMessage->id)}}" class="btn btn-danger btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{ trans('admin.delete') }}</a>
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
