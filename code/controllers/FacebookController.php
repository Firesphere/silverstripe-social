<?php
/**
 * I don't know, the SDK is not making much sense...
 *
 * @author Simon 'Sphere' Erkelens
 * @todo clean up this mess. It's not really making sense.
 * @todo implement better method to post.
 */
class FacebookController extends Controller {


	public function signin(){
		$member = Member::currentUser();
		if($member){
			$SiteConfig = SiteConfig::current_site_config();
			$state = Session::get('state');
			if(!$state){
				$state = md5(mt_rand());
				Session::set('state', $state);
			}
			$config = array(
				'appId' => $SiteConfig->FBAppID,
				'secret' => $SiteConfig->FBSecret,
			);
			$facebook = new Facebook($config);
			if(!$facebook->getUser()){
				$login_url_params = array(
					'scope' => 'publish_stream,read_stream,offline_access,manage_pages',         
					'fbconnect' =>  1,         
					'display'   =>  "page",         
					'next' => Director::absoluteBaseURL().'FacebookController/postFacebook',
					'state' => $state,
				);
				$login_url = $facebook->getLoginUrl($login_url_params);
				$this->redirect($login_url);
			}
			else{
				echo 'success';
				sleep(10);
				$this->redirect('/admin/settings');
			}
		}
	}
	
	public function callback(){
		$member = Member::currentUser();
		if($member){
			$SiteConfig = SiteConfig::current_site_config();
			$request = $this->getRequest()->requestVars();
			$state = Session::get('state');
			if($_GET['state'] == $state){
				$facebook = new Facebook(
					array(
						'appId' => $SiteConfig->FBAppID,
						'secret' => $SiteConfig->FBSecret,
						'code' => $request['code'],
					)
				);
				$facebook->setAccessToken($facebook->getAccessToken());
				if($facebook->getUser() && $facebook->getAccessToken()){		
					$SiteConfig->FBVerified = $facebook->getAccessToken();
				}
				$SiteConfig->write();
				echo 'success';
				sleep(10);
			}
			$this->redirect('/admin/settings');
		}
	}
	
	/**
	 * @todo fix this BIG mess.
	 */
	public static function postFacebook($message, $link = null){
		$member = Member::currentUser();
		if($member){

			if($link == null){
				$link = Director::absoluteBaseURL();
			}
			$SiteConfig = SiteConfig::current_site_config();
			$page = '/'.$SiteConfig->FBPageID.'/feed';
			$facebook = new Facebook(
				array(
					'appId' => $SiteConfig->FBAppID,
					'secret' => $SiteConfig->FBSecret,
				)
			);
			$permissions_list = $facebook->api('/me/permissions');
			$permissions_needed = array('publish_stream', 'read_stream', 'manage_pages');
			foreach($permissions_needed as $perm){  
				if( !isset($permissions_list['data'][0][$perm]) || $permissions_list['data'][0][$perm] != 1 ){
					$login_url_params = array(
						'scope' => 'publish_stream,read_stream,offline_access,manage_pages',         
						'fbconnect' =>  1,         
						'display'   =>  "page",         
						'next' => Director::absoluteBaseURL().'FacebookController/postFacebook',
					);
					$login_url = $facebook->getLoginUrl($login_url_params);
					header('Location: '.$login_url);
				}
			}
			$pages = json_decode(file_get_contents('https://graph.facebook.com/me/accounts?access_token='.$facebook->getAccessToken()),1);
			foreach($pages['data'] as $userpage){
				if($userpage['id'] == $SiteConfig->FBPageID){
					break;
				}
			}
			$facebook->setAccessToken($userpage['access_token']);
			if($message == ''){
				$message = "This is a test-post for the Facebook API";
			}
			$data = array(
				'message' => $message,
				'link' => $link,
				'access_token' => $userpage['access_token']	
			);
			$postresult = $facebook->api($page, 'post', $data);
		}
	}

}
