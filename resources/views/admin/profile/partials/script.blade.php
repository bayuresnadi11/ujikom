<script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otpInput');
            const countdownElement = document.getElementById('countdown');
            const progressBar = document.getElementById('progressBar');
            const resendBtn = document.getElementById('resendBtn');
            const resendTimer = document.getElementById('resendTimer');
            
            let countdown = 60;
            let timerInterval;
            
            // Format waktu
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }
            
            // Update progress bar
            function updateProgressBar() {
                const progress = (countdown / 60) * 100;
                progressBar.style.width = progress + '%';
                
                if (countdown <= 20) {
                    progressBar.classList.remove('bg-success');
                    progressBar.classList.add('bg-warning');
                }
                if (countdown <= 10) {
                    progressBar.classList.remove('bg-warning');
                    progressBar.classList.add('bg-danger');
                }
            }
            
            // Start countdown
            function startCountdown() {
                countdownElement.textContent = formatTime(countdown);
                updateProgressBar();
                
                timerInterval = setInterval(function() {
                    countdown--;
                    countdownElement.textContent = formatTime(countdown);
                    updateProgressBar();
                    
                    if (countdown <= 0) {
                        clearInterval(timerInterval);
                        countdownElement.textContent = '00:00';
                        progressBar.style.width = '0%';
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Kirim Ulang OTP';
                    }
                }, 1000);
            }
            
            // Start timer
            startCountdown();
            
            // OTP input validation
            if (otpInput) {
                otpInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
                
                otpInput.addEventListener('keydown', function(e) {
                    if (!/^\d$/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && 
                        e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' && e.key !== 'Tab') {
                        e.preventDefault();
                    }
                });
            }
            
            // Auto focus next input (if using multiple inputs)
            function focusNext(current, nextId) {
                if (current.value.length === 1) {
                    document.getElementById(nextId).focus();
                }
            }
            
            // Auto submit when all 6 digits are entered
            function autoSubmit() {
                if (otpInput.value.length === 6) {
                    otpInput.form.submit();
                }
            }
            
            // Event listener for auto submit
            otpInput.addEventListener('input', autoSubmit);
        });
    </script>