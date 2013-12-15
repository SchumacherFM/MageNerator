plugin.tx_news {
	rss.channel {
		title = MageNerator.net
		description =
		link = http://magenerator.net
		language = en_GB
		copyright = MageNerator.net
		category =
		generator = TYPO3 EXT:news
	}
	view {
        templateRootPath = EXT:magenerator/html/news/Templates/
        partialRootPath = EXT:magenerator/html/news_footer/Partials/
        layoutRootPath = EXT:magenerator/html/news/Layouts/
	}

    settings {
        cssFile = FILE:fileadmin/magenerator/news/news-basic.css
    }
}