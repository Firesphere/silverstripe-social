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
		$request = $this->getRequest();
		if(Member::currentUser() && !$siteconfig->OAuthToken){
			if (($OAuthToken = $request['oauth_token']) && ($OAuthVerifier = $request['oauth_verifier'])) {
				$conn = new TwitterOAuth($this->ConsumerKey, $this->ConsumerSecret, $OAuthToken, $OAuthVerifier);
				$tokenCredentials = $conn->getAccessToken($OAuthVerifier);
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
	
	/**
	 * Static function to post to twitter. Requires a title and a link.
	 * And ofcourse. Correct setup siteconfig.
	 * @param type $title string string of the title.
	 * @param type $link string with absolute link.
	 */
	public static function postTweet($title, $link){
		$siteConfig = SiteConfig::current_site_config();
		$postresult = false;
		if($siteConfig->ConsumerKey && $siteConfig->ConsumerSecret && $siteConfig->OAuthToken && $siteConfig->OAuthTokenSecret){
			$TweetText = $siteConfig->TweetText;
			if($TweetText == ''){
				$TweetText = $title;
			}
			else{
				$TweetText = str_replace('$Title', $title, $TweetText);
			}
			// Max length is 120 characters, since the URL will be 20 characters long with t.co, 
			// so, let's make that happen.
			if(strlen($TweetText) > 120){
				$TweetText = substr($TweetText, 0, 116).'... '.$link;
			}
			else{
				$TweetText = $TweetText.' '.$link;
			}
			$conn = new TwitterOAuth(
				$siteConfig->ConsumerKey,
				$siteConfig->ConsumerSecret,
				$siteConfig->OAuthToken,
				$siteConfig->OAuthTokenSecret
			);
			$tweetData = array(
				'status' => $TweetText,
			);
			$postresult = $conn->post('statuses/update', $tweetData);
		}
		return $postresult;
	}

}
