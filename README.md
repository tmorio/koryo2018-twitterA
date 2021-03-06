![Logo](https://github.com/tmorio/KoryoStream-Ver4/blob/photo/v4mini.png "logov4")
# KoryoStream-Ver4
小山高専 工陵祭で用いたTwitterタグ晒し機です.  
オープンソースで出しているのでライセンスの範囲内であれば自由に改変して大丈夫です.  
割とガバガバなところもあるかもしれないので、修正していただけたらpull requestでも投げてくれるとありがたいです.
## 何ができるの?
特定のハッシュタグがついたツイートを取得し、ブラウザ上に晒しあげることができます.  
画像や動画にも対応してしました.  
[表示デモ](https://app.moritoworks.com/stream4/)
## 使用ライブラリ
・[fennb/phirehose](https://github.com/fennb/phirehose)
## 必要なもの
・PHP7.0以上が動くWebサーバー  
・MySQL系のデータベース  
・u3m8の再生に対応したブラウザ(そのうちアップデートで他のブラウザにも対応できるようにしていきます)  
・Twitter APIキー  
・[Twitter logo](https://about.twitter.com/en_us/company/brand-resources.html)←ガイドラインの関係上添付できなかった
## 使い方
1. DBサーバー上で予め、文字コード「utf8mb4_general_ci」で適当なデーターベースを作成する.  
2. backend/myid.phpがアクセスできないようにWebサーバーの設定をして下さい.  
  
Nginxの例
```
location = /backend/myid.php {
	deny all;
}
```
Apacheの例(.htaccess)
```
<Files ~ "^\.ht">
deny from all
</Files>

<Files ~ "(myid\.php)">
deny from all
</Files>
```
3. backend/myid.phpに設定情報を書く  
4. maketables.phpを実行する  
```
php maketables.php
```
5. maketables.phpを削除する
```
rm maketables.php
```
6. backendに移動しtweetget.phpを実行する
```
php tweetget.php
```
バックグラウンドで実行する場合
```
nohup php tweetget.php &
```
7. index.phpにブラウザでアクセスしてみる  
8. ツイートして晒されたら勝ち

## ライセンス
The MIT License  
  
Copyright (c) 2018 MoritoWorks,Morikapu(Takenori Morio)

以下に定める条件に従い、本ソフトウェアおよび関連文書のファイル（以下「ソフトウェア」）の複製を取得するすべての人に対し、ソフトウェアを無制限に扱うことを無償で許可します。これには、ソフトウェアの複製を使用、複写、変更、結合、掲載、頒布、サブライセンス、および/または販売する権利、およびソフトウェアを提供する相手に同じことを許可する権利も無制限に含まれます。

上記の著作権表示および本許諾表示を、ソフトウェアのすべての複製または重要な部分に記載するものとします。

ソフトウェアは「現状のまま」で、明示であるか暗黙であるかを問わず、何らの保証もなく提供されます。ここでいう保証とは、商品性、特定の目的への適合性、および権利非侵害についての保証も含みますが、それに限定されるものではありません。 作者または著作権者は、契約行為、不法行為、またはそれ以外であろうと、ソフトウェアに起因または関連し、あるいはソフトウェアの使用またはその他の扱いによって生じる一切の請求、損害、その他の義務について何らの責任も負わないものとします。  
  
要するに、「著作権表示よろしく〜且つ、こちらは責任を取らないけど自由に使っていいよ〜」という事です.

## Frontend Demo
[MoritoWorks APP - KoryoStream Ver4 Demo](https://app.moritoworks.com/stream4/)
