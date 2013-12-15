lib.nav_language = HMENU
lib.nav_language {
	# Ein Sprach-Menue wird erzeugt
	special = language
	# Reihenfolge und Auswahl der Sprachen im Menue
	special.value = 0,1,2,3
	special.normalWhenNoLanguage = 0
	wrap = <ul class="dropdown-menu">|</ul>
	1 = TMENU
	1 {
		noBlur = 1
		# Standard Sprachen
		NO = 1
		NO {
			linkWrap = <li>|</li>
			# Standard-Titel fuer den Link waere Seitenttitel
			# => anderer Text als Link-Text (Optionsschift)
			stdWrap.override = <span class="flag de"></span>Deutsch || <span class="flag en"></span>English || <span class="flag fr"></span>Français || <span class="flag es"></span>Español
			# Standardmaessige Verlinkung des Menues ausschalten
			# Da diese sonstige GET-Parameter nicht enthaelt
			doNotLinkIt = 1
			# Nun wird der Link mit den aktuellen GET-Parametern neu aufgebaut
			stdWrap.typolink.parameter.data = page:uid
			stdWrap.typolink.additionalParams = &L=0 || &L=1 || &L=2 || &L=3
			stdWrap.typolink.addQueryString = 1
			stdWrap.typolink.addQueryString.exclude = L,id,cHash,no_cache
			stdWrap.typolink.addQueryString.method = GET
			stdWrap.typolink.useCacheHash = 1
			stdWrap.typolink.no_cache = 0
		}
		# Aktive Sprache
		ACT < .NO
		ACT.linkWrap = <li class="active">|</li>
		# NO + Uebersetzung nicht vorhanden
		USERDEF1 < .NO
		# ACT + Uebersetzung nicht vorhanden
		USERDEF2 < .ACT
	}
}
