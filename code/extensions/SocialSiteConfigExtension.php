<?php
/**
 * Add the social-features to the SiteConfig. Not really exciting actually.
 *
 * @author Simon 'Sphere' Erkelens
 */
class SocialSiteConfigExtension extends DataExtension {
	
	/**
	 * OAuth data. Currently only Twitter is supported.
	 * @var type 
	 */
	public static $db = array(
		'ConsumerKey' => 'Varchar(255)',
		'ConsumerSecret' => 'Varchar(255)',
		'OAuthToken' => 'Varchar(255)',
		'OAuthTokenSecret' => 'Varchar(255)',
		'TweetText' => 'Varchar(100)',
	);


	/**
	 * If the user is admin, he/she is able to enter the twitter keys required.
	 * There is only one user active at the time. So, after the button is clicked, we don't show it again.
	 * @todo fix the button to make it less ugly.
	 * @param FieldList $fields 
	 */
	public function updateCMSFields(FieldList $fields){
		if($this->owner->OAuthToken != ''){
			$setField = ReadonlyField::create('', '', _t($this->class . '.VERIFIED'), '<h5>Already verified with Twitter</h5><br />');
		}
		else{
			$setField = LiteralField::create('', '
				<div onclick="javascript:window.location.href =\'TwitterController/signin/\'" class="ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="" data-icon="accept" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon btn-icon-accept"></span><span class="ui-button-text">
		'._t($this->class . '.VERIFY', 'Verify with Twitter').'</span></div><br />');
		}
		$fields->addFieldToTab(
			'Root',
			Tab::create(
				'TwitterConnect',
				_t($this->class . '.TWITTERTAB', 'Twitter connect'),
				$setField
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

	}
}
