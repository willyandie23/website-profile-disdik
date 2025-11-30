@extends('backend.layouts.app')

@section('title', 'Log Akvifitas')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table id="app-logs-datatables" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Model</th>
                                <th>Pengguna</th>
                                <th>Guard</th>
                                <th>Modul</th>
                                <th>Aktifitas</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Model</th>
                                <th>Pengguna</th>
                                <th>Guard</th>
                                <th>Modul</th>
                                <th>Aktifitas</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#app-logs-datatables').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/api/app-logs",  // This is the API route, not the backend route
                    "type": "GET",
                    "dataSrc": function (json) {
                        console.log(json); // Log the JSON response
                        return json.data;   // This will be the array of logs
                    },
                    "error": function(xhr, error, thrown) {
                        console.error("Error fetching data: ", error);
                        alert("An error occurred while fetching data. Please try again.");
                    }
                },
                "columns": [
                    { "data": null, "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }},
                    { "data": "system_logable_type" },
                    { "data": "user.name" },
                    { "data": "guard_name" },
                    { "data": "module_name" },
                    { "data": "action" },
                    { "data": "created_at", "render": function(data) {
                        return new Date(data).toLocaleString('id-ID', { 
                            year: 'numeric', 
                            month: '2-digit', 
                            day: '2-digit', 
                            hour: '2-digit', 
                            minute: '2-digit', 
                            hour12: false 
                        }) + ' WIB';
                    }},
                    {
                        "data": "id",
                        "render": function(data, type, row) {
                            return '<a href="/app-logs/' + data + '" class="btn btn-sm btn-info">Detail</a>';
                        }
                    }
                ]
            });
        });
    </script>
@endpush