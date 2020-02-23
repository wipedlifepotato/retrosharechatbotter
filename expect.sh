#!/usr/bin/expect -f

set timeout -1


spawn retroshare-nogui --webinterface 1234

expect "Type account number: "
send -- "2\r\n"
expect "Enter the password for key  :"
send -- "pass\r\n"
expect "Segmentation fault (core dumped)"

