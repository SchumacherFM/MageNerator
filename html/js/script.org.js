/****************************************************************************
 *    Copyright Cyrill@Schumacher.fm for MageNerator.net
 ****************************************************************************/

/****************************************************************
 * VALIDATOR
 **/
(function ($, document) {

    $.validator.addMethod("requiredVersionNo", function (value, element) {
        return /^[0-9]+\.[0-9]+\.[0-9]+$/.test(value);
    }, $.validator.messages.required);

    $.validator.addMethod(
        "multiLangDate", // so cool! matches all date formats, also in php implemented
        function (value, element) {
            var bits = value.match(/([0-9]+)/gi), str;
            if (!bits) {
                return this.optional(element) || false;
            }
            var today = new Date(),
                part0 = parseInt(bits[0]),
                part1 = parseInt(bits[1]),
                part2 = parseInt(bits[2]),
                year = today.getYear() + 1900,
                day2 = 0, month2 = 0, year2 = 0;

            // us date
            if (part0 >= 1 && part0 <= 12 &&
                part1 >= 1 && part1 <= 31 &&
                part2 >= year
                ) {
                month2 = part0;
                day2 = part1;
                year2 = part2;
            }

            // uk date
            if (part0 >= 1 && part0 <= 31 &&
                part1 >= 1 && part1 <= 12 &&
                part2 >= year
                ) {
                month2 = part1;
                day2 = part0;
                year2 = part2;
            }

            // de date 2012.12.31
            if (part2 >= 1 && part2 <= 31 &&
                part1 >= 1 && part1 <= 12 &&
                part0 >= year
                ) {
                month2 = part1;
                day2 = part2;
                year2 = part0;
            }

            if (month2 == 0 || day2 == 0 || year2 == 0) {
                return this.optional(element) || false;
            }

            str = month2 + '/' + day2 + '/' + year2;

            var userDate = new Date(str);
            var newReturn = this.optional(element) || !/Invalid|NaN/.test(userDate);

            newReturn = ( newReturn && userDate > today );

            return newReturn;

        },
        "Please enter a date in the format dd/mm/yyyy"
    );

    var ValiErrorPlacement = function (error, element) {

        if (element.next('i').length == 0) {
            element.after('<i class="icon-exclamation-sign left1em"></i>');
        }
        var ppGroup = element.parent('div.controls').parent('div.control-group');
        ppGroup.addClass('error');
    };

    var ValiUnhighlight = function (element, errorClass) {
        var $element = $(element);
        $element.next('i').remove();
        var ppGroup = $element.parent('div.controls').parent('div.control-group');
        ppGroup.removeClass('error');
    }

    var formSelect2Selected = function () {

        $('form select[multiple="multiple"] option').each(function (i, v) {
            if ($(v).text() != '') {
                $(v).attr("selected", "selected");
            }
        });
    }

    /**
     * VALIDATOR
     ***************************************************************/

    /* global RSA pub key storage */
    var rsaStorage = {};
    rsaStorage.n = '';
    rsaStorage.e = '';

    var initDocumentGeneral = function () {
        jQuery.easing.def = 'easeInOutQuad';

        // adjust height of body > div.container-fluid
        var height = $(window).height() + 38;

        if (height > 600) {
            $('body > div.container-fluid').css('height', height + 'px');
        }

    }

    var piwikAjaxTracker = function (url, title) {
        if (typeof piwikTracker !== 'undefined') {
            piwikTracker.setCustomUrl(url);
            piwikTracker.setDocumentTitle(title);
            piwikTracker.trackPageView();
            piwikTracker.enableLinkTracking();
        }
    }

    var rsaEncryptField = function (fieldId) {

        var retVal = false;

        if (typeof RSAKey != 'function') {
            alert('RSA encryption functions not loaded! Missing rsa.js ...');
            return retVal;
        }

        var $rsaN = $('input#rsa_n');
        var $rsaE = $('input#rsa_e');
        var $password = $(fieldId);

        if ($rsaN.val() != '' && $rsaE.val() != '') {
            rsaStorage.n = $rsaN.val();
            rsaStorage.e = $rsaE.val();
            $rsaN.val('');
            $rsaE.val('');
        }

        // if the form submission results in an error then don't reencrypt the pw
        if ($password.val().search(/^rsa:/) !== -1) {
            return true;
        }

        var rsa = new RSAKey();
        rsa.setPublic(rsaStorage.n, rsaStorage.e);

        var res = rsa.encrypt($password.val());
        // Remove all plaintext-data. This will also prevent plain text authentication.
        $password.val('');

        if (res && res != '') {
            $password.val('rsa:' + hex2b64(res));
            retVal = true;
        } else {
            alert('RSA encryption of your password in field: ' + fieldId + ' failed! Hacking...?');
        }

        return retVal;
    }

    /***********************************************************
     * reload login status box
     */
    var fCloseReloadLoginStatus = function () {
        $.fancybox.close();
        $('.mainLoginStatus').remove();
        ajaxLoginStatus();
    }
    /***********************************************************
     * ajax login form poster and used for registering a user
     */
    var MageAjaxFeLogin = function () {

        var $form = $('#mageloginform');
        var action = $form.attr('action');
        var prefix = $form.attr('id');

        var isCrypted = rsaEncryptField('input#pass');

        if (!isCrypted) {
            return false;
        }

        $('.' + prefix + '_ajax').css('display', 'block');
        $.ajax({
            type : 'POST',
            url : action,
            data : $.param($('#mageloginform').serializeArray()),
            success : function (data) {
                if (data.search(/successReload/g) != -1) {
                    fCloseReloadLoginStatus();
                    piwikAjaxTracker(action, 'Login Post Data');
                } else {
                    $('.MageAjaxContainer').remove();
                    $('.fancybox-inner').append(data);
                }
            }
        });

    }
    /************************************************************************
     * Register Form
     ************************************************************************/
    var ajaxUserFormValidRulez = {
        errorPlacement : ValiErrorPlacement,
        unhighlight : ValiUnhighlight,
        rules : {
            'tx_magenerator_mageusers[feUser][email]' : {
                required : true,
                email : true
            },
            /*            'tx_magenerator_mageusers[feUser][firstName]' : {
             required: true,
             minlength: 2
             },
             'tx_magenerator_mageusers[feUser][lastName]' : {
             required: true,
             minlength: 2
             },
             'tx_magenerator_mageusers[feUser][namespace]' : {
             required: true,
             minlength: 2
             }, */
            'tx_magenerator_mageusers[feUser][password]' : {
                required : true,
                minlength : 6
            },
            'tx_magenerator_mageusers[password2]' : {
                required : true,
                minlength : 6,
                equalTo : "#userPass"
            },
            'tx_magenerator_mageusers[invite]' : {
                required : true,
                minlength : 8
            }
        }
    };

    var ajaxUserFormSubmit = function () {

        var formId = '#mageUserForm';
        var pw1Id = 'input#userPass';
        var pw2Id = 'input#userPass2';

        var $form = $(formId);
        var action = $form.attr('action');
        var prefix = $form.attr('id');

        // if the password isn't an rsa string then add the equal rule
//    if( $(pw2Id).val().search(/^rsa:/) != -1 ){
//        delete ajaxUserFormValidRulez.rules['tx_magenerator_mageusers[password2]'].equalTo;
//    }

        // if the user edits his/her forms and want not to update the password
        // check here if its blank and if so do not check it.
        var rsaEncrypt = true;
        if (action.search(/=save/) != -1 && $(pw1Id).val() == '' && $(pw2Id).val() == '') {
            delete ajaxUserFormValidRulez.rules['tx_magenerator_mageusers[feUser][password]'].required;
            delete ajaxUserFormValidRulez.rules['tx_magenerator_mageusers[password2]'].required;
            rsaEncrypt = false;
        }
        $form.validate(ajaxUserFormValidRulez);

        if (!$form.valid()) {
            return false;
        }

        if (rsaEncrypt) {
            var isCrypted = rsaEncryptField(pw1Id);
            if (!isCrypted) {
                return false;
            }

            isCrypted = rsaEncryptField(pw2Id);
            if (!isCrypted) {
                return false;
            }
        }

//      just for testing
//    $form.submit();
//    return true;

        var timeOuter = null;

        $('.' + prefix + '_ajax').css('display', 'block');
        $form.fadeOut('slow');
        $.ajax({
            type : 'POST',
            url : action,
            data : jQuery.param(jQuery(formId).serializeArray()),
            success : function (data) {

//console.log(data);

                var dataJson = $.parseJSON(data);

                if (dataJson && dataJson.flashMessageContainer) {
                    console.log('flashMessageContainer', dataJson.flashMessageContainer);
                }

                $('.' + prefix + '_ajax').hide();
                if (dataJson.isError == true || dataJson.isError == 1) {
                    $form.fadeIn('slow');
                } else {
                    $form.remove();
                }

                handleAlertBox(dataJson, {noFadeOut : true});

                if (timeOuter) {
                    window.clearTimeout(timeOuter);
                }

                if (!dataJson.isError) {
                    timeOuter = window.setTimeout(function () {
                        fCloseReloadLoginStatus();
                        piwikAjaxTracker(action, 'Register Post Data');
                    }, 7000);
                } else {
                    // eachtime an error occured reset the password
                    // due to RSA and validator class ... there is a bug ...
                    $(pw1Id).val('');
                    $(pw2Id).val('');
                }

            }
        });

    }
// register form END

    /************************************************************************
     * Contact Form
     ************************************************************************/

    var ajaxContactFormSubmit = function () {
        var formId = '#mageContactForm';

        var $form = $(formId);
        var action = $form.attr('action');
        var prefix = $form.attr('id');

        var validRulez = {
            errorPlacement : ValiErrorPlacement,
            unhighlight : ValiUnhighlight,
            rules : {
                'tx_magenerator_contactcenter[contact][email]' : {
                    required : true,
                    email : true
                },
                'tx_magenerator_contactcenter[contact][firstName]' : {
                    required : true,
                    minlength : 2
                },
                'tx_magenerator_contactcenter[contact][lastName]' : {
                    required : true,
                    minlength : 2
                },
                'tx_magenerator_contactcenter[contact][subject]' : {
                    required : true,
                    minlength : 5
                },
                'tx_magenerator_contactcenter[contact][message]' : {
                    required : true,
                    minlength : 10
                }
            }
        };


        $form.validate(validRulez);

        if (!$form.valid()) {
            return false;
        }

//      just for testing
//    $form.submit();
//    return true;

        $('.' + prefix + '_ajax').css('display', 'block');
        $form.fadeOut('slow');
        $.ajax({
            type : 'POST',
            url : action,
            data : jQuery.param(jQuery(formId).serializeArray()),
            success : function (data) {

//console.log(data);

                var dataJson = $.parseJSON(data);

                if (dataJson && dataJson.flashMessageContainer) {
                    console.log('flashMessageContainer', dataJson.flashMessageContainer);
                }

                $('.' + prefix + '_ajax').hide();
                if (dataJson.isError == true || dataJson.isError == 1) {
                    $form.fadeIn('slow');
                } else {
                    $form.remove();
                }

                handleAlertBox(dataJson, {noFadeOut : true});
                piwikAjaxTracker(action, 'Contact Post Data');
            }
        });


    }
// end contact form

    var ajaxLoaderImgW = $('<img/>', {src : '/fileadmin/magenerator/img/ajaxWhiteSmall.gif', 'width' : 16, 'height' : 16, 'class' : 'ajaxLoaderImgW'});

    var ajaxLoginStatus = function () {
        /*
         * Load login status via ajax
         **/
        var $loginAjaxElem = $('#loginAjax');
        var loginStatusUrl = $loginAjaxElem.data('url');
        if (loginStatusUrl && loginStatusUrl.search(/\/login-status\//) != -1) {
            $.get(loginStatusUrl, function (htmlData) {
                $('img.ajax').hide();
                $loginAjaxElem.append(htmlData);
                // does not really make sense
                // piwikAjaxTracker(loginStatusUrl,'Login Status');
            });
        } else {
            $('img.ajax').hide();
            $loginAjaxElem.append('<b>Ajax Login Status Error E95</b>');
        }
    }
    /*end ajaxLoginStatus*/

    var getHashFromHref = function (currentATagObj) {
        var regPage = /\/([a-z0-9\-_]{4,})\//i;
        regPage.exec(currentATagObj.href);
        return RegExp.$1;
    }

    var getHash = function () {
        return window.location.hash.replace(/[^a-z0-9\-_]+/, '');
    }

    var setPageIdentifiers = function (currentATagObj) {
        var nHash = getHashFromHref(currentATagObj);
        $(currentATagObj).addClass(nHash);
        window.location.hash = nHash;
    }

    var buttonSaveElements = function (that) {
        var nHash = getHash();
        $('a.' + nHash).before('<i class="icon-ok pull-left"></i>');
    }

    var removeChars = function () {
        var $that = $(this);
        var txt = $that.val();
        $that.val(txt.replace(/[^a-z0-9_\n]+/ig, ''));
    }

    var removeCharsVersion = function () {
        var $that = $(this);
        var txt = $that.val();
        $that.val(txt.replace(/[^0-9\.]+/ig, ''));
    }


    var LoadSysExtName = function (event) {
        var $that = $(this);
        var selectTargetId = $that.data('target');
        var val = parseInt($that.val());

        if (val <= 0) {
            return false;
        }

        var href = $('a.ajaxExtNamesFormHref').attr('href').replace(/123456789/, val);

        $.getJSON('/' + href, function (response) {

            $('div.' + selectTargetId).show();

            var selectElement = $('#' + selectTargetId);
            selectElement.empty();
            $.each(response, function (index, caption) {

                selectElement.append('<option value="' + index + '">' + caption + '</option>');

            });
            piwikAjaxTracker(href, 'Load System Extensions');
        });
    }

    var subRoutineAddToSelected = function (targetSelectId, value) {
        var addMe = 1;
        $('#' + targetSelectId + ' option').each(function (i, v) {
            if ($(v).text() == '') {
                $(v).remove();
            }

            if ($(v).val() == value) {
                addMe = 0;
            }
        });

        if (addMe == 1) {
            $('#' + targetSelectId).append('<option value="' + value + '">' + value + '</option>');
        }
    }

    var addTwoSelectsToSelected = function (e) {
        e.preventDefault();
        var $that = $(this);

        var targetSelectId = $that.data('target'),
            extNsId = $that.data('ens'),
            extNameId = $that.data('ename');

        // var IdExt = parseInt($('#'+extNsId).val()) + '_' + parseInt($('#'+extNameId).val());
        var TxtExt = $('#' + extNsId + ' option:selected').text() + '_' + $('#' + extNameId + ' option:selected').text();
        subRoutineAddToSelected(targetSelectId, TxtExt);
    }

    var addTextfieldToSelected = function (targetSelectId, textfieldId) {
        // var IdExt = parseInt($('#'+extNsId).val()) + '_' + parseInt($('#'+extNameId).val());
        var TxtExt = $('#' + textfieldId).val().trim();

        if (TxtExt == '') {
            return false;
        }
        subRoutineAddToSelected(targetSelectId, TxtExt);
    }

    var removeFromSelected = function (e) {
        e.preventDefault();
        var targetSelectId = $(this).data('target');
        if (targetSelectId === '') {
            return console.log('Data Target is empty!')
        }
        var targetSelectIdVal = $('#' + targetSelectId).val();

        $.each(targetSelectIdVal, function (selectedK, selectedV) {
            $('#' + targetSelectId + ' option').each(function (i, v) {
                if ($(v).val() == selectedV) {
                    $(v).remove();
                }
            });
        });
    }
    /**
     * manages the alert box for displaying different error resp. success messages
     *
     * @param object data with 3 entries ;-)
     */
    var alertTimeout = null;
    var handleAlertBox = function (data, options) {

        if (!options || typeof options != 'object') {
            options = [];
            options.noFadeOut = false;
            options.closeFancyBox = false;
        }

        var $theAlert = $('div.theAlert');
        var $divAlert = $theAlert.find('div.alert');
        $theAlert.fadeIn('fast');

        $divAlert.removeClass('alert-error');
        $divAlert.removeClass('alert-success');
        if (data.isError) {
            $divAlert.addClass('alert-error');
        } else {
            $divAlert.addClass('alert-success');
        }

        $theAlert.find('h4.alert-heading').html(data.header);
        $theAlert.find('span.alert-text').html(data.text);

        if (options.noFadeOut !== true) {

            if (alertTimeout) {
                window.clearTimeout(alertTimeout);
            }

            alertTimeout = window.setTimeout(function () {
                $('div.theAlert').fadeOut('slow');
            }, 5000);
        } //endif options.noFadeOut

        var newPosition = $theAlert.offset();
//	window.scrollTo(newPosition.left,newPosition.top /* -60 */ );

    }

    var generatorSaveForm = function () {
        var formId = $(this).data('formid');
        if (formId == '') {
            return console.log('formid not found');
        }
        var $formId = $(formId);
        // console.log( $formId.attr('action') );

// for testing
//    $formId.submit();
//    return true;

        $formId.validate({
            errorPlacement : ValiErrorPlacement,
            unhighlight : ValiUnhighlight
        });

        if ($formId.valid()) {

            formSelect2Selected();

            // @todo remove fields which names start with uns_
            var myAction = '/' + $formId.attr('action');
            $.post(myAction, $formId.serialize(), function (data, textStatus) {
                if (typeof data != 'object') {
                    var data = {};
                    data.header = 'Oh snap ...';
                    data.text = 'Strange error has happend ... maybe the server has gone away ...';
                    data.isError = true;
                } else {
                    piwikAjaxTracker(myAction, 'Generator Save Config');
                }

                handleAlertBox(data);

            }, 'json');

        } // end form is valid

    }; // end generatorSaveForm

    /****************************************************************************
     * MODEL AND TAB CREATOR
     */
    var getTabTemplate = function () {
        // do it via knockout!
        var $tpl = $('div.tabTemplate').clone();
        $tpl.removeClass('tabTemplate');
        return $tpl;
    };

    var createModelTab = function () {
        var $modelName = $('input#uns_modelname')
        var $ulTabs = $('ul.nav-tabs');
        var ModelName = $.trim($modelName.val());
        var columnCount = parseInt($('select#uns_columncount').val());
        var $formId = $('form.generatorForm');
        var $tpl = getTabTemplate();
        var $tabContent = $('div.tab-content');

        $formId.validate({
            errorPlacement : ValiErrorPlacement,
            unhighlight : ValiUnhighlight
        });

        if (!$formId.valid() || $ulTabs.find('li.tab' + ModelName).length > 0) {
            var eData = {};
            eData.header = 'Tab already defined';
            eData.text = '@todo Translation: Please define another model name.';
            eData.isError = true;
            handleAlertBox(eData);

            return console.log('Error, form or model tab already defined');
        }

        if (columnCount == 0) {
            addTextfieldToSelected('blankModels', 'uns_modelname');
            return false;
        }


        $ulTabs.find('li.active').removeClass('active');


        var liTab = $('<li/>', {'class' : 'active tab' + ModelName});
        var liTabA = $('<a/>', {'data-toggle' : 'tab', 'href' : '#tab' + ModelName});

        liTabA.html(ModelName);
        liTab.append(liTabA);
        $ulTabs.append(liTab);

        // add Button
        $tpl.find('table thead a.btn').attr('data-tabid', ModelName);

        $tpl.attr('id', 'tab' + ModelName);

        $tpl.find('h3').html(
            $tpl.find('h3').html().replace(/###tableName###/, ModelName)
        );

        // build the table
        var $tableRow = $tpl.find('tr.sqlColumnRow').clone();

        // remove button
        $tableRow.find('a.btn.rm').attr('data-tabid', ModelName);
        $tpl.find('tr.sqlColumnRow').remove();

        $tpl.find('table.table tbody').remove();
        var $tbody = $('<tbody/>');

        var rows = [];
        for (var i = 1; i <= columnCount; ++i) {
            $tableRow.attr('id', i);
            rows[i] = $tableRow.get(0).outerHTML.replace(/###tablex###/g, ModelName);
            rows[i] = rows[i].replace(/###cid###/g, i);
        }
        $tbody.append(rows.join(''));
        rows = null;


        $tpl.find('table.table').append($tbody);

        // now append the new tab to the content
        $tabContent.append($tpl);
        $tabContent.find('div.tab-pane.active').removeClass('active');
        $tpl.removeClass('hide');
        $tpl.addClass('active');

        /*plugin table drag n drop*/
        $tpl.find('table.table').tableDnD();

    }; // endfunc createModelTab

    var removeTableRow = function (e) {
        e.preventDefault();
        var $that = $(this);
        var tabId = $that.data('tabid');
        $that.parent(/*td*/).parent(/*tr*/).remove();
        if ($('div#tab' + tabId + ' table.table tbody > tr').length == 0) {
            $('div#tab' + tabId).remove();
            $('ul.nav-tabs li.tab' + tabId).remove(); // @todo maybe a fadeout animation and on end, remove it
            $('ul.nav-tabs li').first().addClass('active');
            $('div#tab1').addClass('active');
        }
    }

    var addTableRow = function (e) {
        e.preventDefault();
        var tabId = $(this).data('tabid');
        var ModelName = tabId;
        var $tpl = getTabTemplate();
        var $tableRow = $tpl.find('tr.sqlColumnRow');
        $tableRow.find('a.btn.rm').data('tabid', tabId);
        var $trLast = $('#tab' + tabId + ' table.table tbody tr:last');
        var trNewLength = $('#tab' + tabId + ' table.table tbody tr').length + 1;

        $tableRow.attr('id', trNewLength);

        var outerTrHtml = $tableRow.get(0).outerHTML;
        outerTrHtml = outerTrHtml.replace(/###tablex###/g, ModelName);
        outerTrHtml = outerTrHtml.replace(/###cid###/g, trNewLength);

        $trLast.after(outerTrHtml);
        /*plugin table drag n drop*/
        $('#tab' + tabId + ' table.table').tableDnDUpdate();


    }
    /***
     * /MODEL AND TAB CREATOR
     ****************************************************************************/

    /***************************************************************************
     *    clone me
     **/
    var getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    var removeMe = function (obj) {
        $(obj).parent().parent().remove();
    }
    var cloneMe = function (e) {
        e.preventDefault();
        var $cloneMe = $(this).parent().parent();
        var $clonedMe = $cloneMe.clone();
        $clonedMe.removeClass('cloneMe');
        $clonedMe.addClass('clonedMe');
        $clonedMe.find('a.btn').attr('onclick', 'removeMe(this);');
        $clonedMe.find('i.toIconMinus').removeClass('icon-plus');
        $clonedMe.find('i.toIconMinus').addClass('icon-minus');

        var clonedMeLength = $('div.clonedMe').length + 1;

        $clonedMe.find('input').each(function (index, elem) {
            var newName = $(elem).attr('name').replace(/\[0\]/, '[' + clonedMeLength + ']');
            $(elem).attr('name', newName);
        });

        $clonedMe.find('input[type="text"]').each(function (index, elem) {
            $(elem).val('');
        });

        $clonedMe.hide();
        $cloneMe.after($clonedMe);
        $clonedMe.fadeIn('slow');
    }
    /***************************************************************************/

    /***************************************************************************
     *    duplicate tab
     **/

    var duplicateTab = function (e) {
        e.preventDefault();
        var tab1Id = 'tab1';
        var newTabName = $('#' + tab1Id + ' input.newTabName').val().trim();

        var $formId = $('form.generatorForm');
        var newTabIndex = $('div.tab-pane').length + 1;
        var $ulTabs = $('ul.nav-tabs');

        // now clone it
        var $clonedTab = $('div#' + tab1Id).clone().
            addClass('active').
            attr('id', 'tab' + newTabName).
            hide();


        $formId.validate({
            errorPlacement : ValiErrorPlacement,
            unhighlight : ValiUnhighlight
        });

        if (!$formId.valid() || $ulTabs.find('li.tab' + newTabName).length > 0) {
            return console.log('Error, form or tab already defined');
        }

        $('div#' + tab1Id).removeClass('active');

        $ulTabs.find('li.active').removeClass('active');


        var liTab = $('<li/>', {'class' : 'active tab' + newTabName});
        var liTabA = $('<a/>', {'data-toggle' : 'tab', 'href' : '#tab' + newTabName});

        liTabA.html(newTabName);
        liTab.append(liTabA);
        $ulTabs.append(liTab);

        // add name to remove button
        $clonedTab.find('a.removeTab').data('tabid', newTabName);
        $clonedTab.find('span.add-on.hide').removeClass('hide');

        // now update array form index
        $clonedTab.find('input[name*="[tab1]"]').each(function (index, elem) {
            var attrN = $(elem).attr('name').replace(/\[tab1\]/, newTabName);
        });

        $clonedTab.find('div.onDupRm').each(function (index, elem) {
            $(elem).remove();
        });

        $('div.tab-content .tab-pane:last').after($clonedTab);
        $clonedTab.fadeIn('slow');

        // reset values in first tab
        $('#' + tab1Id + ' input[type="text"]').each(function (index, elem) {
            $(elem).val('');
        });

    }

    var removeTab = function (e) {
        e.preventDefault();
        var tabId = $(this).data('tabid');

        $('div#tab' + tabId).remove();
        $('ul.nav-tabs li.tab' + tabId).remove(); // @todo maybe a fadeout animation and on end, remove it
        $('ul.nav-tabs li').first().addClass('active');
        $('div#tab1').addClass('active');

    }

    /*********************************************************************************************
     *  pro account register functionality
     *********************************************************************************************/
    var proAccountRegister = function () {

        var $divs = $('div.proAccountRegister');

        // @todo check if customer has a pro account
        // if so show the advanced fields: address

        var additionalRequiredFields = [
            'firstName',
            'lastName',
            'company',
            'address',
            'zip',
            'city',
            'country'
        ];

        if ($divs.css('display') == 'none') {
            $divs.fadeIn('slow');

            for (var key in additionalRequiredFields) {
                val = additionalRequiredFields[key];
                ajaxUserFormValidRulez.rules['tx_magenerator_mageusers[feUser][' + val + ']'] = {required : true};
            }


        } else {
            $divs.fadeOut('slow', function () {
                $divs.find('input,textarea').val('');
            });

            for (var key in additionalRequiredFields) {
                val = additionalRequiredFields[key];
                delete ajaxUserFormValidRulez.rules['tx_magenerator_mageusers[feUser][' + val + ']'];
            }

        }

    }

    /**
     * loads the navigation
     */

    var
        $LoaderDiv1 = $('<div />', {'class' : 'progress progress-striped active wunderProgress'}),
        $LoaderDiv2 = $('<div />', {'class' : 'bar wunderBar'}),
        $LoaderFb = $('<div />', {'class' : 'fbLoader'}), // css3 loader! who case for old browsers!?
        mainContent = 'div#mainContent',
        $mainContent = $(mainContent),
        wunderBarWidthCounter = 0,
        intervalWunderBar = null,
        myClearInterval = function () {
            window.clearInterval(intervalWunderBar);
            wunderBarWidthCounter = 0;
        }

    var ajaxLoader = function (e) {
        e.preventDefault();

        var $mainGeneratorContent = $('div.mainGeneratorContent');
        if ($mainGeneratorContent.length > 0) {
            $mainGeneratorContent.fadeOut('slow');
        }
        $mainContent.empty();

        $LoaderFb.appendTo(mainContent);
        $LoaderDiv1.appendTo(mainContent);
//        $LoaderDiv1.appendTo(mainContent);
//        $LoaderDiv2.appendTo($LoaderDiv1);
//        $('div.wunderBar').css('width', '1');
//        if (intervalWunderBar) {
//            myClearInterval();
//        }
//        intervalWunderBar = window.setInterval(function () {
//            wunderBarWidthCounter += 2;
//            $('div.wunderBar').css('width', wunderBarWidthCounter + '%');
//            if (wunderBarWidthCounter >= 100) {
//                myClearInterval();
//            }
//        }, 200);

        var $that = this;
        $.get($that.href, function (response) {
//            myClearInterval();
            $mainContent.html(response);
            $('div.mainGeneratorContent').fadeIn('slow');
            setPageIdentifiers($that);
            $('.aTooltip').tooltip({placement : 'right'});

            /*plugin table drag n drop*/
            $('table.table.modelTableAutoGen').tableDnD();
            piwikAjaxTracker($that.href, $($that).html());


        });

    }
    /*end ajaxLoader*/

    $(document).ready(function () {

        initDocumentGeneral();

        // heise.de social sharer
        if ($('#socialshareprivacy').length > 0) {

            var imgPath = 'fileadmin/magenerator/js/socialshareprivacy/';

            $('#socialshareprivacy').socialSharePrivacy({
                services : {
                    facebook : {
                        'dummy_img' : imgPath + 'dummy_facebook_en.png',
                        'txt_info' : '2-clicks for more privacy'
                    },
                    twitter : {
                        'dummy_img' : imgPath + 'dummy_twitter.png',
                        'txt_info' : '2-clicks for more privacy'
                    },
                    gplus : {
                        'dummy_img' : imgPath + 'dummy_gplus.png',
                        'txt_info' : '2-clicks for more privacy'
                    }
                },
                'cookie_domain' : 'magenerator.net',
                'css_path' : imgPath + 'socialshareprivacy.css',
                'uri' : 'http://magenerator.net'
            });
        } // endif socialshareprivacy

        $('a.fancyboxLogin').fancybox({
            topRatio : 0.15,
            type : 'ajax',
            afterLoad : function () {
                // remove silly JS libs, loaded from T3 ext rsaauth
                // we use our own optimized via getScript
                this.content = this.content.replace(/<script[^>]+><\/script>/gi, '');
                $.getScript('/fileadmin/magenerator/js/jsbn/rsa-min.js');
            },
            afterShow : function () {
                $('form#mageloginform').on('click', 'input.MageAjaxFeLogin', MageAjaxFeLogin);
                $('form#mageUserForm').on('change', 'select.proAccountRegister', proAccountRegister).
                    on('click', 'button.ajaxUserFormSubmit', ajaxUserFormSubmit);
            },
            beforeClose : function () {
                $('form#mageloginform').off('click', 'input.MageAjaxFeLogin', MageAjaxFeLogin);
                $('form#mageUserForm').off('change', 'select.proAccountRegister', proAccountRegister).
                    on('click', 'button.ajaxUserFormSubmit', ajaxUserFormSubmit);
            }
        });

        /* that is bad, even at a not loaded FB content we bind this event */


        // first time to check
        ajaxLoginStatus();

        // fancybox for normal html pages
        $('a.fancyboxHtml').fancybox({
            type : 'ajax',
            width : 820,
            aspectRatio : false,
            autoSize : false,
            scrolling : 'auto'
        });

        // @todo remove live function and change to on()
        $('ul.nav li').on('click', 'a.ajaxLoader', ajaxLoader);

        $('div#mainContent').on('click', 'a.createModelTab', function (e) {
            e.preventDefault();
            createModelTab();
        })
            .on('change', 'select.LoadSysExtName', LoadSysExtName)
            .on('click', 'a.addTwoSelectsToSelected', addTwoSelectsToSelected)
            .on('click', 'a.removeFromSelected', removeFromSelected)
            .on('click', 'button.generatorSaveForm', generatorSaveForm)
            .on('click', 'a.ajaxLoader', ajaxLoader)
            .on('keyup', 'input.removeChars', removeChars)
            .on('keyup', 'textarea.removeChars', removeChars)
            .on('keyup', 'input.removeCharsVersion', removeCharsVersion)
            .on('click', 'a.addTableRow', addTableRow)
            .on('click', 'a.removeTableRow', removeTableRow)
            .on('click', 'a.cloneMe', cloneMe)
            .on('click', 'a.duplicateTab', duplicateTab)
            .on('click', 'a.removeTab', removeTab)

        /**
         * function for checking a textfield value via ajax for validity
         * @todo check if needed
         */
            .on('change', 'input.ajaxVerify', function (e) {
                var ajaxVerifyURI = '/' + $('a.ajaxVerifyURI').attr('href');
                var $ajaxVerify = $('input.ajaxVerify');

                $ajaxVerify.after(ajaxLoaderImgW);

                var searchFor = $ajaxVerify.val().trim();
                var ppGroup = $(e.target).parent().parent();
                ppGroup.removeClass('error');

                if (searchFor == '') {
                    return false;
                }

                ajaxVerifyURI = ajaxVerifyURI.replace(/XsearchX/, escape(searchFor));

                $.getJSON(ajaxVerifyURI, function (data) {

                    if (data && data.isError) {
                        ppGroup.addClass('error');
                        handleAlertBox(data);

                    }
                    $('img.ajaxLoaderImgW').remove();
                    piwikAjaxTracker(ajaxVerifyURI, 'ajaxVerify');
                });

            });

        /**
         * general slow bindings
         */
        $(document).on('click', 'button.ajaxContactFormSubmit', ajaxContactFormSubmit);


    });

})(jQuery, document);