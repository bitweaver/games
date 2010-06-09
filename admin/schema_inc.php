<?php
/**
 * @version $Header$
 * @package games
 */
$tables = array(
	'games_types' => "
		type C(40) PRIMARY,
		class C(128) NOTNULL,
		handler_file C(128) NOTNULL,
		package C(128) NOTNULL,
		active I1 NOTNULL DEFAULT 0,
		title C(160),
		summary C(250),
		description XL,
		instructions XL,
		format_guid C(16) NOTNULL
	",
	'games_scores' => "
		score_id I4 PRIMARY,
		game_type C(40) NOTNULL,
		user_id I4 NOTNULL,
		score I4 NOTNULL,
		comment XL
		CONSTRAINT '
			, CONSTRAINT `games_scores_type_map_ref` FOREIGN KEY (`game_type`) REFERENCES `".BIT_DB_PREFIX."games_types` (`type`)'
	",
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( GAMES_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( GAMES_PKG_NAME, array(
	'description' => "Games package to display games and log scores.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
));

// Sequences
$gBitInstaller->registerSchemaSequences( GAMES_PKG_NAME, array (
	'games_score_id_seq' => array( 'start' => 1 )
));

// User Permissions
$gBitInstaller->registerUserPermissions( GAMES_PKG_NAME, array(
	array ( 'p_games_play'   		, 'Can play game'     		 , 'basic'      , GAMES_PKG_NAME ),
	array ( 'p_games_admin'  		, 'Can admin games'          , 'admin'      , GAMES_PKG_NAME ),
	array ( 'p_games_scores_admin'  , 'Can admin game scores'    , 'admin'      , GAMES_PKG_NAME ),
	array ( 'p_games_scores_update' , 'Can update any game score', 'editors'    , GAMES_PKG_NAME ),
	array ( 'p_games_scores_create' , 'Can create a game score'  , 'registered' , GAMES_PKG_NAME ),
	array ( 'p_games_scores_view'   , 'Can view game scores'     , 'basic'      , GAMES_PKG_NAME ),
	array ( 'p_games_scores_expunge', 'Can delete any game score', 'admin'      , GAMES_PKG_NAME ),
));

// Default Preferences
$gBitInstaller->registerPreferences( GAMES_PKG_NAME, array(
	array ( GAMES_PKG_NAME , 'games_default_ordering' , 'game_id_desc' ),
	array ( GAMES_PKG_NAME , 'games_list_game_id'   , 'y'              ),
	array ( GAMES_PKG_NAME , 'games_list_title'       , 'y'            ),
	array ( GAMES_PKG_NAME , 'games_list_description' , 'y'            ),
	array ( GAMES_PKG_NAME , 'games_home_id'          , 0              ),
));

// Requirements
$gBitInstaller->registerRequirements( GAMES_PKG_NAME, array(
	'liberty' => array( 'min' => '2.1.4' ),
));
