#----------------------------
# Page Title Changer
#----------------------------
config.noPageTitle = 2
page.headerData.5 >

lib.footercopy = COA
lib.footercopy {
	10 = HTML
	10.value (
		<h4 class="copy">&copy; Copyright 2012 MageNerator.net</h4>
		<a class="copy" href="http://www.magentocommerce.com/license/" target="_blank">Magento &reg; is a registered trademark of Irubin Consulting Inc.</a>
	)
	wrap = |
}

#--------------------------------------------
# Piwik Anylytics
#--------------------------------------------
#// tracker.setCookieDomain('*.magenerator.net');
#// tracker.setDomains('*.magenerator.net');

page.footerData.434234 = HTML
page.footerData.434234.value (
<!-- We do not trust Google Analytics, therefore we have Piwik on our servers and no data will be given away :-) -->
<!-- my Piwik Tracking Code -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.magenerator.net/" : "http://piwik.magenerator.net/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
	var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 3);
	piwikTracker.trackPageView();
	piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://piwik.magenerator.net/piwik.php?idsite=3" style="border:0" alt="" /></p></noscript>
<!-- End my Piwik Tracking Code -->
)

# disable local piwik tracking
[globalString = ENV:HTTP_HOST=magenerator.local]
	page.footerData.434234 >
[global]

 ######               ######
 #     #   ##   #   # #     #   ##   #
 #     #  #  #   # #  #     #  #  #  #
 ######  #    #   #   ######  #    # #
 #       ######   #   #       ###### #
 #       #    #   #   #       #    # #
 #       #    #   #   #       #    # ######

lib.paypal = COA
lib.paypal {
    10 = HTML
    10.value = <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" class="generatedExtensions">
    20 = HTML
    20.value = <input type="hidden" name="cmd" value="_s-xclick">
    30 = HTML
    30.value = <input type="hidden" name="hosted_button_id" value="BSGHKMYRYAAK8">
    40 = HTML
    40.value = <input type="image" src="/fileadmin/magenerator/img/paypal/donate_de.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen mit PayPal.">
    50 = HTML
    50.value = </form>
    wrap = |
}

# english
[globalVar = GP:L = 1]
    lib.paypal.30.value = <input type="hidden" name="hosted_button_id" value="7AFQXJHPA28Z8">
    lib.paypal.40.value = <input type="image" src="/fileadmin/magenerator/img/paypal/donate_en.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
[global]

# french
[globalVar = GP:L = 2]
    lib.paypal.30.value = <input type="hidden" name="hosted_button_id" value="U4YFWRKXP5A2E">
    lib.paypal.40.value = <input type="image" src="/fileadmin/magenerator/img/paypal/donate_fr.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
[global]

# spanish
[globalVar = GP:L = 3]
    lib.paypal.30.value = <input type="hidden" name="hosted_button_id" value="EDV867GCT3EMN">
    lib.paypal.40.value = <input type="image" src="/fileadmin/magenerator/img/paypal/donate_es.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
[global]
                                                           #####
###### #    # ##### ###### #    #  ####  #  ####  #    # #     #  ####  #    # #    # #####
#       #  #    #   #      ##   # #      # #    # ##   # #       #    # #    # ##   #   #
#####    ##     #   #####  # #  #  ####  # #    # # #  # #       #    # #    # # #  #   #
#        ##     #   #      #  # #      # # #    # #  # # #       #    # #    # #  # #   #
#       #  #    #   #      #   ## #    # # #    # #   ## #     # #    # #    # #   ##   #
###### #    #   #   ###### #    #  ####  #  ####  #    #  #####   ####   ####  #    #   #
lib.extensionCount = COA
lib.extensionCount {
  10 = CONTENT
  10{
    table = tx_magenerator_domain_model_generatecounter
    select {
        selectFields = count(*) counter
        pidInList = 0,5
        where =
    }

    renderObj = COA
    renderObj{
        wrap = <h4>|</h4>
        10 = TEXT
        10.field = counter
    }
  }

	30 = TEXT
	30.value = Generated Extensions
	wrap = |
}

lib.rightBanner = COA
lib.rightBanner {
	10 = TEXT
	10.value = <li><h3>

	20 = TEXT
	20.value = Demo-Partner

	30 = TEXT
	30.value (
				</h3></li>

				<li>
                    <a href="http://www.unic.com" target="_blank" title="Unic AG" class="thumbnail">
                        <img src="/fileadmin/magenerator/sponsors/unic_ag.gif" alt="Unic AG">
                    </a>
                    Space available!
				</li>
				<li>
                    <a href="http://www.typo3.org" target="_blank" title="TYPO3" class="thumbnail">
                        <img src="/fileadmin/magenerator/sponsors/typo3.png" alt="TYPO3">
                    </a>
                    Space available!
				</li>
				<li>
                    <a href="http://www.magentocommerce.com/" target="_blank" title="Magento - eCommerce Software for Growth" class="thumbnail">
                        <img src="/fileadmin/magenerator/sponsors/magento.gif" alt="Magento">
                    </a>
                    Space available!
				</li>

	)
	wrap = <ul class="thumbnails partner">|</ul>
}

[globalVar = GP:L = 1]
	lib.rightBanner.20.value = Sponsors
[global]

[globalVar = GP:L = 2]
	lib.rightBanner.20.value = Sponsors FR
[global]

[globalVar = GP:L = 3]
	lib.rightBanner.20.value = Sponsors ES
[global]
