# test_contact-form

## セットアップ手順

### 1.クローンとビルド

任意のディレクトリでリポジトリをクローンし、プロジェクトディレクトリに移動後、コンテナを起動します。

# 1. リポジトリをクローン、ディレクトリ名をプロジェクト名に変更

```bash
git clone git@github.com:gomashio-no-omusubi/test_contact-form.git
```

# 2. 作成したディレクトリに移動

cd test_contact-form

# 3. コンテナのビルドと起動

docker-compose up -d --build

````

※ コンテナが完全に起動するまで、スペックにより数十秒〜数分かかる場合があります。

### 2. Laravel環境構築

コンテナ内で以下のコマンドを実行し、アプリをセットアップします。

```bash
# 1. コンテナに入る
docker-compose exec php bash

# 2. 依存パッケージのインストール
composer install

# 3. 設定ファイルの準備
# ※ .env のDB接続情報は、ご自身の環境および docker-compose.yml の設定に合わせて適宜書き換えてください。
cp .env.example .env

# 4. アプリケーションキーの生成
php artisan key:generate

# 5. データベースの構築と初期データ投入
# 【注意】DBコンテナが完全に起動したのを確認してから実行してください。
php artisan migrate
php artisan db:seed
````

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
