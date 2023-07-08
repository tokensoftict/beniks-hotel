$.widget("ui.autocomplete", $.ui.autocomplete, {

    _renderMenu: function(ul, items) {
        var that = this;
        ul.attr("class", "nav nav-pills nav-stacked  bs-autocomplete-menu");
        $.each(items, function(index, item) {
            that._renderItemData(ul, item);
        });
    },

    _resizeMenu: function() {
        var ul = this.menu.element;
        ul.outerWidth(Math.min(
            // Firefox wraps long text (possibly a rounding bug)
            // so we add 1px to avoid the wrapping (#7513)
            ul.width("").outerWidth() + 1,
            this.element.outerWidth()
        ));
    }

});

(function() {
    "use strict";


    $('.bs-autocomplete').each(function() {
        var _this = $(this),
            _data = _this.data(),
            _hidden_field = $('#' + _data.hidden_field_id);

        _this.after('<div class="bs-autocomplete-feedback form-control-feedback"><div class="loader">Loading... </div></div>')
            .parent('.form-group').addClass('has-feedback');

        var feedback_icon = _this.next('.bs-autocomplete-feedback');
        feedback_icon.hide();

        _this.autocomplete({
            minLength: 2,
            autoFocus: true,
            maxResults: 30,
            source: function(request, response) {
                var cities = [
                    $.getJSON(
                        productfindurl+"?query=" + request.term,
                        function (data) {
                            response(data);
                        })
                ];
            },

            search: function() {
                feedback_icon.show();
                _hidden_field.val('');
            },

            response: function() {
                feedback_icon.hide();
            },

            focus: function(event, ui) {
                //_this.val(ui.item[_data.item_label]);
                event.preventDefault();
            },

            select: function(event, ui) {
                _this.val('');
                appendToTable(ui.item);
                event.preventDefault();
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            let html = '<a>' + item.cloth_name + '</a>&nbsp;&nbsp;&nbsp;&nbsp';
            item['cloth_service_mappers'].forEach(function(service){
                html+=service.laundry_service.laundry_service_name+' : '+formatMoney(service.price)+"&nbsp;&nbsp;"
            });
            return $('<li></li>')
                .data("item.autocomplete", item)
                .append(html)
                .appendTo(ul);
        };
        // end autocomplete
    });
})();
