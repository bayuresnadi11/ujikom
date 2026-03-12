<style>
/* ================= VISUAL HEADER WITH BACKGROUND ================= */
.visual-header {
    position: relative;
    width: 100%;
    height: 240px;
    background-color: #0A5C36;
    background-size: cover;
    background-position: center;
    overflow: hidden;
}

.visual-header::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.1) 100%);
}

.camera-btn {
    position: absolute;
    bottom: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 999;
    font-size: 18px;
    transition: all 0.3s ease;
}

.camera-btn:hover {
    background: rgba(0,0,0,0.8);
    transform: scale(1.05);
}

.visual-profile-section {
    position: relative;
    margin-top: -60px;
    padding: 0 20px 20px;
    text-align: center;
    z-index: 20;
}

.visual-avatar-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 16px;
    border-radius: 50%;
    padding: 4px;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.visual-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    background: #f0f0f0;
}

.visual-name {
    font-size: 24px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 4px;
}

.visual-top-bar {
    position: absolute;
    top: 0;
    width: 100%;
    padding: 35px 20px;
    display: flex;
    justify-content: flex-end;
    align-items: flex-start;
    z-index: 10;
}

.visual-btn {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(0,0,0,0.3);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 18px;
}

.visual-btn:hover {
    background: rgba(0,0,0,0.5);
    transform: scale(1.05);
    color: white;
}

/* Background Modal */
.bg-modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 2000;
    display: none;
    align-items: flex-end;
    justify-content: center;
    backdrop-filter: blur(2px);
}

.bg-modal-overlay.active {
    display: flex;
}

.bg-sheet {
    width: 100%;
    max-width: 480px;
    background: white;
    border-radius: 20px 20px 0 0;
    padding: 24px 20px 40px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.bg-modal-overlay.active .bg-sheet {
    transform: translateY(0);
}

.bg-handle {
    width: 40px;
    height: 4px;
    background: #eee;
    border-radius: 2px;
    margin: 0 auto 20px;
}

.bg-option {
    display: flex;
    align-items: center;
    gap: 16px;
    width: 100%;
    padding: 16px;
    border: none;
    background: #f8f9fa;
    border-radius: 12px;
    color: #333;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
    text-align: left;
}

.bg-option:hover {
    background: #f1f3f5;
}

.bg-option i {
    font-size: 20px;
    width: 24px;
    text-align: center;
    color: var(--primary);
}
</style>
