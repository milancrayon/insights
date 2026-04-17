$(document).ready(function () {

    let postbl = new DataTable('#be_table_posts', {
        responsive: true,
        searching: true,
        ordering: true,
        paging: true,
        serverSide: true,
        processing: true,
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        layout: {
            topStart: {
                div: {
                    className: 'dt-filters-left'
                }
            },
            topEnd: [
                'search',
                'buttons',
                {
                    buttons: [{
                        extend: 'colvis',
                        columns: function (idx, data, node) {
                            const title = $(node).text().trim();
                            return title !== "";
                        },
                        popoverTitle: 'Toggle Columns',
                        className: 'btn btn-primary'
                    }]
                },
            ],
            bottomStart: 'pageLength',
            bottomEnd: ['info', 'paging']
        },
        buttons: [
            {
                extend: 'collection',
                text: '<i class="ki-duotone ki-exit-down"></i> Export',
                buttons: ['csv', 'excel']
            },
        ],
        language: {
            search: '<i class="ki-duotone ki-magnifier"></i>',
            buttons: {
                colvis: '<i class="ki-duotone ki-setting-4"></i> Columns'
            }
        },
        ajax: {
            url: TYPO3.settings.ajaxUrls.insight_be_table_posts,
            type: 'GET'
        },
        columnDefs: [
            { targets: [8, 9, 10, 11, 12, 13, 14], visible: false },
            { targets: [7], orderable: false },
        ],
        columns: [
            {
                data: 'publishdate'
            }, {
                data: 'title',
                render: function (data, type, row, meta) {
                    let _html = '<div class="nametab" ><div class="author-info">';
                    if (row?.thumbnail) {
                        _html = _html + "<img src='" + row.thumbnail + "' alt='" + data + "'  width='50px' height='50px' class='author-img'  /> "
                    } else { 
                        _html = _html + `<img src="${meta.settings.json.blankImage}"
                      alt="No Image" width="50" height="50" class="author-img" />`
                    }
                    _html = _html + '<div class="author-text"><strong class="author-name"> ' + data + '</strong><p class="author-slug">' + row.slug + '</p></div></div>';
                    return _html
                }
            }, {
                data: 'category'
            }, {
                data: 'author'
            }, {
                data: 'tags'
            }, {
                data: 'viewers'
            }, {
                data: 'likes'
            }, {
                data: 'uid',
                render: function (data, type, row, meta) {
                    let _html = '<span class="actions">';
                    if (row?.editlink != "") {
                        _html = _html + '<a href="' + row.editlink + '" > <i class="ki-duotone ki-notepad-edit editicon"></i></a>';
                    }

                    _html = _html + '<div class="form-check form-switch"> <input type="checkbox" class="poststatus form-check-input t3js-toggle-record" data-uid="' + row.uid + '"  data-poststatus="' + row.poststatus + '"';
                    if (row.poststatus) {
                        _html = _html + 'checked >  </div> ';
                    } else {
                        _html = _html + ' >  </div> ';
                    }

                    _html = _html + '<div class="view-post" data-uid="' + row.uid + '">' +
                        '<i class="ki-duotone ki-eye eyeicon"></i>' +
                        '</div>';
                    _html += '</span>';
                    return _html
                }
            }, {
                data: 'uid'
            }, {
                data: 'alternativetitle'
            }, {
                data: 'metakeyword'
            }, {
                data: 'metadescription'
            }, {
                data: 'teaser'
            }, {
                data: 'description'
            }, {
                data: 'archivedate'
            }
        ],
        order: [[7, 'desc']],
        initComplete: function () {
            $('#be_table_posts_wrapper .dt-filters-left')
                .append($('.filters'));
        }

    });

    $('#filter-date').on('click', function () {
        this.showPicker();
    });

    $('#filter-date').on('change', function () {
        postbl.column(0).search(this.value).draw();
    });

    ['#filter-category', '#filter-author', '#filter-tag', '#comment-filter-post'].forEach(id => {
        if ($(id).length > 0) {
            $(id).select2({
                minimumResultsForSearch: Infinity
            });
        }
    });

    $('#filter-category').on('change', function () {
        postbl.column(2).search(this.value).draw();
    });

    $('#filter-author').on('change', function () {
        postbl.column(3).search(this.value).draw();
    });

    $('#filter-tag').on('change', function () {
        postbl.column(4).search(this.value).draw();
    });


    $(document).on("change", ".poststatus", function (e) {
        var $this = $(this);
        var uid = $this.data('uid');
        var poststatus = $this.data('poststatus');
        $.ajax({
            url: TYPO3.settings.ajaxUrls.insight_posts_status,
            type: 'GET',
            data: {
                uid: uid,
                poststatus: poststatus
            },
            success: function (result) {
                postbl.draw(false);
            }
        });
    });

    $(document).on("click", ".view-post", function (e) {
        var $this = $(this);
        var uid = $this.data('uid');
        $.ajax({
            url: TYPO3.settings.ajaxUrls.insight_posts_details,
            type: 'GET',
            data: {
                uid: uid
            },
            success: function (result) {
                let _data = result.data;
                if (_data) {
                    let _detailhtl = "";

                    if (_data.thumbnail != "") {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Thumbnail</div><div class="col-9"> <img src="' + _data.thumbnail + '" height="50px" width="50px" alt="' + _data.title + '" /></div></div>';
                    }
                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Title</div><div class="col-9">' + _data.title + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Alternative Title</div><div class="col-9">' + _data.alternativetitle + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Slug</div><div class="col-9">' + _data.slug + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Teaser</div><div class="col-9">' + _data.teaser + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Category</div><div class="col-9">' + _data.category + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Tags</div><div class="col-9">' + _data.tags + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Author</div><div class="col-9">' + _data.author + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Viewers</div><div class="col-9">' + _data.viewers + '</div></div>';

                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Likes</div><div class="col-9">' + _data.likes + '</div></div>';
                    _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Description</div><div class="col-9">' + _data.description + '</div></div>';

                    if (_data.metakeyword != "") {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Meta Keyword</div><div class="col-9">' + _data.metakeyword + '</div></div>';
                    }
                    if (_data.metadescription != "") {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Meta Description</div><div class="col-9">' + _data.metadescription + '</div></div>';
                    }
                    if (_data.archivedate) {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Archive Date</div><div class="col-9">' + _data.archivedate + '</div></div>';
                    }
                    if (_data.publishdate) {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Publish Date</div><div class="col-9">' + _data.publishdate + '</div></div>';
                    }

                    if (_data.poststatus == 1) {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Status</div><div class="col-9">Publish</div></div>';

                    } else {
                        _detailhtl = _detailhtl + '<div class="row"><div class="col-3">Status</div><div class="col-9">Draft</div></div>';

                    }

                    $('#post-popup .tab-content #details').html(_detailhtl);
                    $('#post-popup .modal-title').html(_data.title);

                    let _liketabdata = "";
                    if (_data.likedata.length > 0) {
                        _liketabdata = _liketabdata + '<table class="table table-responsive">';
                        _liketabdata = _liketabdata + '<thead>';
                        _liketabdata = _liketabdata + '</th>';
                        _liketabdata = _liketabdata + '<th>Ip Address';
                        _liketabdata = _liketabdata + '</th>';
                        _liketabdata = _liketabdata + '<th>Browser';
                        _liketabdata = _liketabdata + '</th>';
                        _liketabdata = _liketabdata + '</tr>';
                        _liketabdata = _liketabdata + '</thead>';

                        _liketabdata = _liketabdata + '<tbody>';
                        _data.likedata.map((ld) => {
                            _liketabdata = _liketabdata + '<tr>';
                            _liketabdata = _liketabdata + '<td>' + ld.ipaddress;
                            _liketabdata = _liketabdata + '</td>';
                            _liketabdata = _liketabdata + '<td>' + ld.browser;
                            _liketabdata = _liketabdata + '</td>';
                            _liketabdata = _liketabdata + '</tr>';
                        });
                        _liketabdata = _liketabdata + '</tbody>';
                        _liketabdata = _liketabdata + '</table>';
                    }else{
                        _liketabdata = _liketabdata +'<p class="text-center"> No likes for this post</p>';
                    }
                    $('#post-popup .tab-content #likes').html(_liketabdata);
                }
                $('#post-popup').fadeIn();
            }
        });
    });

    $(document).on("click", ".close-popup", function (e) {
        $('#post-popup').fadeOut();
    });

    let commenttbl = new DataTable('#be_table_commentlist', {
        responsive: true,
        searching: true,
        ordering: true,
        paging: true,
        serverSide: true,
        processing: true,
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        layout: {
            topStart: {
                div: { className: 'dt-filters-left' }
            },
            topEnd: [
                'search',
                'buttons',
                {
                    buttons: [{
                        extend: 'colvis',
                        columns: function (idx, data, node) {
                            const title = $(node).text().trim();
                            return title !== "";
                        },
                        popoverTitle: 'Toggle Columns',
                        className: 'btn btn-primary'
                    }]
                }
            ],
            bottomStart: 'pageLength',
            bottomEnd: ['info', 'paging']
        },
        buttons: [
            {
                extend: 'collection',
                text: '<i class="ki-duotone ki-exit-down"></i> Export',
                buttons: ['csv', 'excel']
            },
        ],
        language: {
            search: '<i class="ki-duotone ki-magnifier"></i>',
            buttons: {
                colvis: '<i class="ki-duotone ki-setting-4"></i> Columns'
            }
        },
        ajax: {
            url: TYPO3.settings.ajaxUrls.insight_be_table_comments,
            type: 'GET'
        },
        columnDefs: [
            { targets: [6], visible: false },
            { targets: [5], orderable: false },
        ],
        columns: [
            {
                data: 'crdate'
            }, {
                data: 'name',
                render: function (data, type, row, meta) {
                    let _html = '<div class="nametab" ><div class="author-info">';
                    _html = _html + '<div class="author-text"><strong class="author-name"> ' + data + '</strong><p class="author-slug">' + row.email + '</p></div></div>';
                    return _html
                }
            }, {
                data: 'comment'
            }, {
                data: 'post',
                render: function (data, type, row, meta) {
                    let _html = data;
                    if (row?.posteditlink != "") {
                        _html = _html + '<a href="' + row.posteditlink + '" > <i class="ki-duotone ki-paper-clip postlink"></i></a>';
                    }

                    return _html
                }
            }, {
                data: 'status',
                render: function (data, type, row, meta) {
                    let _html = "";
                    _html = _html + '<div class="form-check form-switch"> <input type="checkbox" class="commentstatus form-check-input t3js-toggle-record m-auto" data-uid="' + row.uid + '"  data-status="' + row.status + '"';
                    if (row.status == "approved") {
                        _html = _html + 'checked >  </div> ';
                    } else {
                        _html = _html + ' >  </div> ';
                    }
                    return _html
                }
            }, {
                data: 'uid',
                render: function (data, type, row, meta) {
                    let _html = '<span class="actions">';
                    if (row?.editlink != "") {
                        _html = _html + '<a href="' + row.editlink + '" > <i class="ki-duotone ki-notepad-edit editicon"></i></a>';
                    }
                    _html += '</span>';
                    return _html
                }
            }, {
                data: 'uid'
            }
        ],
        order: [[5, 'desc']],
        initComplete: function () {
            $('#be_table_commentlist_wrapper .dt-filters-left')
                .append($('.comments-filters'));
        }
    });


    $('#comment-filter-post').on('change', function () {
        commenttbl.column(3).search(this.value).draw();
    });

    $('#comment-filter-date').on('click', function () {
        this.showPicker();
    });

    $('#comment-filter-date').on('change', function () {
        commenttbl.column(0).search(this.value).draw();
    });

    $(document).on("change", ".commentstatus", function (e) {
        var $this = $(this);
        var uid = $this.data('uid');
        var status = $this.data('status');
        $.ajax({
            url: TYPO3.settings.ajaxUrls.insight_comment_status,
            type: 'GET',
            data: {
                uid: uid,
                status: status
            },
            success: function (result) {
                commenttbl.draw(false);
            }
        });
    });

    setTimeout(() => {
        $('.dt-search input[type="search"]').attr('placeholder', 'Search...');
    }, 50);


    let athtbl = new DataTable('#be_table_authors', {
        responsive: true,
        searching: true,
        ordering: true,
        paging: true,
        serverSide: true,
        processing: true,
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        layout: {
            topStart: {
                div: {
                    className: 'heading',
                    text: ''
                }
            },
            topEnd: [
                'search',
                'buttons',
                {
                    buttons: [{
                        extend: 'colvis',
                        columns: function (idx, data, node) {
                            const title = $(node).text().trim();
                            return title !== "";
                        },
                        popoverTitle: 'Toggle Columns',
                        className: 'btn btn-primary'
                    }]
                }
            ],
            bottomStart: 'pageLength',
            bottomEnd: ['info', 'paging']
        },
        buttons: [
            {
                extend: 'collection',
                text: '<i class="ki-duotone ki-exit-down"></i> Export',
                buttons: ['csv', 'excel']
            },
        ],
        language: {
            search: '<i class="ki-duotone ki-magnifier"></i>',
            buttons: {
                colvis: '<i class="ki-duotone ki-setting-4"></i> Columns'
            }
        },
        ajax: {
            url: TYPO3.settings.ajaxUrls.insight_be_table_authors,
            type: 'GET'
        },
        columnDefs: [
            { targets: [6], visible: false },
            { targets: [5], orderable: false },
        ],
        columns: [{
            data: 'name',
            render: function (data, type, row, meta) {
                let _html = '<div class="nametab" ><div class="author-info">';
                if (row?.avtar) {
                    _html = _html + "<img src='" + row.avtar + "' alt='" + data + "'  width='50px' height='50px' class='author-img'  /> "
                } else {
                    _html = _html + `<img src="${meta.settings.json.blankImage}"
                      alt="No Image" width="50" height="50" class="author-img" />`
                }
                _html = _html + '<div class="author-text"><strong class="author-name"> ' + data + '</strong><p class="author-slug">' + row.slug + '</p></div></div>';
                return _html
            }
        }, {
            data: 'email'
        }, {
            data: 'designation'
        }, {
            data: 'intro'
        }, {
            data: 'socialmedia',
            render: function (data, type, row, meta) {
                let _html = "<div class='d-flex flex-row align-items-center justify-content-center gap-2'>";

                if (data.length > 0) {
                    data.map((e) => {
                        _html += `
                <div class="">
                    <a href="${e.url}">
                        <img src="${e.icon}" alt="${e.url}" width="20" height="20">
                    </a>
                </div>
            `;
                    });
                }

                _html += "</div>";
                return _html;
            }

        }, {
            data: 'uid',
            render: function (data, type, row, meta) {
                let _html = '<span class="actions">';

                if (row?.editlink != "") {
                    _html = _html + '<a href="' + row.editlink + '" > <i class="ki-duotone ki-notepad-edit editicon"></i></a>';
                }

                _html += '</span>';
                return _html
            }
        }, {
            data: 'uid'
        }
        ],
        order: [[5, 'desc']],
        initComplete: function () {
            $('#be_table_authors_wrapper .dt-filters-left')
                .append($('.filters'));
        }
    });




});