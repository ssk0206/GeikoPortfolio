<div style="margin:10px;">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- フラッシュメッセージの表示 --}}
    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
</div>