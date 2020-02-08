<?php
require_once('chatServ_retroshare.php');
$serv=new chatServ('localhost','1234');
//$send=$serv->sendMessage('LA2F5CB25E2CFDA04', 'Сообщуха из JSON');
//print_r($send);
$lasttime=time();
while(1){
	sleep(5);
	$messages=$serv->readMessage('LA2F5CB25E2CFDA04');
	//print_r($messages);
/*
Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [author_id] => a15bbad739c9671d2f648694da2d48f3
                    [author_name] => P0t4t0S3rv1c3
                    [id] => 2671838495
                    [incoming] => 
                    [links] => Array
                        (
                        )

                    [msg] => test
                    [read] => 1
                    [recv_time] => 1581196081
                    [send_time] => 1581196081
                    [was_send] => 1
                )

            [1] => Array
                (
                    [author_id] => a15bbad739c9671d2f648694da2d48f3
                    [author_name] => P0t4t0S3rv1c3
                    [id] => 1883337274
                    [incoming] => 
                    [links] => Array
                        (
                        )

                    [msg] => ping
                    [read] => 1
                    [recv_time] => 1581196369
                    [send_time] => 1581196369
                    [was_send] => 1
                )

        )

    [debug_msg] => 
    [returncode] => ok
    [statetoken] => 4698
)

*/
	if ( $messages['returncode'] != 'ok' ){
		print('cant read... err');
		sleep(5);
		continue;
	}
	foreach($messages['data'] as $msg){
			if( strlen($msg['author_name']) == 0 || $msg['recv_time'] < $lasttime) continue;
			//print_r($msg);
			$author=$msg['author_name'];
			$msg=$msg['msg'];
			print( $author .' writed '. $msg."\r\n" );
		
			if  ( stristr($msg, "ping") !== FALSE){
				print("SEND\n");
				array_push($ids,$msg['id']);
				$lasttime=time();
				$send=$serv->sendMessage('LA2F5CB25E2CFDA04', 'PONG from JSON');
			}
			//print_r($msg);
			print("--------\r\n");
	}
}//while

?>
