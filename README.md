# coachtech フリマ

## 概要

ある企業が開発した独自のフリマアプリ

## 環境構築

**Docker ビルド**

1. `git clone git@github.com:kvujic/flea-market-app.git`
2. `cd flea-market-app`
3. DockerDesktop アプリを立ち上げる
4. `docker-compose up -d --build`

> ※ Apple Silicon (M1/M2) を使用している場合、docker-compose.yml の mysql, phpMyAdmin, MailHog などで`platform: linux/amd64` の設定が必要になることがあります。

```bash
mysql:
    platform: linux/amd64
    image: mysql:8.0.26
    environment:
```

**Laravel 環境構築**

1. PHP コンテナに入る

```bash
docker-compose exec php bash
```

2. パッケージをインストール

```bash
composer install
```

3. 「.env.example」ファイルを「.env」ファイルに命名を変更。または、新しく.env ファイルを作成

```bash
cp .env.example .env
```

4. .env に以下の環境変数を設定

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

6. アプリケーションキーの作成

```bash
php artisan key:generate
```

7. 画像表示のためのシンボリックリンクの設定

```bash
php artisan storage:link
```

8. マイグレーションの実行

```bash
php artisan migrate
```

9. シーディングの実行

```bash
php artisan db:seed
```

### MailHog（開発用メール確認）

開発環境では、MailHog を使用してメールの送信内容をブラウザ上で確認できます  
SMTP サーバーとして動作し、実際には送信せず、UI 上で内容をチェックできるツールです

1. .env に以下を追記

```text
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

2. ブラウザでアクセス  
   http://localhost:8025
   > 会員登録後に表示されるページ内の「認証はこちらから」をクリックすると上記 URL にアクセスできます

### Stripe の環境構築

1. Stripe アカウントを作成（必須）

   > 公式サイト：[Stripe](https://dashboard.stripe.com)

2. Stripe PHP SDK のインストール（PHP コンテナ内で実行）

```bash
docker-compose exec php bash
composer require stripe/stripe-php
```

3. ダッシュボードにログイン後、「開発者」→ 「API キー」でテストキーを取得
4. 取得した「公開可能キー」と「シークレットキー」を.env ファイルに設定

```text
STRIPE_KEY=pk_test_***************
STRIPE_SECRET=sk_test_***************
```

5. Stripe CLI のインストール（ホスト PC 上で実行）

```bash
# macOS(Homebrew)
brew install stripe/stripe-cli/stripe
```

> macOS 以外の環境のインストール手順は、下記の公式ページを参照してください  
> 公式サイト：[Stripe](https://docs.stripe.com/stripe-cli)

6. Webhook 起動

```bash
stripe login
stripe listen --forward-to http://localhost/api/stripe/webhook
```

7. コマンドを実行した際に取得できる webhook のシークレットキーを.env ファイルに設定

```text
STRIPE_WEBHOOK_SECRET=whsec_***************
```

> コマンド実行後、購入処理を完了させる際に必要なため、Webhook 起動中は終了しないでください

### 単体テスト環境構築

1. `docker-compose exec php bash`
2. 「.env」ファイルから「.env.testing」を作成

```bash
cp .env .env.testing
```

3. .env.testing の以下の環境変数を変更

```text
APP_ENV=test

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=test_db
DB_USERNAME=root
DB_PASSWORD=root
```

4. テスト用データベースの作成

```bash
docker-compose exec mysql bash
mysql -u root -p
```

> パスワードは docker-compose.yml の MYSQL_ROOT_PASSWORD に設定されているものを入力してください

```sql
CREATE DATABASE test_db;
SHOW DATABASES;
```

5. テストの実行

```bash
docker-compose exec php bash
php artisan test
```

特定のテストクラスだけを実行したい場合：

```bash
php artisan test --filter=テストファイル名
```

> 各テストクラスで use refresh database; を使用しているため、テストごとに自動でマイグレーションが実行されます

## 動作確認時の注意事項

**COACHTECH ロゴ**

- クリックするとトップページへ遷移することができます

**Stripe 決済処理**

- カード決済時のテスト用カード番号
  ```text
  メールアドレス：任意のメールアドレス
  カード番号：4242 4242 4242 4242
  カード有効期限：未来の年月
  セキュリティコード：任意の3桁の数字
  カード保有者の名前：任意の名前
  国または地域：任意の国名を選択
  ```
- コンビニ決済  
  画面遷移後、PC の画面をリロードすることで支払い完了画面が表示されます  
  その後は手動で http://localhost/ への画面遷移が必要です

**商品画像**

- ダミーデータの商品画像が表示されない場合（storage/app/public/images/内に画像がない場合）は以下のコマンドを実行してください
  ```bash
  docker-compose exec php bash
  php artisan images:download
  php artisan migrate:fresh --seed
  ```

## サンプルユーザー

ログイン URL：http://localhost/login/

> 認証済みユーザーです

- 山田太郎（商品１ − ５を出品）  
  Email: taro@example.com  
  Password: password1

- 鈴木花（商品６ −10 を出品）  
  Email: hana@example.com  
  Password: password2

- 野原ひろし（商品 1 と 4 を購入済み）  
  Email: hiroshi@example.com  
  Password: password3

## 使用技術

- PHP(8.4.8)
- Laravel(10.48.29)
- Livewire(3.6.3)
- MySQL(8.0.26)
- Fortify(1.25.4)
- Stripe(17.3)
- MailHog(latest)
- JavaScript(カテゴリ選択・画像プレビュー・検索など一部の画面操作に使用)

## ER 図

![art](src/er.drawio.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin：http://localhost:8080/
- MailHog：http://localhost:8025/
