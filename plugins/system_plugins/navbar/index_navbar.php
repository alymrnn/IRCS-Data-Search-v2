<style>
    @font-face {
        font-family: 'Poppins';
        src: url('dist/font/poppins/Poppins-Regular.ttf') format('truetype');
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* scrollbar */
    /* width */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #332D2D;
    }

    .highlight {
        border: 1px solid #CA3F3F;
    }

    /* th,
    td {
        border: 1px solid #EEE;
        text-align: center;
    } */
</style>

<!-- Navbar -->
<!-- <nav class="main-header navbar navbar-expand-md border-bottom-0" style="background:#163A65;"> -->
<nav class="main-header navbar navbar-expand-md border-bottom-0" style="background:#744E94;">
    <a href="" class="navbar-brand ml-2">
        <img src="dist/img/statistics.png" alt="Minor Defect Record System Logo" class="brand-image">
        <span class="brand-text font-weight-normal text-light" style="color: white; font-size: 22px;">IRCS DATA SEARCH v2.0</span>
    </a>

    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item mr-4 pt-3">
            <p style="color: #fff; font-size: 15px;"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;<span
                    id="datetime"></span></p>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<script>
    function refreshDateTime() {
        const datetimeDisplay = document.getElementById("datetime");
        const now = new Date();

        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = now.toLocaleDateString(undefined, dateOptions);

        const timeOptions = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
        const formattedTime = now.toLocaleTimeString(undefined, timeOptions);

        const formattedDateTime = `${formattedDate} | ${formattedTime}`;

        datetimeDisplay.textContent = formattedDateTime;
    }
    setInterval(refreshDateTime, 1000); 
</script>