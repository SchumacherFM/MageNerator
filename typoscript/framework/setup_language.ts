# -----------------------------------
# Language Settings
# -----------------------------------
config{
	sys_language_mode = content_fallback
	linkVars = L
	locale_all = de_DE.UTF-8
	language = de
	htmlTag_langKey = de-DE
	sys_language_uid = 0

	# Records that are not locallized till be hidden
	sys_language_overlay = hideNonTranslated
}

[globalVar = GP:L = 1]
	config {
		sys_language_uid = 1
		language = en
		locale_all = en_US.UTF-8
		htmlTag_langKey = en-EN
	}
[global]

[globalVar = GP:L = 2]
	config {
		sys_language_uid = 2
		language = fr
		locale_all = fr_FR.UTF-8
		htmlTag_langKey = fr-FR
	}
[global]

[globalVar = GP:L = 3]
	config {
		sys_language_uid = 3
		language = es
		locale_all = es_ES.UTF-8
		htmlTag_langKey = es-ES
	}
[global]
