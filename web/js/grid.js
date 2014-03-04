window.GridModal = function(gridContainerSelector) {

    if (gridContainerSelector == undefined) {
        gridContainerSelector = '[data-role=modal-grid]';
    }

    var _gridModal = this;

    this.fillContainer = function(html) {
        $('#modal-window .modal-body').html(html);
        $('#modal-window-title').html($('#modal-window .modal-body h1').html());
        $('#modal-window .modal-footer').html('');
        $('#modal-window .modal-footer').append($('#modal-window .form-actions'));
        $('#modal-window .modal-body h1').remove();
        $('#modal-window [data-role=cancel]').attr('data-dismiss', 'modal');
        $('#modal-window [data-role=cancel]').attr('href', 'javascript:void(0)');
        _gridModal.replaceButton(
            $('#modal-window .modal-footer button'),
            $('#modal-window .modal-body form')
        );
    };

    this.prepareContainer = function() {

        if ($('#modal-window').length == 0) {

            $('body').append('\
                <div class="modal fade" id="modal-window" tabindex="-1" role="dialog" aria-labelledby="modal-window-title" aria-hidden="true">\
                  <div class="modal-dialog">\
                    <div class="modal-content">\
                      <div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
                        <h4 class="modal-title" id="modal-window-title">Carregando...</h4>\
                      </div>\
                      <div class="modal-body">\
                        &nbsp;\
                      </div>\
                      <div class="modal-footer">\
                        &nbsp;\
                      </div>\
                    </div>\
                  </div>\
                </div>\
            ');
        } else {
            $('#modal-window .modal-body').html('&nbsp;');
            $('#modal-window-title').html('Carregando...');
        }
    };

    this.replaceButton = function(buttons, form) {
        buttons.each(function(index, element){
            var button = $(element);
            button.click(function(event){
                event.preventDefault();
                _gridModal.prepareContainer();
                $('#modal-window').modal();
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function(data) {
                        _gridModal.fillContainer(data);
                    }
                });
                return false;
            });
        });
    };

    this.replaceLink = function(anchors){
        anchors.each(function(index, element){
            var anchor = $(element);
            anchor.click(function(event){
                event.preventDefault();
                _gridModal.prepareContainer();
                $('#modal-window').modal();
                $.ajax({
                    url: anchor.attr('href'),
                    success: function(data) {
                        _gridModal.fillContainer(data);
                    }
                });
                return false;
            });
        });
    };

    // Constructor
    this.replaceLink($(gridContainerSelector + ' a[data-role=create]'));
    this.replaceLink($(gridContainerSelector + ' a:has(.table-edit)'));
};

var gridModalInstance = new GridModal();
