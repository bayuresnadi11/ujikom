@props(['theme' => 'dark'])

@php
    $isDark = $theme === 'dark';
    $headerBg = $isDark ? 'var(--gradient-dark)' : 'white';
    $iconBg = $isDark ? 'rgba(255, 255, 255, 0.15)' : 'var(--light)';
    $iconColor = $isDark ? 'white' : 'var(--text)';
    $iconBorder = $isDark ? 'none' : '2px solid var(--light-gray)';
@endphp

<style>
    .back-header-{{ $theme }} {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: {{ $headerBg }};
        z-index: 1100;
        box-shadow: {{ $isDark ? 'var(--shadow-md)' : 'var(--shadow-sm)' }};
        {{ $isDark ? 'backdrop-filter: blur(10px);' : 'border-bottom: 1px solid var(--light-gray);' }}
    }

    .back-header-{{ $theme }} .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid {{ $isDark ? 'rgba(255, 255, 255, 0.15)' : 'var(--light-gray)' }};
    }

    .back-header-{{ $theme }} .back-button {
        background: {{ $iconBg }};
        border: {{ $iconBorder }};
        font-size: 18px;
        cursor: pointer;
        color: {{ $iconColor }};
        padding: 6px;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .back-header-{{ $theme }} .back-button:hover {
        background: {{ $isDark ? 'rgba(255, 255, 255, 0.25)' : 'rgba(255, 255, 255, 0.8)' }};
        transform: translateY(-1px);
    }

    .back-header-{{ $theme }} .header-spacer {
        width: 40px;
        height: 40px;
    }

    /* Mobile responsiveness */
    @media (min-width: 600px) {
        .back-header-{{ $theme }} {
            max-width: 480px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 0;
        }
    }
</style>

<header class="back-header back-header-{{ $theme }}">
    <div class="header-top">
        <a href="javascript:history.back()" class="back-button" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="header-spacer"></div>
    </div>
</header>
