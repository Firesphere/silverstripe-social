<?php
/**
 * I don't know, the SDK is not making much sense...
 *
 * @author Simon 'Sphere' Erkelens
 */
class FacebookSetup {

	public static $facebookData = array();
	
	public function __construct($facebook){
		self::$facebookData = new Facebook($facebook);
	}
	
	private function setappID($id){
		self::$facebook['appID'] = $id;
	}
	
	private function setSecret($secret){
		self::$facebook['Secret'] = $secret;
	}
	
	private function setCookie($cookie = false){
		self::$facebook['Cookie'] = $cookie;
	}
	
	private function setPageID($page){
		self::$facebook['PageID'] = $page;
	}
	
	public function signin(){
		$SiteConfig = SiteConfig::current_site_config();
		$this->setappID($SiteConfig->FBAppID);
		$this->setSecret($SiteConfig->FBSecret);
		$this->setPageID($SiteConfig->FBPageID);
	}

}
