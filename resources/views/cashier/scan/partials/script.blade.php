<script>
    let html5QrCode;
    let scanMode = 'venue';
    let isScanning = false;
    let availableCameras = [];
    let currentCameraId = null;

    function showNotification(type, title, message, duration = 3000) {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ'
        };
        
        notification.innerHTML = `
            <div class="notification-icon">${icons[type]}</div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">×</button>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }

    function showQr(ticketCode) {
        document.getElementById('qrContainer').innerHTML = '';
        document.getElementById('ticketCode').innerText = ticketCode;

        new QRCode(document.getElementById('qrContainer'), {
            text: ticketCode,
            width: 220,
            height: 220
        });

        const modal = new bootstrap.Modal(document.getElementById('qrModal'));
        modal.show();
    }
    function showGreenFlash() {
        const scanner = document.getElementById('qr-reader');
        const flash = document.createElement('div');

        flash.style.cssText = `
            position: absolute;
            inset: 0;
            background: rgba(40, 167, 69, 0.6);
            z-index: 9999;
            pointer-events: none;
            animation: greenFlash 0.5s ease;
        `;

        if (!document.getElementById('green-flash-style')) {
            const style = document.createElement('style');
            style.id = 'green-flash-style';
            style.textContent = `
                @keyframes greenFlash {
                    0% { opacity: 0; }
                    50% { opacity: 1; }
                    100% { opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        scanner.style.position = 'relative';
        scanner.appendChild(flash);
        setTimeout(() => flash.remove(), 500);
    }

    document.querySelectorAll('.mode-toggle button').forEach((btn, index) => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.mode-toggle button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            scanMode = index === 0 ? 'venue' : 'section';
            
            const modeText = scanMode === 'venue' ? 'Masuk Venue' : 'Masuk Lapangan';
            document.querySelector('.scanner-status').innerHTML = 
                `<i class="fas fa-check-circle me-2"></i> Mode: ${modeText}`;
            
            showNotification('info', 'Mode Berubah', `Sekarang dalam mode ${modeText}`, 2000);
        });
    });

    document.getElementById('camera-select').addEventListener('change', function() {
        const selectedCameraId = this.value;
        if (selectedCameraId && selectedCameraId !== currentCameraId) {
            switchCamera(selectedCameraId);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        initScanner();
    });

    function showRedFlash() {
        const scanner = document.getElementById('qr-reader');
        const flash = document.createElement('div');

        flash.style.cssText = `
            position: absolute;
            inset: 0;
            background: rgba(220, 53, 69, 0.6);
            z-index: 9999;
            pointer-events: none;
            animation: redFlash 0.5s ease;
        `;

        if (!document.getElementById('red-flash-style')) {
            const style = document.createElement('style');
            style.id = 'red-flash-style';
            style.textContent = `
                @keyframes redFlash {
                    0% { opacity: 0; }
                    50% { opacity: 1; }
                    100% { opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        scanner.style.position = 'relative';
        scanner.appendChild(flash);
        setTimeout(() => flash.remove(), 500);
    }

    function initScanner() {
        html5QrCode = new Html5Qrcode("qr-reader");
        
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                availableCameras = cameras;
                populateCameraSelect(cameras);
                
                let defaultCameraId = cameras[cameras.length - 1].id;
                currentCameraId = defaultCameraId;
                
                document.getElementById('camera-select').value = defaultCameraId;
                startScanning(defaultCameraId);
            } else {
                showError('Tidak ada kamera yang terdeteksi');
                document.getElementById('camera-select').innerHTML = 
                    '<option value="">Tidak ada kamera</option>';
            }
        }).catch(err => {
            showError('Error mendapatkan kamera: ' + err);
            document.getElementById('camera-select').innerHTML = 
                '<option value="">Error loading kamera</option>';
        });
    }

    function populateCameraSelect(cameras) {
        const select = document.getElementById('camera-select');
        select.innerHTML = '';
        
        cameras.forEach((camera, index) => {
            const option = document.createElement('option');
            option.value = camera.id;
            
            let label = camera.label || `Kamera ${index + 1}`;
            
            if (label.toLowerCase().includes('back') || label.toLowerCase().includes('rear')) {
                label = `📷 ${label} (Belakang)`;
            } else if (label.toLowerCase().includes('front')) {
                label = `🤳 ${label} (Depan)`;
            } else {
                label = `📷 ${label}`;
            }
            
            option.textContent = label;
            select.appendChild(option);
        });
    }

    function startScanning(cameraId) {
    const config = {
        fps: 10,
        disableFlip: false
    };

    html5QrCode.start(
        cameraId,
        config,
        onScanSuccess,
        onScanError
    ).then(() => {
        const modeText = scanMode === 'venue' ? 'Masuk Venue' : 'Masuk Lapangan';
        document.querySelector('.scanner-status').innerHTML = 
            `<i class="fas fa-check-circle me-2"></i> Mode: ${modeText}`;
    }).catch(err => {
        showError('Gagal memulai scanner: ' + err);
    });
}


    function switchCamera(newCameraId) {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                currentCameraId = newCameraId;
                startScanning(newCameraId);
                showNotification('info', 'Mengganti Kamera', 'Sedang beralih ke kamera lain...', 2000);
            }).catch(err => {
                showError('Gagal menghentikan kamera: ' + err);
                showNotification('error', 'Error', 'Gagal mengganti kamera');
            });
        } else {
            currentCameraId = newCameraId;
            startScanning(newCameraId);
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isScanning) return;
        isScanning = true;

        console.log('QR Code detected:', decodedText);

        if (navigator.vibrate) {
            navigator.vibrate([200, 100, 200]);
        }

        validateTicket(decodedText);
    }

    function onScanError(errorMessage) {
    }

    function validateTicket(ticketCode) {
        const placeholder = document.getElementById('scan-placeholder');
        if (placeholder) {
            placeholder.remove();
        }

        const scanId = 'scan-' + Date.now();
        
        const logContainer = document.getElementById('scan-log');
        const loadingCard = document.createElement('div');
        loadingCard.id = scanId;
        loadingCard.className = 'card mb-3 border-0 shadow-sm';
        loadingCard.innerHTML = `
            <div class="card-body text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x text-success mb-2"></i>
                <p class="mb-0 fw-bold small">Memvalidasi...</p>
                <small class="text-muted text-break">${ticketCode}</small>
            </div>
        `;
        logContainer.insertBefore(loadingCard, logContainer.firstChild);

        fetch('/cashier/scan/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                ticket_code: ticketCode,
                scan_mode: scanMode
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccessResult(data, scanId);
            } else {
                showErrorResult(data, scanId);
            }
            
            let delay = data.success ? 1500 : 3000; 
            setTimeout(() => { isScanning = false; }, delay);
        })
        .catch(error => {
            console.error('Validation error:', error);
            const card = document.getElementById(scanId);
            if(card) {
                card.innerHTML = `
                    <div class="error-result mb-0">
                         <div class="d-flex align-items-center mb-2">
                            <div class="result-icon text-danger fs-2 me-3">⚠</div>
                            <div>
                                <div class="fw-bold text-danger">ERROR</div>
                                <div class="small text-muted">Gagal koneksi ke server</div>
                            </div>
                        </div>
                    </div>
                `;
                setTimeout(() => {
                    card.classList.add('fade-out');
                    setTimeout(() => card.remove(), 500);
                }, 7000);
            }
            
            setTimeout(() => { isScanning = false; }, 2000);
        });
    }

    function showSuccessResult(data, scanId) {
        showGreenFlash();
        playSuccessBeep();
        
        const card = document.getElementById(scanId);
        if (!card) return;

        let html = `
            <div class="success-result mb-0">
                <div class="d-flex align-items-start">
                    <div class="result-icon text-success me-3">✓</div>
                    <div class="flex-grow-1">
                        <div class="result-title text-success mb-1">TIKET VALID</div>
                        <div class="text-muted small mb-2">${scanMode === 'venue' ? 'Masuk Venue' : 'Masuk Lapangan'}</div>
                        
                        <div class="row g-2">
                             <div class="col-6">
                                <small class="text-muted d-block">Kode</small>
                                <strong class="text-success text-break">${data.ticket_code || '-'}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Nama</small>
                                <strong class="text-dark">${data.customer_name || '-'}</strong>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block">Venue & Lapangan</small>
                                <div class="text-dark fw-semibold">${data.venue_name || '-'}</div>
                                <div class="text-dark small">${data.section_name || '-'}</div>
                                <div class="small text-muted mt-1">${data.play_date || '-'} • ${data.play_time || '-'}</div>
                            </div>
                        </div>

                         <div class="mt-2 pt-2 border-top border-success border-opacity-25 d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">${data.scan_status_text || '-'}</span>
                            <small class="text-success">${data.scan_time || new Date().toLocaleTimeString()}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        card.innerHTML = html;

    }

    function showErrorResult(data, scanId) {
        playErrorSound();
        showRedFlash();
        
        const card = document.getElementById(scanId);
        if (!card) return;
        
        let errorMessage = data.message || 'Tiket tidak valid';
        let errorIcon = '✗';
        let errorColor = 'danger';
        let notifType = 'error';

        if (data.error_type === 'already_scanned') {
            errorIcon = '⚠';
            errorColor = 'warning';
            notifType = 'warning';
        } else if (data.error_type === 'too_early') {
            errorIcon = '🕐';
            errorColor = 'warning';
            notifType = 'warning';
        } else if (data.error_type === 'venue_not_scanned') {
            errorIcon = '🚪';
            errorColor = 'warning';
            notifType = 'warning';
        }

        let html = `
            <div class="error-result mb-0">
                <div class="d-flex align-items-start">
                    <div class="result-icon text-${errorColor} me-3">${errorIcon}</div>
                    <div class="flex-grow-1">
                        <div class="result-title text-${errorColor} mb-1">GAGAL</div>
                        <div class="text-muted small mb-2">${errorMessage}</div>
                        
                        ${data.ticket_code ? `
                            <div class="mb-1">
                                <small class="text-muted">Kode: </small>
                                <strong class="text-dark">${data.ticket_code}</strong>
                            </div>
                        ` : ''}
                        
                        ${data.customer_name ? `
                            <div class="mb-1">
                                <small class="text-muted">Nama: </small>
                                <strong class="text-dark">${data.customer_name}</strong>
                            </div>
                        ` : ''}
                        
                         <div class="mt-2 pt-2 border-top border-${errorColor} border-opacity-25 d-flex justify-content-between">
                            <span class="badge bg-${errorColor}">${data.scan_status_text || 'Error'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        card.innerHTML = html;

        
        setTimeout(() => {
            card.classList.add('fade-out');
            setTimeout(() => {
                card.remove();
                const logContainer = document.getElementById('scan-log');
                if (logContainer && logContainer.children.length === 0) {
                     logContainer.innerHTML = `
                        <div id="scan-placeholder" class="text-center text-muted py-5 fade-in">
                            <i class="fas fa-qrcode fa-4x mb-3 opacity-50"></i>
                            <p class="small">Arahkan QR code ke scanner untuk memulai scan</p>
                        </div>
                     `;
                }
            }, 500);
        }, 7000);
    }

    function showError(message) {
        document.getElementById('result').innerHTML = 
            `<div class="error-result">
                <div class="result-icon text-danger">⚠</div>
                <div class="text-muted">${message}</div>
            </div>`;
        
        document.querySelector('.scanner-status').innerHTML = 
            `<i class="fas fa-exclamation-triangle me-2"></i> ${message}`;
        
        showNotification('error', 'Error', message);
    }

    function playSuccessBeep() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const gainNode = audioContext.createGain();

        gainNode.connect(audioContext.destination);
        gainNode.gain.setValueAtTime(0.9, audioContext.currentTime);

        const beep = (freq, start, dur) => {
            const osc = audioContext.createOscillator();
            osc.type = 'sine';
            osc.frequency.value = freq;
            osc.connect(gainNode);
            osc.start(start);
            osc.stop(start + dur);
        };

        const t = audioContext.currentTime;
        beep(1100, t, 0.12);
        beep(1500, t + 0.15, 0.15);
    }

    window.addEventListener('beforeunload', function() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    });

    setInterval(() => {
        if (html5QrCode) {
            console.log('Scanner state:', {
                isScanning: html5QrCode.isScanning,
                scanMode: scanMode,
                currentCamera: currentCameraId
            });
        }
    }, 5000);

function playErrorSound() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();

        for (let i = 0; i < 2; i++) {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.type = 'square';
            osc.frequency.value = 220;

            const start = ctx.currentTime + i * 0.18;
            gain.gain.setValueAtTime(1, start);
            gain.gain.exponentialRampToValueAtTime(0.01, start + 0.15);

            osc.start(start);
            osc.stop(start + 0.15);
        }
    } catch {}
}
</script>