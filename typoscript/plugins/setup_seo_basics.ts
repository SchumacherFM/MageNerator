plugin.tx_seobasics >
plugin.tx_seobasics = COA
plugin.tx_seobasics {

	# Append a line break for every header tag
	5 = TEXT
	5.value (

	
)

	# Building the page title
	10 = TEXT 
	10.data = page:tx_seo_titletag // page:title
	10.trim = 1
	10.stdWrap.stdWrap.append = TEXT
	10.stdWrap.stdWrap.append.data = TSFE:tmpl|sitetitle
	10.stdWrap.stdWrap.append.trim = 1
	10.stdWrap.stdWrap.append.required = 1
	10.stdWrap.stdWrap.append.if.isTrue = {$plugin.tx_seo.titleWrapAppendSiteTitle}
	10.stdWrap.stdWrap.append.noTrimWrap = | - ||
	10.stdWrap.noTrimWrap = {$plugin.tx_seo.titleWrap}
	10.stdWrap.insertData = 1
	10.htmlSpecialChars = 1
	10.wrap = <title>|</title>
	10.append < .5

	20 < .10
	20.wrap = <meta name="title" content="|" />


	# Building the Keywords tag
	30 = TEXT 
	30 < .10
	30.data = page:keywords
	30.stdWrap.noTrimWrap = {$plugin.tx_seo.keywordsWrap}
	30.stdWrap.stdWrap >
	30.required = 1
	30.wrap = <meta name="keywords" content="|" />


	# Building the Description tag
	40 = TEXT
	40 < .30
	40.data = page:description
	40.stdWrap.noTrimWrap = {$plugin.tx_seo.descriptionWrap}
	40.wrap = <meta name="description" content="|" />


	# Building the date tag (last changed)
	50 = TEXT
	50 < .10
	50.data = page:SYS_LASTCHANGED // page:crdate
	50.date = Y-m-d
	50.stdWrap >
	50.wrap = <meta name="date" content="|" />

	# Building the canonical tag
	60 = TEXT
	60 < .10
	60.data >
	60.cObject = USER
	60.cObject.userFunc = tx_seobasics->getCanonicalUrl
	#60.cObject.useDomain = current
	60.required = 1
	60.if.isTrue = {$plugin.tx_seo.enableCanonicalTag}
	60.stdWrap >
	60.wrap = <link rel="canonical" href="|" />
}


# activate SEO in main "page." configuration
config.noPageTitle = 2
page.headerData.776 = < plugin.tx_seobasics


# Include sitemap.xml
includeLibs.tx_seobasics_sitemap = EXT:seo_basics/class.tx_seobasics_sitemap.php
tx_seo_xmlsitemaps = PAGE
tx_seo_xmlsitemaps {
	typeNum = 776
	config.disableAllHeaderCode = 1
	config.renderCharset = UTF-8
	config.xmlprologue = xml_10
	config.additionalHeaders = Content-type: text/xml
	config.xhtml_cleaning = 0
	10 = USER
	10.userFunc = tx_seobasics_sitemap->renderXMLSitemap
}