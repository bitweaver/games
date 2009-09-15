<?php
// $Header: /cvsroot/bitweaver/_bit_games/list.php,v 1.1 2009/09/15 14:54:44 wjames5 Exp $
// Copyright (c) 2004 bitweaver Games
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once( '../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'games' );

// Look up the content
require_once( GAMES_PKG_PATH.'lookup_game_inc.php' );

// Now check permissions to access this page
$gBitSystem->verifyPermission( 'p_games_play' );

// Create new games object
$gamesList = $gGameSystem->getList( $_REQUEST );
$gBitSmarty->assign_by_ref( 'gamesList', $gamesList );

// getList() has now placed all the pagination information in $_REQUEST['listInfo']
$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:games/list_games.tpl', tra( 'Games' ) , array( 'display_mode' => 'list' ));

?>