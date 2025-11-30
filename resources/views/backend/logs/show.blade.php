@extends('backend.layouts.app')

@section('title', 'Log Aktivitas')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-3 f-w-400 text-muted">
                <i class="ti ti-info-circle"></i> Detail Umum
              </h6>
              <hr class="my-2">
              <p class="mb-1"><strong>Pengguna:</strong> <span id="user">Loading...</span></p>
              <hr class="my-2">
              <p class="mb-1"><strong>Waktu Dibuat:</strong> <span id="created_at">Loading...</span></p>
              <hr class="my-2">
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">
              <i class="ti ti-activity"></i>Aktifitas</h6>
              <hr class="my-2">
              <p class="mb-1"><strong>Aktifitas:</strong> <span id="action">Loading...</span></p>
              <hr class="my-2">
              <p class="mb-1"><strong>Modul:</strong> <span id="module_name">Loading...</span></p>
              <hr class="my-2">
              <p class="mb-1"><strong>Guard:</strong> <span id="guard_name">Loading...</span></p>
              <hr class="my-2">
              <p class="mb-1"><strong>IP Address:</strong> <span id="ip_address">Loading...</span></p>
              <hr class="my-2">
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">
              <i class="ti ti-database-export"></i>Data Lama</h6>
              <p class="mb-1"><strong>Data:</strong></p>
              <pre id="old_value" style="background-color: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px; white-space: pre-wrap;">Loading...</pre>
              <hr class="my-2">
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">
              <i class="ti ti-database-import"></i>Data Baru</h6>
              <p class="mb-1"><strong>Data:</strong></p>
              <pre id="new_value" style="background-color: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px; white-space: pre-wrap;">Loading...</pre>
              <hr class="my-2">
            </div>
          </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/app-logs/{{ $id }}') // Menggunakan $logId dari controller
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log data untuk debugging

                    // Format tanggal
                    const formatDate = (dateString) => {
                        const options = { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric', 
                            hour: '2-digit', 
                            minute: '2-digit', 
                            timeZone: 'Asia/Jakarta', // Sesuaikan dengan zona waktu Anda
                            hour12: false 
                        };
                        return new Date(dateString).toLocaleString('id-ID', options) + ' WIB';
                    };

                    document.getElementById('user').innerText = data.data.user.name;
                    document.getElementById('created_at').innerText = formatDate(data.data.created_at);
                    document.getElementById('action').innerText = data.data.action;
                    document.getElementById('module_name').innerText = data.data.module_name;
                    document.getElementById('guard_name').innerText = data.data.guard_name;
                    document.getElementById('ip_address').innerText = data.data.ip_address;

                    // Menguraikan JSON string menjadi objek
                    const oldValue = JSON.parse(data.data.old_value);
                    const newValue = JSON.parse(data.data.new_value);

                    // Menampilkan nilai yang diinginkan dari objek
                    document.getElementById('old_value').innerText = JSON.stringify(oldValue, null, 2); // Menampilkan dalam format yang lebih rapi
                    document.getElementById('new_value').innerText = JSON.stringify(newValue, null, 2); // Menampilkan dalam format yang lebih rapi
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
@endpush