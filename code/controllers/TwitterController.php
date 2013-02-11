<?php
/**
 * Connect the SS3 CMS to Twitter so you can use it to post to Twitter if you add a new post.
 * Improvements are welcome.
 *
 * @author Simon 'Sphere' Erkelens
 */
class TwitterController extends Controller {
	
	/**
	 * If this array is empty or not defined, all actions are allowed
	 *
	 * @var Array
	 */
	public static $allowed_actions = array(
		'signin',
		'callback',
		'verified',
		'denied',
	);


	public function init() {
		parent::init();
	}


	public function index() {
		return($this);
	}


	/**
	 * Setup and handle the signin via the TwitterOAuth class.
	 * User must be logged on, otherwise it shouldn't be allowed to even link this! 
	 */
	public function signin() {
		if(Member::currentUserID()){
			$siteconfig = SiteConfig::current_site_config();
			$conn = new TwitterOAuth($siteconfig->ConsumerKey, $siteconfig->ConsumerSecret);
			$temp = $conn->getRequestToken(Director::absoluteBaseURL().'TwitterController/callback');
			$redirURL = $conn->getAuthorizeURL($temp);
			$this->redirect($redirURL);
		}
	}

	/**
	 * Callback handler for updating the siteconfig with the oauth-data.
	 * @param type $request 
	 */
	public function callback($request) {
		$siteconfig = SiteConfig::current_site_config();
		if(Member::currentUser() && !$siteconfig->OAuthToken){
			if (($OAuthToken = $request->getVar('oauth_token')) && ($OAuthVerifier = $request->getVar('oauth_verifier'))) {
				$conn = new TwitterOAuth($this->ConsumerKey, $this->ConsumerSecret, $OAuthToken, $OAuthVerifier);
				$tokenCredentials = $conn->getAccessToken($OAuthVerifier);
				Session::set('TokenCredentials', $tokenCredentials);
				Session::set('ConsumerKey', $this->ConsumerKey);
				Session::set('ConsumerSecret', $this->ConsumerSecret);

				$siteconfig->OAuthToken = $tokenCredentials['oauth_token'];
				$siteconfig->OAuthTokenSecret = $tokenCredentials['oauth_token_secret'];
				$siteconfig->write();

				$this->redirect('/admin/settings');
			}
			else {
				$this->redirect($this->Link('denied'));
			}
		}
		else {
			$this->redirect($this->Link('denied'));
		}
	}

	/**
	 * Request denied.
	 * @return type 
	 */
	public function denied() {
		return($this);
	}

}
