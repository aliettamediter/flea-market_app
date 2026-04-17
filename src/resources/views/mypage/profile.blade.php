@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')
    <div class="profile-edit">
        <h1 class="profile-edit__title">プロフィール設定</h1>
        <form class="profile-edit__form" method="POST" action="{{ route('mypage.profile.update') }}"
            enctype="multipart/form-data" novalidate>
            @csrf
            @method('PATCH')
            <div class="profile-edit__image-group">
                @if($profile && $profile->profile_image)
                    <img class="profile-edit__image" id="profile-preview" src="{{ asset('storage/' . $profile->profile_image) }}"
                        alt="プロフィール画像">
                @else
                    <div class="profile-edit__image-placeholder" id="profile-placeholder"></div>
                    <img class="profile-edit__image" id="profile-preview" src="" style="display:none;">
                @endif
                <label class="profile-edit__image-btn" for="profile_image">画像を選択する</label>
                <input class="profile-edit__file-input" type="file" name="profile_image" id="profile_image"
                    accept="image/*">
            </div>
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="name">ユーザー名</label>
                <input class="profile-edit__input" type="text" name="name" id="name" value="{{ old('name', $profile->name ?? '') }}">
                <p class="profile-edit__error">@error('name'){{ $message }}@enderror</p>
            </div>
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="postal_code">郵便番号</label>
                <input class="profile-edit__input" type="text" name="postal_code" id="postal_code"
                    value="{{ old('postal_code', $profile->postal_code ?? '') }}" placeholder="123-4567">
                <p class="profile-edit__error">@error('postal_code'){{ $message }}@enderror</p>
            </div>
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="address">住所</label>
                <input class="profile-edit__input" type="text" name="address" id="address"
                    value="{{ old('address', $profile->address ?? '') }}">
                <p class="profile-edit__error">@error('address'){{ $message }}@enderror</p>
            </div>
            <div class="profile-edit__group">
                <label class="profile-edit__label" for="building">建物名</label>
                <input class="profile-edit__input" type="text" name="building" id="building"
                    value="{{ old('building', $profile->building ?? '') }}">
            </div>
            <button class="profile-edit__btn" type="submit">更新する</button>
        </form>
    </div>
@endsection

@section('js')
    @section('js')
        <script>
            const profileImageInput = document.getElementById('profile_image');
            const previewImage = document.getElementById('profile-preview');
            const placeholder = document.getElementById('profile-placeholder');
            if (profileImageInput && previewImage) {
                profileImageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (!file || !file.type.startsWith('image/')) {
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function (loadEvent) {
                        previewImage.src = loadEvent.target.result;
                        previewImage.style.display = 'block';
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        </script>
    @endsection