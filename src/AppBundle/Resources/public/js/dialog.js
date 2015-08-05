$(function() {
    $('.dialog').click(function (e) {
        e.preventDefault();
        var otherw = $('#dialog-out').dialog({autoOpen: false, modal: true});
        console.log('eh');
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




