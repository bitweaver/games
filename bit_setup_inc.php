<?php
global $gBitSystem;

$registerHash = array(
	'package_name' => 'games',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

// If package is active and the user has view auth then register the package menu
if( $gBitSystem->isPackageActive( 'games' ) && $gBitUser->hasPermission( 'p_games_view' ) ) {
	$menuHash = array(
		'package_name'  => GAMES_PKG_NAME,
		'index_url'     => GAMES_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:games/menu_games.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}

if( !spl_autoload_functions() || !in_array( 'hordeyaml_autoloader', spl_autoload_functions() ) ) {
	// schmema for auto loading Horde/Yaml
	function hordeyaml_autoloader($class) {
		if( strstr( $class, 'Horde_Yaml' ) ){
			foreach( explode( PATH_SEPARATOR, get_include_path()) as $path ){
				$file = str_replace('_', '/', $class) . '.php';
				if( file_exists( $path.'/'.$file ) ){ 
					require $file; 
					break;
				}
			}
		}
	}       
	spl_autoload_register('hordeyaml_autoloader');
}


