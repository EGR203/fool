<?php

return [
	'access_token' => env('VK_ACCESS_TOKEN'),
	'confirm_token' => env('VK_CONFIRM_TOKEN'),
	'python_script_path' => base_path() . "/app/python/lessons_schedule/parser.py",
	'table_path' => base_path() . "/storage/table.xls",
	'group_default' => env('VK_GROUP_DEFAULT'),
];