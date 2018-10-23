<?php
require_once('./lib/Phirehose.php');
require_once('./lib/OauthPhirehose.php');
require_once('./myid.php');

class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {

    //文字コード設定(絵文字対策のためにUTF8MB4)
    $strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
    //DB接続試行
    try {
         $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
	 $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
         echo $e->getMessage();
         exit;
    }

   $data = json_decode($status, true);

   if(preg_match('/RT|@/',$data['text']) == false){

    //タグ指定やNGワードの設定(削除対象文字)
    $tagsNG = array('#koryosai2018','#koryosai','#morikapusantest',"'","<",">","\\");

    if (is_array($data) && isset($data['user']['screen_name'])) {

	   $result = preg_replace("(https?://[-_.!~*\'()a-zA-Z0-9;/?:@&=+$,%#]+)", '', $data['text']);
	   $result = preg_replace(array('/\r\n/','/\r/','/\n/','/\\\/u'), '', $result);
           $result = str_ireplace($tagsNG, '', $result);
	   $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
           $namedata = preg_replace(array('/\r\n/','/\r/','/\n/','/\\\/u'), '', $data['user']['name']);
           $namedata = str_ireplace($tagsNG, '', $namedata);
           //取得結果出力
           print "[取得]内容:";
           print $data['user']['screen_name'] . ': ' . $result . "\n";

           $username = $namedata;
           $userid = $data['user']['screen_name'];
           $iconurl = $data['user']['profile_image_url_https'];
           $strcontent = $result;

	   if(empty($data['extended_entities'])){
	   	//ID位置取得
           	$fp = fopen("counter.txt", 'r');
           	$pointer = fgets($fp);
           	fclose($fp);
		$mediaurl = "";
	   }else{
		$pointer = 1;
           	if(($data['extended_entities']['media'][0]['type'] == "photo")){
                	$mediaurl = $data['extended_entities']['media'][0]['media_url'];
           	}
	   }

           //DRBUG
           print "DB挿入:" . "ID=" . $pointer . "," . $username . "," . $userid . "," . $iconurl . "," . $strcontent . "," . $mediaurl . "\n";

           //実行するSQL文設定
           $query = "UPDATE Data SET USER_NAME = :username, USER_ID = :userid, USER_ICON = :usericon, TEXT = :text, MEDIA = :media WHERE id = :id";
           //SQL実行準備
           $stmt = $dbh->prepare($query);
           //各キーに文章代入
           $params = array(':username' => $username, ':userid' => $userid, ':usericon' => $iconurl, ':text' => $strcontent, ':media' => $mediaurl, ':id' => $pointer);
           //INSERT実行
           $stmt->execute($params);

	   if(empty($data['extended_entities'])){
	   	if($pointer >= 5){
			$pointer = 2;
			}else{
			$pointer = $pointer + 1;
	   		}
	   $cowriter = fopen("counter.txt", "w");
           @fwrite($cowriter, $pointer);
           fclose($cowriter);
	  }
	}
    }
  }
}

$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->setTrack(SEARCHWORD);
$sc->consume();

