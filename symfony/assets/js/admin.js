import $ from 'jquery';
import 'datatables.net-bs5';
import Chart from 'chart.js/auto'

const handleGraphData = () => {
    $.ajax({
        url: '/admin/login_logs',
        type: 'GET',
        success: (_data) => {
            let labels = _data['labels'];
            let loginsAttemptsByEachDay = _data['data'];

            const data = {
                labels: labels,
                datasets: [{
                    label: 'Login',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: loginsAttemptsByEachDay
                }]
            };

            const config = {
                type: 'line',
                data: data,
                options: {}
            };

            new Chart($('#lastLoginChart'), config);
        },
        error: () => console.log('Cannot initiate Graph')
    })
}

const initDataTable = () => {
    $('#example').DataTable();
}

$(document).ready(() => {
    initDataTable();
    handleGraphData();
});