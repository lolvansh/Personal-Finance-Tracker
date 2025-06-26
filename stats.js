window.addEventListener("DOMContentLoaded", async function (){

  loadlinechart();
  loadDoughnutChart();
  loadlastchart();


})



async function loadlinechart(params) {

  try {
    const response = await fetch('Weeklydata.php');
    const data = await response.json();
    console.log("Data received:", data);

    const weeklylabels = data.map(entry => entry.day);
    const values = data.map(entry => entry.total);

    const lineCtx = document.getElementById("weeklychart").getContext("2d");

    const weeklychart = new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: weeklylabels,
        datasets: [{
          label: 'Expenses',
          data: values,
          borderColor: 'rgba(141, 236, 192, 0.7)',
          backgroundColor: 'rgba(141, 236, 192, 0.2)',
          tension: 0.4,
          fill: true,
          pointRadius: 5,
          pointBackgroundColor: 'rgba(33, 34, 32, 0.14)'
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
  } catch (error) {
    console.error("Error fetching chart data:", error);
  }
}





// doughnut lodu
async function loadDoughnutChart() {
  const response = await fetch('categorydata.php');
  const data = await response.json();

  const labels = data.map(item => item.category);
  const values = data.map(item => item.total);

  const doughnutCtx = document.getElementById("doughnutChart").getContext("2d");

  new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Spending',
          data: values,
          backgroundColor: [
            'hsl(133, 60.00%, 64.70%)', 'hsla(132, 64.50%, 75.70%, 0.85)', 'hsl(133, 26.60%, 33.10%)', 'hsl(133, 27.80%, 42.40%)', 'hsl(132, 31.70%, 61.00%)',
            'hsl(134, 31.70%, 75.30%)', 'hsla(133, 41.20%, 70.00%, 0.88)'
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
        }
      }
    }
  });
}


async function loadlastchart(params) {

  const ctx = document.getElementById('incomeExpenseLineChart').getContext('2d');

new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],  // Replace with dynamic labels later
    datasets: [
      {
        label: 'Income',
        data: [1200, 1500, 1300, 1600, 1400, 1800],  // Replace with real income data
        borderColor: 'rgb(63, 197, 63)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.3,
        fill: true
      },
      {
        label: 'Expense',
        data: [1000, 1100, 900, 1300, 1200, 1500],  // Replace with real expense data
        borderColor: 'rgb(245, 88, 109)',
        backgroundColor: 'rgba(255, 20, 20, 0.10)',
        tension: 0.3,
        fill: true
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      },
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


}