<script type="text/javascript">
    $(document).ready(function () {
        search_ircs_data_count(1);
        // load_defect_table();
    });

    document.getElementById("list_of_ircs_res").addEventListener("scroll", function () {
        var scrollTop = this.scrollTop;
        var scrollHeight = this.scrollHeight;
        var offsetHeight = this.offsetHeight;

        if ((offsetHeight + scrollTop + 1) >= scrollHeight) {
            get_next_page();
        }
    });

    const get_next_page = () => {
        var current_page = parseInt(sessionStorage.getItem('ircs_table_pagination')) || 1;
        let total = parseInt(sessionStorage.getItem('count_rows')) || 0;
        var last_page = parseInt(sessionStorage.getItem('last_page')) || 1;
        var next_page = current_page + 1;

        if (next_page <= last_page && total > 0) {
            search_ircs_data_count(next_page);
        }
    }

    const count_ircs = () => {
        var search_date_from = sessionStorage.getItem('search_date_from');
        var search_date_to = sessionStorage.getItem('search_date_to');

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'count_ircs_list',
                search_date_from: search_date_from,
                search_date_to: search_date_to
            },
            success: function (response) {
                sessionStorage.setItem('count_rows', response);
                var count = `Total: ${response}`;
                $('#ircs_table_info').html(count);

                if (response > 0) {
                    load_ircs_last_page();
                } else {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                }
            }
        });
    }

    const load_ircs_last_page = () => {
        var search_date_from = sessionStorage.getItem('search_date_from');
        var search_date_to = sessionStorage.getItem('search_date_to');
        var current_page = sessionStorage.getItem('ircs_table_pagination');

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'ircs_list_last_page',
                search_date_from: search_date_from,
                search_date_to: search_date_to
            },
            success: function (response) {
                sessionStorage.setItem('last_page', response);
                let total = parseInt(sessionStorage.getItem('count_rows'));
                var next_page = current_page + 1;

                if (next_page > response || total < 1) {
                    document.getElementById("btnNextPage").style.display = "none";
                    document.getElementById("btnNextPage").setAttribute('disabled', true);
                } else {
                    document.getElementById("btnNextPage").style.display = "block";
                    document.getElementById("btnNextPage").removeAttribute('disabled');
                }
            }
        });
    }

    const search_ircs_data_count = current_page => {
        var search_date_from = document.getElementById('search_date_from').value;
        var search_date_to = document.getElementById('search_date_to').value;

        var search_date_from_1 = sessionStorage.getItem('search_date_from');
        var search_date_to_1 = sessionStorage.getItem('search_date_to');

        if (current_page > 1) {
            switch (true) {
                case search_date_from !== search_date_from_1:
                case search_date_to !== search_date_to_1:
                    search_date_from = search_date_from_1;
                    search_date_to = search_date_to_1;
                    break;
                default:
            }
        } else {
            sessionStorage.setItem('search_date_from', search_date_from);
            sessionStorage.setItem('search_date_to', search_date_to);
        }

        $.ajax({
            url: 'process/index_p.php',
            type: 'POST',
            cache: false,
            data: {
                method: 'search_ircs_data_count',
                search_date_from: search_date_from,
                search_date_to: search_date_to,
                current_page: current_page
            },
            beforeSend: () => {
                var loading = `<tr id="loading"><td colspan="4" style="text-align:center;"><div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div></td></tr>`;
                if (current_page == 1) {
                    $('#ircs_table tbody').html(loading);
                } else {
                    $('#ircs_table tbody').append(loading);
                }
            },
            success: function (response) {
                $('#loading').remove();
                if (current_page == 1) {
                    $('#ircs_table tbody').html(response);
                } else {
                    $('#ircs_table tbody').append(response);
                }
                sessionStorage.setItem('ircs_table_pagination', current_page);
                count_ircs();
            }
        });
    }

    const clear_search_ircs_record = () => {
        document.getElementById("search_date_from").value = '';
        document.getElementById("search_date_to").value = '';

        search_ircs_data_count(1);
    };

    const export_ircs_data_count = () => {
        var search_date_from = document.getElementById('search_date_from').value.trim();
        var search_date_to = document.getElementById('search_date_to').value.trim();

        if (search_date_from === '') {
            search_date_from = new Date().toISOString().slice(0, 10);
        }
        if (search_date_to === '') {
            search_date_to = new Date().toISOString().slice(0, 10);
        }

        window.open(
            'process/exp_ircs_record.php?' +
            'search_date_from=' + encodeURIComponent(search_date_from) +
            '&search_date_to=' + encodeURIComponent(search_date_to),
            '_blank'
        );
    };


    // const load_defect_table = () => {
    //     $.ajax({
    //         url: 'process/index_p.php',
    //         type: 'POST',
    //         cache: false,
    //         data: {
    //             method: 'load_defect_list'
    //         },
    //         success: function (response) {
    //             $('#list_of_ircs_data').html(response);
    //         },
    //         error: function (xhr, status, error) {
    //         }
    //     });
    // };
</script>