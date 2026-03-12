<script>
    // Simple script to handle view logic if needed
    function switchToList() {
        window.location.href = "{{ route('chat.index') }}";
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll on load
        const box = document.getElementById('messagesContainer');
        if(box) box.scrollTop = box.scrollHeight;
    });
</script>