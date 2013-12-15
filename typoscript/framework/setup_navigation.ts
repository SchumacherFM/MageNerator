# --------------------------------------------
# Main (nav_main)
# --------------------------------------------
lib.nav_main {
	excludeUidList = 72,73,74

}

lib.brand = COA
lib.brand {
	10 = TEXT
	10.typolink.parameter = 202
	10.typolink.ATagParams = class="brand"><span class="brandLogo"></span
	10.typolink.ATagBeforeWrap = 1
	10.typolink.title = MageNerator.net - Magento Extension Template Generator
	10.typolink.wrap = <span class="hide-text">|</span>
}

lib.topnav = COA
lib.topnav {

	wrap = <ul class="nav">|</ul>

	20 = HMENU
	20 {
		special = directory
		special.value = 205

		1 < std_TMENU
		1 {
			wrap = |
			NO.stdWrap.wrap = <i class="{field:subtitle} icon-white"></i>&nbsp;|
			NO.stdWrap.wrap.insertData = 1

			ACT.wrapItemAndSub = <li class="active">|</li> |*| <li class="active">|</li> |*| <li class="last active">|</li>
			ACT.stdWrap.wrap = |

			CUR < .ACT

		}
	}
}


lib.topnavAbout = COA
lib.topnavAbout {

	wrap = <ul class="nav">|</ul>

	20 = HMENU
	20 {
		special = directory
		special.value = 268

		1 < std_TMENU
		1 {
			wrap = |
			# due to jQuery 1.8.0 we have to build it that way ...
			IFSUB.stdWrap.wrap = <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="{field:subtitle} icon-white"></i>&nbsp;|<b class="caret"></b></a>
			IFSUB.stdWrap.wrap.insertData = 1
        	IFSUB.wrapItemAndSub = <li class="dropdown">|</li> |*| <li class="dropdown">|</li> |*| <li class="dropdown last4">|</li>
            # IFSUB.ATagParams = class="dropdown-toggle"	data-toggle="dropdown"
            IFSUB.doNotLinkIt = 1
			ACTIFSUB < .IFSUB
		}
        2 < std_TMENU
        2 {
            wrap = <ul class="dropdown-menu">|</ul>
			NO.stdWrap.wrap = <i class="{field:subtitle}"></i>&nbsp;|
			NO.stdWrap.wrap.insertData = 1
            NO.ATagParams = class="fancyboxHtml" rel="nofollow"
            NO.additionalParams = &type=510
            CUR < .NO
        }

	}
}



lib.leftnav = HMENU
lib.leftnav {

	special = directory
    # template creator
	special.value = 247

	1 < std_TMENU
	1 {
		wrap = <ul class="nav nav-list grey">|</ul>
		NO.ATagParams = class="ajaxLoader" rel="nofollow"
#		NO.ATagParams.insertData = 1
        NO.doNotLinkIt.data = field:tx_magenerator_donotlinkit
        NO.doNotLinkIt.insertData = 1

		NO.additionalParams = &type=510
        NO.stdWrap.wrap = <i class="{field:subtitle}"></i>&nbsp;|
        NO.stdWrap.wrap.insertData = 1
		ACT.wrapItemAndSub = <li class="active">|</li> |*| <li class="active">|</li> |*| <li class="last active">|</li>
		ACT.stdWrap.wrap = |
		ACT.ATagParams = class="ajaxLoader" rel="nofollow"
		ACT.additionalParams = &type=510
		CUR < .ACT
	}
}

# package creator
[PIDinRootline = 259]
    lib.leftnav.special.value = 259
[global]




# link to login status, generate a link for ajax
lib.feuser = COA
lib.feuser {

	5 = HTML
	5.value = <div id="loginAjax" class="pull-right" data-url="

	10 = COA
	10 {
		10 = TEXT
		10.value = http://

		20 = TEXT
		20.data = getenv : HTTP_HOST

		25 = TEXT
		25.value = /

		40 = TEXT
		40.typolink.parameter = 248
		40.typolink.additionalParams = &type=510
		40.typolink.returnLast = url
		wrap = |
	}

	15 = HTML
	15.value = ">

	30 = HTML
	30.value = <img class="ajax" src="/fileadmin/magenerator/img/ajax.gif" alt="ajax loading"/>

	40 = HTML
	40.value = </div>

	wrap = |
}

# see extension magenerator template: Loggedout.html
# damned thing is that fluid does not allow multi arrays as additionalParams
# therefore we build here a TS COA
lib.feuserForgotPwLink = COA
lib.feuserForgotPwLink {
		40 = TEXT
		40.typolink.parameter = 245
		40.typolink.additionalParams = &type=510&tx_felogin_pi1%5Bforgot%5D=1
		40.typolink.returnLast = url
		wrap = |

}

lib.feuserLogoutLink = COA
lib.feuserLogoutLink {
		40 = TEXT
		40.typolink.parameter = 245
		40.typolink.additionalParams = &type=510&logintype=logout
		40.typolink.returnLast = url
		wrap = |

}


lib.nav_footerleft = COA
lib.nav_footerleft {

	wrap = |

	20 = HMENU
	20 {
		special = directory
		special.value = 240

		1 < std_TMENU
		1 {
			wrap = |
			IFSUB.stdWrap.wrap = <h2>|</h2>
        	IFSUB.wrapItemAndSub = | |*| | |*| |
            IFSUB.doNotLinkIt = 1
			ACTIFSUB < .IFSUB

		}
        2 < std_TMENU
        2 {
            wrap = <ul class="unstyled footer">|</ul>
			NO.stdWrap.wrap = <i class="{field:subtitle} icon-white"></i>&nbsp;|
			NO.stdWrap.wrap.insertData = 1
            CUR < .NO
        }

	}
}

lib.nav_footerright < lib.nav_footerleft
lib.nav_footerright.20.special.value = 285

[globalVar = TSFE:id = 204,273] or [PIDinRootline=204]
    lib.nav_footermiddle = TEXT
    lib.nav_footermiddle.value = &nbsp;
[else]
    # avoiding the error that details is not configured when calling the details page
    lib.nav_footermiddle = RECORDS
    lib.nav_footermiddle {
        tables = tt_content
        # Inhaltselement mit ID 42 ist Quelle
        source = 1760
    }

[end]