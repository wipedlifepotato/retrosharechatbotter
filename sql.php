<?php
$db = new SQLite3("pubnodes.db");
function addNode($cert,$name){
	global $db;
	$name = $db->escapeString($name);
	$cert = $db->escapeString($cert);
	$sql = "INSERT INTO peers VALUES('$name', '$cert')";
	return $db->exec($sql);
}
function getNodes($limit=10, $off=0){
	global $db;
	$sql = "SELECT * FROM peers LIMIT $limit OFFSET $off";
	$result = $db->query($sql);
	while($r = $result->fetchArray(SQLITE3_ASSOC) ){
		foreach($r as $val=>$name){
			print($val." : ". $name."\n");
		}	
	}
	
}

function installDB(){
	global $db;
	$sql = "CREATE table peers (name TEXT, cert TEXT);";
	return $db->exec($sql);
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
if (sizeof($argv) < 2) exit(0);
if( strstr($argv[1], "install") !== FALSE )
	installDB();
addNode($argv[1], $argv[2]);
getNodes();


$db->close();


