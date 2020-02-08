<?php
require_once('chatServ_retroshare.php');
$serv=new chatServ('localhost','1234');
$room="L90E043C6C6376AFD";
$nameofbot='P0t4t0S3rv1c3';
//$send=$serv->sendMessage('LA2F5CB25E2CFDA04', 'Сообщуха из JSON');
//print_r($send);
$lasttime=time();

function getRetroshareLink($cert, $name, $text){
	$cert=urlencode($cert);
	return
	"<a href='retroshare://certificate?radix=$cert&;name=$name'>$text</a>";
}
$rawmycert=
"CQEGAcGexsBNBF49Pt0BCACxVFxCPBTfacDSrzad4abYwr0FNwJErB2BcfvYaft1
FFZTZvWBNBD8x8D/A6/6K5sRCtT/1LiU7FEGX+aXvz/pqfOS1q5UPAzDqjc51qFs
GOqd1wh3b/UBCbQwSClybA21okEq0RxBslOVCrW496BwUtqcrTbuhJJK0QzFGbYu
mRTR4aRtidGzHOv04+pNx7w38BujKLZ2p2x9sLlHqbtXKjPiUqeLNnaBDFJY29hR
mYdOgy0qD5e8jB2npkT4Sw8SMmQifb+iugSYx5QhR1sylPms5azGbwwyF0mkaHHM
gRXRfaIQpBh947lr2sGpgx1irKxERN1HNMkTS+Z4zt3vABEBAAHNKlAwdDR0MFMz
cnYxYzMgKEdlbmVyYXRlZCBieSBSZXRyb1NoYXJlKSA8PsLAXwQTAQIAEwUCXj0+
3QkQXVN1xlvJnvECGQEAAEWiB/907jjSRIFLywc8Wrb6zuvQ/ghiQ7l7swGxLB1o
cdULBllWoG1+HXV0KZqv/9btOKw5n5ka3yRhJiyOkd43n2n7TSmj3gKUQirRd3pZ
A3dQ5/eHWBYafBSgIa1YRnn1nQAZ/88wNgs1Jd0eZQLks9nXtXjk+4aUFnAXdvHh
CWMjKGSzhUEn7VHSEcJ2Kre3LXksqltzw6LSLi8ZcMJnZCSIFBEaIUC3z3A37OTi
ZjT9nd0f81BwGzReBGRyEcOJKvRFIa9QdwRw0HE5LD6r9iuqECizmt8CF/Ok1eSy
tRsgivXBg5T6msaV2JrT9mFJFarXogQlpVAoo2LpE6C7ALSLAgZtb7K1Bh4DBgqJ
ABEGHgQABgtNeSBjb21wdXRlcgUQcPFbev6WztdfH2H3+nmQ5gcD+dTm";
$mycertUrl=getRetroshareLink($rawmycert, "potatoservice", "add my own");
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
				if( $serv->add_peer($args[1]) ) $serv->sendMessage($room, 'added! add me: '.$mycertUrl);
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
