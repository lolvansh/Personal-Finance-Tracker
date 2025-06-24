
  const lineCtx = document.getElementById("weeklyLineChart").getContext("2d");

  const monthlyLineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Expenses',
        data: [300, 450, 250, 600, 400, 700],
        borderColor: 'rgba(141, 236, 192, 0.7)',
        backgroundColor: 'rgba(141, 236, 192, 0.2)',
        tension: 0.4,
        fill: false,
        pointRadius: 5,
        pointBackgroundColor: 'rgba(141, 236, 192, 0.10)'
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top'
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });




  const doughnutCtx = document.getElementById('categoryDoughnutChart').getContext('2d');

  const categoryDoughnutChart = new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: ['Grocery', 'Transport', 'Shopping', 'Medical', 'Entertainment'],
      datasets: [{
        label: 'Spending',
        data: [150, 90, 200, 50, 110],
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });

  console.log(doughnutCtx);