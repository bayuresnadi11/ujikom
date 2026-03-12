<div class="section-card">
    <div class="section-header">
        <div class="section-icon" style="background: #FF6B6B; color: white;">
            <i class="fas fa-credit-card"></i>
        </div>
        <div>
            <h3 class="section-title">Midtrans Payment Gateway</h3>
            <p class="section-desc">Konfigurasikan pengaturan pemrosesan pembayaran dan gateway.</p>
        </div>
        <span class="status-badge {{ ($setting->midtrans_is_production ?? false) ? 'success' : 'warning' }}">
            <span class="status-indicator"></span>
            {{ ($setting->midtrans_is_production ?? false) ? 'Mode Produksi' : 'Mode Sandbox' }}
        </span>
    </div>

    <div class="form-group">
        <label class="form-label">
            Server Key <span class="required">*</span>
        </label>
        <div class="input-group">
            <i class="fas fa-server input-icon"></i>
            <input type="text" 
                   class="form-control input-with-icon" 
                   name="midtrans_server_key"
                   placeholder="Masukan Kunci Server Midtrans"
                   value="{{ old('midtrans_server_key', $setting->midtrans_server_key ?? '') }}"
                   id="serverKey">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">
            Client Key <span class="required">*</span>
        </label>
        <div class="input-group">
            <i class="fas fa-id-card input-icon"></i>
            <input type="text"
                   name="midtrans_client_key" 
                   class="form-control input-with-icon" 
                   placeholder="Masukan Kunci Klien Midtrans"
                   value="{{ old('midtrans_client_key', $setting->midtrans_client_key ?? '') }}"
                   id="clientKey">
        </div>
    </div>

    <div class="switch-container">
        <div class="switch-label">
            <span class="switch-title">Mode Produksi</span>
            <span class="switch-desc">Aktifkan untuk transaksi nyata dengan uang sungguhan</span>
        </div>
        <input type="hidden" name="midtrans_is_production" value="0">

        <label class="toggle-switch">
            <input 
                type="checkbox"
                id="productionMode"
                name="midtrans_is_production"
                value="1"
                {{ old('midtrans_is_production', $setting->midtrans_is_production ?? false) ? 'checked' : '' }}
            >
            <span class="toggle-slider"></span>
        </label>
    </div>

    <div class="alert" style="background: rgba(243, 156, 18, 0.1); color: var(--warning); padding: 16px; border-radius: 8px; margin-top: 20px; border-left: 4px solid var(--warning);">
        <div style="display: flex; align-items: flex-start;">
            <i class="fas fa-exclamation-triangle me-2" style="margin-top: 2px;"></i>
            <div>
                <strong style="display: block; margin-bottom: 4px;">Peringatan: Mode Produksi</strong>
                <p style="margin: 0; font-size: 0.9rem;">
                    Pastikan semua kredensial benar sebelum beralih ke mode produksi.
                    Uji secara menyeluruh terlebih dahulu di mode sandbox.
                </p>
            </div>
        </div>
    </div>
</div>