@extends('layouts.main', ['title' => 'Tips Manajemen Venue'])

@push('styles')
    @include('landowner.tips.partials.tips-style')
@endpush

@section('content')
    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span>Notification message</span>
    </div>

    <!-- Main Container -->
    <div class="mobile-container">
        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header">
                <h1 class="page-title">Tips Manajemen Venue</h1>
                <p class="page-subtitle">Tips dan strategi untuk mengelola venue olahraga Anda</p>
            </section>

            <!-- Category Filter -->
            <div class="category-filter">
                <div class="filter-container">
                    <div class="filter-title">
                        <i class="fas fa-filter"></i>
                        Filter Kategori
                    </div>
                    <div class="filter-chips">
                        <div class="filter-chip active" data-category="all">Semua Tips</div>
                        <div class="filter-chip" data-category="pricing">Penentuan Harga</div>
                        <div class="filter-chip" data-category="marketing">Pemasaran</div>
                        <div class="filter-chip" data-category="facilities">Fasilitas</div>
                        <div class="filter-chip" data-category="maintenance">Perawatan</div>
                        <div class="filter-chip" data-category="customer">Layanan Pelanggan</div>
                        <div class="filter-chip" data-category="digital">Digital</div>
                        <div class="filter-chip" data-category="safety">Keamanan</div>
                    </div>
                </div>
            </div>

            <!-- Tips Content -->
            <div class="tips-container">
                <!-- Marketing Tips -->
                <div class="tips-section" id="marketing-tips">
                    <h2 class="section-title">
                        <i class="fas fa-bullhorn"></i> Pemasaran
                    </h2>
                    <div class="tips-scroll-container" id="marketing-scroll">
                        <!-- Card 1: Foto Berkualitas Tinggi -->
                        <div class="tip-card" data-category="marketing">
                            <div class="tip-header">
                                <h3 class="tip-title">Foto Berkualitas Tinggi dan Desain Menarik</h3>
                                <span class="tip-category">Pemasaran</span>
                            </div>
                            <p class="tip-content">
                                Investasi dalam foto profesional dapat meningkatkan minat penyewa hingga 70%. Tampilkan semua sudut venue Anda dengan angle yang menarik dan pencahayaan yang baik. Pastikan foto mencakup fasilitas utama, area parkir, dan fasilitas pendukung lainnya.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>4 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn bookmarked" onclick="toggleBookmark(this)">
                                        <i class="fas fa-bookmark"></i> Tersimpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Manfaatkan Media Sosial -->
                        <div class="tip-card" data-category="marketing">
                            <div class="tip-header">
                                <h3 class="tip-title">Manfaatkan Media Sosial Secara Optimal</h3>
                                <span class="tip-category">Pemasaran</span>
                            </div>
                            <p class="tip-content">
                                Promosikan venue Anda di Instagram dan Facebook dengan hashtag lokal. Tampilkan testimoni dari penyewa sebelumnya dan buat konten yang menarik perhatian calon penyewa. Gunakan fitur stories untuk update real-time dan live streaming untuk virtual tour.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>6 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Program Referral -->
                        <div class="tip-card" data-category="marketing">
                            <div class="tip-header">
                                <h3 class="tip-title">Program Referral yang Menguntungkan</h3>
                                <span class="tip-category">Pemasaran</span>
                            </div>
                            <p class="tip-content">
                                Buat program referral dengan memberikan diskon atau hadiah untuk pelanggan yang merekomendasikan venue Anda kepada teman atau kolega mereka. Sistem ini dapat membangun basis pelanggan loyal dan mengurangi biaya akuisisi pelanggan baru.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>5 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Konten Video -->
                        <div class="tip-card" data-category="marketing">
                            <div class="tip-header">
                                <h3 class="tip-title">Konten Video yang Menarik</h3>
                                <span class="tip-category">Pemasaran</span>
                            </div>
                            <p class="tip-content">
                                Buat video tur virtual venue Anda. Video 1-2 menit dapat memberikan gambaran lengkap tentang fasilitas dan suasana venue kepada calon penyewa. Sertakan footage aktivitas olahraga yang sedang berlangsung untuk menunjukkan venue dalam aksi.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>7 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 5: Kolaborasi dengan Komunitas -->
                        <div class="tip-card" data-category="marketing">
                            <div class="tip-header">
                                <h3 class="tip-title">Kolaborasi dengan Komunitas Lokal</h3>
                                <span class="tip-category">Pemasaran</span>
                            </div>
                            <p class="tip-content">
                                Bekerjasama dengan komunitas olahraga lokal untuk mengadakan event atau latihan reguler di venue Anda. Ini dapat meningkatkan visibilitas dan menarik pengguna baru. Tawarkan harga khusus untuk komunitas sebagai bentuk dukungan.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>8 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 6: SEO Lokal -->
                        <div class="tip-card" data-category="marketing">
                            <div class="tip-header">
                                <h3 class="tip-title">Optimasi SEO Lokal yang Efektif</h3>
                                <span class="tip-category">Pemasaran</span>
                            </div>
                            <p class="tip-content">
                                Optimalkan SEO lokal dengan mencantumkan venue Anda di Google My Business dan direktori lokal. Tambahkan foto, jam operasional, dan informasi kontak yang lengkap. Mintalah review dari pelanggan untuk meningkatkan kredibilitas dan ranking.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>5 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Tips -->
                <div class="tips-section" id="pricing-tips">
                    <h2 class="section-title">
                        <i class="fas fa-tags"></i> Penentuan Harga
                    </h2>
                    <div class="tips-scroll-container" id="pricing-scroll">
                        <!-- Card 1 -->
                        <div class="tip-card" data-category="pricing">
                            <div class="tip-header">
                                <h3 class="tip-title">Analisis Harga Pasar yang Mendalam</h3>
                                <span class="tip-category">Harga</span>
                            </div>
                            <p class="tip-content">
                                Selalu pantau harga venue serupa di wilayah Anda. Tentukan harga kompetitif dengan mempertimbangkan fasilitas yang Anda tawarkan dan keunggulan unik venue Anda. Gunakan data historis untuk menentukan harga optimal.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>5 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="tip-card" data-category="pricing">
                            <div class="tip-header">
                                <h3 class="tip-title">Strategi Diskon Musiman yang Efektif</h3>
                                <span class="tip-category">Harga</span>
                            </div>
                            <p class="tip-content">
                                Tawarkan diskon pada musim sepi untuk meningkatkan okupansi. Berikan diskon khusus untuk pemesanan jangka panjang atau penyewa tetap yang loyal. Promosikan paket khusus untuk event tertentu.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>3 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="tip-card" data-category="pricing">
                            <div class="tip-header">
                                <h3 class="tip-title">Strategi Dynamic Pricing yang Cerdas</h3>
                                <span class="tip-category">Harga</span>
                            </div>
                            <p class="tip-content">
                                Terapkan harga dinamis berdasarkan waktu, hari, dan musim. Harga lebih tinggi di akhir pekan dan hari libur, dan lebih rendah pada hari kerja siang hari. Gunakan data permintaan untuk penyesuaian harga.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>6 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="tip-card" data-category="pricing">
                            <div class="tip-header">
                                <h3 class="tip-title">Paket Bundling yang Menarik</h3>
                                <span class="tip-category">Harga</span>
                            </div>
                            <p class="tip-content">
                                Tawarkan paket bundling seperti sewa venue + peralatan atau sewa venue + konsumsi. Ini meningkatkan nilai transaksi dan kepuasan pelanggan. Berikan harga khusus untuk paket yang lebih komprehensif.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>4 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facilities Tips -->
                <div class="tips-section" id="facilities-tips">
                    <h2 class="section-title">
                        <i class="fas fa-cogs"></i> Fasilitas
                    </h2>
                    <div class="tips-scroll-container" id="facilities-scroll">
                        <!-- Card 1 -->
                        <div class="tip-card" data-category="facilities">
                            <div class="tip-header">
                                <h3 class="tip-title">Fasilitas Tambahan yang Meningkatkan Nilai</h3>
                                <span class="tip-category">Fasilitas</span>
                            </div>
                            <p class="tip-content">
                                Tambahkan fasilitas seperti WiFi gratis, area parkir yang luas, toilet bersih, dan ruang ganti. Fasilitas tambahan meningkatkan nilai venue di mata penyewa. Pertimbangkan juga fasilitas seperti tempat duduk penonton dan area istirahat.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>5 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="tip-card" data-category="facilities">
                            <div class="tip-header">
                                <h3 class="tip-title">Pencahayaan Optimal untuk Aktivitas Olahraga</h3>
                                <span class="tip-category">Fasilitas</span>
                            </div>
                            <p class="tip-content">
                                Pastikan pencahayaan venue memadai untuk aktivitas olahraga. Gunakan lampu LED yang hemat energi dan memiliki brightness yang sesuai untuk olahraga malam hari. Pertimbangkan sistem pencahayaan yang dapat disesuaikan untuk berbagai jenis olahraga.
                            </p>
                            <div class="tip-footer">
                                <div class="tip-meta">
                                    <i class="far fa-clock"></i>
                                    <span>4 min read</span>
                                </div>
                                <div class="tip-actions">
                                    <button class="tip-action-btn" onclick="toggleBookmark(this)">
                                        <i class="far fa-bookmark"></i> Simpan
                                    </button>
                                    <button class="tip-action-btn share-btn" onclick="shareTip(this)">
                                        <i class="fas fa-share-alt"></i> Bagikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div class="empty-state" id="empty-state" style="display: none;">
                    <i class="fas fa-lightbulb empty-state-icon"></i>
                    <h3 class="empty-state-title">Tidak ada tips untuk kategori ini</h3>
                    <p class="empty-state-desc">Coba pilih kategori lain untuk melihat tips manajemen venue.</p>
                </div>
            </div>
        </main>

        <!-- Bottom Nav -->
        @include('layouts.bottom-nav')
    </div>
@endsection

@push('scripts')
    @include('landowner.tips.partials.tips-script')
@endpush