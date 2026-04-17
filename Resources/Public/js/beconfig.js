$(document).ready(function () {
    let counter = 0;
    $(document).on("click", "#newsocialmedia", function () {

        let $container = $("#socialMediaContainer");
        let sm = $container.find(".social-row").length;

        if (sm !== 0) {
            counter = sm;
        }

        let html = `
            <div class=" border ps-3 pe-3 social-row w-100 position-relative">

                <button class="deletesm" type="button">
                    <i class="ki-duotone ki-trash"></i>
                </button>
                <div class="row">

                    <div class="col-12 col-lg-6">
                        <label>Link</label>
                        <input type="text" name="settingsform[socialmedia][${counter}][link]" class="form-control" required />
                    </div>

                    <div class="col-12 col-lg-6">
                        <label>Icon</label>
                        <div class="position-relative w-100">
                            <input type="file" name="settingsform[socialmedia][${counter}][icon]" class="form-control pe-5" />
                        </div>
                    </div>
                </div>

            </div>
    `;
        if ($($container).find(".nomedia").length > 0) {
            $($container).find(".nomedia").remove();
        }
        $container.append(html);
        counter++;
    });

    $(document).on("click", ".deletesm", function (e) {
        e.preventDefault();

        let $row = $(this).closest(".social-row");

        if ($row.length) {
            $row.remove();

            $("#socialMediaContainer .social-row").each(function (index) {
                $(this).find("input").each(function () {
                    let name = $(this).attr("name");
                    if (name) {
                        name = name.replace(/\[\d+\]/, `[${index}]`);
                        $(this).attr("name", name);
                    }
                });
            });

            counter = $("#socialMediaContainer .social-row").length;
            if (counter == 0) {
                let $container = $("#socialMediaContainer");
                $container.append("<p class='text-center nomedia'>No social media found !</p>");
            }
        }
    });
    function toggleStatusRows() {
        if ($("#displaycomment").is(":checked")) {
            $(".status_to_show_row").removeClass('hide');
        } else {
            $(".status_to_show_row").addClass('hide');
        }
    }

    $(document).on("click", "#displaycomment", toggleStatusRows);

    $(document).ready(function () {
        toggleStatusRows();
    });

    $('.js-select2').select2({
        minimumResultsForSearch: Infinity,
    });


    // custom accordion 
    $(".accordion-toggle").on("click", function (e) {
        e.preventDefault();

        const $item = $(this).closest(".accordion-item");
        const $body = $item.find(".accordion-body");

        $(".accordion-item.open").not($item).removeClass("open")
            .find(".accordion-body").stop(true, true).slideUp(200);

        if ($item.hasClass("open")) {
            $body.stop(true, true).slideUp(200);
            $item.removeClass("open");
        } else {
            $body.stop(true, true).slideDown(200);
            $item.addClass("open");
        }
    });

    document.querySelectorAll('.menu-link').forEach(item => {
        item.addEventListener('click', function () {

            document.querySelectorAll('.content-box').forEach(box => {
                box.classList.remove('active', 'show');
            });
            document.querySelectorAll('.menu-link').forEach(link => {
                link.classList.remove('active');
            });
            this.classList.add('active');
            const target = this.dataset.target || this.getAttribute('data-bs-target');
            if (target) {
                const el = document.querySelector(target);
                if (el) {
                    el.classList.add('active', 'show');
                }
            }
        });
    });

    $('.ai-wrapper').on('change', '.js-ai-toggle', function () {
        if ($(this).is(':checked')) {
            $('.js-ai-toggle').not(this).prop('checked', false);
        }
    });
    $('.ai-wrapper').on('click', '.js-toggle-apikey', function () {
        // Find the input field in the same row
        const inputField = $(this).closest('.input-group').find('.js-apikey-input');
        const icon = $(this).find('span');

        // Check current type and flip it
        if (inputField.attr('type') === 'password') {
            inputField.attr('type', 'text');
            icon.removeClass('ki-eye ki-duotone').addClass('ki-eye-slash ki-duotone');
        } else {
            inputField.attr('type', 'password');
            icon.removeClass('ki-eye-slash ki-duotone').addClass('ki-eye ki-duotone');
        }
    });
});