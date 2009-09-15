<?php
// $Header: /cvsroot/bitweaver/_bit_games/admin/admin_games_inc.php,v 1.1 2009/09/15 14:54:44 wjames5 Exp $

require_once( GAMES_PKG_PATH.'BitGameSystem.php' );

$formGamesLists = array(
	"games_list_summary" => array(
		'label' => 'Summary',
		'note' => 'Display the summary.',
	),
	"games_list_description" => array(
		'label' => 'Description',
		'note' => 'Display the description.',
	),
);
$gBitSmarty->assign( 'formGamesLists', $formGamesLists );

// Process the form if we've made some changes
if( !empty( $_REQUEST['games_settings'] )) {
	$gamesToggles = array_merge( $formGamesLists );
	foreach( $gamesToggles as $item => $data ) {
		simple_set_toggle( $item, GAMES_PKG_NAME );
	}
	simple_set_int( 'games_home_id', GAMES_PKG_NAME );
}

$gameSystem = new BitGameSystem();
$gBitSmarty->assign_by_ref( 'gameSystem', $gameSystem );

// get list of games to register
$games = array();
foreach( $gBitSystem->mPackages as $pkg ){
	// parse each yaml file
	$infoFile = $pkg['path']."games.yaml"; 
	$gamesArray = $gameSystem->parseInfoFile( $infoFile ); 
	foreach( $gamesArray as $gameDefaults ){
		// if the game is already registered
		// we dont want to override the title and description 
		// which can be changed by the user	
		// merge in data from database
		if( $game = $gameSystem->getGameType( $gameDefaults['type'] ) ){
			$game = array_merge( $gameDefaults, $game ); 
		// first time registration
		}else{
			$game = $gameDefaults;
		}
		// not included
		$game['package'] = $pkg['name'];
		// request to store 
		if( !empty( $_REQUEST['games'] ) && in_array( $game['type'], $_REQUEST['games'] ) ){
			$game['active'] = TRUE;
		}
		$gameSystem->registerGameType( $game );
		array_push( $games, $game ); 
	}
}
$gBitSmarty->assign( 'games', $games );

// list of active games
$activeHash = array( 'active' => TRUE );
$activeGames = $gameSystem->getList( $activeHash );
$gBitSmarty->assign( 'activeGames', $activeGames );


