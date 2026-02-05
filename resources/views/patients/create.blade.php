@extends('layouts.app')

@section('title', 'Tambah Pasien Baru')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>Tambah Pasien Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                           id="laki" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                           id="perempuan" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" 
                                   id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Identity Information -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="nik" class="form-label">NIK</label>
        <input type="text" class="form-control @error('nik') is-invalid @enderror" 
               id="nik" name="nik" value="{{ old('nik') }}">
        @error('nik')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="no_bpjs" class="form-label">No. BPJS</label>
        <input type="text" class="form-control @error('no_bpjs') is-invalid @enderror" 
               id="no_bpjs" name="no_bpjs" value="{{ old('no_bpjs') }}">
        @error('no_bpjs')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Additional Contact -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="no_hp_keluarga" class="form-label">No. HP Keluarga</label>
        <input type="tel" class="form-control @error('no_hp_keluarga') is-invalid @enderror" 
               id="no_hp_keluarga" name="no_hp_keluarga" value="{{ old('no_hp_keluarga') }}">
        @error('no_hp_keluarga')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Address Details -->
<div class="row mb-3">
    <div class="col-md-3">
        <label for="rt" class="form-label">RT</label>
        <input type="text" class="form-control @error('rt') is-invalid @enderror" 
               id="rt" name="rt" value="{{ old('rt') }}">
        @error('rt')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="rw" class="form-label">RW</label>
        <input type="text" class="form-control @error('rw') is-invalid @enderror" 
               id="rw" name="rw" value="{{ old('rw') }}">
        @error('rw')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="kode_pos" class="form-label">Kode Pos</label>
        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
               id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}">
        @error('kode_pos')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="kelurahan" class="form-label">Kelurahan</label>
        <input type="text" class="form-control @error('kelurahan') is-invalid @enderror" 
               id="kelurahan" name="kelurahan" value="{{ old('kelurahan') }}">
        @error('kelurahan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" class="form-control @error('kecamatan') is-invalid @enderror" 
               id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}">
        @error('kecamatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="kota" class="form-label">Kota/Kabupaten</label>
        <input type="text" class="form-control @error('kota') is-invalid @enderror" 
               id="kota" name="kota" value="{{ old('kota') }}">
        @error('kota')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Medical Information -->
<div class="row mb-3">
    <div class="col-md-4">
        <label for="golongan_darah" class="form-label">Golongan Darah</label>
        <select class="form-select @error('golongan_darah') is-invalid @enderror" 
                id="golongan_darah" name="golongan_darah">
            <option value="">Pilih Golongan Darah</option>
            <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
            <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
            <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
            <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
        </select>
        @error('golongan_darah')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
        <select class="form-select @error('status_pernikahan') is-invalid @enderror" 
                id="status_pernikahan" name="status_pernikahan">
            <option value="">Pilih Status</option>
            <option value="Belum Menikah" {{ old('status_pernikahan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
            <option value="Menikah" {{ old('status_pernikahan') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
            <option value="Cerai" {{ old('status_pernikahan') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
        </select>
        @error('status_pernikahan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="pekerjaan" class="form-label">Pekerjaan</label>
        <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" 
               id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}">
        @error('pekerjaan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="alergi" class="form-label">Alergi</label>
        <input type="text" class="form-control @error('alergi') is-invalid @enderror" 
               id="alergi" name="alergi" value="{{ old('alergi') }}" 
               placeholder="Contoh: Penisilin, Debu, Makanan Laut">
        @error('alergi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Emergency Contact -->
<div class="row mb-3">
    <div class="col-md-6">
        <label for="nama_keluarga" class="form-label">Nama Keluarga (Darurat)</label>
        <input type="text" class="form-control @error('nama_keluarga') is-invalid @enderror" 
               id="nama_keluarga" name="nama_keluarga" value="{{ old('nama_keluarga') }}">
        @error('nama_keluarga')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="hubungan_keluarga" class="form-label">Hubungan</label>
        <input type="text" class="form-control @error('hubungan_keluarga') is-invalid @enderror" 
               id="hubungan_keluarga" name="hubungan_keluarga" value="{{ old('hubungan_keluarga') }}"
               placeholder="Contoh: Ibu, Suami, Anak">
        @error('hubungan_keluarga')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Notes -->
<div class="row mb-3">
    <div class="col-12">
        <label for="catatan" class="form-label">Catatan Tambahan</label>
        <textarea class="form-control @error('catatan') is-invalid @enderror" 
                  id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
        @error('catatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    No. Rekam Medis akan digenerate otomatis oleh sistem
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Pasien
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Format phone number
    document.getElementById('no_hp').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3 && value.length <= 6) {
            value = value.replace(/(\d{3})(\d{1,3})/, '$1-$2');
        } else if (value.length > 6 && value.length <= 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1-$2-$3');
        } else if (value.length > 9) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        e.target.value = value;
    });
</script>
@endsection