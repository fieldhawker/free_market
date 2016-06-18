#!/bin/sh

echo "STG環境へのリリースを実行しますか？ [Y/n]"
read ANSWER

case $ANSWER in

    "Y" | "y" | "yes" | "Yes" | "YES" ) 
        echo "YES!!";
    
        ####### リリース処理
        
        ### 複数台の場合はサーバリストに順次telnetしていろいろやる ###
        
        # リリース先のディレクトリがなければ作る
        
        # メンテナンスモード
        # php artisan down
        
        # バックアップ
        
        # 権限設定
        
        # storage等のmount解除
        
        # rsync (メンテモードはファイルチェックなのでdeleteしないように)
        
        # storage等のmount
        
        # メンテナンスモード解除
        # php artisan up
    
        echo "STG環境へのリリースを実行しました";
    
    ;;

    * ) 
        echo "STG環境へのリリースを実行しませんでした";
        ;;

esac