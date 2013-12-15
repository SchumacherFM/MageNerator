# -----------------------------------------
# Translating hardcoded text in FCE
# -----------------------------------------
tt_content.stdWrap.dataWrap = |
tt_content.stdWrap.innerWrap >

lib.stdheader.stdWrap.dataWrap >
lib.stdheader.2.headerStyle >
lib.stdheader.3.headerClass >

# class="bodytext" bei RTE abstellen
lib.parseFunc_RTE.nonTypoTagStdWrap.encapsLines.addAttributes.P.class >

# Ummantelung mit <p> bei folgenden Tags verhindern
lib.parseFunc_RTE.nonTypoTagStdWrap.encapsLines.encapsTagList = cite, div, p, pre, hr, h1, h2, h3, h4, h5, h6, table, tr, td

#p bei Tabellenzellen entfernen
lib.parseFunc_RTE.externalBlocks.table.stdWrap.HTMLparser.removeTags = p

#Klassen in Tabellen zulassen
lib.parseFunc_RTE.externalBlocks.table.stdWrap.HTMLparser.tags.table.fixAttrib.class.list >
