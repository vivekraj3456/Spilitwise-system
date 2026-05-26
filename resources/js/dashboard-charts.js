import {
    Chart,
    ArcElement,
    CategoryScale,
    DoughnutController,
    Filler,
    Legend,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
} from 'chart.js';

Chart.register(
    ArcElement,
    CategoryScale,
    DoughnutController,
    Filler,
    Legend,
    LineController,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
);

function readDashboardChartData() {
    const el = document.getElementById('dashboard-chart-data');
    if (!el) return null;

    try {
        return JSON.parse(el.textContent || 'null');
    } catch {
        return null;
    }
}

function formatMoney(value) {
    const number = typeof value === 'number' ? value : Number(value);
    if (!Number.isFinite(number)) return '₹0.00';
    const parts = number.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).split('.');
    const intPart = parts[0];
    const fractionPart = parts[1] ?? '00';
    if (fractionPart === '00') {
        return `₹${intPart}`;
    }

    return `₹${intPart}.${fractionPart}`;
}

export function initDashboardCharts() {
    const data = readDashboardChartData();
    if (!data) return;

    Chart.defaults.font.family = 'Manrope, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
    Chart.defaults.color = '#1e293b'; // slate-800

    const monthlyCanvas = document.getElementById('monthly-expense-chart');
    if (monthlyCanvas && data.monthly) {
        // eslint-disable-next-line no-new
        new Chart(monthlyCanvas, {
            type: 'line',
            data: {
                labels: data.monthly.labels,
                datasets: [
                    {
                        label: 'Spending',
                        data: data.monthly.values,
                        borderColor: '#4AA68A',
                        backgroundColor: 'rgba(91, 197, 167, 0.18)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#5BC5A7',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => formatMoney(ctx.parsed.y),
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 0, autoSkip: true },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.9)' },
                        ticks: {
                            callback: (value) => formatMoney(value),
                        },
                    },
                },
            },
        });
    }

    const categoryCanvas = document.getElementById('category-doughnut-chart');
    if (categoryCanvas && data.categories) {
        // eslint-disable-next-line no-new
        new Chart(categoryCanvas, {
            type: 'doughnut',
            data: {
                labels: data.categories.labels,
                datasets: [
                    {
                        data: data.categories.values,
                        backgroundColor: [
                            'rgba(91, 197, 167, 0.95)',
                            'rgba(74, 166, 138, 0.95)',
                            'rgba(15, 23, 42, 0.80)',
                            'rgba(100, 116, 139, 0.80)',
                            'rgba(226, 232, 240, 1)',
                            'rgba(255, 101, 47, 0.85)',
                        ],
                        borderColor: '#FFFFFF',
                        borderWidth: 2,
                        hoverOffset: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const val = ctx.parsed;
                                return `${ctx.label}: ${formatMoney(val)}`;
                            },
                        },
                    },
                },
            },
        });
    }
}
