<style>
    /* ================= ADMIN LAYOUT COMPATIBILITY ================= */
    :root {
        --primary: #0A5C36;
        --primary-dark: #08482b;
        --secondary: #27AE60;
        --accent: #2ECC71;
        --light: #f7fdf9;
        --light-gray: #e8f5ef;
        --text: #1a3a27;
        --text-light: #5a7a6a;
        --danger: #E74C3C;
        --warning: #F39C12;
        --gradient-primary: linear-gradient(135deg, #0A5C36 0%, #27AE60 100%);
        --shadow-md: 0 4px 20px rgba(10, 92, 54, 0.12);
        --shadow-lg: 0 8px 30px rgba(10, 92, 54, 0.15);
    }

    /* Layout Containers (matched to Settings page) */
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    .admin-header {
        background: white;
        border-radius: 16px;
        padding: 24px 32px;
        margin-bottom: 32px;
        box-shadow: var(--shadow-md);
        border-left: 6px solid var(--primary);
    }

    .admin-title {
        color: var(--primary);
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .admin-subtitle {
        color: var(--text-light);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    /* Animations */
    .fade-down {
        animation: fadeDown 0.6s ease forwards;
        opacity: 0;
    }
    
    .fade-page {
        animation: fadeUp 0.6s ease forwards;
        opacity: 0;
    }

    @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ================= CHAT CONTAINER ================= */
    /* Resembles .settings-panel */
    .mobile-container {
        width: 100%;
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        min-height: 600px;
        position: relative;
    }

    /* ================= SEARCH BAR ================= */
    .search-section {
        padding: 24px 32px;
        background: white;
        border-bottom: 1px solid var(--light-gray);
    }

    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--light);
        border-radius: 12px;
        padding: 12px 16px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        max-width: 100%;
    }

    .search-container:focus-within {
        border-color: var(--accent);
        background: white;
        box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
    }

    .search-icon { color: var(--text-light); font-size: 18px; }
    .search-input {
        flex: 1; border: none; background: transparent;
        font-size: 15px; color: var(--text); outline: none; width: 100%;
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    /* ================= TABS ================= */
    .chat-tabs {
        display: flex;
        padding: 0 32px;
        gap: 24px;
        background: white;
        border-bottom: 1px solid var(--light-gray);
        margin-top: -1px; /* Overlap border */
    }

    .tab-btn {
        padding: 16px 4px;
        background: transparent;
        border: none;
        font-size: 16px;
        font-weight: 600;
        color: var(--text-light);
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .tab-btn .badge {
        background: var(--danger);
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 4px;
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* ================= QUICK ACTIONS ================= */
    .quick-actions {
        display: flex;
        gap: 12px;
        padding: 20px 32px;
        background: white;
        border-bottom: 1px solid var(--light-gray);
    }
    
    .quick-action {
        padding: 10px 20px;
        border-radius: 20px;
        background: var(--light);
        color: var(--primary);
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex; align-items: center; gap: 8px;
    }
    
    .quick-action.active {
        background: var(--primary); color: white;
    }

    /* ================= CHAT LIST ================= */
    .chats-list { padding: 0; }
    .chat-item {
        display: flex; align-items: center; gap: 20px;
        padding: 20px 32px;
        background: white; cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--light-gray);
    }
    .chat-item:hover { background: var(--light-gray); }
    
    .chat-avatar { position: relative; }
    .avatar-img {
        width: 56px; height: 56px; border-radius: 50%;
        background: var(--gradient-primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: white;
    }
    .online-status {
        position: absolute; bottom: 2px; right: 2px;
        width: 14px; height: 14px; background: var(--accent);
        border-radius: 50%; border: 3px solid white;
    }
    
    .chat-info { flex: 1; }
    .chat-header-info { display: flex; justify-content: space-between; margin-bottom: 6px; }
    .chat-name { font-weight: 700; color: var(--text); font-size: 16px; }
    .chat-time { font-size: 13px; color: var(--text-light); }
    .chat-preview { font-size: 14px; color: var(--text-light); }
    .chat-preview.unread { font-weight: 600; color: var(--text); }
    .unread-badge {
        background: var(--primary); color: white;
        font-size: 12px; padding: 4px 8px; border-radius: 12px;
    }

    /* ================= CHAT BOX ================= */
    .chat-box-wrapper {
        height: 600px; /* Fixed height for consistency */
        display: flex; flex-direction: column;
    }

    .chat-header-detail {
        padding: 20px 32px;
        border-bottom: 1px solid var(--light-gray);
        display: flex; justify-content: space-between; align-items: center;
    }
    
    .avatar-img-small { width: 40px; height: 40px; border-radius: 50%; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; overflow: hidden; }
    .avatar-img-small img { width: 100%; height: 100%; object-fit: cover; }

    .messages-container-box {
        flex: 1; overflow-y: auto; padding: 32px; background: #f9fafb;
    }
    
    .message-wrapper { margin-bottom: 20px; display: flex; }
    .message-sent { justify-content: flex-end; }
    .message-received { justify-content: flex-start; }
    
    .message-bubble-box {
        max-width: 70%; padding: 16px 20px; border-radius: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .message-sent .message-bubble-box {
        background: var(--gradient-primary); color: white;
        border-bottom-right-radius: 4px;
    }
    .message-received .message-bubble-box {
        background: white; color: var(--text);
        border-bottom-left-radius: 4px; border: 1px solid var(--light-gray);
    }
    
    .message-input-wrapper {
        padding: 20px 32px; background: white; border-top: 1px solid var(--light-gray);
    }
    .message-input-box {
        width: 100%; padding: 16px 20px; border: 2px solid var(--light-gray);
        border-radius: 12px; resize: none; font-family: inherit;
    }
    .btn-send-box {
        background: var(--gradient-primary); color: white; border: none;
        width: 50px; height: 50px; border-radius: 12px;
    }

    /* Helper styles for admin header */
    .bi-chat-dots-fill { font-size: 2rem; }
    .me-3 { margin-right: 1rem; }
    
    /* FAB */
    .fab-button {
        position: fixed; bottom: 40px; right: 40px;
        width: 60px; height: 60px;
        background: var(--gradient-primary); color: white;
        border-radius: 50%; border: none;
        box-shadow: 0 4px 15px rgba(10, 92, 54, 0.4);
        font-size: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center;
        z-index: 999;
        transition: transform 0.3s ease;
    }
    .fab-button:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(10, 92, 54, 0.5); }
</style>
