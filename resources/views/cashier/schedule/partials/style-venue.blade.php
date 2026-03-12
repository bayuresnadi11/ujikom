<style>
    :root {
        --primary: #0A5C36;
        --primary-dark: #08482b;
        --primary-light: #2E8B57;
        --secondary: #27AE60;
        --accent: #2ECC71;
        --light: #f7fdf9;
        --light-gray: #e8f5ef;
        --text: #1a3a27;
        --text-light: #5a7a6a;
        --gradient-primary: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
        --gradient-dark: linear-gradient(135deg, #08482b 0%, #0A5C36 100%);
        --shadow-md: 0 4px 20px rgba(10, 92, 54, 0.12);
        --shadow-lg: 0 8px 30px rgba(10, 92, 54, 0.15);
        --shadow-xl: 0 12px 40px rgba(10, 92, 54, 0.18);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
        background: linear-gradient(135deg, #f0f9f4 0%, #e1f5eb 100%);
        color: var(--text);
        min-height: 100vh;
        overflow: hidden;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ================= HEADER ================= */
    .display-header {
        background: var(--gradient-dark);
        color: white;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 32px rgba(10, 92, 54, 0.3);
        z-index: 10;
        border-bottom: 3px solid rgba(255, 255, 255, 0.2);
        position: relative;
    }

    .display-brand {
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .display-brand i {
        background: rgba(255, 255, 255, 0.2);
        padding: 14px;
        border-radius: 14px;
        font-size: 28px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .display-brand .subtitle {
        font-size: 1rem;
        font-weight: 400;
        opacity: 0.9;
        margin-top: 4px;
        display: block;
    }

    .display-clock {
        text-align: right;
    }

    .display-clock .time {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: 1px;
        background: linear-gradient(135deg, #ffffff 0%, #e8f5ef 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .display-clock .date {
        font-size: 1.1rem;
        opacity: 0.95;
        font-weight: 500;
        margin-top: 6px;
        color: rgba(255, 255, 255, 0.9);
    }

    /* ================= MAIN LAYOUT ================= */
    .display-content {
        flex: 1;
        padding: 25px;
        display: flex;
        gap: 25px;
        height: calc(100vh - 130px);
        position: relative;
        z-index: 1;
    }

    /* ================= LEFT PANEL FIX ================= */
/* Perbaikan untuk active-schedule-panel agar konsisten dengan current-slot-panel */

.active-schedule-panel {
    flex: 0 0 40%;
    min-width: 0;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.active-schedule-card {
    background: white;
    border-radius: 24px;
    box-shadow: var(--shadow-xl);
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.8);
    position: relative;
}

.active-schedule-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: var(--gradient-primary);
}

.active-schedule-card .card-header {
    background: var(--gradient-dark);
    color: white;
    padding: 25px;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(10, 92, 54, 0.25);
    position: relative;
}

.active-schedule-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: linear-gradient(180deg, #ffffff 0%, #f7fdf9 100%);
    padding: 40px;
    position: relative;
    overflow: hidden;
}

/* ================= SLIDE CONTAINER FIX ================= */
/* Pastikan slide container mengisi penuh card-body */

.slide-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.slide-wrapper {
    display: flex;
    height: 100%;
    width: 100%;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
}

.schedule-slide {
    min-width: 100%;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px;
    flex-shrink: 0;
}

/* ================= CURRENT SCHEDULE STYLES FIX ================= */
/* Samakan dengan current-slot dari blade section */

.current-schedule-time {
    font-size: 5rem;
    font-weight: 900;
    line-height: 1.05;
    letter-spacing: 2px;
    margin-bottom: 20px;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.current-schedule-time.time-belum-scan {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.current-schedule-time.time-masuk-venue {
    background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.current-schedule-label {
    font-size: 1.5rem;
    color: var(--text-light);
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.current-schedule-details {
    text-align: center;
    margin-bottom: 25px;
}

.schedule-section {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 10px;
}

.schedule-customer {
    font-size: 2.2rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.2;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 20px;
    text-align: center;
}

.schedule-status {
    font-size: 1.4rem;
    font-weight: 700;
    padding: 12px 30px;
    border-radius: 50px;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* ================= COUNTDOWN TIMER FIX ================= */
/* Samakan dengan blade section */

.countdown-container {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.countdown-label {
    font-size: 1.5rem;
    color: var(--text-light);
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.countdown-timer {
    font-size: 8rem;
    font-weight: 900;
    line-height: 1;
    letter-spacing: 2px;
    color: #e74c3c;
    text-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    margin-bottom: 20px;
    font-feature-settings: "tnum";
    font-variant-numeric: tabular-nums;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    min-width: 600px;
    display: inline-block;
}

.countdown-schedule {
    font-size: 1.4rem;
    color: var(--text-light);
    font-weight: 500;
    opacity: 0.8;
}

.countdown-section {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--primary);
    margin-top: 15px;
}

/* ================= SLIDE INDICATORS FIX ================= */
/* Posisikan slide indicators dengan benar */

.slide-indicators {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 10px;
    z-index: 10;
}

.slide-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(10, 92, 54, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}

.slide-indicator.active {
    background: var(--primary);
    transform: scale(1.2);
}

    /* Current Schedule Styles */
    .current-schedule-time {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: 2px;
        margin-bottom: 20px;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Time colors for different statuses */
    .current-schedule-time.time-belum-scan {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .current-schedule-time.time-masuk-venue {
        background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Countdown Timer for masuk_lapang */
    .countdown-container {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .countdown-label {
        font-size: 1.5rem;
        color: var(--text-light);
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .countdown-timer {
        font-size: 8rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: 2px;
        color: #e74c3c;
        text-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        margin-bottom: 20px;
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        min-width: 600px;
        display: inline-block;
    }

    .countdown-schedule {
        font-size: 1.4rem;
        color: var(--text-light);
        font-weight: 500;
        opacity: 0.8;
    }

    .countdown-section {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--primary);
        margin-top: 15px;
    }

    .current-schedule-label {
        font-size: 1.5rem;
        color: var(--text-light);
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    .current-schedule-details {
        text-align: center;
        margin-bottom: 25px;
    }

    .schedule-section {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .schedule-customer {
        font-size: 2.2rem;
        font-weight: 600;
        color: var(--text);
        line-height: 1.2;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
    }

    .schedule-status {
        font-size: 1.4rem;
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 50px;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

/* ================= STATUS BADGES FIX ================= */

.status-booked {
    background: var(--gradient-primary);
    color: white;
}

.status-available {
    background: linear-gradient(135deg, #e8f5ef 0%, #d1e7dd 100%);
    color: var(--text);
    border: 2px solid var(--accent);
}


    /* Right: All Schedules Grid */
    .schedules-grid-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding-right: 10px;
        height: 100%;
    }

    .schedules-header {
        background: var(--gradient-dark);
        color: white;
        padding: 16px 25px;
        border-radius: 20px 20px 0 0;
        font-weight: 700;
        font-size: 1.3rem;
        box-shadow: 0 4px 15px rgba(10, 92, 54, 0.2);
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 0;
    }

    .schedules-container {
        padding: 20px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 0 0 20px 20px;
        flex: 1;
        overflow: hidden;
        min-height: 200px;
    }

    .schedules-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 20px;
        width: 100%;
        height: 100%;
    }

    /* Schedule Card */
    .schedule-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-left: 6px solid;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        animation: slideInUp 0.5s ease forwards;
        opacity: 0;
        min-height: 160px;
    }

    .schedule-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
    }

    .schedule-card.current {
        border: 3px solid var(--accent);
        box-shadow: 0 0 30px rgba(46, 204, 113, 0.3);
        transform: scale(1.02);
    }

    .schedule-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .schedule-time {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
    }

    .schedule-badge {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 20px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .badge-booked {
        background: rgba(46, 204, 113, 0.15);
        color: var(--primary);
    }

    .badge-available {
        background: rgba(149, 165, 166, 0.15);
        color: #7f8c8d;
    }

    .badge-current {
        background: rgba(46, 204, 113, 0.3);
        color: var(--primary);
        border: 1px solid var(--accent);
    }

    /* Scan Status Badge Colors */
    .badge-belum-scan {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
    }

    .badge-masuk-venue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .badge-masuk-lapang {
        background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        color: white;
    }

    /* Status Colors for large display */
.status-belum-scan {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white;
}

.status-masuk-venue {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
}

.status-masuk-lapang {
    background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
    color: white;
}

    .schedule-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .schedule-section-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .schedule-customer-name {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text);
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* Color variations for schedule cards */
    .color-0 { border-color: #0A5C36; }
    .color-1 { border-color: #27AE60; }
    .color-2 { border-color: #2ECC71; }
    .color-3 { border-color: #2E8B57; }
    .color-4 { border-color: #229954; }
    .color-5 { border-color: #186A3B; }

    /* Dimmed style for current schedules in grid */
    .schedule-card.hidden-from-grid {
        opacity: 0.4;
        filter: blur(1px);
        transform: scale(0.95);
        pointer-events: none;
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 260px;
        width: 100%;
    }

    .empty-icon {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        box-shadow: 0 10px 40px rgba(107, 114, 128, 0.3);
    }

    .empty-icon i {
        font-size: 3.5rem;
        color: white;
    }

    .empty-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .empty-subtitle {
        color: var(--text-light);
        font-size: 1.2rem;
        font-weight: 500;
        max-width: 400px;
        line-height: 1.5;
    }

    /* Now Playing Banner */
    .now-playing-banner {
        background: var(--gradient-primary);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: 700;
        position: absolute;
        top: 20px;
        right: 20px;
        animation: pulse 2s infinite;
        z-index: 5;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(46, 204, 113, 0); }
        100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
    }

    /* Fade out animation */
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; display: none; }
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animation delays for cards */
    .schedule-card:nth-child(1) { animation-delay: 0.1s; }
    .schedule-card:nth-child(2) { animation-delay: 0.2s; }
    .schedule-card:nth-child(3) { animation-delay: 0.3s; }
    .schedule-card:nth-child(4) { animation-delay: 0.4s; }
    .schedule-card:nth-child(5) { animation-delay: 0.5s; }
    .schedule-card:nth-child(6) { animation-delay: 0.6s; }
    .schedule-card:nth-child(7) { animation-delay: 0.7s; }
    .schedule-card:nth-child(8) { animation-delay: 0.8s; }
    .schedule-card:nth-child(9) { animation-delay: 0.9s; }

/* ================= RESPONSIVE FIX ================= */

@media (max-width: 1200px) {
    .current-schedule-time {
        font-size: 4rem;
    }
    
    .schedule-customer {
        font-size: 1.8rem;
    }
    
    .countdown-timer {
        font-size: 6rem;
        min-width: 450px;
    }
}

    @media (max-width: 992px) {
        .display-content {
            flex-direction: column;
            height: auto;
        }
        
        .active-schedule-panel {
            flex: none;
            height: 350px;
        }
        
        .schedules-grid-panel {
            height: 500px;
        }
    }

    @media (max-width: 768px) {
        .display-header {
            padding: 15px 20px;
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .display-brand {
            flex-direction: column;
            gap: 10px;
        }
        
        .display-clock {
            text-align: center;
        }
        
        .schedules-grid {
            grid-template-columns: 1fr;
            grid-template-rows: repeat(9, 1fr);
            gap: 15px;
        }
        
        .current-schedule-time {
            font-size: 2.5rem;
        }
        
        .schedule-customer {
            font-size: 1.6rem;
        }
    }
.landowner-avatar {
    width: 60px;
    height: 60px;
    border-radius: 14px; /* Match the brand icon border radius */
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
</style>