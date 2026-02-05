<div class="container-fluid">
    <div class="text-center mb-3">
        <i class="fas fa-user-circle fa-4x text-primary"></i>
        <h5 class="mt-2 mb-0">{{ $patient->nama }}</h5>
        <small class="text-muted">{{ $patient->no_rekam_medis }}</small>
    </div>

    <hr>

    <div class="row mb-2">
        <div class="col-5 text-muted">Usia</div>
        <div class="col-7 fw-bold">{{ $patient->umur }} tahun</div>
    </div>

    <div class="row mb-2">
        <div class="col-5 text-muted">Jenis Kelamin</div>
        <div class="col-7">
            <span class="badge bg-{{ $patient->jenis_kelamin == 'L' ? 'info' : 'danger' }}">
                {{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </span>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-5 text-muted">Tanggal Lahir</div>
        <div class="col-7">{{ \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d M Y') }}</div>
    </div>

    <div class="row mb-2">
        <div class="col-5 text-muted">No. HP</div>
        <div class="col-7">{{ $patient->no_hp }}</div>
    </div>

    <div class="row mb-2">
        <div class="col-5 text-muted">Alamat</div>
        <div class="col-7">{{ $patient->alamat }}</div>
    </div>

    <hr>

    <div class="row text-center">
        <div class="col">
            <div class="p-2 bg-light rounded">
                <h6 class="mb-0">{{ $patient->visits()->count() }}</h6>
                <small class="text-muted">Total Kunjungan</small>
            </div>
        </div>
    </div>
</div>
