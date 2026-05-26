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

let monthlyChart = null;
let categoryChart = null;

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

function getChartTheme() {
    const isDark = document.documentElement.classList.contains('dark');

    return {
        isDark,
        textColor: isDark ? '#e2e8f0' : '#1e293b',
        gridColor: isDark ? 'rgba(51, 65, 85, 0.7)' : 'rgba(226, 232, 240, 0.9)',
        cardBorder: isDark ? '#0f172a' : '#ffffff',
        lineFill: isDark ? 'rgba(91, 197, 167, 0.24)' : 'rgba(91, 197, 167, 0.18)',
    };
}

export function initDashboardCharts() {
    const data = readDashboardChartData();
    if (!data) return;

    const theme = getChartTheme();

    Chart.defaults.font.family = 'Manrope, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
    Chart.defaults.color = theme.textColor;

    const monthlyCanvas = document.getElementById('monthly-expense-chart');
    if (monthlyCanvas && data.monthly) {
        if (monthlyChart) monthlyChart.destroy();

        monthlyChart = new Chart(monthlyCanvas, {
            type: 'line',
            data: {
                labels: data.monthly.labels,
                datasets: [
                    {
                        label: 'Spending',
                        data: data.monthly.values,
                        borderColor: '#4AA68A',
                        backgroundColor: theme.lineFill,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#5BC5A7',
                        pointBorderColor: theme.cardBorder,
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
                        backgroundColor: theme.isDark ? 'rgba(15, 23, 42, 0.95)' : '#ffffff',
                        titleColor: theme.textColor,
                        bodyColor: theme.textColor,
                        borderColor: theme.isDark ? 'rgba(51, 65, 85, 0.8)' : 'rgba(226, 232, 240, 0.9)',
                        borderWidth: 1,
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
                        grid: { color: theme.gridColor },
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
        if (categoryChart) categoryChart.destroy();

        const categoryColors = theme.isDark
            ? [
                'rgba(91, 197, 167, 0.95)',
                'rgba(52, 211, 153, 0.9)',
                'rgba(148, 163, 184, 0.75)',
                'rgba(71, 85, 105, 0.85)',
                'rgba(30, 41, 59, 0.95)',
                'rgba(248, 113, 113, 0.85)',
            ]
            : [
                'rgba(91, 197, 167, 0.95)',
                'rgba(74, 166, 138, 0.95)',
                'rgba(15, 23, 42, 0.80)',
                'rgba(100, 116, 139, 0.80)',
                'rgba(226, 232, 240, 1)',
                'rgba(255, 101, 47, 0.85)',
            ];

        categoryChart = new Chart(categoryCanvas, {
            type: 'doughnut',
            data: {
                labels: data.categories.labels,
                datasets: [
                    {
                        data: data.categories.values,
                        backgroundColor: categoryColors,
                        borderColor: theme.cardBorder,
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
                            color: theme.textColor,
                        },
                    },
                    tooltip: {
                        backgroundColor: theme.isDark ? 'rgba(15, 23, 42, 0.95)' : '#ffffff',
                        titleColor: theme.textColor,
                        bodyColor: theme.textColor,
                        borderColor: theme.isDark ? 'rgba(51, 65, 85, 0.8)' : 'rgba(226, 232, 240, 0.9)',
                        borderWidth: 1,
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
