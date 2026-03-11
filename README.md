# coachtechフリマ(フリマアプリ)

## セットアップ手順

### 1.クローンとビルド

任意のディレクトリでリポジトリをクローンし、プロジェクトディレクトリに移動後、コンテナを起動します。

#### 1. リポジトリをクローン、ディレクトリ名をプロジェクト名に変更

```bash
git clone git@github.com:gomashio-no-omusubi/flea-market-app
```

#### 2. 作成したディレクトリに移動

```bash
cd flea-market-app
```

#### 3. DockerDesktopアプリを立ち上げコンテナのビルドと起動

```bash
docker-compose up -d --build
```

##### ※ コンテナが完全に起動するまで、スペックにより数十秒〜数分かかる場合があります。

### 2. Laravel環境構築

コンテナ内で以下のコマンドを実行し、アプリをセットアップします。

#### 1. コンテナに入る

```bash
docker-compose exec php bash
```

#### 2. 依存パッケージのインストール

```bash
composer install
```

#### 3. 設定ファイルの準備

```bash
cp .env.example .env
```

#### 4. .envファイルの11行目以降を以下のように修正

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

#### 5. アプリケーションキーの生成

```bash
php artisan key:generate
```

#### 6. マイグレーションの実行

```bash
php artisan migrate
```

#### 7.　シーディングの実行

```bash
php artisan db:seed
```

## 開発環境

### アクセスURL

- **お問い合わせ画面** : http://localhost/
- **ユーザー登録画面** : http://localhost/register
- **phpMyAdmin** : http://localhost:8080/

## 使用技術(実行環境)

- **PHP** : 8.1.34
- **Laravel** : 8.83.8
- **jquery** :
- **MySQL** : 8.0.26
- **nginx** : 1.21.1

## ER図

![ER図](./diagram.png)
