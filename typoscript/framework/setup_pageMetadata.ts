# -----------------------------------
# META TAG Definitions
# -----------------------------------

page.meta {
	keywords = {$meta.keywords}
	description = {$meta.description}
	language = de
	revisit-after = 7 days
	robots = index,follow
	lastmodified {
		data = page:lastUpdated
		if.isTrue.data = page:lastUpdated
		date = Y/m/d
		data = register : SYS_LASTCHANGED
		if >
	}
}

# -----------------------------------
# Favicon
# -----------------------------------
page.shortcutIcon = fileadmin/magenerator/favicon.ico
