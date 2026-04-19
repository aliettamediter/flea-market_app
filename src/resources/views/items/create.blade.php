@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
    <div class="exhibit">
        <h1 class="exhibit__title">商品の出品</h1>
        <form class="exhibit__form" method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data"
            novalidate>
            @csrf
            <div class="exhibit__section">
                <p class="exhibit__label">商品画像</p>
                <div class="exhibit__image-area" id="image-area">
                    <img class="exhibit__image-preview is-hidden" id="image-preview" src="" alt="選択した画像のプレビュー">
                    <label class="exhibit__image-btn" for="image">画像を選択する</label>
                    <input class="exhibit__file-input" type="file" name="image" id="image" accept="image/*">
                </div>
                <p class="exhibit__error">@error('image'){{ $message }}@enderror</p>
            </div>
            <div class="exhibit__section">
                <h2 class="exhibit__section-title">商品の詳細</h2>
                <div class="exhibit__group">
                    <label class="exhibit__label">カテゴリー</label>
                    <div class="exhibit__categories">
                        @foreach($categories as $category)
                            <label class="exhibit__category-label">
                                <input class="exhibit__category-input" type="checkbox" name="category_id[]"value="{{ $category->id }}"{{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                                <span class="exhibit__category-tag">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="exhibit__error">@error('category_id'){{ $message }}@enderror</p>
                </div>
                <div class="exhibit__group">
                    <label class="exhibit__label">商品の状態</label>
                    <div class="exhibit__select-wrap">
                        <select class="exhibit__select" name="condition_id">
                            <option value="">選択してください</option>
                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="exhibit__error">@error('condition_id'){{ $message }}@enderror</p>
                </div>
            </div>
            <div class="exhibit__section">
                <h2 class="exhibit__section-title">商品名と説明</h2>
                <div class="exhibit__group">
                    <label class="exhibit__label">商品名</label>
                    <input class="exhibit__input" type="text" name="name" value="{{ old('name') }}">
                    <p class="exhibit__error">@error('name'){{ $message }}@enderror</p>
                </div>
                <div class="exhibit__group">
                    <label class="exhibit__label">ブランド名</label>
                    <input class="exhibit__input" type="text" name="brand" value="{{ old('brand') }}">
                </div>
                <div class="exhibit__group">
                    <label class="exhibit__label">商品の説明</label>
                    <textarea class="exhibit__textarea" name="description">{{ old('description') }}</textarea>
                    <p class="exhibit__error">@error('description'){{ $message }}@enderror</p>
                </div>
                <div class="exhibit__group">
                    <label class="exhibit__label">販売価格</label>
                    <div class="exhibit__price-wrap">
                        <span class="exhibit__price-symbol">¥</span>
                        <input class="exhibit__price-input" type="number" name="price" value="{{ old('price') }}">
                    </div>
                    <p class="exhibit__error">@error('price'){{ $message }}@enderror</p>
                </div>
            </div>
            <button class="exhibit__btn" type="submit">出品する</button>
        </form>
    </div>
@endsection

@section('js')
    <script>
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('image-preview');
        const imageArea = document.getElementById('image-area');
        if (imageInput && previewImage && imageArea) {
            const resetPreview = () => {
                previewImage.src = '';
                previewImage.classList.add('is-hidden');
                imageArea.classList.remove('exhibit__image-area--preview');
            };
            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) {
                    resetPreview();
                    return;
                }
                if (!file.type.startsWith('image/')) {
                    resetPreview();
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (loadEvent) {
                    previewImage.src = loadEvent.target.result;
                    previewImage.classList.remove('is-hidden');
                    imageArea.classList.add('exhibit__image-area--preview');
                };
                reader.readAsDataURL(file);
            });
        }

        document.querySelectorAll('.exhibit__category-input').forEach(function (input) {
            input.addEventListener('change', function () {
                this.nextElementSibling.classList.toggle('exhibit__category-tag--active', this.checked);
            });
            document.querySelectorAll('.exhibit__category-input').forEach(function (input) {
                if (input.checked) {
                    input.nextElementSibling.classList.add('exhibit__category-tag--active');
                }
            });
        })
    </script>
@endsection
