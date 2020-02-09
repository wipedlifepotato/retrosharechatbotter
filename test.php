<?php
require_once('chatServ_retroshare.php');
require_once('commands.php');
$serv=new chatServ('localhost','1234');
$rooms=array("L90E043C6C6376AFD","LA2F5CB25E2CFDA04", "L8040CFF426125BAF", "L6841392D6FE78A6F", "LC5D0D53DF79DB7B3");
$nameofbot='P0t4t0S3rv1c3';
//$send=$serv->sendMessage('LA2F5CB25E2CFDA04', 'Сообщуха из JSON');
//print_r($send);
$lasttime=time();
$MXS=120;

while(1){
	sleep(5);
	foreach($rooms as $room){
	//
	$messages=$serv->readMessage($room);
	$messages_size=sizeof($messages['data']);
	if ( $messages['returncode'] != 'ok' ){
		print('cant read... err(maybe not messages yet, write to channel)'. $messages['returncode']);
		if( ! $serv->sendMessage($room,"vsem darova") )print('no like its trouble of you');
		else print("jes, its trouble of not messages... like to fixed");
		sleep(1);
		continue;
	}
	$commands=array(
		"ping"=>doPing,
		"retroshare://certificate"=>doAddCertFromLink,
		"/help"=>doHelp,
		"/add"=>doAdd,
		"/getpubnodes"=>doGetpubnodes,
		"/wantbepubnodes"=>doWantbepubnodes,
		"/dice"=>doDice,
		"/joke"=>doJoke
	);
	foreach($messages['data'] as $msg){
			if( strlen($msg['author_name']) == 0 || $msg['recv_time'] < $lasttime 
				|| strstr($msg['author_name'], 'P0t4t0S3rv1c3') ) continue;
			//print_r($msg);
			$author=$msg['author_name'];
			$msg=$msg['msg'];
			print( $author .' writed '. $msg."\r\n" );
			foreach($commands as $command=>$fun){
				if( stristr($msg, $command) !== FALSE){
					print("Found command... call function");
					$fun($serv,$room,$msg);
					break;
				}
			}
			print("--------\r\n");
	}
	$lasttime=time();
	if($messages_size > $MXS) $serv->clearLobbies();
}//foreach
}//while

?>
