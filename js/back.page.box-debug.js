'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Back = function () {

    /**
     * @param object
     */
    function Back(object) {
        _classCallCheck(this, Back);

        this.object = object ? object : {};
        this.interval = null;
        this.initFn(this.object, this.constructor.setObject, this.addEl, this.constructor.setInputVal);

        this.categoryChilds(this.object, this.constructor.setInputVal);

        this.search(this.interval, this.object);
        this.sections();
        this.sort('.item-menu', this.object, this.constructor.setInputVal);
        this.tabs('[data-tab]');
        this.toggleBox();
    }

    /**
     * param {object} object
     * param {object} el
     * param {object} parent
     * set object
     */


    _createClass(Back, [{
        key: 'search',


        /**
         *
         * @param interval
         * @param object
         */
        value: function search(interval, object) {
            function ajaxSearch(data) {
                jQuery.ajax({
                    type: 'POST',
                    url: '/wp-content/plugins/jsplugin/scripts/back.search.php',
                    data: { data: data.values, compare: object },
                    beforeSend: function beforeSend() {
                        jQuery('<span class="js-spinner"></span>').appendTo(jQuery(data.el.closest('.bd')));
                    },
                    success: function success(response) {
                        data.el.closest('.bd').css({
                            'max-height': '1000px'
                        });
                        jQuery(data.el.closest('.bd').find('#ajax-items')).html(response);
                    },
                    complete: function complete() {
                        jQuery('.js-spinner').remove();
                        data = {};
                    }
                });
            }

            jQuery(document).on('keyup', '[data-search]', function (event) {
                var thisEl = jQuery(this);
                var data = {
                    el: thisEl,
                    values: { type: thisEl.data('search'), val: thisEl.val(), id: jQuery('#post_ID').val() }
                };

                try {
                    if (thisEl.val().length < 3) {
                        throw 'neni vetsi jak tri';
                    } else if (event.keyCode === 8) {
                        throw 'to je back';
                    }
                } catch (err) {
                    console.log(err);
                    return;
                }
                console.log(event.keyCode);

                clearTimeout(interval);
                interval = setTimeout(ajaxSearch, 450, data);
            });
        }

        /**
         * add item to item menu ajax
         * @param object
         */

    }, {
        key: 'addEl',
        value: function addEl(object) {
            jQuery.ajax({
                type: 'POST',
                url: '/wp-content/plugins/jsplugin/scripts/tpl.page.php',
                data: { items: object },
                success: function success(response) {
                    jQuery('#jsmenu').html(response);
                }
            });
        }

        /**
         * @param {object} object
         * @param {function} setObject
         * @param {function} addEl
         * @param setInputVal
         */

    }, {
        key: 'initFn',
        value: function initFn(object, setObject, addEl, setInputVal) {
            jQuery(document).on('click', '[data-item]', function () {

                var thisEl = jQuery(this);
                var thisData = thisEl.data('item');
                var thisChecked = thisEl.prop('checked');
                var thisType = thisEl.closest('[data-type]').data('type');

                if (thisEl.closest('li').hasClass('active')) {
                    thisEl.closest('li').removeClass('active');
                } else {
                    thisEl.closest('li').addClass('active');
                }

                setObject(object, thisEl, thisType);
                addEl(object);
                setInputVal(object);

                jQuery('[data-item="' + thisData + '"]').attr('checked', thisChecked);

                thisEl = null;
                thisData = null;
                thisChecked = null;
                thisType = null;
            });
        }

        /**
         * @param object
         * @param setInputVal
         */

    }, {
        key: 'categoryChilds',
        value: function categoryChilds(object, setInputVal) {

            jQuery(document).on('change', '[name="childs"]', function () {
                var thisEl = jQuery(this);
                var thisItem = thisEl.closest('[data-type]').find('[data-item]').data('item');

                if (thisEl.prop('checked') === true) {
                    object[thisItem].childs = true;
                    thisEl.closest('.section').removeClass('disabled');
                    thisEl.closest('[data-type]').find('[data-posts]').text('only posts');
                } else {
                    delete object[thisItem].childs;
                    thisEl.closest('.section').addClass('disabled');
                    thisEl.closest('.section').find('select').val('');
                    thisEl.closest('[data-type]').find('[data-posts]').text('');
                    thisEl.closest('[data-type]').find('[data-count]').text('');
                    thisEl.closest('[data-type]').find('[data-sort]').text('');
                }
                setInputVal(object);
            });

            jQuery(document).on('change', '.section select', function () {

                var thisEl = jQuery(this);
                var thisItem = thisEl.closest('[data-type]').find('[data-item]').data('item');
                var name = thisEl.attr('name');

                if (thisEl.val()) {
                    object[thisItem][name] = thisEl.val();
                    thisEl.closest('[data-type]').find('[data-' + name + ']').text(thisEl.val());
                } else {
                    delete object[thisItem][name];
                    thisEl.closest('[data-type]').find('[data-' + name + ']').text('');
                }

                console.log(name);
                console.log(object);
                setInputVal(object);

                name = null;
            });
        }

        /**
         * toggle section
         */

    }, {
        key: 'sections',
        value: function sections() {
            jQuery(document).on('click', '[data-section]', function () {

                var thisEl = jQuery(this);
                var thisBox = thisEl.closest('.jsbox');
                var thisHight = thisBox.find('.bd').outerHeight();

                thisBox.toggleClass('open').find('.bd').animate({
                    'max-height': thisHight > 0 ? 0 : thisBox.find('.box-menu').outerHeight()
                }, 120);
                jQuery('.jsbox').each(function () {
                    if (jQuery(this).index() !== thisBox.index()) {
                        jQuery(this).removeClass('open').find('.bd').animate({
                            'max-height': '0'
                        }, 120);
                    }
                });
            });
        }

        /**
         * toggle box bd
         */

    }, {
        key: 'toggleBox',
        value: function toggleBox() {
            jQuery(document).on('click', '[data-toggle]', function (event) {
                event.preventDefault();

                var thisEl = jQuery(this);
                var thisParent = thisEl.closest('[data-parent]');
                var thisBd = thisParent.find('.bd');

                if (thisBd.hasClass('hdn') === true) {
                    thisBd.removeClass('hdn');
                    thisParent.addClass('open');
                } else {
                    thisBd.addClass('hdn');
                    thisParent.removeClass('open');
                }
            });
        }

        /**
         * tabs
         * @param el
         */

    }, {
        key: 'tabs',
        value: function tabs(el) {
            jQuery(document).on('click', el, function (event) {
                event.preventDefault();

                var thisTab = jQuery(this);
                var tab = jQuery('[data-tabs="' + thisTab.data('tab') + '"]');

                console.log(thisTab);

                thisTab.closest('.box-menu').find('[data-tab]').removeClass('active');
                thisTab.addClass('active');

                thisTab.closest('.box-menu').find('[data-tabs]').addClass('hdn');
                thisTab.closest('.box-menu').find(tab).removeClass('hdn');
                //tab.removeClass('hdn');
            });
        }

        /**
         * init sort plugin on mouseenter event, sort item, create sortObject and set input
         * @param {string} el
         * @param {object} object
         * @param {function} setInputVal
         */

    }, {
        key: 'sort',
        value: function sort(el, object, setInputVal) {
            jQuery('#jsmenu').on('mouseenter', function () {
                jQuery(el).sortable({
                    placeholder: "ui-state-highlight",
                    stop: function stop() {
                        var sortObject = {};
                        jQuery('.item-menu').find('.item-box').each(function () {
                            var key = jQuery(this).data('menu');
                            sortObject[key] = object[key];
                        });
                        setInputVal(sortObject);
                    }
                });
            });
        }
    }], [{
        key: 'setObject',
        value: function setObject(object, el, type) {
            if (object[el.data('item')]) {
                delete object[el.data('item')];
                jQuery('[data-item="' + el.data('item') + '"]').attr('checked', false);
            } else {
                object[el.data('item')] = {
                    id: el.data('item'),
                    title: el.closest('li').text().trim(),
                    type: type
                };
                if (el.data('posts')) {
                    object[el.data('item')].posts = el.data('posts');
                }
            }
        }

        /**
         * param {object} object
         * set value input
         */

    }, {
        key: 'setInputVal',
        value: function setInputVal(object) {

            jQuery('input#js_append').val(JSON.stringify(object));
        }
    }]);

    return Back;
}();

jQuery(document).ready(function () {
    var jsAppend = jQuery('#js_append');
    var object = jsAppend.val() ? JSON.parse(jsAppend.val()) : {};

    new Back(object);
});