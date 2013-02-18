# silverstripe-social
=======================

## Introduction

This is a simple social-media-sharer. It will use the API of the selected (currently, only Twitter yet) social media to post something.
Follow the instructions in your settings to get things working

## Maintainer Contacts

* Simon "Sphere" Erkelens `simon[at]casa-laguna[dot]net`

## Features

* Post to twitter. Example-code, taken from my [`silverstripe-newsmodule`](https://github.com/Firesphere/silverstripe-newsmodule):

````php
<?php
	public function onAfterWrite(){
		parent::onAfterWrite();
		$siteConfig = SiteConfig::current_site_config();
		/**
		 * This is related to another module of mine.
		 * Check it at my repos: Silverstripe-Social.
		 * It auto-tweets your new Newsitem. If the TwitterController exists ofcourse.
		 */
		if($this->Live && !$this->Tweeted && $siteConfig->TweetOnPost){
			if(class_exists('TwitterController')){
				TwitterController::postTweet($this->Title, $this->AbsoluteLink());
				$this->Tweeted = true;
				$this->write();
			}
		}
	}

````

## Lacks

* Support for all users instead of just one account
* Facebook
* And a lot more

## Installation

If you don't have a github account, just download:
 1. Click on the big "ZIP" button at the top.
 2. Extract the zip to your site-root
 3. Run in your browser - `www.example.com/dev/build` to rebuild the database. 

Other option is to clone the repo into your site-root:
 1.  In your site-root, do `git clone https://github.com/Firesphere/silverstripe-social.git`. 
 2.  Run in your browser - `www.example.com/dev/build` to rebuild the database. 

Although, I would like it if you forked and cloned, because if you do, you can help me by adding features and make pull-requests to improve this module!
 1.  Make a fork of this module.
 2.  In your site-root, do `git clone https://{your username}@github.com/{your username}/silverstripe-social.git`. 
 3.  Run in your browser - `www.example.com/dev/build` to rebuild the database. 

Note, forking is NOT REQUIRED, only handy if you want to help out.

After installation, setup your app with the desired social network. For twitter, go here: `https://dev.twitter.com`
Setting up your application, will give you a consumer key/secret combination. Set this in your siteconfig and you're good to go.

I have explicitly NOT included my own keys in this module, because it's not necessary, create your own is better for you ;)
Any questions would preferably be asked via the issues-github-method.

## Configuration

* In the SiteConfig, set your wished configuration in the tabs you want to use.

## Best practices

If you have Facebook connected to twitter, don't post to facebook and twitter as well! Twitter will reject your message because it's a duplicate!


## Notes

* Your "login with twitter" button looks fugly!

Yes, I know. I'm sorry.

## Plans

* Integrate Facebook OAuth.
* Integrate Pinterest.
* Integrate Google+ (Not gonna happen soon. G+ API is a closed API at the moment)
* Multi-user support.

## Requests

* Improvements.
* Translations.

## Other

* This module is given "as is" and I am not responsible for any damage it might do to your brain, dog, cat, house, computer or website.
* Code Comments should not be taken too seriously, since I'm bad at writing serious code-comments.
* Please use the Issue-tracker, otherwise I get lost too.

## Actual license

This module is published under BSD 2-clause license, although these are not in the actual classes, the license does apply:

http://www.opensource.org/licenses/BSD-2-Clause

Copyright (c) 2013, Simon "Sphere" Erkelens

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


(I shouldn't scream, should I? This is copy-paste from BSD-2 license...)
