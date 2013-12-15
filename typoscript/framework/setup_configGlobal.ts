# only for debugging and development
#---------------------------
config.no_cache = 0

# -----------------------------------
# Cache settings
# -----------------------------------
config.cache_period = 7200
config.cache_clearAtMidnight = 1

# -----------------------------------
# URL settings
# -----------------------------------
config.typolinkCheckRootline=1
config.extTarget = _blank
config.intTarget = _top

# -----------------------------------
# XHTML Transitional
# -----------------------------------
config.xhtml_cleaning = all
config.doctype = xhtml_trans
config.htmlTag_langKey = en
config.xmlprologue = none

# -----------------------------------
# Charset Settings
# -----------------------------------
page.config.metaCharset = utf-8
page.config.renderCharset = utf-8
page.config.additionalHeaders = Content-Type:text/html;charset=utf-8

# -----------------------------------
# Define global link Vars
# -----------------------------------
# L for langauage, S for size
config.linkVars = L, S

# -----------------------------------
# Simulate Static Config
# -----------------------------------
#config.simulateStaticDocuments = 1
#config.pageTitleFirst=1
#config.simulateStaticDocuments_addTitle=30
#config.simulateStaticDocuments_noTypeIfNoTitle=1
#config.simulateStaticDocuments_pEnc = md5
#config.simulateStaticDocuments_pEnc = base64
#config.simulateStaticDocuments_pEnc_onlyP = backPid, tt_news, cHash, L, print, pS, pL, arc, cat, begin_at, swords

# -----------------------------------
# Other configurations
# -----------------------------------
config.admPanel = 0
config.removeDefaultJS = external
config.inlineStyle2TempFile = 1
# config.spamProtectEmailAddresses = 3
config.setJS_mouseOver = 0
