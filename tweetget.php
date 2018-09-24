<?php
require_once('./lib/Phirehose.php');
require_once('./lib/OauthPhirehose.php');

//各種キーの設定
define('TWITTER_CONSUMER_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('TWITTER_CONSUMER_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('OAUTH_TOKEN', 'xxxxxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('OAUTH_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
define('DB_HOST', 'localhost');
define('DB_NAME', 'hogehoge');
define('DB_ID', 'hogehoge');
define('DB_PASS', 'hogehogehoge');

class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {
    //JSONデコード
    $data = json_decode($status, true);
    //検索ターゲット指定
    $tags = array('#koryosai2018','#koryosai');

    if (is_array($data) && isset($data['user']['screen_name'])) {
        //改行コード変換
        $result = str_ireplace('/\r\n|\r|\n/', '', $data['text']);
        //URL削除
        $result = preg_replace('/https?:\/\/[0-9a-z_,.:;&=+*%$#!?@()~\'\/-]+/i', '', $result);
        //ツイートからハッシュタグを除去
        $result = str_replace($tags, '', $result);
        $result = htmlentities($result, ENT_QUOTES, 'UTF-8');

        //取得結果出力
        print "[取得]内容:";
        print $data['user']['screen_name'] . ': ' . $result . "\n";
        }
     }
}

$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
//検索ターゲット指定
$sc->setTrack(array('#koryosai2018', 'hello'));   //取得したいタグを入れる
//$sc->setTrack(array('Hello','FGO'));
$sc->consume();