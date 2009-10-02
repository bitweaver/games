<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_games/remove.php,v 1.2 2009/10/02 18:51:04 wjames5 Exp $
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( GAMES_PKG_PATH.'BitGame.php');
include_once( GAMES_PKG_PATH.'lookup_games_inc.php' );

$gBitSystem->verifyPackage( 'games' );

if( !$gContent->isValid() ) {
	$gBitSystem->fatalError( "No games indicated" );
}

$gContent->verifyExpungePermission();

if( isset( $_REQUEST["confirm"] ) ) {
	if( $gContent->expunge()  ) {
		header ("location: ".BIT_ROOT_URL );
		die;
	} else {
		vd( $gContent->mErrors );
	}
}

$gBitSystem->setBrowserTitle( tra( 'Confirm delete of: ' ).$gContent->getTitle() );
$formHash['remove'] = TRUE;
$formHash['game_id'] = $_REQUEST['game_id'];
$msgHash = array(
	'label' => tra( 'Delete Games' ),
	'confirm_item' => $gContent->getTitle(),
	'warning' => tra( 'This games will be completely deleted.<br />This cannot be undone!' ),
);
$gBitSystem->confirmDialog( $formHash,$msgHash );

?>
