<div class="section-card">
    <div class="section-header">
        <div class="section-icon" style="background: #25D366; color: white;">
            <i class="fab fa-whatsapp"></i>
        </div>
        <div>
            <h3 class="section-title">WhatsApp Gateway (Japati)</h3>
            <p class="section-desc">Konfigurasikan WhatsApp API untuk pemberitahuan dan pesan.</p>
        </div>
        <span class="status-badge success status-success">
            <span class="status-indicator"></span>
            Connected
        </span>
    </div>

    <div class="form-group">
        <label class="form-label">
            API Token <span class="required">*</span>
        </label>
        <div class="input-group">
            <i class="fas fa-key input-icon"></i>
            <input type="text" 
                    name="japati_api_token"
                   class="form-control input-with-icon" 
                   placeholder="Enter your Japati API token"
                   value="{{ old('japati_api_token', $setting->japati_api_token ?? '') }}"
                   id="apiToken">
        </div>
        <p class="form-text">
            <i class="fas fa-shield-alt me-1"></i>  Token API dienkripsi dan disimpan dengan aman.
        </p>
    </div>

    <div class="form-group">
        <label class="form-label">
            Gateway Number <span class="required">*</span>
        </label>
        <div class="input-group">
            <i class="fas fa-phone input-icon"></i>
            <input type="text" 
                    name="japati_gateway_number" 
                   class="form-control input-with-icon" 
                   value="{{ old('japati_gateway_number', $setting->japati_gateway_number ?? '') }}"
                   placeholder="Masukan Nomor Anda">
        </div>
    </div>
</div>