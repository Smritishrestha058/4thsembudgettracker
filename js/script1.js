document.addEventListener("DOMContentLoaded", function () {
    // Variables for DOM elements
    let dashboard = document.getElementById("dashboard");
    let addForm = document.getElementById("transactionForm");
    let budgetForm = document.getElementById("budgetForm");
    let sidenav = document.getElementById("sidenav");
    let navbar = document.getElementById("navbar");
    let contact = document.getElementById("contact");
    let transactionhistory = document.getElementById("transaction-history-container");
    let currentSpent = 0;
    let categoryBudgets = {};
    const todayDay = document.querySelector(".today-day");
    const todayDate = document.querySelector(".today-date");
    const currentDate = new Date();
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

    // Format dates to YYYY-MM-DD
    // const formatDate = (date) => date.toISOString().split('T')[0];
    const formatDate = (date) => {
        return date.getFullYear() + '-' + 
               String(date.getMonth() + 1).padStart(2, '0') + '-' + 
               String(date.getDate()).padStart(2, '0');
    };
    
    const fromDate = formatDate(firstDay);
    const toDate = formatDate(lastDay);

    // Set default values in date inputs
    document.getElementById('fromdate').value = fromDate;
    document.getElementById('todate').value = toDate;

    // Fetch and display data for the current month
    fetchData(fromDate, toDate);

    // Predefined budget setting via button click
    window.setBudgetAmount = function (amount) {
        document.getElementById('total-budget').value = amount;
    };

    // Function to slide in the form and shift other content
    function slideInWithIndent(element, otherContent) {
        element.style.display = "block";
        setTimeout(() => {
            element.classList.add('open');
            otherContent.classList.add('shifted');
        }, 10);
    }

    // Function to slide out the form and reset the shift
    function slideOutWithIndent(element, otherContent) {
        element.classList.remove('open');
        otherContent.classList.remove('shifted');
        setTimeout(() => {
            element.style.display = "none";
        }, 500);
    }

    // Open budget form
    window.openbudget = function () {
        slideInWithIndent(budgetForm, dashboard);
    };

    // Close budget form
    window.closebudget = function () {
        slideOutWithIndent(budgetForm, dashboard);
    };

    // Open add transaction form
    window.openadd = function () {
        slideInWithIndent(addForm, dashboard);
    };

    // Close add transaction form
    window.closeadd = function () {
        slideOutWithIndent(addForm, dashboard);
    };

    // Show the dashboard and hide other sections
    window.showDashboard = function () {
        hideAllSections();
        dashboard.style.display = "grid";
    };

    // Show overview and hide other sections
    window.showoverview = function () {
        hideAllSections();
        document.getElementById("overview-container").style.display = "block";
    };

    window.showcontactform = function () {
        hideAllSections(); // Hide other sections
        console.log("Show contact form function called");
        dashboard.style.display = "none";
        document.getElementById("contact").style.display = "grid";
    };

    window.showtransactions = function () {
        hideAllSections(); // Hide other sections
        console.log("Show contact form function called");
        dashboard.style.display = "none";
        document.getElementById("transaction-history-container").style.display = "grid";
    };
    function hideAllSections() {
        dashboard.style.display = "none"; // Hide dashboard
        document.getElementById("transactionForm").style.display = "none"; // Hide add form
        budgetForm.style.display = "none"; // Hide budget form
        contact.style.display = "none";
        document.getElementById("overview-container").style.display = "none"; // Hide calendar
        transactionhistory.style.display = "none";
    }

    document.getElementById('add-entry-btn').addEventListener('click', function(event) {
    addEntry(event);
    });
    function fetchRemainingBudget() {
        fetch('fetch_budget.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('display-remaining-budget').innerText = `${data.remainingBudget}`;
                updateProgressBar(data.totalBudget, data.totalSpent);

            })
            .catch(error => console.error('Error fetching budget:', error));
    }

    // Call the function to fetch data
    fetchRemainingBudget();
    // document.getElementById("budgetform").onsubmit = function() (
    //     fetchRemainingBudget();
    // )

    document.getElementById("transactionForm").onsubmit = function () {
        const dateInput = document.getElementById("date").value;
    
        // Check if date input is empty
        if (!dateInput) {
            alert("Please select a date.");
            return false;
        }
    
        // Parse the selected date and ensure it is valid
        const selectedDate = new Date(dateInput + "T00:00:00"); // Force local time zone
        if (isNaN(selectedDate.getTime())) {
            alert("Invalid date selected.");
            return false;
        }
    
        // Get today's date and reset time to midnight
        const today = new Date();
        today.setHours(0, 0, 0, 0);
    
        // Reset selectedDate's time to midnight
        selectedDate.setHours(0, 0, 0, 0);
    
        // Log the dates for debugging
        console.log('Selected Date:', selectedDate);
        console.log('Today:', today);
    
        // Compare the timestamps
        if (selectedDate.getTime() > today.getTime()) {
            alert("The transaction date cannot be in the future.");
            return false; // Prevent form submission
        }
    
        return true; // Allow form submission
    };
    window.onload = function() {
        const tips = [
            "Track every expense to avoid overspending.",
            "Set realistic savings goals each month.",
            "Allocate funds for emergencies.",
            "Review your budget regularly to find areas to cut costs.",
            "Use budgeting apps to stay on track.",
            "Separate wants from needs when making purchases.",
            "Create a plan to pay off debt faster."
        ];
    
        let currentTipIndex = 0;
    
        // Function to update tips every 2 seconds with animation
        setInterval(function() {
            const tipsList = document.getElementById('tips-list');
            
            // Update the content with the next tip
            tipsList.innerHTML = `<li>${tips[currentTipIndex]}</li>`;
            
            // Reset animation by removing the class and re-adding it
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.opacity = '0'; // Set to invisible
    
            // Wait a short duration before fading it back in
            setTimeout(function() {
                sidebar.style.opacity = '1'; // Show sidebar again
                sidebar.style.animation = 'none'; // Reset animation
                sidebar.offsetHeight; // Trigger reflow to restart animation
                sidebar.style.animation = ''; // Reapply animation
            }, 100); // Short delay before showing again
    
            // Move to the next tip and loop back if necessary
            currentTipIndex = (currentTipIndex + 1) % tips.length;
        }, 2000); // Change every 2 seconds
    };
    
    function updateProgressBar(totalBudget, totalSpent) {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
    
        // Calculate the percentage spent
        const percentageUsed = (totalSpent / totalBudget) * 100;
    
        // Ensure the percentage does not exceed 100%
        const safePercentage = Math.min(percentageUsed, 100);
    
        // Update the progress bar width and text
        progressBar.style.width = `${safePercentage}%`;
        progressBar.textContent = `${safePercentage.toFixed(0)}%`;
    
        // Display a message based on usage
        if (percentageUsed >= 100) {
            progressText.textContent = 'You have exceeded your budget! 😞';
            progressBar.style.backgroundColor = '#f44336'; // Change color to red
        } else if (percentageUsed >= 80) {
            progressText.textContent = 'You are nearing your budget limit. Be careful!';
            progressBar.style.backgroundColor = '#ff9800'; // Change color to orange
        } else {
            progressText.textContent = `You have ${totalBudget - totalSpent} left to spend.`;
            progressBar.style.backgroundColor = '#4caf50'; // Green for safe spending
        }
    }

    
    // Function to get the month index from the date
    function getMonthFromDate(dateString) {
        const date = new Date(dateString);
        return date.getMonth();
    }

// Fetch data from the PHP script
fetch('fetch_weekly_data.php')
    .then(response => response.json()) // Parse JSON response
    .then(chartData => {
        // Initialize objects to store income and expense data by date
        const incomeData = {};
        const expenseData = {};

        // Process the chartData (received from PHP)
        chartData.forEach(item => {
            const date = item.Date;
            if (item.Entry_Type === 'income') {
                incomeData[date] = item.Total;
            } else if (item.Entry_Type === 'expense') {
                expenseData[date] = item.Total;
            }
        });

        // Generate labels for the current week (dates)
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - startDate.getDay()); // Start from Sunday
        const labels = [];
        for (let i = 0; i < 7; i++) {
            const current = new Date(startDate);
            current.setDate(startDate.getDate() + i);
            labels.push(current.toISOString().split('T')[0]); // Format: YYYY-MM-DD
        }

        // Prepare datasets for the chart
        const incomeValues = labels.map(label => incomeData[label] || 0); // Map dates to income values
        const expenseValues = labels.map(label => expenseData[label] || 0); // Map dates to expense values

        // Render the line chart with Chart.js
        const ctx = document.getElementById('linechart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Dates of the current week
                datasets: [
                    {
                        label: 'Income',
                        data: incomeValues,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        fill: true,
                    },
                    {
                        label: 'Expense',
                        data: expenseValues,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Weekly Income and Expense',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Amount',
                        },
                        beginAtZero: true,
                    },
                },
            },
        });
    })
    .catch(error => {
        console.error('Error fetching chart data:', error); // Handle errors
    });


function opensidenav() {
    document.getElementById('sidenav').style.display="flex";
    dashboard.style.display="none";
    navbar.style.display="none";
    document.getElementById("footer").style.display="none";
}

function closesidenav(){
    document.getElementById('sidenav').style.display="none";
    dashboard.style.display="grid";
    navbar.style.display="flex";
    document.getElementById("footer").style.display="flex";
}

document.getElementById('filterButton').addEventListener('click', (event) => {
    event.preventDefault();
    console.log("This has been prevented");
    
    const fromDate = document.getElementById('fromdate').value;
    const toDate = document.getElementById('todate').value;

    console.log(document.getElementById('fromdate')); // Should not be null
    console.log(document.getElementById('todate'));   // Should not be null
    console.log(document.getElementById('filterButton')); // Should not be null

    if (!fromDate || !toDate) {
        alert('Please select both from and to dates.');
        return;
    }

    fetchData(fromDate, toDate);
});

document.getElementById('resetButton').addEventListener('click', (event) => {
    event.preventDefault();
    const currentDate = new Date();
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    
    // Format dates to YYYY-MM-DD
    // const formatDate = (date) => date.toISOString().split('T')[0];
    const formatDate = (date) => {
        return date.getFullYear() + '-' + 
               String(date.getMonth() + 1).padStart(2, '0') + '-' + 
               String(date.getDate()).padStart(2, '0');
    };
    
    const fromDate = formatDate(firstDay);
    const toDate = formatDate(lastDay);

    // Set default values in date inputs
    document.getElementById('fromdate').value = fromDate;
    document.getElementById('todate').value = toDate;

    // Fetch and display data for the current month
    fetchData(fromDate, toDate);
});

fetch('get_linechart_data.php')
    .then(response => response.json()) // Parse JSON response
    .then(chartData => {
        // Initialize objects to store income and expense data by date
        const incomeData = {};
        const expenseData = {};

        // Process the chartData (received from PHP)
        chartData.forEach(item => {
            const date = item.Date;
            if (item.Entry_Type === 'income') {
                incomeData[date] = item.Total;
            } else if (item.Entry_Type === 'expense') {
                expenseData[date] = item.Total;
            }
        });

        // Generate labels for the current week (dates)
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - startDate.getDay()); // Start from Sunday
        const labels = [];
        for (let i = 0; i < 7; i++) {
            const current = new Date(startDate);
            current.setDate(startDate.getDate() + i);
            labels.push(current.toISOString().split('T')[0]); // Format: YYYY-MM-DD
        }

        // Prepare datasets for the chart
        const incomeValues = labels.map(label => incomeData[label] || 0); // Map dates to income values
        const expenseValues = labels.map(label => expenseData[label] || 0); // Map dates to expense values

        // Render the line chart with Chart.js
        const ctx = document.getElementById('lineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Dates of the current week
                datasets: [
                    {
                        label: 'Income',
                        data: incomeValues,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        fill: true,
                    },
                    {
                        label: 'Expense',
                        data: expenseValues,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Weekly Income and Expense',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Amount',
                        },
                        beginAtZero: true,
                    },
                },
            },
        });
    })
    .catch(error => {
        console.error('Error fetching chart data:', error); // Handle errors
    });

    let incomeChartInstance; // To store the income chart instance
    let expenseChartInstance; // To store the expense chart instance
    
    function fetchData(fromDate, toDate) {
        fetch(`get_chart_data.php?from=${fromDate}&to=${toDate}`)
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then(chartData => {
                if (chartData.error) {
                    throw new Error(chartData.error);
                }
    
                // Process data to group by category
                const incomeData = {};
                const expenseData = {};
    
                chartData.forEach(item => {
                    const category = item.Category; // Use category instead of date
                    if (item.Entry_Type === 'income') {
                        incomeData[category] = (incomeData[category] || 0) + item.Total;
                    } else if (item.Entry_Type === 'expense') {
                        expenseData[category] = (expenseData[category] || 0) + item.Total;
                    }
                });
    
                // Generate labels based on unique categories
                const incomeCategories = Object.keys(incomeData);
                const incomeValues = Object.values(incomeData);
    
                const expenseCategories = Object.keys(expenseData);
                const expenseValues = Object.values(expenseData);
    
                // Render separate charts
                renderIncomeChart(incomeCategories, incomeValues);
                renderExpenseChart(expenseCategories, expenseValues);
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
                alert('Failed to load chart data. Please try again.');
            });
    }
    
    function renderIncomeChart(labels, data) {
        const ctx = document.getElementById('incomeChart').getContext('2d');
    
        if (incomeChartInstance) {
            incomeChartInstance.destroy();
        }
    
        incomeChartInstance = new Chart(ctx, {
            type: 'pie', // Change type if needed
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income by Category',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Income by Category',
                    },
                },
            },
        });
    }
    
    function renderExpenseChart(labels, data) {
        const ctx = document.getElementById('expensesChart').getContext('2d');
    
        if (expenseChartInstance) {
            expenseChartInstance.destroy();
        }
    
        expenseChartInstance = new Chart(ctx, {
            type: 'pie', // Change type if needed
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Expenses by Category',
                        data: data,
                        backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Expenses by Category',
                    },
                },
            },
        });
    }
    
    function generateColors(count) {
        // Generate an array of random colors
        const colors = [];
        for (let i = 0; i < count; i++) {
            const color = `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(
                Math.random() * 255
            )}, 0.2)`;
            colors.push(color);
        }
        return colors;
    }
    
});