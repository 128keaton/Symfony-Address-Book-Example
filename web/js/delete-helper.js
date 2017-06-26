$(function () {
    var createForm = function (action, data) {
        var $form = $('<form action="' + action + '" method="POST"></form>');
        for (input in data) {
            if (data.hasOwnProperty(input)) {
                $form.append('<input name="' + input + '" value="' + data[input] + '">');
            }
        }

        return $form;
    };

    $(document).on('click', 'a[data-delete-link]', function (e) {
        e.preventDefault();
        var $this = $(this);

        var $form = createForm($this.attr('href'), {
            id: $this.attr('data-delete-link'),
            _method: 'DELETE'
        }).hide();

        $('body').append($form); // Firefox requires form to be on the page to allow submission
        $form.submit();
    });
});