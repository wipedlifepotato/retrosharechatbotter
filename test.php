<?php
require_once('chatServ_retroshare.php');
$serv=new chatServ('localhost','1234');
$room="L90E043C6C6376AFD";
$nameofbot='P0t4t0S3rv1c3';
//$send=$serv->sendMessage('LA2F5CB25E2CFDA04', 'Сообщуха из JSON');
//print_r($send);
$lasttime=time();
while(1){
	sleep(5);
	$messages=$serv->readMessage($room);
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
		print('cant read... err'. $messages['returncode']);
		sleep(5);
		continue;
	}
	foreach($messages['data'] as $msg){
			if( strlen($msg['author_name']) == 0 || $msg['recv_time'] < $lasttime 
				|| strstr($msg['author_name'], 'P0t4t0S3rv1c3') ) continue;
			//print_r($msg);
			$author=$msg['author_name'];
			$msg=$msg['msg'];
			print( $author .' writed '. $msg."\r\n" );
			
			if  ( stristr($msg, "ping") !== FALSE)
				$send=$serv->sendMessage($room, 'PONG from JSON');
			elseif  ( stristr($msg, "/help") !== FALSE){
				$send=$serv->sendMessage($room, '/add *PLAINOLDFORMATCERT* /getpubnodes /wantbepubnodes');
			}elseif  ( stristr($msg, "/add") !== FALSE){
				
				$args=explode(" ",$msg, 3);
				if( $serv->add_peer($args[1]) ) $serv->sendMessage($room, 'added!');
				else $serv->sendMessage($room, 'cant add, check your cert: '.$serv->addpeer_dbg_msg);
				print("cert: ". $args[1]);
			}elseif  ( stristr($msg, "/getpubnodes") !== FALSE){
				$serv->sendMessage($room, 'http://livingstonei2p.xyz/retroshare/pubnodes.txt');
				$serv->sendMessage($room, 'http://7m7kocs5edegpic3.onion/retroshare/pubnodes.txt');
				$serv->sendMessage($room, 
					'http://potatlpuvdqy7ps5ac5i3tkornq5rqyfy6d7nv56r2r2nejtpmsa.b32.i2p/retroshare/pubnodes.txt');
			}elseif  ( stristr($msg, "/wantbepubnodes") !== FALSE) 
				$serv->sendMessage($room, "email: potatolivingstonei2p@gmail.com");
			//print_r($msg);
			print("--------\r\n");
	}
	$lasttime=time();
}//while

?>
