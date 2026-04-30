# フリマアプリ
フリマアプリの模擬開発案件です。
会員登録、ログイン、商品出品、商品購入、コメント、いいね、メール認証などの機能を実装しています。

## 環境構築
※ Laravel アプリケーション本体は `src` ディレクトリ配下にあります。

1. リポジトリをクローン
```bash
git clone https://github.com/aliettamediter/flea-market_app.git
cd flea-market_app
```

2. 環境変数の設定
```bash
cp src/.env.example src/.env
```

3. Dockerコンテナの起動
```bash
docker-compose up -d
```

4. PHPコンテナに入る
```bash
docker-compose exec php bash
```

5. パッケージのインストール
```
composer install
```

6. アプリケーションキーの生成
```
php artisan key:generate
```

7. マイグレーションとシーダーの実行
```
php artisan migrate --seed
```

8. ストレージのシンボリックリンク作成
```
php artisan storage:link
```

9. Stripeの設定
 1. [Stripe](https://stripe.com/jp) にアカウントを作成してください
 2. ダッシュボードの「開発者」→「APIキー」からテスト用のキーを取得してください
 3. `src/.env` に以下を追加してください：
`.env` に以下を追加してください：
  STRIPE_KEY=取得したPublishableKey
  STRIPE_SECRET=取得したSecretKey
  ※ テストカード番号：`4242 4242 4242 4242`（有効期限・CVCは任意の数字）

## メール認証について

本アプリはメール認証にMailhogを使用しています。

1. `http://localhost:8025` にアクセス
2. 届いた認証メールを開く
3. 認証リンクをクリック
※ Dockerコンテナ起動後にMailhogが自動的に起動します。

## テスト実行

### 1. テスト用データベースの作成
```
docker-compose exec mysql bash
mysql -u root -p
CREATE DATABASE demo_test;
```

### 2. .env.testingの作成
```
cp src/.env .env.testing
```
・２カ所を以下に変更
APP_ENV=test
APP_KEY=
・３カ所を以下に変更
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root

### 3. テストを実行
```
docker-compose exec php bash
php artisan key:generate --env=testing
php artisan config:clear
php artisan migrate --env=testing
```

## 機能一覧

- 会員登録・メール認証(Mail.hog)
- ログイン・ログアウト
- プロフィール設定
- 商品出品・一覧・詳細
- 商品検索
- いいね機能
- コメント機能
- 購入・決済（Stripe）

## 使用技術

| 技術 | バージョン |
|------|-----------|
| PHP | 8.1 |
| Laravel | 8.83 |
| MySQL | 8.0 |
| Nginx | 1.21 |
| Docker | - |
| Stripe | - |
| Mailhog | - |

## テーブル設計

| テーブル | 説明 |
|---------|------|
| users | ユーザー情報 |
| profiles | プロフィール情報 |
| items | 商品情報 |
| conditions | 商品の状態 |
| categories | カテゴリ |
| category_item | 商品とカテゴリの中間テーブル |
| likes | いいね |
| comments | コメント |
| purchases | 購入履歴 |

## ER図

![ER図](docs/erd.png)

## URL

| URL | 説明 |
|-----|------|
| http://localhost | トップページ |
| http://localhost/register | 会員登録 |
| http://localhost/login | ログイン |
| http://localhost/mypage | マイページ |
| http://localhost/sell | 商品出品 |
| http://localhost:8025 | Mailhog |
| http://localhost:8080 | phpMyAdmin |