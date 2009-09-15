<?php
global $gGameSystem;
require_once( GAMES_PKG_PATH.'BitGameSystem.php');

// if we already have a gGameSystem, we assume someone else created it for us, and has properly loaded everything up.
if( empty( $gGameSystem ) || !is_object( $gGameSystem ) || !$gGameSystem->isValid() ) {
	// if game_type supplied, use that
	if( !empty( $_REQUEST['game'] ) ){
		$gGameSystem = new BitGameSystem( $_REQUEST['game'] );
		$gGameSystem->load();
	// otherwise create empty system
	} else {
		$gGameSystem = new BitGameSystem();
	}

	$gBitSmarty->assign_by_ref( "gGameSystem", $gGameSystem );
}
?>
