<?php
/**
 * I don't know, the SDK is not making much sense...
 *
 * @author Simon 'Sphere' Erkelens
 * @todo clean up this mess. It's not really making sense.
 * @todo implement better method to post.
 * @todo make this thing work. It's completely broken.
 */
class FacebookController extends Controller {

	private static $allowed_actions = array(
		'signin',
		'callback',
		'postFacebook',
	);

	public function signin(){
		$member = Member::currentUser();
		$SiteConfig = SiteConfig::current_site_config();
		if($member){
			$config = array(
				'appId' => $SiteConfig->FBAppID,
				'secret' => $SiteConfig->FBSecret,
			);
			$facebook = new Facebook($config);
			
			if(!$facebook->getUser()){
				$login_url_params = array(
					'scope' => 'publish_stream,read_stream,manage_pages',
					'redirect_uri' => Director::absoluteBaseURL().'FacebookController/callback',
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
			$this->redirect('/admin/settings');
		}
	}
	
	/**
	 * @todo fix this BIG mess.
	 */
	public static function postFacebook($message, $link = null, $impression = null){
		$member = Member::currentUser();
		$postresult = false;
		$SiteConfig = SiteConfig::current_site_config();
		if($member && $SiteConfig->FBAppID && $SiteConfig->FBSecret){
			if($link == null){
				$link = Director::absoluteBaseURL();
			}
			$page = '/'.$SiteConfig->FBPageID.'/feed';
			$facebook = new Facebook(
				array(
					'appId' => $SiteConfig->FBAppID,
					'secret' => $SiteConfig->FBSecret,
				)
			);
			$token = $facebook->api('/me/accounts');
			foreach($token['data'] as $pages){
				if($pages['id'] == $SiteConfig->FBPageID){
					$facebook->setAccessToken($pages['access_token']);
					$verified = true;
					break;
				}
			}
			if($verified){
				$data = array(
					'message' => $message,
					'link' => $link,
					'picture' => $impression
				);
				$postresult = $facebook->api($page, 'post', $data);
			}
		}
		return $postresult;
	}

}
