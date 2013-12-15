plugin.tx_dixeasylogin_pi1 {
	# take a look at the constants for more remarks
	allowCreate = {$plugin.tx_dixeasylogin_pi1.allowCreate}
	allowUpdate = {$plugin.tx_dixeasylogin_pi1.allowUpdate}
	user_pid = {$plugin.tx_dixeasylogin_pi1.user_pid}
	usergroup = {$plugin.tx_dixeasylogin_pi1.usergroup}
	pid_loginPage = {$plugin.tx_dixeasylogin_pi1.pid_loginPage}

	preserveGETvars = tx_ttnews

	provider {
		5 = CONTENTELEMENT
		5.name = User/Pass
		5.uid = {$plugin.tx_dixeasylogin_pi1.uid_felogin}
		5.showWhenLoggedIn = 1

		10 = OPENID
		10.name = Google
		10.url = https://www.google.com/
		10.icon = EXT:dix_easylogin/res/icons/google.gif
		10.showWhenLoggedIn = {$plugin.tx_dixeasylogin_pi1.allowUpdate}

		20 = OPENID
		20.name = Yahoo
		20.url = https://me.yahoo.com/
		20.icon = EXT:dix_easylogin/res/icons/yahoo.ico
		20.showWhenLoggedIn = {$plugin.tx_dixeasylogin_pi1.allowUpdate}

		30 = OPENID
		30.name = myOpenID
		30.url = http://###NAME###.myopenid.com/
		30.icon = EXT:dix_easylogin/res/icons/myopenid.ico
		30.showWhenLoggedIn = {$plugin.tx_dixeasylogin_pi1.allowUpdate}

		40 = OPENID
		40.name = Wordpress
		40.url = http://###NAME###.wordpress.com/
		40.icon = EXT:dix_easylogin/res/icons/wordpress.ico
		40.showWhenLoggedIn = {$plugin.tx_dixeasylogin_pi1.allowUpdate}

		50 = FACEBOOK
		50.name = facebook
		50.icon = EXT:dix_easylogin/res/icons/facebook.jpg
		50.appId = {$plugin.tx_dixeasylogin_pi1.facebook_appID}
		50.appSecret = {$plugin.tx_dixeasylogin_pi1.facebook_appSecret}
		50.showWhenLoggedIn = {$plugin.tx_dixeasylogin_pi1.allowUpdate}

		# OAuth Version 1.0 as used by Twitter
		60 = OAUTH1
		60.name = Twitter
		60.icon = EXT:dix_easylogin/res/icons/twitter.gif
		# sigMethod is one of these: HMAC_SHA1   PLAINTEXT   RSA_SHA1
		60.sigMethod = HMAC_SHA1
		60.consumerKey = {$plugin.tx_dixeasylogin_pi1.twitter_consumerKey}
		60.consumerSecret = {$plugin.tx_dixeasylogin_pi1.twitter_consumerSecret}
		60.requestTokenUrl = https://api.twitter.com/oauth/request_token
		60.authorizeUrl = https://api.twitter.com/oauth/authorize
		60.accessTokenUrl = https://api.twitter.com/oauth/access_token
		# you can use markers in the requestProfileUrl, the corresponding values from the accessToken-response will be substituted
		60.requestProfileUrl = https://api.twitter.com/1/users/lookup.json?user_id=###user_id###
		60.profileMap {
			# format:
			# key = response parameter
			# possible keys: fullname, firstname, lastname, suffix, prefix, postcode, country, nickname, email
			nickname = screen_name
			fullname = name
			id = id
		}
		60.showWhenLoggedIn = {$plugin.tx_dixeasylogin_pi1.allowUpdate}
	}

	# possible values: nickname,email,fullname,postcode,country,prefix,firstname,lastname,suffix
	# possible by OpenID, but not used in fe_users: dob (date of birth), gender, language, timezone
	optionalInfo = nickname,email,fullname,postcode,country,prefix,firstname,lastname,suffix
	requiredInfo = nickname,email,fullname,postcode,country,prefix,firstname,lastname,suffix

	template_path = fileadmin/magenerator/dix_easylogin/
}

page.2 = USER_INT
page.2 {
	userFunc = tx_dixeasylogin_pi1->sendXrdsHeader
	pid < plugin.tx_dixeasylogin_pi1.pid_loginPage
}

[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.include_jQuery}]
	page.headerData.851 = TEXT
	page.headerData.851.value = <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>

	page.headerData.852 = TEXT
	page.headerData.852.value = <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

	page.headerData.853 = TEXT
	page.headerData.853.value = <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-lightness/jquery-ui.css" type="text/css" />
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.felogin}]
	plugin.tx_dixeasylogin_pi1.provider.5 >
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.google}]
	plugin.tx_dixeasylogin_pi1.provider.10 >
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.yahoo}]
	plugin.tx_dixeasylogin_pi1.provider.20 >
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.myopenid}]
	plugin.tx_dixeasylogin_pi1.provider.30 >
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.wordpress}]
	plugin.tx_dixeasylogin_pi1.provider.40 >
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.facebook}]
	plugin.tx_dixeasylogin_pi1.provider.50 >
[global]
[globalVar = LIT:1 = {$plugin.tx_dixeasylogin_pi1.disable.twitter}]
	plugin.tx_dixeasylogin_pi1.provider.60 >
[global]
