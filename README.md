# お問い合わせフォーム

## 環境構築

### 前提条件

以下のソフトウェアがインストールされている必要があります：

- Docker
- Docker Compose
- Git

### 1. リポジトリのクローン

```bash
git clone https://github.com/komody/contact-form
cd contact-form
```

### 2. Dockerコンテナのビルドと起動

```bash
docker compose up -d --build
```

### 3. 依存関係のインストール

```bash
docker compose exec php composer install
```

### 4. 環境変数ファイルの作成

`src`ディレクトリに`.env`ファイルを作成し、以下の内容を設定してください：

```bash
cd src
touch .env
```

`.env`ファイルに以下の内容を記述してください：

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 5. アプリケーションキーの生成

```bash
docker compose exec php php artisan key:generate
```

### 6. データベースマイグレーションの実行

```bash
docker compose exec php php artisan migrate
```

### 7. データベースシーディングの実行

```bash
docker compose exec php php artisan db:seed
```

これにより、カテゴリーデータとお問い合わせのダミーデータ（35件）が作成されます。

## 使用技術(実行環境)

- **Laravel**: 8.75
- **PHP**: 7.3|8.0
- **MySQL**: 8.0.26
- **Nginx**: 1.21.1
- **Docker**: Docker Compose

## ER図

![ER図](./er-diagram.png)

## URL

- **開発環境**: http://localhost/
- **管理画面**: http://localhost/admin
- **phpMyAdmin**: http://localhost:8080/
