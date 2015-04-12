<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define("BASE_URL", "http://localhost/adminQuinela/");
define("BASE_URL2", "http://localhost/adminQuinela/index.php/");
define("STYLE_URL", "http://localhost/adminQuinela/application/resources/" );
define("JS_URL", "http://localhost/adminQuinela/application/resources/js/" );
define("BOWER_URL", "http://localhost/adminQuinela/application/resources/bower_components/" );
define("IMG_URL", "http://localhost/adminQuinela/application/resources/img/");
define("IMGPILOTOS_URL", "application/resources/img/pilotos/");
define("IMGPISTAS_URL", "application/resources/img/gp/");
define("IMGEXPERTO_URL", "application/resources/img/expertos/");
define("IMGESCUDERIA_URL", "application/resources/img/escuderias/");
define("KEY", 'Q3!A#Y)S{qWi8a6:i}f8VJUGM(V(Fy*#}e*$N)DlJ2Q%w{1+/#zd^h6kmv.,gbQ');


define("IMGDESTEXPERTO_URL", $_SERVER['DOCUMENT_ROOT'] ."QUINIELA-FASTmag/application/resources/img/expertos/");
define("IMGORIGTEXPERTO_URL", $_SERVER['DOCUMENT_ROOT']. "adminQuinela/application/resources/img/expertos/");

define("IMGDESTPILOTO_URL", $_SERVER['DOCUMENT_ROOT'] ."QUINIELA-FASTmag/application/resources/img/pilotos/");
define("IMGORIGTPILOTO_URL", $_SERVER['DOCUMENT_ROOT']. "adminQuinela/application/resources/img/pilotos/");

define("IMGDESTJORNADA_URL", $_SERVER['DOCUMENT_ROOT'] ."QUINIELA-FASTmag/application/resources/img/gp/");
define("IMGORIGJORNADA_URL", $_SERVER['DOCUMENT_ROOT']. "adminQuinela/application/resources/img/gp/");

define('FBAPPID' , '608278609309133');
define('FBSECRET' , 'f9b5e28e4109e77e846945d8ff51e96c');

define('TWCONSUMERKEY', 'pPgGreciBkCIOTscndpZLHox0');
define('TWCONSUMERSECRET', 'FRE5dBRKJzugj3316NPyVbC7w5IQZxu4nE4BzTHhbA0BQD7zQR');
define('TWACCESSTOKEN', ' 1315222147-g5KmA8V6hyV8F4yf5WJMZYUanYKxvCvlfUIWW7v');
define('TWACCESSTOKENSECRET', '89d4rxHiyKzBWiYVQfTYtvNIzgWJgeDi8bcXKBU1BOt5G');
/* End of file constants.php */
/* Location: ./application/config/constants.php */