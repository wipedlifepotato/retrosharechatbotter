<?php
require_once('chatServ_retroshare.php');
$serv=new chatServ('localhost','1234');
$room="L90E043C6C6376AFD";
$nameofbot='P0t4t0S3rv1c3';



$serv->sendMessage($room, "<b>this is html bl<s>y</s>at</b>");
