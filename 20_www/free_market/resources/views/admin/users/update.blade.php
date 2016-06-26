@extends('layouts.admin_layout')

@section('title')
    会員編集
    @stop

    @section('content')

            <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>会員編集</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="">Home</a></li>
            <li><a href="/admin/users">会員一覧</a></li>
            <li>会員編集</li>
        </ol>
    </section>

    <!-- メインコンテンツ -->
    <section class="content">

        <!-- コンテンツ1 -->
        <div class="row">

            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">会員を編集します</h3>
                    </div>

                    
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="POST" action="/admin/users/{{$user->id}}">
                        <div class="box-body">
                            
                            @if (Session::has('message'))

                                @if (Session::get('message') == 'error')
                                    <div class="alert alert-info">入力内容に不備があります。</div>
                                @endif

                            @endif
                            
                            @if (count($errors) > 0)
                            <div class="callout callout-warning lead">
                                <h4>エラーが発生しました!</h4>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                            
                            <h4>名前</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="name" class="form-control input-lg" value="{{$user->name}} "
                                       placeholder="名前太郎">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>カナ</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="kana" class="form-control input-lg" value="{{$user->kana}}"
                                       placeholder="ナマエタロウ">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>メールアドレス</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control input-lg" value="{{$user->email}}"
                                       placeholder="xxxxx@xxxxxx.xxx">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="box-footer">
                            <button type="submit" class="btn btn-block btn-success btn-lg">編集</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

            </div>

        </div><!-- end row -->

    </section>


@endsection


@section('style')

@endsection

@section('script')

    <script>

    </script>

@endsection
