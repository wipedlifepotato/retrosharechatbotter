<?php
$enumWritePubNodes=array(
	"clear"=>(int)1,
	"tor"=>(int)2,
	"i2p"=>(int)4
);
$db = new SQLite3("pubnodes.db");


function getNodes($serv,$room,$limit=10, $off=0){
	global $db;
	$sql = "SELECT * FROM peers LIMIT $limit OFFSET $off";
	$result = $db->query($sql);
	$returns="";
	$i=0;
	while($r = $result->fetchArray(SQLITE3_ASSOC) ){
		$i++;
		$returns.=getRetroshareLink($r['cert'], $r['name'], $i." ");
	}
	$serv->sendMessage($room, $returns);
}

function writePubNodes($serv, $room,$what=-1){ // 1==tor; 2 == onion; 4 == potato
	global $enumWritePubNodes;
	print("what==".((int)$what & $enumWritePubNodes['tor'])." ".$enumWritePubNodes['tor']." ".(int)$what );
	if( ($what & $enumWritePubNodes['clear']) != 0
		  || $what==0 ) $serv->sendMessage($room, 'http://livingstonei2p.xyz/retroshare/pubnodes.php');
	if(($what & $enumWritePubNodes['tor']) != 0
		 || $what==0 ) $serv->sendMessage($room, 'http://7m7kocs5edegpic3.onion/retroshare/pubnodes.php');
	if(($what & $enumWritePubNodes['i2p']) != 0
		 || $what==0 ) $serv->sendMessage($room, 
		'http://potatlpuvdqy7ps5ac5i3tkornq5rqyfy6d7nv56r2r2nejtpmsa.b32.i2p/retroshare/pubnodes.php');
}

function addCert($serv, $cert, $room){
				global $mycertUrl;
				if( $serv->add_peer($cert) ){
					 $serv->sendMessage($room, 'added! add me: ');
					 $serv->sendMessage($room, $mycertUrl);
				}else $serv->sendMessage($room, 'cant add, check your cert: '.$serv->addpeer_dbg_msg);
				print("cert: ". $cert);				
}

//enum in php? HA! to delete this shit in future i think...

function getRetroshareLink($cert, $name, $text){
	$cert=urlencode($cert);
	$name=urlencode($name);
	
	$r="<a href='retroshare://certificate?radix=$cert&name=$name'>$text</a>";
	

	return $r;
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




//

//


function doHelp($serv,$room,$msg=""){
	$send=$serv->sendMessage($room, '/add *PLAINOLDFORMATCERT* /getpubnodes (or in http:)[tor/i2p/clear] /wantbepubnodes');
}

function doAddCertFromLink($serv,$room,$msg){
				$tmp=explode("?",$msg, 2);
				print_r($tmp);
				$tmp=explode("&",$tmp[1]);
				print_r($tmp);
				$c=substr($tmp[0],6);
				$c=urldecode($c);
				addCert($serv, $c, $room);
}

function doPing($serv,$room,$msg=""){
	return $serv->sendMessage($room, 'PONG from JSON');
}

function doAdd($serv,$room,$msg){
				$args=explode(" ",$msg, 3);
				addCert($serv, $args[1], $room);
}
function doGetpubnodes($serv,$room,$msg){
				$args=explode(" ",$msg, 3);
				print_r($args);
				print( sizeof($args) );
				if( sizeof($args) == 2){
					if( stristr($args[1], "tor") !== FALSE ) return writePubNodes($serv, $room, $enumWritePubNodes['tor']);
					elseif( stristr($args[1], "clear")!== FALSE  ) return writePubNodes($serv, $room, $enumWritePubNodes['clear']);
					elseif( stristr($args[1], "i2p")!== FALSE  ) return writePubNodes($serv, $room, $enumWritePubNodes['i2p']);
				}else getNodes($serv, $room);	
}
function doWantbepubnodes($serv,$room,$msg){
	$serv->sendMessage($room, "email: potatolivingstonei2p@gmail.com");
}

function doDice($serv,$room,$msg){
	$serv->sendMessage($room, "num... ".rand(0,6) );
}

function doJoke($serv,$room,$msg){
	$serv->sendMessage($room, "colobok povesilsa suicide...");
}

?>
