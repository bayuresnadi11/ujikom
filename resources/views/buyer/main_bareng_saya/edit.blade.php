{{--
=============================================================================
VIEW: EDIT MAIN BARENG (HALAMAN EDIT)
Halaman untuk mengedit informasi main bareng yang sudah dibuat (sebagai host)
=============================================================================
--}}

{{-- Extend layout utama dan set judul halaman --}}
@extends('layouts.main', ['title' => 'Edit Main Bareng - SewaLap'])

{{-- Section untuk menambahkan CSS khusus halaman ini --}}
@push('styles')
    {{-- Memasukkan file CSS untuk styling dari partial --}}
    @include('buyer.main_bareng_saya.partials.style')
@endpush

@section('content')
    <div class="mobile-container" id="mobileContainer">
        <!-- ====================== HEADER ====================== -->
        {{-- Header khusus dengan tombol kembali --}}
        <header class="mobile-header">
            <div class="header-top">
                {{-- Tombol kembali ke halaman sebelumnya --}}
                <button class="header-icon" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="logo">
                    <i class="fas fa-edit logo-icon"></i>
                    Edit Main Bareng
                </div>
                <div class="header-actions"></div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content" style="padding-top: 60px;">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Edit Main Bareng</h1>
                <p class="page-subtitle">
                    Perbarui informasi main bareng Anda
                </p>
            </div>

            <!-- ====================== INFORMASI BOOKING (READONLY) ====================== -->
            {{-- Menampilkan informasi booking yang tidak bisa diubah (sebagai referensi) --}}
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="fas fa-ticket-alt"></i>
                    Informasi Booking
                </h3>
                <div class="card" style="margin-bottom: 0;">
                    {{-- Gambar venue --}}
                    <div class="card-image">
                        @if($playTogether->booking->venue->photo)
                            <img src="{{ asset('storage/' . $playTogether->booking->venue->photo) }}" 
                                 alt="{{ $playTogether->booking->venue->venue_name }}" class="venue-image" />
                        @else
                            <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                 alt="{{ $playTogether->booking->venue->venue_name }}" class="venue-image" />
                        @endif
                        {{-- Kode booking --}}
                        <div class="card-booking-code">{{ $playTogether->booking->ticket_code }}</div>
                    </div>
                    <div class="card-body">
                        {{-- Nama venue --}}
                        <div class="card-row">
                            <div class="card-label">
                                <i class="fas fa-futbol"></i>
                                Venue
                            </div>
                            <div class="card-value">{{ $playTogether->booking->venue->venue_name }}</div>
                        </div>
                        {{-- Tanggal main --}}
                        <div class="card-row">
                            <div class="card-label">
                                <i class="fas fa-calendar-alt"></i>
                                Tanggal
                            </div>
                            <div class="card-value">{{ \Carbon\Carbon::parse($playTogether->date)->translatedFormat('d M Y') }}</div>
                        </div>
                        {{-- Lokasi venue --}}
                        <div class="card-row">
                            <div class="card-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Lokasi
                            </div>
                            <div class="card-value">{{ $playTogether->location }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ====================== FORM EDIT MAIN BARENG ====================== -->
            {{-- Form untuk mengedit informasi main bareng (AJAX submit) --}}
            <form id="editForm" onsubmit="submitForm(event)">
                @csrf
                @method('PUT')  {{-- Method spoofing untuk PUT request --}}

                <!-- ====================== JUMLAH PESERTA ====================== -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-users"></i>
                        Jumlah Peserta
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Maksimal Peserta *</label>
                        <input type="number" 
                               class="form-control" 
                               id="max_participants" 
                               name="max_participants" 
                               value="{{ $playTogether->max_participants }}"
                               min="2" 
                               required>
                        <div class="error-message" id="max_participants_error"></div>
                    </div>
                </div>

                <!-- ====================== TIPE & BIAYA ====================== -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        Biaya
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran *</label>
                        {{-- Radio button untuk tipe gratis atau berbayar --}}
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" 
                                       name="type" 
                                       value="free" 
                                       class="radio-input"
                                       {{ $playTogether->type === 'free' ? 'checked' : '' }}
                                       onchange="togglePriceField()">
                                <span>Gratis</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" 
                                       name="type" 
                                       value="paid" 
                                       class="radio-input"
                                       {{ $playTogether->type === 'paid' ? 'checked' : '' }}
                                       onchange="togglePriceField()">
                                <span>Berbayar</span>
                            </label>
                        </div>
                        <div class="error-message" id="type_error"></div>
                    </div>

                    {{-- Field biaya per peserta (hanya muncul jika tipe = paid) --}}
                    <div class="form-group" id="price_field" style="{{ $playTogether->type === 'paid' ? '' : 'display:none;' }}">
                        <label class="form-label">Biaya per Peserta (Rp) *</label>
                        <input type="number" 
                               class="form-control" 
                               id="price_per_person" 
                               name="price_per_person" 
                               value="{{ $playTogether->price_per_person }}"
                               min="0" 
                               step="1000">
                        <div class="error-message" id="price_per_person_error"></div>
                    </div>
                </div>

                <!-- ====================== PRIVASI ====================== -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-lock"></i>
                        Privasi
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Tipe Privasi *</label>
                        {{-- Radio button untuk privacy: public, private, community --}}
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" 
                                       name="privacy" 
                                       value="public" 
                                       class="radio-input"
                                       {{ $playTogether->privacy === 'public' ? 'checked' : '' }}>
                                <span>Public</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" 
                                       name="privacy" 
                                       value="private" 
                                       class="radio-input"
                                       {{ $playTogether->privacy === 'private' ? 'checked' : '' }}>
                                <span>Private</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" 
                                       name="privacy" 
                                       value="community" 
                                       class="radio-input"
                                       {{ $playTogether->privacy === 'community' ? 'checked' : '' }}>
                                <span>Community</span>
                            </label>
                        </div>
                        <div class="error-message" id="privacy_error"></div>
                    </div>
                </div>

                <!-- ====================== JENIS KELAMIN ====================== -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-venus-mars"></i>
                        Jenis Kelamin Peserta
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Gender *</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="mixed" {{ $playTogether->gender === 'mixed' ? 'selected' : '' }}>Campur (Mixed)</option>
                            <option value="male" {{ $playTogether->gender === 'male' ? 'selected' : '' }}>Laki-laki saja</option>
                            <option value="female" {{ $playTogether->gender === 'female' ? 'selected' : '' }}>Perempuan saja</option>
                        </select>
                        <div class="error-message" id="gender_error"></div>
                    </div>
                </div>

                <!-- ====================== PERSETUJUAN HOST ====================== -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-check"></i>
                        Persetujuan Host
                    </h3>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" 
                                   class="checkbox-input" 
                                   id="host_approval" 
                                   name="host_approval"
                                   {{ $playTogether->host_approval ? 'checked' : '' }}>
                            <span>Memerlukan persetujuan host untuk bergabung</span>
                        </label>
                        <p class="text-muted" style="margin-top: 6px; margin-left: 24px;">
                            Jika dicentang, peserta baru harus menunggu persetujuan Anda sebelum bisa bergabung
                        </p>
                    </div>
                </div>

                <!-- ====================== DESKRIPSI ====================== -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-align-left"></i>
                        Deskripsi
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  maxlength="1000" 
                                  placeholder="Tambahkan deskripsi atau catatan...">{{ $playTogether->description }}</textarea>
                        <div class="error-message" id="description_error"></div>
                        {{-- Counter karakter --}}
                        <p class="text-muted" style="margin-top: 6px;">
                            <span id="charCount">{{ strlen($playTogether->description ?? '') }}</span>/1000 karakter
                        </p>
                    </div>
                </div>

                <!-- ====================== TOMBOL AKSI ====================== -->
                <div class="detail-section">
                    <div style="display: flex; gap: 10px;">
                        {{-- Tombol Batal (kembali ke halaman sebelumnya) --}}
                        <button type="button" class="btn-cancel" onclick="window.history.back()" style="flex: 1;">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        {{-- Tombol Submit Simpan Perubahan --}}
                        <button type="submit" class="btn-submit" id="submitBtn" style="flex: 2;">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * FUNGSI TOGGLE PRICE FIELD
         * Menampilkan atau menyembunyikan field biaya per peserta
         * berdasarkan pilihan metode pembayaran (Gratis / Berbayar)
         */
        function togglePriceField() {
            const type = document.querySelector('input[name="type"]:checked').value;
            const priceField = document.getElementById('price_field');
            const priceInput = document.getElementById('price_per_person');
            
            if (type === 'paid') {
                priceField.style.display = 'block';
                priceInput.required = true;  // Field menjadi required jika berbayar
            } else {
                priceField.style.display = 'none';
                priceInput.required = false; // Field tidak required jika gratis
                priceInput.value = '';       // Kosongkan nilai
            }
        }

        /**
         * COUNTER KARAKTER DESKRIPSI
         * Menampilkan jumlah karakter yang sudah ditulis (maksimal 1000)
         */
        document.getElementById('description').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        /**
         * FUNGSI CLEAR ERRORS
         * Menghapus semua pesan error dan class error dari form
         */
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('error');
            });
        }

        /**
         * FUNGSI SHOW ERRORS
         * Menampilkan pesan error validasi dari server
         * @param {object} errors - Object error dari response server
         */
        function showErrors(errors) {
            clearErrors();
            for (const [field, messages] of Object.entries(errors)) {
                const input = document.getElementById(field);
                const errorEl = document.getElementById(field + '_error');
                if (input) {
                    input.classList.add('error');
                }
                if (errorEl && messages && messages.length > 0) {
                    errorEl.textContent = messages[0];
                    errorEl.classList.add('show');
                }
            }
        }

        /**
         * FUNGSI SUBMIT FORM (AJAX)
         * Mengirim data form secara asynchronous ke server
         * @param {Event} event - Event submit form
         */
        async function submitForm(event) {
            event.preventDefault();  // Mencegah reload halaman

            const form = event.target;
            const formData = new FormData(form);
            
            // Konversi nilai checkbox ke 1 atau 0
            formData.set('host_approval', document.getElementById('host_approval').checked ? 1 : 0);

            // Konversi FormData ke object untuk dikirim sebagai JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Disable tombol submit dan tampilkan loading
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

            try {
                // Kirim request PUT ke server
                const response = await fetch('{{ route('buyer.main_bareng_saya.update', $playTogether->id) }}', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Tampilkan toast sukses
                    showToast(result.message, 'success');
                    
                    // Redirect ke halaman detail setelah 1.5 detik
                    setTimeout(() => {
                        window.location.href = '{{ route('buyer.main_bareng_saya.show', $playTogether->id) }}';
                    }, 1500);
                } else {
                    if (result.errors) {
                        // Tampilkan error validasi
                        showErrors(result.errors);
                    } else {
                        // Tampilkan pesan error umum
                        showToast(result.message || 'Terjadi kesalahan', 'error');
                    }
                }
            } catch (error) {
                // Tampilkan error jaringan
                showToast('Terjadi kesalahan jaringan', 'error');
                console.error('Error submitting form:', error);
            } finally {
                // Kembalikan tombol submit ke state semula
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
            }
        }

        /**
         * FUNGSI SHOW TOAST
         * Menampilkan notifikasi toast sementara
         * @param {string} message - Pesan yang akan ditampilkan
         * @param {string} type - Tipe notifikasi (success / error / info)
         */
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle'}"></i>
                </div>
                <div class="toast-content">
                    <h4>${type === 'success' ? 'Sukses!' : type === 'error' ? 'Error!' : 'Info'}</h4>
                    <p>${message}</p>
                </div>
            `;

            container.appendChild(toast);
            
            // Animasi muncul
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Hapus setelah 5 detik
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
@endpush