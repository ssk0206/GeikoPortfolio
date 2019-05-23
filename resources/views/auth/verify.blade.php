@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">登録したメールを確認してください。</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{-- {{ __('A fresh verification link has been sent to your email address.') }} --}}
                            登録したメールアドレスに確認メールを送信しました。
                        </div>
                    @endif

                    続行する前に、電子メールで確認リンクを確認してください。メールが届かない場合は、 <a href="{{ route('verification.resend') }}">ここをクリックして別のメールをリクエストしてください</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
