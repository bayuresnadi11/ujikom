{{-- modals/stats.blade.php --}}
<!-- Modal 5: Stats Modal -->
<div id="statsModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-chart-bar"></i> Statistik Jadwal
            </h2>
            <button class="modal-close" onclick="closeStatsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="statsContent">
                <!-- Stats akan di-load disini -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal btn-modal-primary" onclick="closeStatsModal()">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>
</div>