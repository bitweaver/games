<?php
require_once( GAMES_PKG_PATH.'BitGameSystem.php');

class BitGame extends BitBase {
	/**
	 * mPackageName package name
	 */
	protected $mPackageName = GAMES_PKG_NAME;

	/**
	 * mGameType machine readable type of game
	 * 
	 * @var string
	 * @access public
	 */
	protected $mGameType;

	/**
	 * mGameTitle human readable name
	 */
	protected $mGameTitle;

	/**
	 * mDisplayTemaplate game play display template
	 */
	protected $mDisplayTemplate;

	function __construct(){
		BitBase::BitBase();
		$this->mGameSettings = BitGameSystem::loadGameTypeSettings( $this->getGameType() );
		$this->mGameTitle = $this->getGameSetting( 'title' ); 
	}

	public function load(){
	}

	public function storeScore(){
	}

	public function display(){
		global $gBitSystem;
		$gBitSystem->display( "bitpackage:".$this->mPackageName."/".$this->mDisplayTemplate , tra( $this->mGameTitle ), array( 'display_mode' => 'display' ) );
	}

	public function getGameType(){
		return $this->mGameType;
	}

	public function getGameSetting( $pKey ){
		if( !empty( $this->mGameSettings[ $pKey ] ) ){
			return $this->mGameSettings[ $pKey ];
		}
		return NULL;
	}
	
}
