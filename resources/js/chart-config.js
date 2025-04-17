$(document).ready(function () {
    console.log('Chart config loaded and ready');
    
    let salesPurchasesBar = document.getElementById('salesPurchasesChart');
    if (!salesPurchasesBar) {
        console.error('salesPurchasesChart element not found!');
    } else {
        console.log('salesPurchasesChart element found!');
        
        $.get('/sales-purchases/chart-data', function (response) {
            console.log('Sales & Purchases data received:', response);
            
            if (!response || !response.sales || !response.sales.original || !response.sales.original.days || !response.sales.original.data) {
                console.error('Invalid sales response format!', response);
                return;
            }
            
            let salesPurchasesChart = new Chart(salesPurchasesBar, {
                type: 'bar',
                data: {
                    labels: response.sales.original.days,
                    datasets: [{
                        label: window.salesLabel || 'Sales',
                        data: response.sales.original.data,
                        backgroundColor: [
                            '#6366F1',
                        ],
                        borderColor: [
                            '#6366F1',
                        ],
                        borderWidth: 1
                    },
                        {
                            label: window.purchasesLabel || 'Purchases',
                            data: response.purchases.original.data,
                            backgroundColor: [
                                '#A5B4FC',
                            ],
                            borderColor: [
                                '#A5B4FC',
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            console.log('Sales & Purchases chart created!');
        }).fail(function(error) {
            console.error('Error fetching sales-purchases data:', error);
        });
    }

    let overviewChart = document.getElementById('currentMonthChart');
    if (!overviewChart) {
        console.error('currentMonthChart element not found!');
    } else {
        console.log('currentMonthChart element found!');
        
        $.get('/current-month/chart-data', function (response) {
            console.log('Current month data received:', response);
            
            if (!response || response.sales === undefined || response.purchases === undefined || response.expenses === undefined) {
                console.error('Invalid month overview response format!', response);
                return;
            }
            
            let currentMonthChart = new Chart(overviewChart, {
                type: 'doughnut',
                data: {
                    labels: [
                        window.salesLabel || 'Sales',
                        window.purchasesLabel || 'Purchases',
                        window.expensesLabel || 'Expenses'
                    ],
                    datasets: [{
                        data: [response.sales, response.purchases, response.expenses],
                        backgroundColor: [
                            '#F59E0B',
                            '#0284C7',
                            '#EF4444',
                        ],
                        hoverBackgroundColor: [
                            '#F59E0B',
                            '#0284C7',
                            '#EF4444',
                        ],
                    }]
                },
            });
            console.log('Current month chart created!');
        }).fail(function(error) {
            console.error('Error fetching current-month data:', error);
        });
    }

    let paymentChart = document.getElementById('paymentChart');
    if (!paymentChart) {
        console.error('paymentChart element not found!');
    } else {
        console.log('paymentChart element found!');
        
        $.get('/payment-flow/chart-data', function (response) {
            console.log('Payment flow data received:', response);
            
            if (!response || !response.months || !response.payment_sent || !response.payment_received) {
                console.error('Invalid payment flow response format!', response);
                return;
            }
            
            let cashflowChart = new Chart(paymentChart, {
                type: 'line',
                data: {
                    labels: response.months,
                    datasets: [
                        {
                            label: window.paymentSentLabel || 'Payment Sent',
                            data: response.payment_sent,
                            fill: false,
                            borderColor: '#EA580C',
                            tension: 0
                        },
                        {
                            label: window.paymentReceivedLabel || 'Payment Received',
                            data: response.payment_received,
                            fill: false,
                            borderColor: '#2563EB',
                            tension: 0
                        },
                    ]
                },
            });
            console.log('Payment flow chart created!');
        }).fail(function(error) {
            console.error('Error fetching payment-flow data:', error);
        });
    }
});
