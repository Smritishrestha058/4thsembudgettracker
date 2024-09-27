let contact_form = document.getElementById("contact");
let home = document.getElementById("home");
let navbar = document.getElementById("navbar");
let dashboard = document.getElementById("dashboard");
let add = document.getElementById("add");
let overview = document.getElementById("overview");
let budget = document.getElementById("budget");
let totalBudget=0;
let startDate='';
let remainingBalance = 0;

function showcontactform() {
    contact_form.style.display = "grid";
    dashboard.style.display = "none";
    add.style.display = "none";
    overview.style.display = "none";
}

function showDashboard() {
    contact_form.style.display = "none";
    dashboard.style.display = "grid";
    add.style.display = "none";
    overview.style.display = "none";
}
function updateProgressBar() {
    let totalBudget = document.getElementById('total-budget').value;
    let amountSpent = document.getElementById('amount-spent').value;

    let percentageSpent = (amountSpent / totalBudget) * 100;

    document.getElementById('progress').style.width = percentageSpent + '%';

    document.getElementById('progress-text').innerText = 
        percentageSpent.toFixed(2) + '% of your budget spent';
}
function openbudget(){
    budget.style.display="flex";
}
function closebudget(){
    budget.style.display="none";
}
function opensidenav() {
    document.getElementById('sidenav').style.display="flex";
    dashboard.style.display="none";
    navbar.style.display="none";
}
function closesidenav(){
    document.getElementById('sidenav').style.display="none";
    dashboard.style.display="grid";
    navbar.style.display="flex";
}
function submitBudget(event) {
    event.preventDefault(); 
    totalBudget = parseFloat(document.getElementById('total-budget').value);
    startDate = document.getElementById('start-date').value;
    // let key; 

    if (totalBudget && startDate) {
        // Display the entered budget
        document.getElementById('display-budget').textContent = totalBudget.toFixed(2);
        alert('Budget and Start Date saved!');
        localStorage.setItem("Budget",totalBudget)
        closebudget();
        loaddata();
    } else {
        alert('Please fill in all fields.');
    }
}
window.onclick = function(event) {
    if (event.target == budget) {
        budget.style.display = "none";
    }
}
function showoverview() {
    overview.style.display = "block";
    dashboard.style.display = "none";
    add.style.display = "none";
}
function openadd() {
    add.style.width = "500px";
    add.style.display = "block";
    add.style.right = "0";
}
function closeadd() {
    add.style.width = "0";
    add.style.display = "block";
    add.style.right = "-500px";
}
flatpickr("#date", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    altInput: true,
    altFormat: "F j, Y (h:S K)"
});


document.addEventListener("DOMContentLoaded", function () {
    // Initialize line chart
    const lineCtx = document.getElementById('linechart').getContext('2d');
    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                    label: 'Income',
                    data: loadStoredData('income') || Array(12).fill(0), // Load stored income data
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'Expenses',
                    data: loadStoredData('expenses') || Array(12).fill(0), // Load stored expenses data
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    fill: false
                }
            ]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Months'
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount'
                    }
                }
            }
        }
    });

    // Initialize pie chart
    const pieCtx = document.getElementById('piechart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [{
                data: [0, 0], // Placeholder data
                backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 99, 132)'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false
        }
    });

    // Load initial pie chart data from line chart's cumulative values
    updatePieChart();

    // Function to add entry
    function addentry(event) {
        event.preventDefault(); // Prevent form submission and reload

        const dateTime = document.getElementById('date').value;
        const monthIndex = getMonthFromDate(dateTime); // Extract the month
        const type = document.getElementById('entry-type').value;
        const amount = parseFloat(document.getElementById('entry-amount').value);

        if (type === 'income') {
            lineChart.data.datasets[0].data[monthIndex] += amount;
        } else {
            lineChart.data.datasets[1].data[monthIndex] += amount;
            remainingBalance += amount;
            document.getElementById('display-remaining-budget').textContent = remainingBalance.toFixed(2);
        }

        // Update the line chart
        lineChart.update();

        // Update the pie chart with cumulative totals
        updatePieChart();

        // Store updated data in local storage
        saveStoredData('income', lineChart.data.datasets[0].data);
        saveStoredData('expenses', lineChart.data.datasets[1].data);

        // Clear form inputs
        document.getElementById('addform').reset();
    }

    // Function to update the pie chart
    function updatePieChart() {
        const totalIncome = lineChart.data.datasets[0].data.reduce((sum, value) => sum + value, 0);
        const totalExpenses = lineChart.data.datasets[1].data.reduce((sum, value) => sum + value, 0);

        pieChart.data.datasets[0].data = [totalIncome, totalExpenses];
        pieChart.update();
    }

    // Function to get month index from date
    function getMonthFromDate(dateString) {
        const date = new Date(dateString);
        return date.getMonth(); // Returns month as 0 (Jan) to 11 (Dec)
    }

    // Function to load data from local storage
    function loadStoredData(type) {
        const storedData = JSON.parse(localStorage.getItem('budgetData')) || { income: [], expenses: [] };
        return storedData[type];
    }

    // Function to save data to local storage
    function saveStoredData(type, data) {
        const storedData = JSON.parse(localStorage.getItem('budgetData')) || { income: [], expenses: [] };
        storedData[type] = data;
        localStorage.setItem('budgetData', JSON.stringify(storedData));
    }

    // // Add event listener to the form submission
    document.getElementById("addform").addEventListener("submit", addentry);
    document.getElementById("addform").addEventListener("submit", function(event) {
        event.preventDefault(); 
        alert('Basic form test passed!');
        document.getElementById("addform").reset();
        addentry();
    });
});
