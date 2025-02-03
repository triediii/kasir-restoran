<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Tanggal dan Waktu -->
        <li class="nav-item my-auto">
            <span class="text-blue font-weight-bold" id="datetime"></span>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item no-arrow">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt fa-fw text-gray-400"></i>
            </a>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<script>
    function updateDateTime() {
        var now = new Date();
        var day = String(now.getDate()).padStart(2, '0');
        var month = String(now.getMonth() + 1).padStart(2, '0'); // January is 0!
        var year = now.getFullYear();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');

        var formattedDateTime = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds;
        document.getElementById('datetime').innerHTML = formattedDateTime;
    }

    setInterval(updateDateTime, 1000); // Update every second
</script>

<style>
    .text-blue {
        color: blue !important;
    }
</style>
