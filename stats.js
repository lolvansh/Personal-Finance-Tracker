
window.addEventListener("DOMContentLoaded", function (){
  console.log("hello");
  const lineCtx = document.getElementById("weeklychart").getContext("2d");

  const weeklychart  = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Expenses',
        data: [300, 450, 250, 600, 400, 700],
        borderColor: 'rgba(141, 236, 192, 0.7)',
        backgroundColor: 'rgba(141, 236, 192, 0.2)',
        tension: 0.4,
        fill: true,
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




const doughnutCtx = document.getElementById("doughnutChart").getContext("2d");

const doughnutChart = new Chart(doughnutCtx, {
  type: 'doughnut',
  data: {
    labels: ['Grocery', 'Transport', 'Shopping', 'Medical', 'Entertainment'],
    datasets: [
      {
        label: 'Spending',
        data: [150, 60, 200, 50, 110],
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
        ],
        borderColor: '#fff',
        borderWidth: 2
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom'
      },
      
    }
  }
});


const barCtx = document.getElementById("incomeExpenseBarChart").getContext("2d");

const barChart = new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], // Change as needed
    datasets: [
      {
        label: 'Income',
        data: [500, 800, 600, 1000, 750, 900],
        backgroundColor: 'rgba(75, 192, 192, 0.7)'
      },
      {
        label: 'Expense',
        data: [400, 650, 500, 850, 600, 700],
        backgroundColor: 'rgba(255, 99, 132, 0.7)'
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      title: {
        display: true,
        text: 'Monthly Income vs Expense'
      }
    },
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});



})