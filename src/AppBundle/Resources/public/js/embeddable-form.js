$(function () {
    console.log('dassdsadasdasdasdsda');
    $('.embeddable-form').each(function () {
        var source = $(this).data('source');
        console.log(source);
        var name = $(this).data('name');
        var dataTable = $(this).find('.data');
        $(this).find('.search-input').autocomplete({
            source: source,
            minLenth: 2,
            select: function (event, ui) {
                console.log(ui);
                var row = createProductRow(ui.item, name);
                dataTable.append(row);
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>").text(item.code).appendTo(ul);
        };
        appendInitialRows($(this).data('rows'), name, dataTable);
    });
});
function createProductRow(product, inputName) {
    var id = $('<td>').append($('<input type="hidden">')
        .prop('name', inputName + '[' + product.id + ']'));
    console.log(product);
    var code = $('<td>').html(product.code);
    var title = $('<td>').html(product.title);
    var price = $('<td>').html(product.price);
    if (product.quantity === undefined)
        product.quantity = 0;
    var quantity = $('<td>')
        .append('<input type="text" name="quantity[' + product.id + ']" value="'+ product.quantity+'"/>');
    var deleteButton = $('<td>').append($('<div title="delete">').button({
        icons: {
            primary: "ui-icon-trash"
        },
        text: false
    }).on('click', function () {
        $(this).parent().parent().remove();
    }));
    return $('<tr>')
        .append(id)
        .append(code)
        .append(title)
        .append(price)
        .append(quantity)
        .append(deleteButton);
}

function appendInitialRows(initialRows, name, dataTable) {
    console.log(initialRows);
    if (!initialRows) {
        return;
    }
    for (var index in initialRows) {
        var row = createProductRow(initialRows[index], name);
        dataTable.append(row);
    }
}