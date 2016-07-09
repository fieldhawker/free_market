@extends('layouts.admin_layout')

@section('title')
    商品編集
    @stop

    @section('content')

            <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>商品編集</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="/admin/">Home</a></li>
            <li><a href="/admin/items">商品一覧</a></li>
            <li>商品編集</li>
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
                        <h3 class="box-title">商品を編集します</h3>
                    </div>


                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="POST" action="/admin/items/{{$item->id}}">
                        <div class="box-body">

                            @if ($is_exclusives)
                                <div class="alert alert-info">別の管理者が編集中です。</div>
                            @endif

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
                                <input type="text" name="name" class="form-control input-lg" placeholder="商品の名前"
                                       value="{{ old('name', $item->name) }}">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>価格</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" name="price" class="form-control input-lg" placeholder="100"
                                       value="{{ old('price', $item->price) }}">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>説明</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="caption" class="form-control input-lg" placeholder="商品の説明"
                                       value="{{ old('caption', $item->caption) }}">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>状態</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>

                                <select class="form-control" name="status">

                                    @foreach (Config::get('const.items.status') as $key => $text)
                                        <option value="{{$key}}"
                                                @if ( old('status', $item->status) == $key) selected="selected" @endif >{{Config::get('const.items.status.'.$key)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <h4>商品の状態</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>

                                <select class="form-control" name="items_status">

                                    @foreach (Config::get('const.items.items_status') as $key => $text)
                                        <option value="{{$key}}"
                                                @if ( old('items_status', $item->items_status) == $key) selected="selected" @endif >{{Config::get('const.items.items_status.'.$key)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <h4>開始日時</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="started_at" class="form-control input-lg"
                                       placeholder="2016-01-01 01:02:03"
                                       value="{{ old('started_at', $item->started_at) }}">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>終了日時</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="ended_at" class="form-control input-lg"
                                       placeholder="2035-01-01 01:02:03"
                                       value="{{ old('ended_at', $item->ended_at) }}">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                            <h4>配送料</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>

                                <select class="form-control" name="delivery_charge">

                                    @foreach (Config::get('const.items.delivery_charge') as $key => $text)
                                        <option value="{{$key}}"
                                                @if ( old('delivery_charge', $item->delivery_charge) == $key) selected="selected" @endif >{{Config::get('const.items.delivery_charge.'.$key)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <h4>発送方法</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>

                                <select class="form-control" name="delivery_plan">

                                    @foreach (Config::get('const.items.delivery_plan') as $key => $text)
                                        <option value="{{$key}}"
                                                @if ( old('delivery_plan', $item->delivery_plan) == $key) selected="selected" @endif >{{Config::get('const.items.delivery_plan.'.$key)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <h4>発送元の都道府県</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>

                                <select class="form-control" name="pref">

                                    @foreach (Config::get('const.pref') as $key => $text)
                                        <option value="{{$key}}"
                                                @if ( old('pref', $item->pref) == $key) selected="selected" @endif >{{Config::get('const.pref.'.$key)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <h4>発送日数</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>

                                <select class="form-control" name="delivery_date">

                                    @foreach (Config::get('const.items.delivery_date') as $key => $text)
                                        <option value="{{$key}}"
                                                @if ( old('delivery_date', $item->delivery_date) == $key) selected="selected" @endif >{{Config::get('const.items.delivery_date.'.$key)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <h4>コメント</h4>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" name="comment" class="form-control input-lg" placeholder="コメント"
                                       value="{{ old('comment', $item->comment) }}">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        @if (!$is_exclusives)
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="_method" value="PUT">

                            <div class="box-footer">
                                <a href="/admin/items/">
                                    <button type="button" class="btn btn-default btn-lg">戻る</button>
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">編集</button>
                            </div>
                        @endif
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
