<!-- resources/views/buyer/fields/show.blade.php -->

<!-- Tambahkan button untuk chat dengan pemilik -->
<div class="field-actions" style="margin-top: 20px;">
    <a href="{{ route('chat.create', $field->owner_id) }}" 
       class="btn-chat-owner"
       style="display: flex; align-items: center; justify-content: center; gap: 10px;
              padding: 14px 24px; background: linear-gradient(135deg, #3498db, #2980b9);
              color: white; text-decoration: none; border-radius: 12px;
              font-weight: 700; transition: all 0.3s ease;">
        <i class="fas fa-comments"></i>
        Chat dengan Pemilik
    </a>
</div>