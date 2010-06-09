<?php
// $Header$

// Initialization
require_once( '../kernel/setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'games' );

// Check permissions to access this page before even try to get content
$gBitSystem->verifyPermission( 'p_games_play' );

if( !empty( $_REQUEST['game'] ) ){
	// Load up a game
	require_once( GAMES_PKG_PATH.'lookup_game_inc.php' );

	if( !$gGameSystem->isValid() ) {
		// if we dont have a valid game type to play return 404
		$gBitSystem->setHttpStatus( 404 );

		$msg = tra( "The requested game:".ucfirst($_REQUEST['game'])." could not be found." );

		$gBitSystem->fatalError( tra( $msg ) ); 
	}else{
		// Display the template
		$gGameSystem->display();
	}
}else{
	include_once( GAMES_PKG_PATH.'list.php' );
	die;
}

