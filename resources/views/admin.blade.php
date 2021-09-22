<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage - Start Bootstrap Template</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="#!">管理画面</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:document.logout_link.submit()">ログアウト</a>
                            </li>
                            <form name="logout_link" action="{{ route('logout') }}" method="post" style="display: none">
                                @csrf
                            </form>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">ユーザ登録</a>
                            </li>
                        @endauth

                    </ul>
                </div>
            </div>
        </nav>
        <!-- Section-->
        <section>
        販売ファイル一覧
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (count($errors) > 0)
                        <div>
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <div class="alert alert-danger text-center" role="alert">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- post先はname指定。resourceで自動生成される。php artisan route:listで確認 --}}
                    <form method="POST" action="{{route('upload.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="title" value="{{old('title')}}" placeholder="タイトル">
                        <input type="text" name="detail" value="{{old('detail')}}" placeholder="詳細">
                        <input type="file" name="file">
                        <input type="checkbox" name="free_flag">無料
                        <input type="submit" value="アップロード">
                    </form>

                    <table class="table-auto">
                        <thead>
                        <tr>
                            <th class="px-4 py-2">タイトル</th>
                            <th class="px-4 py-2">詳細</th>
                            <th class="px-4 py-2">ファイル名</th>
                            <th class="px-4 py-2">作成日</th>
                            <th class="px-4 py-2">無料</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($uploads as $upload)
                            <tr>
                                <td class="border px-4 py-2">{{$upload->title}}</td>
                                <td class="border px-4 py-2">{{$upload->detail}}</td>
                                <td class="border px-4 py-2">{{$upload->file_name}}</td>
                                <td class="border px-4 py-2">{{$upload->created_at}}</td>
                                <td class="border px-4 py-2">
                                    @if($upload->free_flag) 
                                        無料
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            販売履歴
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-auto">
                        <thead>
                        <tr>
                            <th class="px-4 py-2">購入日時</th>
                            <th class="px-4 py-2">購入者</th>
                            <th class="px-4 py-2">タイトル</th>
                            <th class="px-4 py-2">売上(利益)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td class="border px-4 py-2">{{$purchase->created_at}}</td>
                                <td class="border px-4 py-2">{{$purchase->user->name}}</td>
                                <td class="border px-4 py-2">{{$purchase->upload->title}}</td>
                                <td class="border px-4 py-2">96円</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <b>ユーザ一覧</b>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-auto">
                        <thead>
                        <tr>
                            <th class="px-4 py-2">名前</th>
                            <th class="px-4 py-2">メールアドレス</th>
                            <th class="px-4 py-2">登録日</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{$user->name}}</td>
                                <td class="border px-4 py-2">{{$user->email}}</td>
                                <td class="border px-4 py-2">{{$user->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2021</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
