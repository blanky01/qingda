<?php return array (
'class'=>'CDbConnection',	
'connectionString' => "mysql:host={$_SERVER['DB_QINGDA_HOST']};port={$_SERVER['DB_QINGDA_PORT']};dbname=db_bms_english4",
	'username' => $_SERVER['DB_QINGDA_USERNAME'],
	'password' => $_SERVER['DB_QINGDA_PASSWORD'],
	'charset' => 'utf8',
	'tablePrefix' => 'my_',
	'emulatePrepare' => true,
	//'enableProfiling' => true,
	//'enableParamLogging'=>true,
	//'queryCacheID'=>'mcache',
	//'queryCachingDuration'=>36000,
	'persistent' => false,
	//'schemaCacheID'=>'mcache',
	//'schemaCachingDuration'=>864000,
);
