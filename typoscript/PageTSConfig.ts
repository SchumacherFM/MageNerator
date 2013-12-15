###########################################
# TemplaVoila
###########################################
TCEFORM.pages.tx_templavoila_to.removeItems = 1,3,4,5,6,7
TCEFORM.pages.tx_templavoila_ds.removeItems = 4

#-------------------------
# Header Bezeichnungen
#-------------------------
TCEFORM.tt_content.header_layout.altLabels {
	0 = No Header
	1 = Header H1 (gross)
	2 = Header H2 (mittel)
	3 = Header H3 (klein)
	4 = Header H4
	5 = Header H5
	6 = Header H1 (visuallyhidden)
}

#---------------------------------------------------------
# Standardwerte f�r den Zugriff bei neu erstellten Seiten
#---------------------------------------------------------
TCEMAIN.permissions {
    # userid = "ID des Users eintragen"
    groupid = 2

    user = show, editcontent, edit, delete, new
    group = show, editcontent, edit, delete, new
    everybody = show
}

templavoila.wizards.newContentElement.renderMode = tabs

#---------------------------------------------------
# disable content view when editing page properties
#---------------------------------------------------
TCEFORM.pages.tx_templavoila_flex.disabled = 0
TCEFORM.tt_content.tx_templavoila_flex.disabled = 0

#---------------------------------------------------------
# TCEForm Einstellungen
#---------------------------------------------------------
TCEFORM.tt_content {

	CType {
		removeItems = search
	}

	# Tab: General
	# CType.disabled = 1
#	header_position.disabled = 1
#	header_link.disabled = 1
#	date.disabled = 1
#	linkToTop.disabled = 1
#	sys_language_uid.disabled = 1

#	colPos.disabled = 1
#	imagecols.disabled = 1
#	spaceBefore.disabled = 1
#	spaceAfter.disabled = 1
#	section_frame.disabled = 1

	# Tab: Media
	#imageorient.disabled = 1
	imageorient {
#		removeItems = 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 17
	}
#	imageorient.disableNoMatchingValueElement = 26

	# image_link.disabled = 1

	image_zoom.disabled = 1
	image_noRows.disabled = 1
	image_effects.disabled = 1
	image_compression.disabled = 1
	imagecols.disabled = 1
	imageborder.disabled = 1
	imagewidth.disabled = 0
	imageheight.disabled = 1
	imagecaption.disabled = 1
	imagecaption_position.disabled = 1
	longdescURL.disabled = 1
	image_compression.disabled = 1
	image_effects.disabled = 1

	# Tab: Appearance
#	layout.disabled = 1
}

TCEFORM.pages {

	# TemplaVoila
	#tx_templavoila_flex.disabled = 1

	# Tab: General
#	tx_realurl_exclude.disabled = 1
#	tx_realurl_pathsegment.disabled = 1
	#content_from_pid.disabled = 1
#	nav_title.disabled = 1

	# Tab: Access
#	fe_login_mode.disabled = 1
	#fe_group.disabled = 1

	# Tab: Appearance
#	layout.disabled = 1
#	newUntil.disabled = 1
#	module.disabled = 1

	# Tab: Metadata
	abstract.disabled = 1
	#keywords.disabled = 1
	author.disabled = 1
	author_email.disabled = 1
	#description.disabled = 1
#	lastUpdated.disabled = 1

	# Tab: Behaviour
#	is_siteroot.disabled = 1
#	php_tree_stop.disabled = 1
#	alias.disabled = 1
#	no_search.disabled = 1
#	no_cache.disabled = 1
#	target.disabled = 1
#	cache_timeout.disabled = 1
#	l18n_cfg.disabled = 1
#	url_scheme.disabled = 1

	# Tab: Resources
#	media.disabled = 1

	# Tab: Resources
	#storage_pid.disabled = 1
	#tsconfig.disabled = 1

	# Extended
#	tx_weeaar_robotstxt.disabled = 1
	#tx_jbstopslide_stop.disabled = 1
}

#---------------------------------------------------
# RTE Konfiguration
#---------------------------------------------------
# Allowing <span> between <a></a>
# http://lists.typo3.org/pipermail/typo3-project-rte/2011-June/002267.html
# Usage of the class keep  <a class="button" href="#"><span class="keep">  Jetzt bestellen</span></a>
RTE.default {

  showButtons = *

  # define labels for headings
  buttons.formatblock.items {
    h4.label = Heading 4
  }
  hidePStyleItems = h5, h6

  proc.allowedClasses (
	link,doc,pdf,email,lightbox,keep
  )

  proc {

	exitHTMLparser_db = 1
	exitHTMLparser_db {
		allowTags < RTE.default.proc.allowTags
		tags {
			b.remap = strong
			i.remap = em
		}
	}
	exitHTMLparser_rte = 1
	exitHTMLparser_rte {
		allowTags < RTE.default.proc.allowTags
		tags {
			strong.remap = b
			em.remap = i
		}
	}
  }

}

RTE.default.classesAnchor = link,doc,pdf,email,lightbox,keep

RTE.classesAnchor >
RTE.classesAnchor {
	link {
		class = link
		type = url
		titleText = external link
	}
	keep {
		class = keep
		type = url
		titleText = internal link
	}
	pdf {
		class = pdf
		type = media
		titleText = PDF
	}
	doc {
		class = doc
		type = media
		titleText = Document
	}
	email {
		class = email
		type = mail
		titleText = Email
	}
	lightbox {
		class = lightbox
		type = url
		titleText = Lightbox
	}
}
