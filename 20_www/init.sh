#!/bin/sh

echo "開発環境の初期設定を開始しますか？ [Y/n]"
read ANSWER

case $ANSWER in

    "Y" | "y" | "yes" | "Yes" | "YES" ) 
        echo "YES!!";
        
        # vender系のインストール
        cd /usr/share/nginx/html/free_market
        composer install

        # envファイル作成
        cd /usr/share/nginx/html/free_market
        cp -p .env.example .env
        php artisan key:generate

        # マイグレーション
        cd /usr/share/nginx/html/free_market
        php artisan migrate:refresh --seed
        
        # autoloadを更新
        composer dump-autoload

        # シンボリックリンクの生成
        cd /usr/share/nginx/html/free_market/public
        ln -s ../vendor/almasaeed2010/adminlte/dist/ dist
        ln -s ../vendor/almasaeed2010/adminlte/bootstrap/ bootstrap
        ln -s ../vendor/almasaeed2010/adminlte/plugins/ plugins
        ln -s /usr/share/phpMyAdmin/ phpmyadmin

        ;;

    * ) 
        echo "開発環境の初期設定を開始しませんでした";
        ;;

esac


