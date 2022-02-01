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
		"callback_query":{
			"id": "4382bfdwdsb323b2d9",
			"from":{
				"last_name":"Test Lastname",
				"type": "private",
				"id":1111111,
				"first_name":"Test Firstname",
				"username":"Testusername"
			},
			"data": "'${CMD}'",
			"inline_message_id": "1234csdbsk4839"
		}
}' ${URL}
