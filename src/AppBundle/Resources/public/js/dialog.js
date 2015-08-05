$(function() {
    var otherw = $('<div>').dialog({autoOpen: false, modal: true});
    $('.dialog').click(function (e) {
        console.log('eh');
        e.preventDefault();
        var page = this.href;
        console.log(this.href);
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

