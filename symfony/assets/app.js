import './styles/app.scss';
import './bootstrap';
import {Toast} from 'bootstrap';

import $ from 'jquery';

$(document).ready(function () {
    const showToast = (type, message) => {
        $('.' + type + 'MessageBody').text(message);

        let toast = $('#' + type + 'Toast');
        let bsAlert = new Toast(toast);
        bsAlert.show();
    }


    $.ajax({
        url: '/flashes',
        type: 'GET',
        success: function (data) {
            if (data['success'].length > 0) {
                showToast('success', data['success'][0]);
            }

            if (data['error'].length > 0) {
                showToast('success', data['error'][0]);
            }
        },
    })
})