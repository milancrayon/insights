$(document).ready(function () {
    $('.insight-editor').each(function () {
        if (!$(this).data('codemirror')) {
            var cm = CodeMirror.fromTextArea(this, {
                lineNumbers: true,
                mode: "htmlmixed",
                theme: "default",
                matchBrackets: true,
                autoCloseBrackets: true,
                viewportMargin: Infinity
            });
            $(this).data('codemirror', cm);
        }
    });

    $(document).on("click", ".list-group-item", function (e) { 
        $(document).find('.insight-editor').each(function () {
            var cm = $(this).data('codemirror');
            if (cm) {
                setTimeout(function () {
                    cm.refresh();
                }, 50);
            }
        });
    });
});
