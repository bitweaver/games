<?php
/**
* $Header: /cvsroot/bitweaver/_bit_games/BitGameSystem.php,v 1.3 2009/10/02 18:51:04 wjames5 Exp $
* $Id: BitGameSystem.php,v 1.3 2009/10/02 18:51:04 wjames5 Exp $
*/

/**
* Game is a abstract factory class implemented by Game plugins
*/

/**
* Copyright (c) 2009 Citizens Union Foundation
* Funding for the development of this package was provided by the John S. and James L. Knight Foundation.
* All Rights Reserved. See below for details and a complete list of authors.
* Licensed under the GNU GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/gpl.html for details 
*
* Developed by Tekimaki LLC http://tekimaki.com
* Date created 2009/9/1
*
* @author Will James <will@tekimaki.com>
* @version $Revision: 1.3 $ $Date: 2009/10/02 18:51:04 $ $Author: wjames5 $
* @class BitGameSystem
*/

require_once( KERNEL_PKG_PATH.'BitBase.php' );

/**
* This is used to uniquely identify the object
*/
define( 'BITGAMES_CONTENT_TYPE_GUID', 'bitgame' );

class BitGameSystem extends BitBase {
	/**
	 * mGame the game class
	 * 
	 * @var string
	 * @access private
	 */
	private $mGame;

	/**
	 * mGameTypes
	 */
	private $mGameTypes = array();

	/**
	 * mScoreId a score 
	 * 
	 * @var number
	 * @access private
	 */
	private $mScoreId;

	/**
	 * BitGameSystem During initialisation, be sure to call our base constructors
	 * 
	 * @param string $pGame 
	 * @access public
	 * @return void
	 */
	function BitGameSystem( $pGameType = NULL ) {
		BitBase::BitBase();
		if( !empty( $pGameType ) && $gameType = $this->isActiveGameType( $pGameType ) ){
			global $gBitSystem;
			$game = $this->mGameTypes[ $pGameType ];
			require_once ( '../'.$gBitSystem->mPackages[$game['package']]['dir']."/".$game['handler_file'] );
			$gameClass = $game['class']; 
			$this->mGame = new $gameClass();
		}
	}

	/**
	 * isValid Make sure game is loaded and valid
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 */
	function isValid() {
		return( !empty( $this->mGame ) && $this->isValidGameType( $this->mGame->getGameType() ) );
	}

	/**
	 * isValidGameType check if a game is registered  
	 */
	function isValidGameType( $pType ){
		$rtn = FALSE;
		if( $pType != NULL ){
			if( !empty( $this->mGameTypes[ $pType ] ) ){
				$rtn = TRUE;
			}elseif( $this->mGameTypes[ $pType ] = $this->loadGameTypeSettings( $pType ) ){
				$rtn = TRUE;
			}
		}
		return $rtn;
	}

	/* static */
	function loadGameTypeSettings( $pType ){
		global $gBitSystem;
		$bindVars[] = $pType;
		$query = "
				SELECT gt.*
				FROM `".BIT_DB_PREFIX."games_types` gt
				WHERE gt.`type` = ?";
		$result = $gBitSystem->mDb->getRow( $query, $bindVars );
		return $result;
	}

	function isActiveGameType( $pType ){
		$rtn = FALSE;
		if( $this->isValidGameType( $pType ) && $this->mGameTypes[ $pType ]['active'] ){
			$rtn = TRUE;
		}
		return $rtn;
	}

	/**
	 *
	 */
	function registerGameType( &$pParamHash ){
		if( $this->verifyGameType( $pParamHash ) ){
			$this->mDb->StartTrans();
			$table = BIT_DB_PREFIX."games_types";
			// update
			if( $this->mDb->getOne( "SELECT gt.`type` FROM `".BIT_DB_PREFIX."games_types` gt WHERE gt.`type` = ?", $pParamHash['type'] ) ){ 
				$result = $this->mDb->associateUpdate( $table, $pParamHash['store_type'], array( 'type' => $pParamHash['type'] ) );
			}else{
			// register new
				$pParamHash['store_type']['type'] = $pParamHash['type'];
				$result = $this->mDb->associateInsert( $table, $pParamHash['store_type'] );
			}
			$this->mDb->CompleteTrans();
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	 *
	 */
	function verifyGameType( &$pParamHash ){
		// required type
		if( !empty( $pParamHash['type'] ) ){
			// optional title
			$pParamHash['store_type']['title'] = !empty( $pParamHash['title'] )?$pParamHash['title']:$pParamHash['type'];
		}else{
			$this->mErrors['store_type']['type'] = "Required game type not specified.";
		}
		// required class
		if( !empty( $pParamHash['class'] ) ){
			$pParamHash['store_type']['class'] = $pParamHash['class'];
		}else{
			$this->mErrors['store_type']['class'] = "Required game handler class not specified.";
		}
		// required handler_file
		if( !empty( $pParamHash['handler_file'] ) ){
			$pParamHash['store_type']['handler_file'] = $pParamHash['handler_file'];
		}else{
			$this->mErrors['store_type']['handler_file'] = "Required game handler handler file not specified.";
		}
		// required package
		if( !empty( $pParamHash['package'] ) ){
			$pParamHash['store_type']['package'] = $pParamHash['package'];
		}else{
			$this->mErrors['store_type']['package'] = "Required game package not specified.";
		}
		// required active (or not)
		$pParamHash['store_type']['active'] = !empty( $pParamHash['active'] )?1:0;
		// optional summary
		if( !empty( $pParamHash['summary'] ) ){
			$pParamHash['store_type']['summary'] = $pParamHash['summary'];
		}
		// required description
		if( !empty( $pParamHash['description'] ) ){
			$pParamHash['store_type']['description'] = $pParamHash['description'];
		}else{
			$this->mErrors['store_type']['description'] = "Required game handler description not specified.";
		}
		// required class
		if( !empty( $pParamHash['instructions'] ) ){
			$pParamHash['store_type']['instructions'] = $pParamHash['instructions'];
		}else{
			$this->mErrors['store_type']['instructions'] = "Required game handler instructions not specified.";
		}
		// required format_guid
		if( !empty( $pParamHash['format_guid'] ) ){
			$pParamHash['store_type']['format_guid'] = $pParamHash['format_guid'];
		}else{
			$this->mErrors['store_type']['format_guid'] = "Required game handler format_guid not specified.";
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	 *
	 */
	function unregisterGameType( $pParamHash ){
		$query = "DELETE FROM `".BIT_DB_PREFIX."games_types` WHERE `type` = ?";
		$ret = $this->mDb->query( $query, array( $pParamHash ) );
		$this->mDb->CompleteTrans();
	}
	
	/**
	 * load Load the data from the database
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function load() {
		if( $this->isValid( $this->mGame ) ){
			$this->mGame->load();
		}
	}

	/**
	 * @TODO write it when important
	 */
	function loadScore( $pScoreId ){
	}

	/**
	 * @param array $pParamHash hash of values that will be used to store the score
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function storeScore( &$pParamHash ) {
		if( $this->isValid() && $this->verifyScore( $pParamHash ) ){
			$result = FALSE;
			$this->mDb->StartTrans();
			$table = BIT_DB_PREFIX."games_scores";
			if( $this->mScoreId ) {
				$locId = array( "score_id" => $pParamHash['score_id'] );
				$result = $this->mDb->associateUpdate( $table, $pParamHash['store_score'], $locId );
			} else {
				if( @$this->verifyId( $pParamHash['score_id'] ) ) {
					$pParamHash['store_score']['score_id'] = $pParamHash['score_id'];
				} else {
					$pParamHash['store_score']['score_id'] = $this->mDb->GenID( 'games_score_id_seq' );
				}
				$this->mScoreId = $pParamHash['store_score']['score_id'];
				$result = $this->mDb->associateInsert( $table, $pParamHash['store_score'] );
			}
			// game instance may want to update also
			if( $result && $this->mGame->storeScore( $pParamHash ) ){
				$this->mDb->CompleteTrans();
			}else{
				// rollback
				$this->mDb->RollbackTrans();
				$this->mErrors = array_merge( $this->mErrors, $this->mGame->mErrors );
			}
		} else {
			$this->mErrors['store_score'] = tra('Failed to save the score.');
		}

		return( count( $this->mErrors )== 0 );
	}

	/**
	 * verifyScore Make sure the data is safe to store
	 * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	 * 
	 * @param array $pParamHash reference to hash of values that will be used to store the score, they will be modified where necessary
	 * @access private
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function verifyScore( &$pParamHash ) {
		// make sure we're loaded up if editing an existing score
		if( $this->verifyId( $this->mScoreId )  ) {
			$this->loadScore();

			// make sure the score we're editing matches the game type we've loaded
			if( $this->mScore['game_type'] == $this->mGame->getGameType() ){
				$this->mErrors['store_score']['game_type'] = "Mismatched game type on existing score.";
			}
		}

		$pParamHash['store_score'] = array( 
				'game_type' => $this->mGame->getGameType(),
			);

		if( !empty( $pParamHash['user_id'] ) ){
			$user = new BitPermUser( $pParamHash["user_id"] );
			$user->load( TRUE );
			if( $user->isValid() ){
				$pParamHash['store_score']['user_id'] = $pParamHash['user_id']; 
			}else{
				$this->mErrors['user_id'] = tra('Invalid user id');
			}
		}else{
			$this->mErrors['user_id'] = tra('Invalid user id');
		}

		if( !empty( $pParamHash['score'] ) ){
			$pParamHash['store_score']['score'] = (int)$pParamHash['score']; 
		}else{
			$this->mErrors['score'] = tra('No score submitted');
		}

		if( !empty( $pParamHash['comment'] ) ){
			$pParamHash['store_score']['comment'] = $pParamHash['comment']; 
		}

		return( count( $this->mErrors )== 0 );
	}

	/**
	 * expunge 
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 */
	function expungeScore() {
		global $gBitSystem;
		$ret = FALSE;
		if( $this->isValid() && !empty( $this->mScoreId ) ) {
			$this->mDb->StartTrans();
			// if related game has any score related data need to remove it first to respect constraints 
			if( $this->mGame->expungeScore( $this->mScoreId ) ) {
				// delete the main record
				$query = "DELETE FROM `".BIT_DB_PREFIX."games_score` WHERE `score_id` = ?";
				$ret = $this->mDb->query( $query, array( $this->mScoreId ) );
				$this->mDb->CompleteTrans();
			}
		}
		return $ret;
	}


	/**
	 * getList This function generates a list of registered games from games_types 
	 * 
	 * @param array $pParamHash 
	 * @access public
	 * @return array List of games data
	 */
	function getList( &$pParamHash ) {
		$selectSql = $joinSql = $whereSql = '';
		$bindVars = array();

		// defaults
		if( empty( $pParamHash['find'] ) ){
			$pParamHash['find'] = NULL;
		}
		if( empty( $pParamHash['sort_mode'] ) ){
			$pParamHash['sort_mode'] = 'title_desc';
		}
		if( empty( $pParamHash['max_records'] ) ){
			$pParamHash['max_records'] = 10;
		}
		if( empty( $pParamHash['offset'] ) ){
			$pParamHash['offset'] = 0;
		}

		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if( isset( $find ) ){
			if( is_array( $find ) ) {
				// you can use an array of pages
				$whereSql .= " WHERE gt.`title` IN( ".implode( ',',array_fill( 0,count( $find ),'?' ) )." )";
				$bindVars = array_merge ( $bindVars, $find );
			} elseif( is_string( $find ) ) {
				// or a string
				$whereSql .= " WHERE UPPER( gt.`title` )like ? ";
				$bindVars[] = '%' . strtoupper( $find ). '%';
			}
		}

		// active only
		if( !empty( $pParamHash['active'] ) ){
			$whereSql .= "WHERE gt.active = 1";
		}

		$query = "
			SELECT gt.* $selectSql
			FROM `".BIT_DB_PREFIX."games_types` gt
				$joinSql
				$whereSql
			ORDER BY ".$this->mDb->convertSortmode( $sort_mode );
		$query_cant = "
			SELECT COUNT(*)
			FROM `".BIT_DB_PREFIX."games_types` gt
				$joinSql
				$whereSql
				";
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );
		$ret = array();
		while( $res = $result->fetchRow() ) {
			// return an associative array
			$ret[$res['type']] = $res;
		}
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		// add all pagination info to pParamHash
		// LibertyContent::postGetList( $pParamHash );
		return $ret;
	}

	/**
	 * getDisplayUrl Generates the URL to the games page
	 * 
	 * @access public
	 * @return string URL to the games page
	 */
	function getDisplayUrl() {
	}

	function getPlayUrl( $pType ){
		return GAMES_PKG_URL."index.php?game=".$pType;
	}

	function display(){ 
		// call the active game's display template
		$this->mGame->display();
	}

	function parseInfoFile( $pFile ){
		$rslt = array();
		if( file_exists( $pFile ) ){ 
			$data = Horde_Yaml::loadFile( $pFile );
			if( !empty( $data['games'] ) ){
				$rslt = $data['games'];
			}
		}
		return $rslt;
	}

	function getGameType( $pType ){
		if( $this->isValidGameType( $pType ) ){
			return $this->mGameTypes[$pType];
		}
	}

	function registerPlayer( &$pParamHash ){
		global $gBitSystem, $gBitSmarty, $gBitUser;
		// check if user already exists
		if( $gBitUser->userExists( array( 'email' => $pParamHash['email'] ) ) ){
			$userData = $gBitUser->getUserInfo( array( 'email' => $pParamHash['email'] ) );	
			$pParamHash['user_id'] = $userData['user_id'];
			$gBitSmarty->assign_by_ref( 'newGameUser', $userData );
		}else{
			// force some settings
			$usersValidate = $gBitSystem->isFeatureActive( 'users_validate_user' );
			$gBitSystem->setConfig( 'users_validate_user', FALSE );
			// create new user
			$reg = array( 'real_name' => $pParamHash['real_name'], 'email'=>$pParamHash['email'] );
			if( empty( $reg['password'] ) ){
				$reg['password'] = $gBitUser->genPass( 9 );
			}
			$userClass = $gBitSystem->getConfig( 'user_class', 'BitPermUser' );
			$newUser = new $userClass();
			// register, notification is disabled
			if( $newUser->register( $reg, FALSE ) ) {
				$pParamHash['user_id'] = $newUser->mUserId;
				$gBitSmarty->assign_by_ref( 'newGameUser', $newUser->mInfo );
			}else{
				$this->mErrors = array_merge( $this->mErrors, $newUser->mErrors );
			}
			// restore settings
			$gBitSystem->setConfig( 'users_validate_user', $usersValidate );
		}
		return( count( $this->mErrors )== 0 );
	}
}
