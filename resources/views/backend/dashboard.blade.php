<x-backend.shell title="Dashboard - SB Admin">

    <x-slot:head>
        <!-- Font Awesome: nodig voor iconen in cards/headers -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Dashboard">

        {{-- 4 cards --}}
        <div class="row">
            <x-backend.stat-card color="primary" title="Primary Card" />
            <x-backend.stat-card color="warning" title="Warning Card" />
            <x-backend.stat-card color="success" title="Success Card" />
            <x-backend.stat-card color="danger" title="Danger Card" />
        </div>

        {{-- 2 charts (we houden de originele SB Admin header markup hier bewust intact) --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Area Chart Example
                    </div>
                    <div class="card-body">
                        <canvas id="myAreaChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Bar Chart Example
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- datatable (beperkt tot 1 row) --}}
        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                DataTable Example
            </div>

            <table id="datatablesSimple">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </tfoot>

                <tbody>
                <tr>
                    <td>Tiger Nixon</td>
                    <td>System Architect</td>
                    <td>Edinburgh</td>
                    <td>61</td>
                    <td>2011/04/25</td>
                    <td>$320,800</td>
                </tr>
                </tbody>
            </table>
        </x-backend.card>

    </x-backend.page-header>

    <x-slot:scripts>
        <!-- Chart.js: nodig voor chart-area-demo en chart-bar-demo -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

        <!-- Simple Datatables: nodig voor datatables-simple-demo -->
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    </x-slot:scripts>

</x-backend.shell>
