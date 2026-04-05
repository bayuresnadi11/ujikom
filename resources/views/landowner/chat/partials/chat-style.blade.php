<style>        
    /* ================= VARIABLES ================= */
    :root {
        /* Palette Modern & Premium */
        --primary: #8B1538; /* Burgundy */
        --primary-dark: #6B0F2A;
        --primary-light: #FFF5F7; /* Light Pink for backgrounds */
        --secondary: #A01B42;
        --accent: #C7254E;
        
        --bg-body: #FFF5F7;
        --bg-white: #FFFFFF;
        
        --text-main: #2C1810;
        --text-secondary: #5a3a2a;
        --text-tertiary: #9CA3AF;
        
        --danger: #EF4444;
        --warning: #F59E0B;
        --success: #8B1538;
        
        --shadow-sm: 0 1px 2px 0 rgba(139, 21, 56, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(139, 21, 56, 0.1), 0 2px 4px -1px rgba(139, 21, 56, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(139, 21, 56, 0.1), 0 4px 6px -2px rgba(139, 21, 56, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(139, 21, 56, 0.1), 0 10px 10px -5px rgba(139, 21, 56, 0.04);
        
        --gradient-primary: linear-gradient(135deg, #A01B42 0%, #8B1538 100%);
        --gradient-glass: linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.95) 100%);
        
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-full: 9999px;
    }

    /* ================= GLOBAL RESET ================= */
    * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
    
    body {
        margin: 0;
        padding: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E8 100%);
        color: var(--text-main);
        font-size: 14px;
        line-height: 1.5;
        overscroll-behavior-y: none; /* Prevent bounce effect on body */
    }
    
    /* Scrollbar Styling */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #D1D5DB; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #9CA3AF; }

    /* ================= LAYOUT CONTAINER ================= */
    .mobile-container {
        width: 100%;
        height: 100vh;
        height: 100dvh;
        background: var(--bg-white);
        position: fixed; /* Changed to fixed as requested */
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-width: 480px; 
        box-shadow: 0 0 35px rgba(139, 21, 56, 0.12);
    }
    
    @media (min-width: 481px) {
        .mobile-container {
            /* On desktop, we still keep it fixed centered but maybe add margin logic if needed, 
               but fixed+centered is what usually works for 'mobile view on desktop' */
            border-radius: 24px; /* Optional rounded corners on desktop */
            height: 95vh; /* slightly less than full screen for look */
            top: 2.5vh;
        }
    }

    /* ================= HEADER ================= */
    .mobile-header {
        position: sticky;
        top: 0;
        z-index: 50;
        background: var(--gradient-primary);
        color: white;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .logo {
        font-size: 20px;
        font-weight: 800;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.5px;
    }
    
    .logo span { font-weight: 400; opacity: 0.9; }
    
    .header-icon {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .header-icon:active { transform: scale(0.95); }

    /* ================= SEARCH AREA ================= */
    .search-section {
        padding: 12px 16px;
        background: var(--bg-white);
        border-bottom: 1px solid #F3F4F6;
        flex-shrink: 0;
    }
    
    .search-container {
        display: flex;
        align-items: center;
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: var(--radius-md);
        padding: 10px 14px;
        transition: all 0.2s;
    }
    
    .search-container:focus-within {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    .search-input {
        flex: 1;
        border: none;
        background: transparent;
        margin-left: 10px;
        font-size: 14px;
        outline: none;
        color: var(--text-main);
    }

    /* ================= TABS ================= */
    .chat-tabs {
        display: flex;
        padding: 0 16px;
        background: var(--bg-white);
        border-bottom: 1px solid #E5E7EB;
        gap: 20px;
        flex-shrink: 0;
    }
    
    .tab-btn {
        padding: 14px 4px;
        background: none;
        border: none;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-secondary);
        position: relative;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .tab-btn.active {
        color: var(--primary);
    }
    
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary);
        border-radius: 2px;
    }
    
    .badge {
        position: absolute;
        top: 6px;
        right: -8px;
        background: var(--danger);
        color: white;
        font-size: 10px;
        padding: 1px 5px;
        border-radius: 99px;
        border: 2px solid white;
    }

    /* ================= MAIN CONTENT AREA ================= */
    .main-content {
        flex: 1;
        overflow-y: auto;
        background: var(--bg-white);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    /* ================= QUICK ACTIONS ================= */
    .quick-actions {
        display: flex;
        gap: 8px;
        padding: 16px;
        overflow-x: auto;
        scrollbar-width: none; /* Hide scrollbar Firefox */
        -ms-overflow-style: none;
        flex-shrink: 0;
    }
    .quick-actions::-webkit-scrollbar { display: none; }
    
    .quick-action {
        padding: 8px 16px;
        border-radius: 20px;
        background: #F3F4F6;
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
        border: 1px solid transparent;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .quick-action.active, .quick-action:hover {
        background: #FFF5F7;
        color: var(--primary);
        border-color: rgba(139, 21, 56, 0.2);
    }
    
    .quick-action i { margin-right: 6px; }

    /* ================= CHAT LIST LIST ================= */
    .chats-list {
        display: flex;
        flex-direction: column;
    }
    
    .chat-item {
        display: flex;
        padding: 14px 16px;
        gap: 14px;
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid #F9FAFB;
    }
    
    .chat-item:hover, .chat-item:active {
        background: #F9FAFB;
    }
    
    .chat-item.unread {
        background: #FFF5F7; /* Very light pink */
    }
    
    .chat-avatar {
        position: relative;
        flex-shrink: 0;
    }
    
    .avatar-img {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #E5E7EB;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--text-secondary);
        font-size: 18px;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
    }
    
    .avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-img.small {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }

    .online-status {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: var(--success);
        border-radius: 50%;
        border: 2px solid white;
    }

    /* Message Delete Button */
    .btn-delete-msg {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--danger);
        color: white;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s;
    }
    
    .message-bubble-box:hover .btn-delete-msg {
        opacity: 1;
        transform: scale(1);
    }

    
    .chat-info {
        flex: 1;
        min-width: 0; /* Enable truncation */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .chat-header-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        gap: 14px;
        align-items: center;
    }
    
    .chat-name {
        font-weight: 600;
        font-size: 15px;
        color: var(--text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chat-time {
        font-size: 11px;
        color: var(--text-tertiary);
        flex-shrink: 0;
    }
    
    .chat-preview {
        font-size: 13px;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .chat-preview.unread {
        color: var(--text-main);
        font-weight: 500;
    }
    
    .unread-badge {
        background: var(--primary);
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
        align-self: center;
        font-weight: 600;
        margin-left: 8px;
    }

    /* ================= CHAT BOX (Active Conversation) ================= */
    .chat-box-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #F8FAFC;
    }
    
    .chat-header-detail {
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .messages-container-box {
        flex: 1;
        overflow-y: auto;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background-image: radial-gradient(#E5E7EB 1px, transparent 1px);
        background-size: 20px 20px; /* Subtle dot pattern */
    }
    
    .message-wrapper {
        display: flex;
        flex-direction: column;
        max-width: 80%;
        animation: slideIn 0.3s ease-out forwards;
    }
    
    .message-wrapper.message-sent {
        align-self: flex-end;
        align-items: flex-end;
    }
    
    .message-wrapper.message-received {
        align-self: flex-start;
        align-items: flex-start;
    }
    
    .message-bubble-box {
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        word-wrap: break-word;
    }
    
    .message-sent .message-bubble-box {
        background: var(--primary);
        color: white;
        border-bottom-right-radius: 4px;
    }
    
    .message-received .message-bubble-box {
        background: white;
        color: var(--text-main);
        border-bottom-left-radius: 4px;
        border: 1px solid #F3F4F6;
    }
    
    .message-meta-box {
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.7;
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 0 4px;
    }
    
    /* ================= MESSAGE INPUT ================= */
    .message-input-wrapper {
        padding: 12px 16px;
        background: white;
        border-top: 1px solid #E5E7EB;
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }
    
    .message-input-box {
        flex: 1;
        background: #F3F4F6;
        border: 1px solid transparent;
        padding: 12px 16px;
        border-radius: 20px;
        font-size: 14px;
        resize: none;
        max-height: 100px;
        font-family: inherit;
        transition: all 0.2s;
    }
    
    .message-input-box:focus {
        background: white;
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 2px rgba(139, 21, 56, 0.1);
    }
    
    .btn-send-box {
        width: 44px;
        height: 44px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s, background 0.2s;
        box-shadow: 0 4px 6px rgba(139, 21, 56, 0.2);
    }
    
    .btn-send-box:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
    }
    
    /* ================= FAB BUTTON ================= */
    .fab-button {
        position: fixed; /* Changed from absolute to fixed */
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 10px 15px -3px rgba(139, 21, 56, 0.4);
        border: none;
        cursor: pointer;
        transition: transform 0.2s;
        z-index: 100;
    }
    
    .fab-button:hover {
        transform: scale(1.1) rotate(90deg);
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        color: var(--text-tertiary);
    }
    
    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
        background: #F3F4F6;
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    /* ================= ANIMATIONS ================= */
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Helper classes */
    .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .d-none { display: none !important; }
    
    /* Bottom Nav (If included from layout) override */
    .bottom-nav {
         /* Ensure it sites correctly if sticky */
    }
    
    /* DISCOVER SECTION STYLES */
    .discover-section { padding: 16px; }
    .section-title { font-size: 18px; font-weight: 700; margin-bottom: 12px; }
    .discover-card {
        background: white;
        padding: 16px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid #F3F4F6;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .discover-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: #FFF5F7;
    }
    .discover-icon {
        width: 48px;
        height: 48px;
        background: #FFF5F7;
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .member-count {
        background: #F3F4F6;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
    }

</style>