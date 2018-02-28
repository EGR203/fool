<?php

return [
	'access_token' => env('VK_ACCESS_TOKEN'),
	'confirm_token' => env('VK_CONFIRM_TOKEN'),
	'python_script_path' => base_path() . "/app/python/lessons_schedule/parser.py",
	'group_default' => env('VK_GROUP_DEFAULT'),
	'beginning_of_study' => new Carbon\Carbon("2017-09-18", new DateTimeZone("Asia/Vladivostok")),
];