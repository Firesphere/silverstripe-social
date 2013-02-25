<?php
/**
 * Add the social-features to the SiteConfig. Not really exciting actually.
 *
 * @author Simon 'Sphere' Erkelens
 * @todo cleanup and translatables
 */
class SocialSiteConfigExtension extends DataExtension {
	
	/**
	 * OAuth data. Currently only Twitter is supported.
	 * @var type 
	 */
	public static $db = array(
		'TwitterAccount' => 'Varchar(255)',
		'ConsumerKey' => 'Varchar(255)',
		'ConsumerSecret' => 'Varchar(255)',
		'OAuthToken' => 'Varchar(255)',
		'OAuthTokenSecret' => 'Varchar(255)',
		'TweetText' => 'Varchar(100)',
		'FBAppID' => 'Varchar(255)',
		'FBSecret' => 'Varchar(255)',
		'FBPageID' => 'Varchar(255)'
	);


	/**
	 * If the user is admin, he/she is able to enter the twitter keys required.
	 * There is only one user active at the time. So, after the button is clicked, we don't show it again.
	 * @todo fix the button to make it less ugly.
	 * @param FieldList $fields 
	 */
	public function updateCMSFields(FieldList $fields){
		/**
		 * Twitter connection (at least this one works!)
		 */
		if($this->owner->OAuthToken != ''){
			$setField = LiteralField::create('dummy', _t($this->class . '.VERIFIED', '<h5>'._t($this->class . '.DONE', 'Already verified with Twitter').'</h5><br />'));
		}
		else{
			$setField = LiteralField::create('dummy', '
				<div onclick="javascript:window.location.href =\'TwitterController/signin/\'" class="ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="" data-icon="accept" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon btn-icon-accept"></span><span class="ui-button-text">
		'._t($this->class . '.VERIFY', 'Verify with Twitter').'</span></div><br />');
		}
		$fields->addFieldToTab(
			'Root',
			Tab::create(
				'TwitterConnect',
				_t($this->class . '.TWITTERTAB', 'Twitter connect'),
				$setField,
				TextField::create('TwitterAccount', _t($this->class . '.TACCOUNT', 'Twitter account'))
			)
		);
		// Only admins can add a consumer key/secret combo. For security reasons ofcourse.
		if(Member::currentUser()->inGroup('administrators')){
			$fields->addFieldsToTab(
				'Root.TwitterConnect',
				array(
					TextField::create('ConsumerKey'),
					TextField::create('ConsumerSecret')
				)
			);
		}
		$fields->addFieldToTab('Root.TwitterConnect', TextField::create('TweetText', _t($this->class . '.TWEETTEXT', 'Text to tweet. $Title will be replaced by the actual page/object title.')));
		
		/**
		 * Facebook connection.
		 */
		if($this->owner->FBVerified){
			$setField = LiteralField::create('dummy', _t($this->class . '.VERIFIED', '<h5>Already verified with Facebook</h5><br />'));
			$setField2 = LiteralField::create('dummy2', '
				<div onclick="javascript:window.location.href =\'FacebookController/postFacebook/\'" class="ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="" data-icon="accept" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon btn-icon-accept"></span><span class="ui-button-text">
		'._t($this->class . '.VERIFYFB', 'Send a test-post').'</span></div><br />');
		}
		else{
			$setField = LiteralField::create('dummy', '
				<div onclick="javascript:window.location.href =\'FacebookController/signin/\'" class="ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="" data-icon="accept" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon btn-icon-accept"></span><span class="ui-button-text">
		'._t($this->class . '.VERIFYFB', 'Verify with Facebook').'</span></div><br />');
			$setField2 = LiteralField::create('dummy2', _t($this->class . '.VERIFYFIRST', 'You need to verify before you can test!'));
		}
		$fields->addFieldToTab(
			'Root',
			Tab::create(
				'FacebookConnect',
				_t($this->class . '.FBTab', 'Facebook connect'),
				$setField,
				$setField2,
				TextField::create('FBPageID', 'Facebook Page ID')
			)
		);
		// Only admins can add a consumer key/secret combo. For security reasons ofcourse.
		if(Member::currentUser()->inGroup('administrators')){
			$fields->addFieldsToTab(
				'Root.FacebookConnect',
				array(
					TextField::create('FBAppID', 'Facebook App ID'),
					TextField::create('FBSecret', 'Facebook secret'),
				)
			);
		}
		$fields->addFieldToTab('Root.FacebookConnect', TextField::create('FBText', _t($this->class . '.FBTEXT', 'Text to post to Facebook. $Title will be replaced by the actual page/object title.')));

	}
}
