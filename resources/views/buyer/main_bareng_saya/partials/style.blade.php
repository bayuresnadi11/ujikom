<style>
/* ================ COPY INI DI AWAL <style> ANDA ================ */

/* HEADER STYLES */
.mobile-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(135deg, #08482B 0%, #0A5C36 100%);
    z-index: 1100;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.logo {
    font-size: 18px;
    font-weight: 800;
    color: white;
    text-decoration: none;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.logo-icon {
    background: rgba(255, 255, 255, 0.2);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.logo span {
    color: #a3e9c0;
    font-weight: 700;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.header-icon {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: white;
    padding: 6px;
    border-radius: 10px;
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #E74C3C;
    color: white;
    font-size: 9px;
    font-weight: 800;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #0A5C36;
}

.header-icon:hover {
    background: rgba(255, 255, 255, 0.25);
}

.search-bar {
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.1);
}

.search-container {
    display: flex;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.search-container:focus-within {
    border-color: #2ECC71;
    box-shadow: 0 0 0 2px rgba(46, 204, 113, 0.2);
}

.search-input {
    flex: 1;
    padding: 14px;
    border: none;
    background: transparent;
    font-size: 14px;
    outline: none;
    color: #212529;
    font-weight: 500;
}

.search-input::placeholder {
    color: #6C757D;
    opacity: 0.8;
    font-size: 13px;
}

.search-btn {
    background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%);
    color: #fff;
    border: none;
    padding: 0 16px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-btn:hover {
    opacity: 0.9;
}

/* BOTTOM NAV STYLES */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: white;
    display: flex;
    justify-content: space-around;
    padding: 8px 0;
    box-shadow: 0 -2px 12px rgba(10, 92, 54, 0.1);
    z-index: 1000;
    border-top: 1px solid #E9ECEF;
}

.nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 10px;
    color: #6C757D;
    text-decoration: none;
    padding: 4px 12px;
    transition: all 0.3s ease;
    border-radius: 10px;
    position: relative;
    cursor: pointer;
    background: none;
    border: none;
    min-width: 56px;
}

.nav-item.active {
    color: #0A5C36;
    transform: translateY(-6px);
}

.nav-item.active .nav-icon {
    background: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
    color: white;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    transform: scale(1.05);
}

.nav-item.active::after {
    content: "";
    position: absolute;
    top: -4px;
    width: 24px;
    height: 3px;
    background: linear-gradient(135deg, #27AE60 0%, #2ECC71 100%);
    border-radius: 2px;
}

.nav-item:hover {
    color: #0A5C36;
    background: rgba(10, 92, 54, 0.05);
}

.nav-icon {
    font-size: 18px;
    margin-bottom: 4px;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: #F8F9FA;
    color: #6C757D;
}

/* ================ VARIABLES ================ */
:root {
    --primary: #0A5C36;
    --primary-dark: #08472a;
    --primary-light: #0e6b40;
    --secondary: #2ecc71;
    --accent: #27ae60;
    --success: #2ecc71;
    --warning: #f39c12;
    --danger: #e74c3c;
    --info: #3498db;
    --light: #F8F9FA;
    --light-gray: #E9ECEF;
    --border: #E6F7EF;
    --text: #1A3A27;
    --text-light: #6C757D;
    --text-lighter: #8A9C93;
    --shadow-sm: 0 1px 3px rgba(10, 92, 54, 0.1);
    --shadow-md: 0 2px 8px rgba(10, 92, 54, 0.15);
    --shadow-lg: 0 4px 12px rgba(10, 92, 54, 0.2);
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    --gradient-accent: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
    --gradient-light: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
    --gradient-dark: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    --radius-sm: 6px;
    --radius-md: 8px;
    --radius-lg: 10px;
    --radius-xl: 12px;
    --radius-2xl: 16px;
}

/* ================ RESET & BASE ================ */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
    background: linear-gradient(135deg, #f0faf5 0%, #e6f7ed 100%);
    color: var(--text);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}

/* ================ MOBILE CONTAINER ================ */
.mobile-container {
    width: 100%;
    min-height: 100vh;
    margin: 0 auto;
    background: #ffffff;
    position: relative;
    overflow-x: hidden;
    max-width: 100%;
}

/* ================ MAIN CONTENT ================ */
.main-content {
    padding: 120px 16px 80px;
    min-height: 100vh;
}

/* ================ PAGE HEADER ================ */
.page-header {
    margin-bottom: 20px;
    text-align: center;
}

.page-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 6px;
    line-height: 1.3;
}

.page-subtitle {
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.4;
    max-width: 280px;
    margin: 0 auto;
}

/* ================ TABS ================ */
.tabs {
    display: flex;
    margin-bottom: 20px;
    background: var(--light);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    overflow: hidden;
}

.tab-btn {
    flex: 1;
    padding: 12px;
    background: none;
    border: none;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    position: relative;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.tab-btn.active {
    color: var(--primary);
    background: white;
}

.tab-btn.active::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--gradient-primary);
}

/* ================ ACTIONS BAR ================ */
.actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 12px 16px;
    background: var(--light);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 12px;
}

.btn-add {
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
    white-space: nowrap;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 160px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-lighter);
    font-size: 14px;
}

/* ================ CARD CONTAINER ================ */
.card-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 20px;
}

.card {
    background: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border);
    transition: all 0.2s ease;
    position: relative;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}

.card-image {
    width: 100%;
    height: 120px;
    overflow: hidden;
    background: var(--light-gray);
    position: relative;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.venue-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card:hover .card-image img {
    transform: scale(1.05);
}

.card-booking-code {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(10, 92, 54, 0.9);
    color: white;
    padding: 3px 8px;
    border-radius: var(--radius-sm);
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.card-header {
    padding: 12px 16px;
    background: var(--light);
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
}

.card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-title i {
    color: var(--accent);
    font-size: 16px;
}

.card-badges {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.card-body {
    padding: 16px;
}

.card-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--light-gray);
}

.card-row:last-child {
    border-bottom: none;
}

.card-label {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 6px;
}

.card-label i {
    width: 16px;
    color: var(--text-lighter);
    font-size: 12px;
}

.card-value {
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    text-align: right;
}

.card-footer {
    padding: 12px 16px;
    background: var(--light);
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

/* ================ BADGES ================ */
.badge {
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.5px;
}

.badge i {
    font-size: 8px;
}

.badge-public {
    background: #E8F5E9;
    color: var(--success);
    border: 1px solid #C8E6C9;
}

.badge-private {
    background: #FFF3E0;
    color: var(--warning);
    border: 1px solid #FFE0B2;
}

.badge-paid {
    background: #E8F5E9;
    color: var(--success);
    border: 1px solid #C8E6C9;
}

.badge-free {
    background: #E3F2FD;
    color: var(--info);
    border: 1px solid #BBDEFB;
}

.badge-host-yes {
    background: #E3F2FD;
    color: var(--info);
    border: 1px solid #BBDEFB;
}

.badge-host-no {
    background: #FFEBEE;
    color: var(--danger);
    border: 1px solid #FFCDD2;
}

.badge-gender {
    background: var(--light);
    color: var(--primary);
    border: 1px solid var(--border);
}

.badge-cost {
    background: linear-gradient(135deg, #E8F5E9, #F1F8E9);
    color: var(--success);
    border: 1px solid #C8E6C9;
    font-weight: 800;
}

.badge-active {
    background: #E8F5E9;
    color: var(--success);
    border: 1px solid #C8E6C9;
}

.badge-canceled {
    background: #FFEBEE;
    color: var(--danger);
    border: 1px solid #FFCDD2;
}

.badge-completed {
    background: #E3F2FD;
    color: var(--info);
    border: 1px solid #BBDEFB;
}

.badge-today {
    background: #FFF3E0;
    color: #E65100;
    border: 1px solid #FFE0B2;
}

.badge-upcoming {
    background: #E8F5E9;
    color: #0A5C36;
    border: 1px solid #C8E6C9;
}

/* ================ ACTION BUTTONS ================ */
.action-buttons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    width: 100%;
}

.btn-action {
    padding: 6px 12px;
    border-radius: var(--radius-sm);
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    flex: 1;
    justify-content: center;
}

.btn-edit {
    background: #E3F2FD;
    color: #2980B9;
    border: 1px solid #BBDEFB;
}

.btn-edit:hover {
    background: #2980B9;
    color: white;
    border-color: #2980B9;
}

.btn-delete {
    background: #FFEBEE;
    color: var(--danger);
    border: 1px solid #FFCDD2;
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
    border-color: var(--danger);
}

.btn-view {
    background: var(--light);
    color: var(--primary);
    border: 1px solid var(--border);
}

.btn-view:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* ================ FILTER INFO ================ */
.filter-info {
    padding: 0 16px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--text-light);
}

.filter-badge {
    background: var(--light);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: var(--primary);
    border: 1px solid var(--border);
}

/* ================ PAGINATION ================ */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.pagination-link {
    padding: 6px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    text-decoration: none;
    font-weight: 600;
    font-size: 12px;
    transition: all 0.2s ease;
    min-width: 32px;
    text-align: center;
}

.pagination-link:hover {
    background: var(--light);
    border-color: var(--primary);
}

.pagination-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* ================ TOAST NOTIFICATION ================ */
.toast-container {
    position: fixed;
    top: 80px;
    right: 16px;
    z-index: 3000;
    max-width: 280px;
    pointer-events: none;
}

.toast {
    background: white;
    border-radius: var(--radius-md);
    padding: 12px;
    margin-bottom: 8px;
    box-shadow: var(--shadow-md);
    border-left: 4px solid var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
    transform: translateX(300px);
    transition: transform 0.2s ease, opacity 0.2s ease;
    opacity: 0;
    pointer-events: auto;
}

.toast.show {
    transform: translateX(0);
    opacity: 1;
}

.toast-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.toast-success .toast-icon {
    background: #E8F5E9;
    color: var(--success);
}

.toast-error .toast-icon {
    background: #FFEBEE;
    color: var(--danger);
}

.toast-info .toast-icon {
    background: #E3F2FD;
    color: var(--info);
}

.toast-content {
    flex: 1;
    min-width: 0;
}

.toast-content h4 {
    margin: 0 0 4px 0;
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
}

.toast-content p {
    margin: 0;
    font-size: 11px;
    color: var(--text-light);
    line-height: 1.4;
}

/* ================ MODAL STYLES ================ */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: var(--radius-lg);
    width: 100%;
    max-width: 320px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-lg);
    animation: modalIn 0.3s ease-out;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-body {
    padding: 16px;
}

.modal-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    background: var(--light);
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
}

.btn-submit {
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 100px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-cancel {
    background: var(--light);
    color: var(--text-light);
    border: 1px solid var(--border);
    padding: 10px 16px;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 80px;
}

.btn-cancel:hover {
    background: var(--border);
    color: var(--text);
}

/* ================ CONFIRMATION MODAL ================ */
.confirm-modal .modal-content {
    max-width: 320px;
}

.confirm-icon {
    width: 60px;
    height: 60px;
    background: #FFEBEE;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: var(--danger);
    font-size: 28px;
    border: 2px solid #FFCDD2;
}

.confirm-text {
    text-align: center;
    margin-bottom: 20px;
    padding: 0 16px;
}

.confirm-text h3 {
    font-size: 18px;
    color: var(--text);
    margin-bottom: 8px;
    font-weight: 700;
}

.confirm-text p {
    color: var(--text-light);
    font-size: 12px;
    line-height: 1.4;
}

/* ================ NO DATA STATE ================ */
.no-data {
    text-align: center;
    padding: 40px 16px;
    color: var(--text-lighter);
}

.no-data i {
    font-size: 36px;
    margin-bottom: 12px;
    color: var(--border);
    opacity: 0.5;
}

.no-data h3 {
    font-size: 16px;
    color: var(--text-light);
    margin-bottom: 6px;
    font-weight: 600;
}

.no-data p {
    font-size: 12px;
    margin-bottom: 16px;
    max-width: 250px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.4;
}

/* ================ FORM STYLES ================ */
.detail-section {
    margin-bottom: 20px;
    background: white;
    border-radius: var(--radius-lg);
    padding: 16px;
    border: 1px solid var(--border);
}

.section-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: var(--accent);
    font-size: 13px;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid var(--light-gray);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 12px;
    color: var(--text-light);
    flex: 1;
    display: flex;
    align-items: center;
    gap: 6px;
}

.detail-label i {
    width: 16px;
    color: var(--text-lighter);
    font-size: 12px;
}

.detail-value {
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    text-align: right;
    flex: 1;
}

.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: var(--text);
    font-size: 12px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    font-size: 13px;
    background: white;
    color: var(--text);
    transition: all 0.2s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(10, 92, 54, 0.1);
}

.form-control.error {
    border-color: var(--danger);
}

.error-message {
    color: var(--danger);
    font-size: 10px;
    margin-top: 4px;
    display: none;
}

.error-message.show {
    display: block;
}

.radio-group {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    padding: 6px;
    border-radius: var(--radius-sm);
    transition: background 0.2s ease;
}

.radio-label:hover {
    background: var(--light);
}

.radio-input {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    padding: 6px;
    border-radius: var(--radius-sm);
    transition: background 0.2s ease;
}

.checkbox-label:hover {
    background: var(--light);
}

.checkbox-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* ================ UTILITIES ================ */
.text-muted {
    color: var(--text-lighter);
    font-size: 11px;
}

.text-sm {
    font-size: 11px;
}

.font-semibold {
    font-weight: 600;
}

.text-center {
    text-align: center;
}

.w-full {
    width: 100%;
}

.mt-2 {
    margin-top: 8px;
}

.mb-4 {
    margin-bottom: 16px;
}

.p-4 {
    padding: 16px;
}

/* ================ RESPONSIVE ================ */
@media (max-width: 360px) {
    .main-content {
        padding: 110px 12px 70px;
    }

    .page-title {
        font-size: 18px;
    }

    .page-subtitle {
        font-size: 12px;
        max-width: 240px;
    }

    .tabs {
        flex-direction: column;
    }

    .actions-bar {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .search-box {
        min-width: 100%;
    }

    .card-header {
        padding: 10px 12px;
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .card-title {
        font-size: 14px;
    }

    .card-body {
        padding: 12px;
    }

    .card-row {
        padding: 8px 0;
        flex-direction: column;
        align-items: flex-start;
        gap: 3px;
    }

    .card-value {
        text-align: left;
        width: 100%;
    }

    .card-footer {
        padding: 10px 12px;
        flex-direction: column;
        gap: 8px;
    }

    .action-buttons {
        width: 100%;
    }

    .btn-action {
        flex: 1;
        justify-content: center;
    }

    .pagination {
        gap: 4px;
    }

    .pagination-link {
        padding: 4px 8px;
        font-size: 11px;
        min-width: 28px;
    }

    .btn-submit,
    .btn-cancel {
        padding: 8px 16px;
        font-size: 12px;
        min-width: 70px;
    }

    .toast-container {
        left: 12px;
        right: 12px;
        max-width: calc(100% - 24px);
    }
}

@media (min-width: 480px) {
    .mobile-container {
        max-width: 480px;
        margin: 10px auto;
        box-shadow: 0 0 40px rgba(10, 92, 54, 0.15);
        border-radius: var(--radius-2xl);
        overflow: hidden;
    }

    .mobile-header {
        max-width: 480px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
    }

    .bottom-nav {
        max-width: 480px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
}
</style>