<?php
define('__REQUEST__', explode('/', $_GET['pondokcoder_simrs_api']));
define('REQUEST_METHOD_NOT_VALID', 100);
define('REQUEST_CONTENTTYPE_NOT_VALID', 101);
define('REQUEST_NOT_VALID', 102);
define('VALIDATE_PARAMETER_REQUIRED', 103);
define('VALIDATE_PARAMETER_DATATYPE', 104);
define('API_NAME_REQUIRED', 105);
define('API_PARAM_REQUIRED', 106);
define('API_DOSNT_EXIST', 107);
define('INVALID_USER_PASS', 108);
define('SUCCESS_RESPONSE', 200);
define('__EXCLUDE_METHOD__', array(
	'__getConn',
	'getConn',
	'__construct',
	'__clone',
	'__wakeup',
	'insert',
	'update',
	'delete',
	'hard_delete',
	'where',
	'order',
	'select',
	'join',
	'on',
	'buildQuery',
	'execute',
	'__toString',
	'getCode',
	'getFile',
	'getLine',
	'getTrace',
	'getPrevious',
	'getTraceAsString',
	'test',
	'__GET__',
	'__POST__',
	'__PUT__',
	'__DELETE__',
	'getClassesInNamespace',
	'getDefinedNamespaces',
	'getNamespaceDirectory',
	'format_date',
	'log',
	'getAuthorizationHeader',
	'getBearerToken',
	'readBearerToken',
	'connect',
	'get',
	'gen_uuid',
	'getMessage'
));
?>
