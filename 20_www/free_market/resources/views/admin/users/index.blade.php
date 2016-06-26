@extends('layouts.admin_layout')

@section('title')
    会員画面 一覧
    @stop

    @section('content')

            <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>会員一覧</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="">Home</a></li>
            <li>会員一覧</li>
        </ol>
    </section>

    <!-- メインコンテンツ -->
    <section class="content">

        <!-- コンテンツ1 -->
        <div class="row">
            
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">会員を一覧表示します</h3>
                    </div>

                                <!-- /.box-header -->
                        <div class="box-body">
                            

                            
                            @if (Session::has('message'))

                                    @if (Session::get('message') == 'register')
                                    <div class="alert alert-info">登録が完了しました。</div>
                                    @endif

                                    @if (Session::get('message') == 'update')
                                    <div class="alert alert-info">編集が完了しました。</div>
                                    @endif

                                    @if (Session::get('message') == 'delete')
                                    <div class="alert alert-info">削除が完了しました。</div>
                                    @endif

                            @endif


                            {{--<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">--}}
                            {{--<div class="row">--}}
                            {{--<div class="col-sm-12">--}}
                            <table id="example1" class="table table-bordered table-striped dataTable"
                                   role="grid" aria-describedby="example1_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending"
                                        style="width: 20px;">ID
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Browser: activate to sort column ascending"
                                        style="width: 202px;">名前
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 178px;">メールアドレス
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 138px;">登録日時
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 138px;">更新日時
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 20px;">
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 20px;">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--*/ $even = true /*--}}
                                @foreach($users as $user)
                                    @if ($even)
                                        <tr role="row" class="even">
                                    @else
                                        <tr role="row" class="odd">
                                            @endif
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->created_at}}</td>
                                            <td>{{$user->updated_at}}</td>
                                            <td class="text-center">
                                                <a href="/admin/users/{{$user->id}}/edit/"
                                                   class="btn btn-primary btn-sm">編集</a>
                                            </td>
                                            <td class="text-center">
                                                <form method="POST" action="/admin/users/{{$user->id}}">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <input type="submit" value="削除"
                                                           class="btn btn-danger btn-sm btn-destroy"
                                                           onclick='return confirm("ID:{{$user->id}}を削除してよろしいですか？");'>
                                                </form>
                                            </td>
                                        </tr>
                                        {{--*/ $even = !$even /*--}}
                                        @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">ID</th>
                                    <th rowspan="1" colspan="1">名前</th>
                                    <th rowspan="1" colspan="1">メールアドレス</th>
                                    <th rowspan="1" colspan="1">登録日時</th>
                                    <th rowspan="1" colspan="1">更新日時</th>
                                    <th rowspan="1" colspan="1"></th>
                                    <th rowspan="1" colspan="1"></th>
                                </tr>
                                </tfoot>
                            </table>
                            {{--</div>--}}
                            {{--</div>--}}

                            {{--</div>--}}
                        </div>
                        <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>


        </div><!-- end row -->

    </section>


    @endsection


    @section('style')

            <!-- DataTables -->
    <link rel="stylesheet" href={{asset("plugins/datatables/dataTables.bootstrap.css")}}>

    @endsection

    @section('script')

            <!-- DataTables -->
    <script src={{asset("plugins/datatables/jquery.dataTables.min.js")}}></script>
    <script src={{asset("plugins/datatables/dataTables.bootstrap.min.js")}}></script>
    <!-- SlimScroll -->
    <script src={{asset("plugins/slimScroll/jquery.slimscroll.min.js")}}></script>
    <!-- FastClick -->
    <script src={{asset("plugins/fastclick/fastclick.js")}}></script>

    <script>
        $(function () {
            $("#example1").DataTable();
//            $('#example1').DataTable({
//                "paging": true,
//                "lengthChange": true,
//                "searching": true,
//                "ordering": true,
//                "info": false,
//                "autoWidth": true
//            });
        });
    </script>

@endsection
