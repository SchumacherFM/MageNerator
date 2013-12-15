# -----------------------------------
# Page Init
# -----------------------------------

page = PAGE
page.typeNum = 0

page.10 = USER
page.10.userFunc = tx_templavoila_pi1->main_page
page.10.disableExplosivePreview = 1

robots = PAGE
robots {
  typeNum = 201
  10 >
  10 < plugin.tx_weeaarrobotstxt_pi1
  10.pid_list = 47
  config {
    disableAllHeaderCode = 1
    additionalHeaders = Content-type:text/plain
    no_cache = 0
  }
}

favicon = PAGE
favicon {
  typeNum = 207
  10 >
  10 < plugin.tx_favicon_pi1
  10.favicon  = fileadmin/magenerator/favicon.ico
  config {
    disableAllHeaderCode = 1
#    additionalHeaders = Content-type:image/ico
    additionalHeaders = Content-type:image/x-icon
#    additionalHeaders = Content-type:text/plain
    no_cache = 0
  }
}

# remove this if we have evaluated the community extension
# to see if there is also a login procedure
pageContentNoCache = PAGE
pageContentNoCache {
  typeNum = 510
  10 >
  10 < styles.content.get
  config {
    disableAllHeaderCode = 1
    no_cache = 0
    admPanel = 0
    debug = 0
  }
}

# because we have other FCEs on this page we need the solely output of this modul
magePlugInContact < pageContentNoCache
magePlugInContact {
  typeNum = 510001
  10 >
  10 < tt_content.list.20.magenerator_contactcenter
}

plugin.tx_ptextlist._CSS_DEFAULT_STYLE >

# -----------------------------------
# Include framework setup TypoScript
# -----------------------------------
# We don't need the global config for both websites, this is done with the common config
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_configGlobal.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_language.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_languageMenu.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_navigation.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_pageContent.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_pageMetadata.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_resize.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/framework/setup_ttContent.ts">

# -----------------------------------
# Include plugins setup TypoScript
# -----------------------------------
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/plugins/dix_easylogin/setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/plugins/news/setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/plugins/setup_seo_basics.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/plugins/setup_felogin.ts">

<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/plugins/html5boilerplate_config/setup.txt">
<INCLUDE_TYPOSCRIPT: source="FILE:fileadmin/ts/magenerator/plugins/html5boilerplate_includes/setup.txt">
