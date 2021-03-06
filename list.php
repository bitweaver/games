<?php
// $Header$
// Copyright (c) 2004 bitweaver Games
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
// Initialization
require_once( '../kernel/setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'games' );

// Look up the content
require_once( GAMES_PKG_PATH.'lookup_game_inc.php' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_games_play' );

// Get list of active games
$listHash = $_REQUEST;
$listHash['active'] = TRUE;
$gamesList = $gGameSystem->getList( $listHash );
$gBitSmarty->assign_by_ref( 'gamesList', $gamesList );

// getList() has now placed all the pagination information in $_REQUEST['listInfo']
$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:games/list_games.tpl', tra( 'Games' ) , array( 'display_mode' => 'list' ));

?>
