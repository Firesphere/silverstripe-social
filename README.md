# silverstripe-social
=======================

## Introduction

This is a simple social-media-sharer. It will use the API of the selected (currently, only Twitter yet) social media to post something.
Follow the instructions in your settings to get things working

## Maintainer Contacts

* Simon "Sphere" Erkelens `simon[at]casa-laguna[dot]net`

## Features

* Post to twitter. Example-code, taken from my [`silverstripe-newsmodule`](https://github.com/Firesphere/silverstripe-newsmodule):
```PHP
		$siteConfig = SiteConfig::current_site_config();
		if($this->Live && !$this->Tweeted && $siteConfig->TweetOnPost){
			if($siteConfig->ConsumerKey && $siteConfig->ConsumerSecret && $siteConfig->OAuthToken && $siteConfig->OAuthTokenSecret){
				$TweetText = $siteConfig->TweetText;
				$TweetText = str_replace('$Title', $this->Title, $TweetText);
				// Max length is 120 characters, since the URL will be 20 characters long with t.co, 
				// so, let's make that happen.
				if(strlen($TweetText) > 120){
					$TweetText = substr($TweetText, 0, 116).'... '.$this->AbsoluteLink();
				}
				else{
					$TweetText = $TweetText.' '.$this->AbsoluteLink();
				}
				/**
				 * I don't think I have Twitter Oauth module included here, do I? 
				 */
				$conn = new TwitterOAuth(
					$siteConfig->ConsumerKey,
					$siteConfig->ConsumerSecret,
					$siteConfig->OAuthToken,
					$siteConfig->OAuthTokenSecret
				);
				$tweetData = array(
					'status' => $TweetText,
				);
				$postResult = $conn->post('statuses/update', $tweetData);
				$this->Tweeted = true;
				$this->write();
			}
		}
```

## Lacks

* Support for all users instead of just one account
* Facebook
* And a lot more

## Installation

If you don't have a github account, just download:
 1. Click on the big "ZIP" button at the top.
 2. Extract the zip to your site-root
 3. Run in your browser - `www.example.com/dev/build` to rebuild the database. 
 4. Create a NewsHolderPage type in your Pages Admin (todo, autocreate this page)

Other option is to clone the repo into your site-root:
 1.  In your site-root, do `git clone https://github.com/Firesphere/silverstripe-social.git`. 
 2.  Run in your browser - `www.example.com/dev/build` to rebuild the database. 

Although, I would like it if you forked and cloned, because if you do, you can help me by adding features and make pull-requests to improve this module!
 1.  Make a fork of this module.
 2.  In your site-root, do `git clone https://{your username}@github.com/{your username}/silverstripe-social.git`. 
 3.  Run in your browser - `www.example.com/dev/build` to rebuild the database. 

Note, forking is NOT REQUIRED, only handy if you want to help out.

## Configuration

* In the SiteConfig, set your wished configuration in the tabs you want to use.

## Best practices

If you have Facebook connected to twitter, don't post to facebook and twitter as well! Twitter will reject your message because it's a duplicate!

## Plans

* Integrate Facebook OAuth.
* Multi-user support.

## Requests

* Improvements.
* Translations.

## Other

* This module is given "as is" and I am not responsible for any damage it might do to your brain, dog, cat, house, computer or website.
* Code Comments should not be taken too seriously, since I'm bad at writing serious code-comments.
* Please use the Issue-tracker, otherwise I get lost too.
* This is a port of a non-released SS2.4 newsmodule I wrote. It might not be entirely "up to code" yet.

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
