(function ($) {
    'use strict';

    window.tpgFixLazyLoad = function () {
        $('.rt-tpg-container').each(function () {
            // jetpack Lazy load
            $(this).find('img.jetpack-lazy-image:not(.jetpack-lazy-image--handled)').each(function () {
                console.log('trigger');
                $(this).addClass('jetpack-lazy-image--handled').removeAttr('srcset').removeAttr('data-lazy-src').attr('data-lazy-loaded', 1);
            });
        });
    };
    window.initTpgPro = function(){

        $('.rt-tpg-container').each(function () {
            var container = $(this),
                loaded_post_ids = [],
                str = $(this).attr("data-layout"),
                id = $.trim(container.attr('id')),
                scID = $.trim(container.attr("data-sc-id")),
                $default_order_by = $('.rt-order-by-action .order-by-default', container),
                $default_order = $('.rt-sort-order-action .rt-sort-order-action-arrow', container),
                $taxonomy_filter = $('.rt-filter-item-wrap.rt-tax-filter', container),
                author_filter = $('.rt-filter-item-wrap.rt-author-filter', container),
                $pagination_wrap = $('.rt-pagination-wrap', container),
                $loadmore = $('.rt-loadmore-action', container),
                $infinite = $('.rt-infinite-action', container),
                $page_prev_next = $('.rt-cb-page-prev-next', container),
                $page_numbers = $('.rt-page-numbers', container),
                html_loading = '<div class="rt-loading-overlay"></div><div class="rt-loading rt-ball-clip-rotate"><div></div></div>',
                preLoader = container.find('.tpg-pre-loader'),
                loader = container.find(".rt-content-loader"),
                filter_container = $(".rt-layout-filter-container", container),
                contentLoader = container.children(".rt-row.rt-content-loader"),
                search_wrap = container.find(".rt-search-filter-wrap"),
                tpg_order = '',
                tpg_order_by = '',
                tpg_taxonomy = '',
                tpg_author = '',
                tpg_term = '',
                tpg_search = '',
                tpg_paged = 1,
                temp_total_pages = parseInt($pagination_wrap.attr('data-total-pages'), 10),
                tpg_total_pages = typeof (temp_total_pages) != 'undefined' && temp_total_pages != '' ? temp_total_pages : 1,
                temp_posts_per_page = parseInt($pagination_wrap.attr('data-posts-per-page'), 10),
                tpg_posta_per_page = typeof (temp_posts_per_page) != 'undefined' && temp_posts_per_page != '' ? temp_posts_per_page : 3,
                infinite_status = 0,
                paramsRequest = {},
                mIsotopWrap = '',
                IsotopeWrap = '',
                isMasonary = $('.rt-row.rt-content-loader.tpg-masonry', container),
                isIsotop = $(".rt-tpg-isotope", container),
                IsoButton = $(".rt-tpg-isotope-buttons", container),
                IsoDropdownFilter = $("select.isotope-dropdown-filter", container),
                isCarousel = $('.rt-carousel-holder', container),
                storeLoadedIds = function () {
                    loader.find('.rt-grid-item').each(function () {
                        var id = $(this).data('id');
                        if (loaded_post_ids.indexOf(id) === -1) {
                            loaded_post_ids.push(parseInt(id, 10));
                        }
                    });
                },
                placeholder_loading = function () {
                    if (loader.find('.rt-loading-overlay').length == 0) {
                        loader.addClass('tpg-pre-loader');
                        loader.append(html_loading);
                    }
                },
                remove_placeholder_loading = function () {
                    loader.find('.rt-loading-overlay, .rt-loading').remove();
                    loader.removeClass('tpg-pre-loader');
                    $loadmore.removeClass('rt-lm-loading');
                    $page_numbers.removeClass('rt-lm-loading');
                    $infinite.removeClass('rt-active-elm');
                    search_wrap.find('input').prop("disabled", false);
                },
                check_query = function () {
                    if ($taxonomy_filter.length > 0) {
                        tpg_taxonomy = $taxonomy_filter.attr('data-taxonomy');
                        var term;
                        if ($taxonomy_filter.hasClass('rt-filter-button-wrap')) {
                            term = $("> .sub-button-group .rt-filter-button-item.selected", filter_container).attr('data-term');
                            if (term === undefined) {
                                term = $taxonomy_filter.find('.rt-filter-button-item.selected').attr('data-term');
                            }
                        } else {
                            term = $(".rt-filter-wrap > .sub-dropdown-wrap.rt-filter-dropdown-wrap .term-default", filter_container).attr('data-term');
                            if (term === undefined) {
                                term = $(".parent-dropdown-wrap.rt-filter-dropdown-wrap .term-default", filter_container).attr('data-term');
                            }
                        }
                        if (typeof (term) != 'undefined' && term != '') {
                            tpg_term = term;
                        }
                    }
                    if (author_filter.length > 0) {
                        var author = '';
                        if (author_filter.hasClass('rt-filter-button-wrap')) {
                            author = author_filter.find('.rt-filter-button-item.selected').attr('data-author') || '';
                        } else {
                            author = $(".rt-filter-dropdown-default.term-default", author_filter).attr('data-term') || '';
                        }
                        if (author && author !== '') {
                            tpg_author = author;
                        }
                    }

                    if ($default_order_by.length > 0) {
                        var order_by_param = $default_order_by.attr('data-order-by');
                        if (typeof (order_by_param) != 'undefined' && order_by_param != '' && (order_by_param.toLowerCase())) {
                            tpg_order_by = order_by_param;
                        }
                    }

                    if ($default_order.length > 0) {
                        var order_param = $default_order.attr('data-sort-order');
                        if (typeof (order_param) != 'undefined' && order_param != '' && (order_param == 'DESC' || order_param == 'ASC')) {
                            tpg_order = order_param;
                        }
                    }
                    if (search_wrap.length > 0) {
                        tpg_search = $.trim(search_wrap.find('input').val());
                    }
                    var archive = container.data('archive') || '',
                        archive_value = container.data('archive-value') || '';

                    paramsRequest = {
                        'scID': scID,
                        'order': tpg_order,
                        'order_by': tpg_order_by,
                        'taxonomy': tpg_taxonomy,
                        'author': tpg_author,
                        'term': tpg_term,
                        'paged': tpg_paged,
                        'action': 'tpgLayoutAjaxAction',
                        'search': tpg_search,
                        'loaded_post_ids': loaded_post_ids,
                        'archive': archive,
                        'archive_value': archive_value,
                        'rttpg_nonce': rttpg.nonce
                    };
                    // console.log(paramsRequest);
                },
                infinite_scroll = function () {
                    if (infinite_status == 1 || $infinite.hasClass('rt-hidden-elm') || $pagination_wrap.length == 0) {
                        return;
                    }
                    var ajaxVisible = $pagination_wrap.offset().top,
                        ajaxScrollTop = $(window).scrollTop() + $(window).height();

                    if (ajaxVisible <= (ajaxScrollTop) && (ajaxVisible + $(window).height()) > ajaxScrollTop) {
                        infinite_status = 1; //stop inifite scroll
                        tpg_paged = tpg_paged + 1;
                        $infinite.addClass('rt-active-elm');
                        storeLoadedIds();
                        ajax_action(true, true);
                    }
                },
                generateData = function (number) {
                    var result = [];
                    for (var i = 1; i < number + 1; i++) {
                        result.push(i);
                    }
                    return result;
                },
                createPagination = function () {
                    if ($page_numbers.length > 0) {
                        $page_numbers.pagination({
                            dataSource: generateData(tpg_total_pages * parseFloat(tpg_posta_per_page)),
                            pageSize: parseFloat(tpg_posta_per_page),
                            autoHidePrevious: true,
                            autoHideNext: true,
                            prevText: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
                            nextText: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>'
                        });
                        $page_numbers.addHook('beforePaging', function (pagination) {
                            infinite_status = 1;
                            tpg_paged = pagination;
                            $page_numbers.addClass('rt-lm-loading');
                            $page_numbers.pagination('disable');
                            ajax_action(true, false);
                        });
                        if (tpg_total_pages <= 1) {
                            $page_numbers.addClass('rt-hidden-elm');
                        } else {
                            $page_numbers.removeClass('rt-hidden-elm');
                        }
                    }
                },
                load_gallery_image_popup = function () {
                    container.find('.rt-row.layout17').each(function () {
                        var self = $(this);
                        self.magnificPopup({
                            delegate: 'a.tpg-zoom',
                            type: 'image',
                            gallery: {
                                enabled: true
                            }
                        });
                    });

                },
                animateAction = function () {
                    var $postItem = $('.rt-grid-item:not(.rt-ready-animation, .isotope-item)', container);
                    $postItem.addClass('rt-ready-animation animated fadeIn');
                },
                ajax_action = function (page_request, append) {
                    page_request = page_request || false;
                    append = append || false;
                    if (!page_request) {
                        tpg_paged = 1;
                        loaded_post_ids = [];
                    }
                    check_query();
                    if (page_request === true && tpg_total_pages > 1 && paramsRequest.paged > tpg_total_pages) {
                        remove_placeholder_loading();
                        return;
                    }
                    $.ajax({
                        url: rttpg.ajaxurl,
                        type: 'POST',
                        data: paramsRequest,
                        cache: false,
                        beforeSend: function () {
                            // console.log(paramsRequest);
                            placeholder_loading();
                        },
                        success: function (data) {
                            // console.log(data);
                            if (!data.error) {
                                tpg_paged = data.paged;
                                tpg_total_pages = data.total_pages;
                                if (data.paged >= tpg_total_pages) {
                                    if ($loadmore.length) {
                                        $loadmore.addClass('rt-hidden-elm');
                                    }
                                    if ($infinite.length) {
                                        infinite_status = 1;
                                        $infinite.addClass('rt-hidden-elm');
                                    }
                                    if ($page_prev_next.length) {
                                        if (!page_request) {
                                            $page_prev_next.addClass('rt-hidden-elm');
                                        } else {
                                            $page_prev_next.find('.rt-cb-prev-btn').removeClass('rt-disabled');
                                            $page_prev_next.find('.rt-cb-next-btn').addClass('rt-disabled');
                                        }
                                    }
                                } else {
                                    if ($loadmore.length) {
                                        $loadmore.removeClass('rt-hidden-elm');
                                    }
                                    if ($infinite.length) {
                                        infinite_status = 0;
                                        $infinite.removeClass('rt-hidden-elm');
                                    }
                                    if ($page_prev_next.length) {
                                        if (!page_request) {
                                            $page_prev_next.removeClass('rt-hidden-elm');
                                        } else {
                                            if (data.paged == 1) {
                                                $page_prev_next.find('.rt-cb-prev-btn').addClass('rt-disabled');
                                                $page_prev_next.find('.rt-cb-next-btn').removeClass('rt-disabled');
                                            } else {
                                                $page_prev_next.find('.rt-cb-prev-btn').removeClass('rt-disabled');
                                                $page_prev_next.find('.rt-cb-next-btn').removeClass('rt-disabled');
                                            }
                                        }
                                    }
                                }
                                if (append) {
                                    if (isIsotop.length) {
                                        IsotopeWrap.append(data.data)
                                            .isotope('appended', data.data)
                                            .isotope('reloadItems')
                                            .isotope('updateSortData')
                                            .isotope();
                                        container.trigger('tpg_item_before_load');
                                        tpgFixLazyLoad();
                                        IsotopeWrap.imagesLoaded(function () {
                                            preFunction();
                                            IsotopeWrap.isotope();
                                            container.trigger('tpg_item_after_load');
                                        });
                                        if (IsoButton.attr('data-count')) {
                                            isoFilterCounter(container, IsotopeWrap);
                                        }
                                    } else if (isMasonary.length) {
                                        container.trigger('tpg_item_before_load');
                                        tpgFixLazyLoad();
                                        mIsotopWrap.append(data.data).isotope('appended', data.data).isotope('updateSortData').isotope('reloadItems');
                                        mIsotopWrap.imagesLoaded(function () {
                                            mIsotopWrap.isotope();
                                            container.trigger('tpg_item_after_load');
                                        });
                                    } else {
                                        contentLoader.append(data.data);
                                        container.trigger('tpg_item_before_load');
                                        container.trigger('tpg_item_after_load');
                                    }
                                } else {
                                    if (isMasonary.length) {
                                        mIsotopWrap.html(data.data).isotope('appended', data.data).isotope('reloadItems');
                                        container.trigger('tpg_item_before_load');
                                        tpgFixLazyLoad();
                                        mIsotopWrap.imagesLoaded(function () {
                                            mIsotopWrap.isotope();
                                            container.trigger('tpg_item_after_load');
                                        });
                                    } else {
                                        contentLoader.html(data.data);
                                        container.trigger('tpg_item_before_load');
                                        container.trigger('tpg_item_after_load');
                                    }
                                }
                                contentLoader.imagesLoaded(function () {
                                    preFunction();
                                    remove_placeholder_loading();
                                    load_gallery_image_popup();
                                });
                                if (!page_request) {
                                    createPagination();
                                }
                                animateAction();
                            } else {
                                remove_placeholder_loading();
                            }
                        },
                        error: function (error) {
                            remove_placeholder_loading();
                        }
                    });
                    if ($('.paginationjs-pages .paginationjs-page', $page_numbers).length > 0) {
                        $page_numbers.pagination('enable');
                    }
                },
                subTax = function (self) {
                    var subList = $(".rt-filter-sub-tax", self).clone();
                    subList.on('click', '.rt-filter-button-item', function () {
                        $(this).parents('.rt-filter-sub-tax').find('.rt-filter-button-item').removeClass('selected');
                        $(this).addClass('selected');
                        ajax_action();
                    });
                    if (subList !== undefined) {
                        filter_container.append(subList);
                    }
                };

            switch ($pagination_wrap.attr('data-type')) {
                case 'load_more':
                    $loadmore.on('click', function () {
                        $(this).addClass('rt-lm-loading');
                        tpg_paged = tpg_paged + 1;
                        storeLoadedIds();
                        ajax_action(true, true);
                    });
                    break;
                case 'pagination_ajax':
                    createPagination();
                    break;
                case 'pagination':
                    break;
                case 'load_on_scroll':
                    $(window).on('scroll load', function () {
                        infinite_scroll();
                    });
                    break;
                case 'page_prev_next':
                    if (tpg_paged == 1) {
                        $page_prev_next.find('.rt-cb-prev-btn').addClass('rt-disabled');
                    }
                    if (tpg_paged == tpg_total_pages) {
                        $page_prev_next.find('.rt-cb-next-btn').addClass('rt-disabled');
                    }
                    if (tpg_total_pages == 1) {
                        $page_prev_next.addClass('rt-hidden-elm');
                    }
                    break;
            }

            if (str) {
                var qsRegex,
                    buttonFilter;
                if (preLoader.find('.rt-loading-overlay').length == 0) {
                    preLoader.append(html_loading);
                }

                if (isCarousel.length) {
                    isCarousel.imagesLoaded(function () {
                        var item = parseInt(isCarousel.data('item'), 10),
                            dItem = parseInt(container.attr('data-desktop-col'), 10),
                            tItem = parseInt(container.attr('data-tab-col'), 10),
                            mItem = parseInt(container.attr('data-mobile-col'), 10),
                            options = isCarousel.data('rtowl-options');
                        isCarousel.owlCarousel({
                            nav: options.nav,
                            dots: options.dots,
                            autoplay: options.autoPlay,
                            autoplayHoverPause: options.stopOnHover,
                            loop: options.loop,
                            autoHeight: options.autoHeight,
                            lazyLoad: options.lazyLoad,
                            rtl: options.rtl,
                            navText: ["<i class=\'fa fa-chevron-left\'></i>", "<i class=\'fa fa-chevron-right\'></i>"],
                            responsiveClass: true,
                            autoplayTimeout: options.autoPlayTimeOut,
                            smartSpeed: options.speed,
                            responsive: {
                                0: {
                                    items: mItem ? mItem : 1
                                },
                                767: {
                                    items: tItem ? tItem : 2
                                },
                                991: {
                                    items: dItem ? dItem : 3
                                }
                            }
                        });
                        remove_placeholder_loading();
                    });
                } else if (isIsotop.length) {
                    var IsoURL = IsoButton.attr('data-url'),
                        IsoCount = IsoButton.attr('data-count');
                    if (!buttonFilter) {
                        if (IsoButton.length) {
                            buttonFilter = IsoButton.find('button.selected').data('filter');
                        } else if (IsoDropdownFilter.length) {
                            buttonFilter = IsoDropdownFilter.val();
                        }
                    }
                    container.trigger('tpg_item_before_load');
                    tpgFixLazyLoad();
                    IsotopeWrap = isIsotop.imagesLoaded(function () {
                        preFunction();
                        IsotopeWrap.isotope({
                            itemSelector: '.isotope-item',
                            masonry: {columnWidth: '.isotope-item'},
                            filter: function () {
                                var $this = $(this);
                                var searchResult = qsRegex ? $this.text().match(qsRegex) : true;
                                var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;
                                return searchResult && buttonResult;
                            }
                        });
                        setTimeout(function () {
                            IsotopeWrap.isotope();
                            container.trigger('tpg_item_after_load');
                            remove_placeholder_loading();
                        }, 100);
                    });
                    // use value of search field to filter
                    var $quicksearch = container.find('.iso-search-input').keyup(debounce(function () {
                        qsRegex = new RegExp($quicksearch.val(), 'gi');
                        IsotopeWrap.isotope();
                    }));

                    IsoButton.on('click touchstart', 'button', function (e) {
                        e.preventDefault();
                        buttonFilter = $(this).attr('data-filter');
                        if (IsoURL) {
                            location.hash = "filter=" + encodeURIComponent(buttonFilter);
                        } else {
                            IsotopeWrap.isotope();
                            $(this).parent().find('.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                    });
                    if (IsoURL) {
                        windowHashChange(IsotopeWrap, IsoButton);
                        $(window).on("hashchange", function () {
                            windowHashChange(IsotopeWrap, IsoButton);
                        });
                    }
                    if (IsoCount) {
                        isoFilterCounter(container, IsotopeWrap);
                    }
                    IsoDropdownFilter.on('change', function (e) {
                        e.preventDefault();
                        buttonFilter = $(this).val();
                        IsotopeWrap.isotope();
                    });
                } else if (container.find('.rt-row.rt-content-loader.tpg-masonry').length) {
                    var masonryTarget = $('.rt-row.rt-content-loader.tpg-masonry', container);
                    container.trigger('tpg_item_before_load');
                    tpgFixLazyLoad();
                    mIsotopWrap = masonryTarget.imagesLoaded(function () {
                        preFunction();
                        mIsotopWrap.isotope({
                            itemSelector: '.masonry-grid-item',
                            masonry: {columnWidth: '.masonry-grid-item'}
                        });
                        container.trigger('tpg_item_after_load');
                        remove_placeholder_loading();
                    });
                }
                animateAction();
            }

            $('#' + id).on('click', '.rt-search-filter-wrap .rt-action', function (e) {
                search_wrap.find('input').prop("disabled", true);
                ajax_action();
            });
            $('#' + id).on('keypress', '.rt-search-filter-wrap .rt-search-input', function (e) {
                if (e.which == 13) {
                    search_wrap.find('input').prop("disabled", true);
                    ajax_action();
                }
            });
            $('#' + id).on('click', '.rt-filter-dropdown-wrap', function (event) {
                var self = $(this);
                self.toggleClass('active-dropdown');
            });// Dropdown click

            $('#' + id).on('click', '.term-dropdown-item', function (event) {
                $loadmore.addClass('rt-lm-loading');
                var $this_item = $(this),
                    dropDownWrap = $this_item.parents('.rt-filter-dropdown-wrap'),
                    dropDown = $this_item.parent('.rt-filter-dropdown'),
                    default_target = dropDownWrap.find('>.rt-filter-dropdown-default'),
                    subTerms = $(".sub-dropdown-wrap", $this_item).clone();
                console.log(subTerms);
                dropDownWrap.removeClass('active-dropdown');
                dropDownWrap.toggleClass('active-dropdown');
                default_target.attr('data-term', $this_item.attr('data-term'));
                default_target.find('>.rt-text').html($this_item.find('>.rt-text').html());
                dropDown.find('.rt-filter-dropdown-item').removeClass('selected');
                $this_item.addClass('selected');
                if (dropDownWrap.data('taxonomy')) {
                    $this_item.parents('.rt-filter-wrap').find('>.sub-dropdown-wrap').remove();
                }
                if (subTerms.length) {
                    subTerms.insertAfter(dropDownWrap);
                }
                ajax_action();
            });//term

            $('#' + id).on('click', '.order-by-dropdown-item', function (event) {
                $loadmore.addClass('rt-lm-loading');
                var $this_item = $(this),
                    old_param = $default_order_by.attr('data-order-by'),
                    old_text = $default_order_by.find('.rt-text-order-by').html();

                $this_item.parents('.rt-order-by-action').removeClass('active-dropdown');
                $this_item.parents('.rt-order-by-action').toggleClass('active-dropdown');
                $default_order_by.attr('data-order-by', $this_item.attr('data-order-by'));
                $default_order_by.find('.rt-text-order-by').html($this_item.html());
                $this_item.attr('data-order-by', old_param);
                $this_item.html(old_text);
                ajax_action();
            });//Order By

            //Sort Order
            $('#' + id).on('click', '.rt-sort-order-action', function (event) {
                $loadmore.addClass('rt-lm-loading');
                var $this_item = $(this),
                    $sort_order_elm = $('.rt-sort-order-action-arrow', $this_item),
                    sort_order_param = $sort_order_elm.attr('data-sort-order');
                if (typeof (sort_order_param) != 'undefined' && sort_order_param.toLowerCase() == 'desc') {
                    $default_order.attr('data-sort-order', 'ASC');
                } else {
                    $default_order.attr('data-sort-order', 'DESC');
                }
                ajax_action();
            });//Sort Order

            $taxonomy_filter.on('click', '.rt-filter-button-item', function () {
                var self = $(this);
                self.parents('.rt-filter-button-wrap').find('.rt-filter-button-item').removeClass('selected');
                self.addClass('selected');
                $("> .rt-filter-sub-tax", filter_container).remove();
                subTax(self);
                ajax_action();
            });

            author_filter.on('click', '.rt-filter-button-item', function () {
                var self = $(this);
                self.parents('.rt-filter-button-wrap').find('.rt-filter-button-item').removeClass('selected');
                self.addClass('selected');
                ajax_action();
            });

            $page_prev_next.on('click', '.rt-cb-prev-btn', function (event) {
                if (tpg_paged <= 1) {
                    return;
                }
                tpg_paged = tpg_paged - 1;
                ajax_action(true, false);
            });
            $page_prev_next.on('click', '.rt-cb-next-btn', function (event) {
                if (tpg_paged >= tpg_total_pages) {
                    return;
                }
                tpg_paged = tpg_paged + 1;
                ajax_action(true, false);
            });
            load_gallery_image_popup();
            container.trigger("tpg_loaded");
        });
    };
    initTpgPro();

    $(window).on('load resize', function () {
        HeightResize();
        overlayIconResizeTpg();
        mdPopUpResize();
        rtAlertPosition();
    });

    $(document).on({
        mouseenter: function () {
            var $this = $(this);
            var $parent = $(this).parents('.rt-tpg-container');
            var id = $parent.attr('id').replace("rt-tpg-container-", "");
            var $title = $this.attr('title');
            $tooltip = '<div class="rt-tooltip" id="rt-tooltip-' + id + '">' +
                '<div class="rt-tooltip-content">' + $title + '</div>' +
                '<div class="rt-tooltip-bottom"></div>' +
                '</div>';
            $('body').append($tooltip);
            var $tooltip = $('body > .rt-tooltip');
            var tHeight = $tooltip.outerHeight();
            var tBottomHeight = $tooltip.find('.rt-tooltip-bottom').outerHeight();
            var tWidth = $tooltip.outerWidth();
            var tHolderWidth = $this.outerWidth();
            var top = $this.offset().top - (tHeight + tBottomHeight) + 14;
            var left = $this.offset().left;
            $tooltip.css('top', top + 'px');
            $tooltip.css('left', left + 'px');
            $tooltip.css('opacity', 1);
            $tooltip.show();
            if (tWidth <= tHolderWidth) {
                var itemLeft = (tHolderWidth - tWidth) / 2;
                left = left + itemLeft;
                $tooltip.css('left', left + 'px');
            } else {
                var itemLeft = (tWidth - tHolderWidth) / 2;
                left = left - itemLeft;
                if (left < 0) {
                    left = 0;
                }
                $tooltip.css('left', left + 'px');
            }
        },
        mouseleave: function () {
            $('body > .rt-tooltip').remove();
        }
    }, '.rt-tpg-social-share a');

    $('.rt-wc-add-to-cart').on('click', function (e) {
        e.preventDefault();
        var $pType = $(this).data('type'),
            $pID = $(this).data('id'),
            self = $(this);
        if (rttpg.woocommerce_enable_ajax_add_to_cart == "no") {
            window.location = self.attr('href');
        } else {
            if ($pType == 'simple') {
                if ($pID) {
                    var data = "id=" + $pID,
                        cart_text = "<div class='rt-woo-view-cart'><a href='" + wc_add_to_cart_params.cart_url + "'>" + wc_add_to_cart_params.i18n_view_cart + "</a></div>";
                    AjaxCall($(this), 'addToCartWc', data, function (data) {
                        if (!data.error) {
                            $('body').append("<div class='rt-response-alert'><div class='rt-alert'>" + data.msg + cart_text + "<span class='cross'>X</span></div></div>");
                            rtAlertPosition();
                            if (rttpg.woocommerce_cart_redirect_after_add == "yes") {
                                window.location.href = wc_add_to_cart_params.cart_url
                            }
                        }
                    });
                }
            } else {
                window.location = self.attr('href');
            }
        }
        return false;
    });

    $(document).on('click', '.rt-response-alert span.cross', function () {
        $(this).parents('.rt-response-alert').fadeOut(1500, function () {
            $(this).remove();
        });
    });

    function rtAlertPosition() {
        var target = $('.rt-alert');
        target.css({
            left: ($(window).width() - target.outerWidth()) / 2,
            top: ($(window).height() - target.outerHeight()) / 2
        });
    }

    function windowHashChange(isotope, IsoButton) {
        var $hashFilter = decodeHash() || '';
        if (!$hashFilter) {
            $hashFilter = IsoButton.find('button.selected').attr('data-filter') || '';
            $hashFilter = $hashFilter ? $hashFilter : '*';
        }
        $hashFilter = $hashFilter || '*';
        isotope.isotope({
            filter: $hashFilter
        });
        IsoButton.find("button").removeClass("selected");
        IsoButton.find('button[data-filter="' + $hashFilter + '"]').addClass("selected");
    }

    function decodeHash() {
        var $matches = location.hash.match(/filter=([^&]+)/i);
        var $hashFilter = $matches && $matches[1];
        return $hashFilter && decodeURIComponent($hashFilter);
    }

    function isoFilterCounter(container, isotope) {
        var total = 0;
        container.find('.rt-tpg-isotope-buttons button').each(function () {
            var self = $(this),
                filter = self.attr("data-filter"),
                itemTotal = isotope.find(filter).length;
            if (filter != "*") {
                self.find('span').remove();
                self.append("<span> (" + itemTotal + ") </span>");
                total = total + itemTotal;
            }
        });
        container.find('.rt-tpg-isotope-buttons button[data-filter="*"]').find('span').remove();
        container.find('.rt-tpg-isotope-buttons button[data-filter="*"]').append("<span> (" + total + ") </span>");
    }

// debounce so filtering doesn't happen every millisecond
    function debounce(fn, threshold) {
        var timeout;
        return function debounced() {
            if (timeout) {
                clearTimeout(timeout);
            }

            function delayed() {
                fn();
                timeout = null;
            }

            setTimeout(delayed, threshold || 100);
        };
    }

    function preFunction() {
        HeightResize();
        overlayIconResizeTpg();
    }

    function renderIsotope(container, $isotope, data, IsoButton) {

        var qsRegexG, buttonFilter;
        if (!buttonFilter) {
            buttonFilter = IsoButton.find('button.selected').data('filter');
        }

        $isotope.append(data)
            .isotope('appended', data)
            .isotope('reloadItems')
            .isotope('updateSortData')
            .isotope();
        preFunction();
        setTimeout(function () {
            preFunction();
            $isotope.isotope();
        }, 100);

        $(IsoButton).on('click touchstart', 'button', function (e) {
            e.preventDefault();
            buttonFilter = $(this).attr('data-filter');
            location.hash = "filter=" + encodeURIComponent(buttonFilter);
        });
        var $quicksearch = container.find('.iso-search-input').keyup(debounce(function () {
            qsRegexG = new RegExp($quicksearch.val(), 'gi');
            $isotope.isotope();
        }));
        if (IsoButton.attr('data-count')) {
            isoFilterCounter(container, $isotope);
        }
    }


    $(".rt-tpg-container a.disabled").each(function () {
        $(this).prop("disabled", true);
        $(this).removeAttr("href");
    });

    function animation() {
        var $pHolder = $('#rt-popup-wrap');
        if (parseInt($pHolder.css('marginLeft')) === 0) {
            $('body, html').removeClass('rt-model-open');
        }
        $pHolder.animate({
            marginLeft: parseInt($pHolder.css('marginLeft'), 10) == 0 ?
                $pHolder.outerWidth() : 0,
        }).promise().done(function () {
            if (parseInt($pHolder.css('marginLeft')) > 0) {
                $pHolder.remove();
            } else {
                $('body, html').addClass('rt-model-open');
            }
        });
    }

    $(document).on('click', '.rt-tpg-container .tpg-single-popup', function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id"),
            wrap_id = $(this).parents('.rt-tpg-container').attr('id').replace("rt-tpg-container-", "");
        var data = "action=tgpSinglePopUp&id=" + id;
        $.ajax({
            type: "post",
            url: rttpg.ajaxurl,
            data: data,
            beforeSend: function () {
                $('body, html').addClass('rt-model-open');
                $("#rt-modal").addClass('md-show rt-modal-' + wrap_id);
                $("#rt-modal .rt-md-content-holder").html('<div class="rt-md-loading">Loading...</div>');
            },
            success: function (data) {
                $("#rt-modal .rt-md-content-holder").html(data.data);
            },
            error: function () {
                $('body, html').removeClass('rt-model-open');
                console.log('Error');
            }
        });
    });
    $('.md-close, .md-overlay').on('click', function (e) {
        e.preventDefault();
        $('body, html').removeClass('rt-model-open');
        $("#rt-modal").removeClass('md-show');
        $("#rt-modal .rt-md-content-holder").html('');
    });
    $(window).bind('keydown', function (event) {
        if (event.keyCode === 27) { // Esc
            $('body, html').removeClass('rt-model-open');
            $("#rt-modal").removeClass('md-show');
            $("#rt-modal .rt-md-content-holder").html('');
        }
    });

    $(document).on('click', '.rt-tpg-container .tpg-multi-popup', function () {

        var current;
        var id = $(this).data("id");
        current = id;
        var itemArray;
        var item_count_holder = $(this).parents('.rt-tpg-container').find('.rt-row.rt-content-loader .rt-grid-item'),
            wrap_id = $(this).parents('.rt-tpg-container').attr('id').replace("rt-tpg-container-", "");
        if (item_count_holder.length) {
            itemArray = item_count_holder.map(function () {
                return $(this).data("id");
            }).get();
        }
        var data = "action=tgpMultiPagePopUp&id=" + id;
        $.ajax({
            type: "post",
            url: rttpg.ajaxurl,
            data: data,
            beforeSend: function () {
                initPopupTeamPro(wrap_id);
                setLevelTgpPro(current, itemArray);
            },
            success: function (data) {
                $("#rt-popup-wrap .rt-popup-content").html(data.data);
            },
            error: function () {
                $('body, html').removeClass('rt-model-open');
                $("#rt-popup-wrap .rt-popup-content").html("<p>Loading error!!!</p>");
            }
        });

        $('.rt-popup-next').on('click', function () {
            rightClick();
        });
        $('.rt-popup-prev').on('click', function () {
            leftClick();
        });
        $('.rt-popup-close').on('click', function () {
            animation();
        });

        $(window).bind('keydown', function (event) {
            if (event.keyCode === 27) { // Esc
                animation();
            } else if (event.keyCode === 37) { // left arrow
                leftClick();
            } else if (event.keyCode === 39) { // right arrow
                rightClick();
            }
        });

        function rightClick() {
            var nextId = nextItem(current, itemArray);
            current = nextId;
            var data = "action=tgpMultiPagePopUp&id=" + current;
            $.ajax({
                type: "post",
                url: rttpg.ajaxurl,
                data: data,
                beforeSend: function () {
                    setLevelTgpPro(current, itemArray);
                    $('#rt-popup-wrap .rt-popup-content').html('<div class="rt-popup-loading"></div>');
                },
                success: function (data) {
                    $('#rt-popup-wrap .rt-popup-content').html(data.data);
                }
            });
        }

        function leftClick() {
            var prevId = prevItem(current, itemArray);
            current = prevId;
            var data = "action=tgpMultiPagePopUp&id=" + current;
            $.ajax({
                type: "post",
                url: rttpg.ajaxurl,
                data: data,
                beforeSend: function () {
                    setLevelTgpPro(current, itemArray);
                    $('#rt-popup-wrap .rt-popup-content').html('<div class="rt-popup-loading"></div>');
                },
                success: function (data) {
                    $('#rt-popup-wrap .rt-popup-content').html(data.data);
                }
            });
        }

        return false;
    });

    function initPopupTeamPro(wrap_id) {
        var html = '<div id="rt-popup-wrap" class="rt-popup-wrap rt-popup-singlePage-sticky rt-popup-singlePage rt-popup-wrap-' + wrap_id + '">' +
            '<div class="rt-popup-content">' +
            '<div class="rt-popup-loading"></div>' +
            '</div>' +
            '<div class="rt-popup-navigation-wrap">' +
            '<div class="rt-popup-navigation">' +
            '<div class="rt-popup-prev" title="Previous (Left arrow key)" data-action="prev"></div>' +
            '<div class="rt-popup-close" title="Close (Esc arrow key)" data-action="close"></div>' +
            '<div class="rt-popup-next" title="Next (Right arrow key)" data-action="next"></div>' +
            '<div class="rt-popup-singlePage-counter"><span class="ccurrent"></span> of <span class="ctotal"></span></div>' +
            '</div>' +
            '</div>' +
            '</div>';
        $("body").append(html);
        var $pHolder = $('#rt-popup-wrap');
        $pHolder.css('display', 'block');
        var navHeight = $pHolder.find('.rt-popup-navigation-wrap').height();
        $pHolder.find('.rt-popup-content').css('padding-top', navHeight + "px");
        animation();
    }

    function nextItem(current, list) {
        var index = list.indexOf(current);
        index++;
        if (index >= list.length)
            index = 0;
        return list[index];
    }

    function prevItem(current, list) {
        var index = list.indexOf(current);
        index--;
        if (index < 0)
            index = list.length - 1;
        return list[index];
    }


    function setLevelTgpPro(current, list) {
        var index = list.indexOf(current) + 1;
        var count = list.length;
        $(".ccurrent").text(index);
        $(".ctotal").text(count);
    }

    function navResize() {
        var $pHolder = jQuery('#rt-popup-wrap');
        $pHolder.css('display', 'block');
    }


    $(document).on('click', ".wc-product-holder .wc-tabs li a", function (e) {
        e.preventDefault();
        var container = $(this).parents('.wc-tabs-wrapper');
        var nav = container.children('.wc-tabs');
        var content = container.children(".panel.entry-content");
        var $this, $id;
        $this = $(this);
        $id = $this.attr('href');
        content.hide();
        nav.find('li').removeClass('active');
        $this.parent().addClass('active');
        container.find($id).show();
    });
    $(document).on('click', "a.woocommerce-main-image.zoom", function (e) {
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.wc-product-holder .single_add_to_cart_button', function (e) {
        e.preventDefault();
        var $this = $(this),
            msgTarget = $this.parents(".wc-add-to-cart"),
            $pID = msgTarget.find('form button[name="add-to-cart"]').val(),
            qtn = msgTarget.find('form input[name="quantity"]').val();
        //var data = rttpg.nonceID + "=" + rttpg.nonce + "&action=addToCartWc&id=" + $pID;
        if (rttpg.woocommerce_enable_ajax_add_to_cart === "no") {
            $this.parents('form').submit();
        } else {
            var data = "id=" + $pID + "&qtn=" + qtn,
                cart_text = "<div class='rt-woo-view-cart'><a href='" + wc_add_to_cart_params.cart_url + "'>" + wc_add_to_cart_params.i18n_view_cart + "</a></div>";
            AjaxCall($(this), 'addToCartWc', data, function (data) {
                if (!data.error) {
                    $('body').append("<div class='rt-response-alert'><div class='rt-alert'>" + data.msg + cart_text + "<span class='cross'>X</span></div></div>");
                    rtAlertPosition();
                    if (rttpg.woocommerce_cart_redirect_after_add === "yes") {
                        window.location.href = wc_add_to_cart_params.cart_url
                    }
                } else {
                    $('body').append("<div class='rt-response-alert'><div class='rt-alert'>" + data.msg + "</div></div>");
                    rtAlertPosition();
                    $("body").children('.rt-response-alert').fadeOut(2000, function () {
                        $(this).remove();
                    });
                }
            });
        }
        return false;
    });

    function AjaxCall(element, action, arg, handle) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;
        data = data;

        var n = data.search(rttpg.nonceID);
        if (n < 0) {
            data = data + "&" + rttpg.nonceID + "=" + rttpg.nonce;
        }

        $.ajax({
            type: "POST",
            url: rttpg.ajaxurl,
            data: data,
            beforeSend: function () {
                element.append("<span class='rt-spine-loading'></span>");
            },
            success: function (data) {
                element.find(".rt-spine-loading").remove();
                handle(data);
            }
        });
    }

    function HeightResize() {
        var wWidth = $(window).width();
        $(".rt-tpg-container").each(function () {
            var self = $(this),
                dCol = self.data('desktop-col'),
                tCol = self.data('tab-col'),
                mCol = self.data('mobile-col'),
                target = $(this).find('.rt-row.rt-content-loader.tpg-even');
            if ((wWidth >= 992 && dCol > 1) || (wWidth >= 768 && tCol > 1) || (wWidth < 768 && mCol > 1)) {
                target.imagesLoaded(function () {
                    var tlpMaxH = 0;
                    target.find('.even-grid-item').height('auto');
                    target.find('.even-grid-item').each(function () {
                        var $thisH = $(this).outerHeight();
                        if ($thisH > tlpMaxH) {
                            tlpMaxH = $thisH;
                        }
                    });
                    target.find('.even-grid-item').height(tlpMaxH + "px");
                });
            } else {
                target.find('.even-grid-item').height('auto');
            }

        });
        if ($(".rt-row.rt-content-loader.layout4").length) {
            equalHeight4Layout4();
        }

        function equalHeight4Layout4() {
            var $maxH = $(".rt-row.rt-content-loader.layout4 .layout4item").height();
            $(".rt-row.rt-content-loader.layout4 .layout4item .layoutInner .rt-img-holder img,.rt-row.rt-content-loader.layout4 .layout4item .layoutInner.layoutInner-content").height($maxH + "px");
        }
    }


    function overlayIconResizeTpg() {
        $('.overlay').each(function () {
            var holder_height = jQuery(this).height();
            var target = $(this).children('.link-holder');
            var targetd = $(this).children('.view-details');
            var a_height = target.height();
            var ad_height = targetd.height();
            var h = (holder_height - a_height) / 2;
            var hd = (holder_height - ad_height) / 2;
            target.css('top', h + 'px');
            targetd.css('margin-top', hd + 'px');
        });
    }


    function mdPopUpResize() {
        var target = jQuery("#rt-modal .rt-md-content-holder"),
            targetHeight = target.outerHeight(),
            title = target.find(".md-header").outerHeight(),
            contentHeight = targetHeight - title - 25;
        target.find(".rt-md-content").height(contentHeight + "px");
    }

    window.tpgMdScriptLoad = function () {
        mdPopUpResize();
        jQuery('.rt-md-content').mCustomScrollbar({
            scrollbarPosition: 'outside'
        });
    };

    window.tpgWcFunctionRun = function () {
        jQuery(".wc-product-holder .tabs.wc-tabs li:first-child a").trigger('click');
        jQuery(document).ready(function () {
            jQuery('a.woocommerce-main-image.zoom').zoom();
        });

        jQuery("#rt-product-gallery.hasImg").owlCarousel({
            items: 1,
            dot: true
        });
    };

})(jQuery);



