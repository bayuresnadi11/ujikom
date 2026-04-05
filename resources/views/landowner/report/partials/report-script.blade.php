<script>
    document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    const input = document.querySelector('.search-input');
    const history = document.getElementById('history');
    const noData = history.querySelector('.no-data');

    input.addEventListener('input', () => {

        if (!history.classList.contains('active')) return;

        const keyword = input.value.toLowerCase();
        let visible = 0;

        history.querySelectorAll('.history-card').forEach(card => {

            const user = card.querySelector('.user-name').textContent.toLowerCase();
            const venue = card.querySelector('.venue-name').textContent.toLowerCase();
            const status = card.querySelector('.status').textContent.toLowerCase();

            if (
                user.includes(keyword) ||
                venue.includes(keyword) ||
                status.includes(keyword)
            ) {
                card.style.display = 'flex';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        noData.style.display = visible === 0 ? 'block' : 'none';
    });

    });

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    function searchSection() {
        const input = document.querySelector('.search-input').value.toLowerCase();
        const cards = document.querySelectorAll('#historyList .history-card');
        const noData = document.getElementById('noDataMessage');
        let visibleCount = 0;

        cards.forEach(card => {
            const userName = card.querySelector('.user-name').textContent.toLowerCase();
            const venueName = card.querySelector('.venue-name').textContent.toLowerCase();
            const status = card.querySelector('.status').textContent.toLowerCase();

            if(userName.includes(input) || venueName.includes(input) || status.includes(input)){
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if(visibleCount === 0){
            noData.style.display = 'block';
        } else {
            noData.style.display = 'none';
        }
    }

    document.querySelector('.search-input').addEventListener('input', searchSection);

    const monthlyData = @json($monthlyData);
    const toggleBtn = document.getElementById('toggleChart');
    const wrapper = document.getElementById('chartWrapper');
    const ctx = document.getElementById('monthlyChart');

    let chartInstance = null;

    toggleBtn.addEventListener('click', () => {
        if (wrapper.style.display === 'none') {
            wrapper.style.display = 'block';
            toggleBtn.innerText = 'Sembunyikan Grafik';

            if (!chartInstance) {
                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(monthlyData),
                        datasets: [{
                            label: 'Jumlah Booking',
                            data: Object.values(monthlyData),
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        } else {
            wrapper.style.display = 'none';
            toggleBtn.innerText = 'Lihat Grafik Bulanan';
        }
    });
</script>