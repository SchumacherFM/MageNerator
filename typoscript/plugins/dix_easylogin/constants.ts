plugin.tx_dixeasylogin_pi1 {
		# if jQuery, jQueryUI and the lightness theme should be included by default.
		# if turned off, you have to take care for yourself that the libraries are loaded (smart if other extensions also include jQuery)
	include_jQuery = 0

		# if the user should be created when not already found in the database
	allowCreate = 1

		# if the user should be able to connect his login with a login provider when already authenticated
	allowUpdate = 1

		# where the fe_users records should be stored when created
	user_pid = 5

		# when a user is created, he will get this usergroup(s)
	usergroup = 2

		# page where the "easylogin" plugin is located
		# used for the xrds definition
	pid_loginPage = 291

		# uid of the common login
	uid_felogin = 248

		# register a facebook app to get these two values
	facebook_appID = YOUR-APP-ID
	facebook_appSecret = YOUR-APP-SECRET

		# register a twitter app to get these two values
	twitter_consumerKey =
	twitter_consumerSecret =

		# enable or disable login methods
	disable.felogin = 1
	disable.google = 0
	disable.yahoo = 0
	disable.myopenid = 0
	disable.wordpress = 0
	disable.facebook = 1
	disable.twitter = 0

}