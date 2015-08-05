$(function() {
    var otherw = $('#dialog-out').dialog({autoOpen: false, modal: true});
    $('.dialog').click(function (e) {
        console.log('eh');
        e.preventDefault();
        page = this.href;
        $.ajax({
            url: this.href,
            success: function () {
                console.log('succes');
                otherw.load(page, function () {
                    otherw.dialog('open');
                });
            }
        });
    });
});

