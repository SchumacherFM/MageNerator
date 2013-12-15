plugin.tx_felogin_pi1 {
        #storagePid - where are the user records? use single value or a commaseperated list
    storagePid = 5
    recursive = 0

        #Template File
    templateFile = fileadmin/magenerator/felogin.html

        #baseURL for the link generation
    feloginBaseURL =

        #wrapContentInBaseClass
    wrapContentInBaseClass = 0


        #typolink-configuration for links / urls
        #parameter and additionalParams are set by extension
    linkConfig {
        target = _top
    }

        #preserve GET vars - define "all" or commaseperated list of GET-vars that should be included by link generation
    preserveGETvars = all


        #additional fields
    showForgotPasswordLink = 0
    showPermaLogin = 1

    # time in hours how long the link for forget password is valid
    forgotLinkHashValidTime = 12

    newPasswordMinLength = 6

    welcomeHeader_stdWrap {
        wrap = <h3>|</h3>
    }
    welcomeMessage_stdWrap {
        wrap = <div>|</div>
    }

    successHeader_stdWrap {
        wrap = <h3 class="successReload">|</h3>
    }
    successMessage_stdWrap {
        wrap = <div>|</div>
    }

    logoutHeader_stdWrap {
        wrap = <h3 class="successReload">|</h3>
    }
    logoutMessage_stdWrap {
        wrap = <div>|</div>
    }

    errorHeader_stdWrap {
        wrap = <h3>|</h3>
    }
    errorMessage_stdWrap {
        wrap = <div>|</div>
    }

    forgotHeader_stdWrap {
        wrap = <h3>|</h3>
    }
    forgotMessage_stdWrap {
        wrap = <div>|</div>
    }

    changePasswordHeader_stdWrap {
        wrap = <h3>|</h3>
    }
    changePasswordMessage_stdWrap {
        wrap = <div>|</div>
    }

    cookieWarning_stdWrap {
        wrap = <p style="color:red; font-weight:bold;">|</p>
    }

    # stdWrap for fe_users fields used in Messages
    userfields {
        username {
            htmlSpecialChars = 1
            wrap = <strong>|</strong>
        }
    }

    #redirect, we use an ajax reloader
    redirectMode = logout
    redirectFirstMethod =
    redirectPageLogin =
    redirectPageLoginError =
    redirectPageLogout = 202

    #disable redirect with one switch
    redirectDisable = 0

    email_from = webmaster [AT] MageNerator.net
    email_fromName = MageNerator.net
    replyTo = webmaster [AT] MageNerator.net


    # Allowed Referrer-Redirect-Domains:
    domains = www.magenerator.net,magenerator.net,magenerator.local

    # Show logout form direct after login
    showLogoutFormAfterLogin = 0

    dateFormat = Y-m-d H:i
}

plugin.tx_felogin_pi1._CSS_DEFAULT_STYLE >

plugin.tx_felogin_pi1._LOCAL_LANG {

	de {
		ll_welcome_header = MageNerator Creator Anmeldung
        ll_welcome_message = Geben Sie Ihre E-Mail Adresse und Ihr Passwort ein, um sich an der Website anzumelden.
        username = E-Mail Adresse:
	}
	en {
		ll_welcome_header = MageNerator Creator Login
        ll_welcome_message = Enter your email address and password here in order to log in on the website.
        username = E-Mail address:
	}
	es {
		ll_welcome_header = ES: MageNerator Creator Login
        ll_welcome_message = ES: Enter your email address and password here in order to log in on the website.
        username = Dirección de correo electrónico:
	}
	fr {
		ll_welcome_header = FR: MageNerator Creator Login
        ll_welcome_message = FR: Enter your email address and password here in order to log in on the website.
        username = Adresse e-mail:
	}
}

plugin.tx_felogin_pi1._DEFAULT_PI_VARS {
}
