$(document).ready(function () {
    function caclreadTime(text, imageCount = 0) {
        // 90 word per minute
        const wordsPerMinute = 90;
        const words = text.trim().split(/\s+/).length;
        let readingTime = words / wordsPerMinute;
        let imageSeconds = 0;
        for (let i = 1; i <= imageCount; i++) {
            imageSeconds += Math.max(3, 12 - i);
        }
        readingTime += imageSeconds / 60;
        return Math.ceil(readingTime);
    }
    const totalImages = document.querySelectorAll('img').length;
    let text = "";
    let timetoread = caclreadTime(text, totalImages);
    const span = document.querySelector('span[hidden]');

    if (span && span.textContent.trim() === '__readtime__') {
        span.outerHTML = timetoread;
        span.removeAttribute('hidden');
    }

    function clearFilters() {
        const form = document.getElementById('filterForm');
        form.reset();
        const radios = form.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => radio.checked = false);
        const selects = form.querySelectorAll('select');
        selects.forEach(select => select.selectedIndex = 0);
        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.value = '';
        }
        history.replaceState(null, null, window.location.pathname);
    }

    $('#clearFiltersButton').on('click', function () {
        clearFilters();
    });


    var $carousel = $('.related-files-carousel');

    if ($carousel.length && typeof $.fn.owlCarousel === 'function') {
        var itemCount = $carousel.find('.item').length;
        $carousel.owlCarousel({
            margin: 15,
            autoplay: itemCount > 1,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1,
                    nav: itemCount > 1,
                    dots: itemCount > 1,
                    loop: itemCount > 1
                },
                576: {
                    items: 2,
                    nav: itemCount > 2,
                    dots: itemCount > 2,
                    loop: itemCount > 2
                },
                992: {
                    items: 3,
                    nav: itemCount > 3,
                    dots: itemCount > 3,
                    loop: itemCount > 3
                }
            },
            navText: [
                '<i class="ki-duotone ki-left fs-5"></i>',
                '<i class="ki-duotone ki-right fs-5"></i>'
            ]
        });
    }

    $('#resetFilter').on('click', function () {
        var $form = $('#filterForm');
        $form.find('input[type="text"], textarea').val('');
        $form.find('select').prop('selectedIndex', 0);
        $form.find('input[type="radio"]').prop('checked', false);
        $form.submit();
    });

    // make active category
    $('.sub-folder.active, .post-link.active').each(function () {

        // Expand all parents
        $(this).parents('.dropdown_nav').removeClass('d-none');
        $(this).closest('.dropdown_nav').removeClass('d-none');
        $(this).closest('.child-nav').removeClass('d-none');

        // Activate main category
        let $mainCat = $(this)
            .closest('.nav-item')
            .parents('.nav-item')
            .first()
            .find('> .doc-link .cat-link');

        $mainCat.addClass('active');

        // ONLY MAIN CATEGORY ICON OPEN
        $mainCat.closest('.nav-item')
            .find('> .parent_link .icon-wrapper .folder_open').show();

        $mainCat.closest('.nav-item')
            .find('> .parent_link .icon-wrapper .folder_close').hide();


        // Toggle arrows (keep your logic)
        $(this).parents('.nav-item').each(function () {
            $(this).children('.doc-link')
                .find('.arrow_carrot-down')
                .addClass('d-none');

            $(this).children('.doc-link')
                .find('.arrow_carrot-up')
                .removeClass('d-none');
        });

    });
    // FIX: Sub-category own arrow
    $('.sub-folder.active').each(function () {

        let $sub = $(this);

        $sub.find('.arrow_carrot-down').addClass('d-none');
        $sub.find('.arrow_carrot-up').removeClass('d-none');

        // ONLY ACTIVE SUB CATEGORY ICON
        let iconWrap = $sub.find('.icon-wrapper');

        iconWrap.find('.folder_open').show();
        iconWrap.find('.folder_close').hide();
    });
    $('.parent_link .dropdown_icon').on('click', function (e) {
        e.preventDefault();

        let parentLi = $(this).closest('.nav-item');
        let dropdown = parentLi.children('.dropdown_nav').children('.child-nav');

        // Close others
        $('.nav-sidebar > .nav-item').not(parentLi).each(function () {
            $(this).find('.child-nav').addClass('d-none');

            // arrows
            $(this).find('> .doc-link .arrow_carrot-down').removeClass('d-none');
            $(this).find('> .doc-link .arrow_carrot-up').addClass('d-none');

            // icons (ONLY MAIN LEVEL)
            $(this).find('> .doc-link .folder_open').hide();
            $(this).find('> .doc-link .folder_close').show();
        });

        // Toggle current
        dropdown.toggleClass('d-none');

        // arrows
        let arrows = parentLi.find('> .doc-link .dropdown_icon');
        arrows.find('.arrow_carrot-down').toggleClass('d-none');
        arrows.find('.arrow_carrot-up').toggleClass('d-none');

        let iconWrap = parentLi.find('> .doc-link .icon-wrapper');

        if (dropdown.hasClass('d-none')) {
            iconWrap.find('.folder_open').hide();
            iconWrap.find('.folder_close').show();
        } else {
            iconWrap.find('.folder_open').show();
            iconWrap.find('.folder_close').hide();
        }
    });
    $('.child-nav .dropdown_icon').on('click', function (e) {
        e.preventDefault();

        let parentLi = $(this).closest('.nav-item');
        let dropdown = parentLi.children('.dropdown_nav');

        // Close siblings
        parentLi.siblings('.nav-item').each(function () {

            $(this).find('> .dropdown_nav').addClass('d-none');

            $(this).find('.arrow_carrot-down').removeClass('d-none');
            $(this).find('.arrow_carrot-up').addClass('d-none');

            // reset icons (ONLY SAME LEVEL)
            $(this).find('> div .folder_open').hide();
            $(this).find('> div .folder_close').show();
        });

        // Toggle current
        dropdown.toggleClass('d-none');

        // arrows
        let row = $(this).closest('.d-flex');
        row.find('.arrow_carrot-down').toggleClass('d-none');
        row.find('.arrow_carrot-up').toggleClass('d-none');

        // fIX ICON (ONLY THIS SUB CATEGORY)
        let iconWrap = parentLi.find('> div .icon-wrapper');

        if (dropdown.hasClass('d-none')) {
            iconWrap.find('.folder_open').hide();
            iconWrap.find('.folder_close').show();
        } else {
            iconWrap.find('.folder_open').show();
            iconWrap.find('.folder_close').hide();
        }
    });
    $('#qsearch').on('keyup', function () {
        let value = $(this).val().toLowerCase().trim();
        let hasResults = false;

        // reset everything first
        $('.nav-sidebar .nav-item').removeClass('hidden-search');

        if (value === '') {
            // restore full tree
            $('.nav-sidebar .nav-item').show();
            $('.dropdown_nav, .child-nav, .posts-navbar').addClass('d-none');
            $('.no-results').hide();
            return;
        }

        // STEP 1: hide all items initially
        $('.nav-sidebar .nav-item').hide();

        // STEP 2: find matches (category / subcategory / posts)
        $('.nav-sidebar .nav-item').each(function () {
            let $item = $(this);

            let text = $item.find('> .doc-link, a').text().toLowerCase();

            if (text.includes(value)) {
                hasResults = true;

                // show current item
                $item.show();

                // show all parents
                $item.parents('.nav-item').each(function () {
                    $(this).show();
                });

                // expand parent containers
                $item.parents('.dropdown_nav').removeClass('d-none');
                $item.parents('.child-nav').removeClass('d-none');
                $item.parents('.posts-navbar').removeClass('d-none');
            }
        });

        // STEP 3: handle no results
        if (!hasResults) {
            $('.no-results').show();
        } else {
            $('.no-results').hide();
        }
    });


    function fetchPoplar() {
        $.ajax({
            url: '/api/popularinsight',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var html = '';
                if (response.results.length > 0) {
                    $.each(response.results, function (i, item) {
                        html = html + '<li class="wow animate__fadeInUp" data-wow-delay="0.4s"><a href="' + item.url + '">' + item.title + '</a></li>';
                    });
                } else {
                    html = '<li>No results found</li>';
                }
                $('#header_search_keyword .list-unstyled').html(html);
            },
            error: function () {
                $searchResult.html('<p>Error loading results</p>');
            }
        });
    }
    fetchPoplar();

    var $searchInput = $("#searchInput");
    var $searchResult = $("#search-result");
    var $searchClear = $("#searchClear");

    if ($searchResult.length > 0) {
        $searchResult.hide();
    }

    function filterSearch() {

        var query = $.trim($searchInput.val()).toLowerCase();

        if (query !== "") {

            $searchResult.show();
            $searchClear.show();

            $.ajax({
                url: '/api/search',
                type: 'GET',
                data: { q: query },
                dataType: 'json',
                success: function (response) {
                    var html = '';
                    if (response.results.length > 0) {
                        html += '<div class="search-result-wrapper">';
                        $.each(response.results, function (i, item) {
                            html += '<div class="search-result-item">';
                            html += '<a href="' + item.url + '" class="title d-flex justify-content-start align-items-center flex-row gap-1">';
                            html += '<svg width="16px" aria-labelledby="title" viewBox="0 0 17 17" fill="currentColor" class="block h-full w-auto" role="img"><title id="title">Building Search UI</title><path d="M14.72,0H2.28A2.28,2.28,0,0,0,0,2.28V14.72A2.28,2.28,0,0,0,2.28,17H14.72A2.28,2.28,0,0,0,17,14.72V2.28A2.28,2.28,0,0,0,14.72,0ZM2.28,1H14.72A1.28,1.28,0,0,1,16,2.28V5.33H1V2.28A1.28,1.28,0,0,1,2.28,1ZM1,14.72V6.33H5.33V16H2.28A1.28,1.28,0,0,1,1,14.72ZM14.72,16H6.33V6.33H16v8.39A1.28,1.28,0,0,1,14.72,16Z"></path></svg> ';
                            html += item.title;
                            html += '</a>';

                            if (item.category && item.category.length > 0) {
                                html += '<ol class="breadcrumb eazydocs-search-wrapper">';
                                $.each(item.category, function (index, cat) {
                                    html += '<li class="breadcrumb-item">';
                                    html += '<a href="' + cat.url + '">' + cat.title + '</a>';
                                    html += '</li>';
                                });
                                html += '</ol>';
                            }
                            html += '<svg viewBox="0 0 24 24" class="searchresult_svg" fill="none" color="white" stroke="white" width="16px" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block h-auto w-16"><polyline points="9 10 4 15 9 20"></polyline><path d="M20 4v7a4 4 0 0 1-4 4H4"></path></svg>'
                            html += '</div>';
                        });
                        html += '</div>';
                        $searchResult
                            .html(html)
                            .addClass("show-results")
                            .fadeIn(150);
                        setTimeout(function () {
                            $('html, body').animate({
                                scrollTop: $("#search-result").offset().top - 120
                            }, 100);
                        }, 200);
                    } else {
                        html = '<p class="no_result">No results found</p>';
                        $searchResult
                            .html(html)
                            .removeClass("show-results")
                            .fadeIn(150);
                    }
                    $('#search-result').html(html);
                    $searchResult
                        .html(html)
                        .addClass("show-results")
                        .fadeIn(150);
                },
                error: function () {
                    $searchResult.html('<p class="no_result">Error loading results</p>');
                    $searchResult
                        .html(html)
                        .removeClass("show-results")
                        .fadeIn(150);
                }
            });

        } else {
            $searchResult
                .removeClass("show-results")
                .hide()
                .html("");
            $searchResult.hide();
            $searchClear.hide();
            $searchResult.html("");

        }
    }

    $searchInput.on("input", filterSearch);

    $searchClear.on("click", function () {
        $searchInput.val("");
        $searchResult.hide();
        $searchClear.hide();
        $searchResult.html("");
    });


    // search overlay click 
    jQuery(".doc_banner_area input[type=search]").on("focus", function () {
        jQuery("body").addClass("search-focused");
    });
    jQuery(".focus_overlay").on("click", function () {
        jQuery("body").removeClass("search-focused");

        jQuery("#search-result").hide();
        jQuery("#searchClear").hide();
    });


    // by clicking on share button modal open 
    $(document).on("click", ".ezd-share-btn", function (e) {
        e.preventDefault();
        $("#share-url").val(window.location.href);
        $("#eazydocs_share").fadeIn();
    });
    $(document).on("click", ".share-this-doc", function () {
        const $input = $(this).closest(".copy-url-wrap").find("input");
        const text = $input.val();
        if (!text) {
            alert("No URL found!");
            return;
        }
        navigator.clipboard.writeText(text)
            .then(() => {
                $(".ezd-link-copied-wrap")
                    .addClass("show")
                    .fadeIn()
                    .delay(2000)
                    .queue(function (next) {
                        $(this).removeClass("show").fadeOut();
                        next();
                    });
            })
            .catch(() => {
                $input.select();
                document.execCommand("copy");
            });
    });
    $(document).on("click", ".ezd-close, .ezd-modal-overlay", function () {
        $("#eazydocs_share").fadeOut();
    });

    // size increase descrease of text
    var step = 1;
    var minSize = 10;
    var maxSize = 60;

    var $elements = $('.post_detail').find('*').not('#font-switcher, #font-switcher *');

    $elements.each(function () {
        var originalSize = $(this).css('font-size');
        $(this).attr('data-original-size', originalSize);
    });

    function changeFontSize(direction) {

        $elements.each(function () {

            var $el = $(this);
            var currentSize = parseFloat($el.css('font-size'));
            if (!currentSize) return;

            var newSize = currentSize;

            if (direction === 'increase') {
                newSize = currentSize + step;
            }

            if (direction === 'decrease') {
                newSize = currentSize - step;
            }

            if (newSize >= minSize && newSize <= maxSize) {
                $el.css('font-size', newSize + 'px');
            }

        });
    }

    $('#switcher-large').on('click', function (e) {
        e.preventDefault();
        changeFontSize('increase');
    });

    $('#switcher-small').on('click', function (e) {
        e.preventDefault();
        changeFontSize('decrease');
    });

    $('#switcher-default').on('click', function (e) {
        e.preventDefault();

        $elements.each(function () {
            var originalSize = $(this).attr('data-original-size');
            $(this).css('font-size', originalSize);
        });
    });

    // print page 
    $(document).on('click', '.print', function (e) {
        e.preventDefault();
        window.print();
    });
});
