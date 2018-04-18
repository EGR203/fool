Admin.Modules.register('admin-custom.proxy-ping-btn', () => {
    $('.proxy-ping-btn').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('isLoading')) {
            return;
        } else {
            $this.data('isLoading', true);
        }
        var $form = $this.parent();
        var url = $form.attr('action');
        var method = $form.attr('method');
        var data = {
            url: url,
            method: method,
            full: false,
            data: {},
        };
        $this.css('background-color', 'black');
        $this.css('color', 'black');
        $.ajax({
            type: 'POST',
            url: '/proxy',
            dataType: 'json',
            data: data,
            complete: function (resp) {
                console.log(resp.responseText);
                console.log(resp.status);
                if (resp.status >= 200 && resp.status < 300) {
                    $this.css('background-color', 'green');
                    $this.css('color', 'white');
                } else if (resp.status < 400) {
                    $this.css('background-color', 'yellow');
                    $this.css('color', 'black');
                } else {
                    $this.css('background-color', 'red');
                    $this.css('color', 'black');
                }

                $this.data('isLoading', false);
            },
        });
    });
    $('.proxy-ping-btn').click();
});
