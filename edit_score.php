<?php
// $Header: /cvsroot/bitweaver/_bit_games/edit_score.php,v 1.2 2009/10/02 18:51:04 wjames5 Exp $
// Copyright (c) 2004 bitweaver Games
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'games' );

require_once( GAMES_PKG_PATH.'lookup_game_inc.php' );

// Now check permissions to access this page
if( $gScore->isValid() ){
	$gScore->verifyUpdatePermission();
}else{
	$gScore->verifyCreatePermission();
}

// Check if the page has changed
if( !empty( $_REQUEST["save_games"] ) ) {

	// Check if all Request values are delivered, and if not, set them
	// to avoid error messages. This can happen if some features are
	// disabled
	if( $gScore->store( $_REQUEST['games'] ) ) {
		header( "Location: ".$gScore->getDisplayUrl() );
		die;
	} else {
		$gBitSmarty->assign_by_ref( 'errors', $gScore->mErrors );
	}
}

// Display the template
$gBitSystem->display( 'bitpackage:games/edit_games.tpl', tra('Games') , array( 'display_mode' => 'edit' ));
?>
