var showErrorMessageInSweatAlert = function (e) {
    var errors = e.responseJSON;
    var errorMessage = 'Something went wrong!<br />';
    if (errors != undefined && Object.keys(errors).length > 0) {
        $.each(errors, function (index, error) {
            if (typeof(error) == "string") {
                errorMessage = errors.message + "<br />";
            } else {
                $.each(error, function (index, val) {
                    errorMessage += val + "<br />";
                });
            }
        });
    }
    swal("Oops...", errorMessage, "error");
};

$('#sidebar li').each(function () {
    $(this).removeClass('active');
});

$(document).ready(function () {

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $(".loading").hide();

    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
        }
    });

    $(document)
        .ajaxStart(function () {
            $(".loading").show();
        })
        .ajaxComplete(function () {
            $(".loading").hide();
        })
        .ajaxError(function (e) {
            $(".loading").hide();
        });
});