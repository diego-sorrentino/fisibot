#!/bin/bash

. settings.sh

if [ 1 != ${BASH_ARGC} ];
then
 echo 'manca il comando'
 exit
fi

CMD=${BASH_ARGV[0]}

curl -v -k -X POST -H "Content-Type: application/json" -H "Cache-Control: no-cache"  -H "HOST: ${HOSTNAME}" -d '
{
	"update_id":10000,
		"message":{
			"date":1441645532,
			"chat":{
				"last_name":"Test Lastname",
				"id":1111111,
				"type": "private",
				"first_name":"Test Firstname",
				"username":"Testusername"
			},
			"message_id":1365,
			"from":{
				"last_name":"Test Lastname",
				"id":1111111,
				"first_name":"Test Firstname",
				"username":"Testusername"
			},
			"text":"/'${CMD}'"
		}
}' ${URL}
