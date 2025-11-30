@extends('backend.layouts.app')

@section('title', 'Hubungi Kami')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="contactTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Pesan</th>
                                <th>Dikirim</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $index => $contact)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>{{ Str::limit($contact->message, 150, '...') }}</td>
                                    <td>{{ $contact->created_at->format('d F Y') }}</td>
                                    <td>
                                        <a href="{{ route('contact.show', $contact->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#contactTable').DataTable({
                processing: true,
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
            });
        });
    </script>
@endpush
