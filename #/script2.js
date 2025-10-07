document.addEventListener("DOMContentLoaded", function () {
    // Variables for DOM elements
    let dashboard = document.getElementById("dashboard");
    let addForm = document.getElementById("transactionForm");
    let budgetForm = document.getElementById("budgetForm");
    let sidenav = document.getElementById("sidenav");
    let navbar = document.getElementById("navbar");
    let contact = document.getElementById("contact");
    let calendar = document.getElementById("calendar-container");
    let totalBudget = 10000; // Set your total budget for calculations
    let currentSpent = 0;
    let categoryBudgets = {};
    // let daysContainer = document.querySelector(".days");
    // const prev = document.querySelector(".prev");
    // const next = document.querySelector(".next");
    const todayDay = document.querySelector(".today-day");
    const todayDate = document.querySelector(".today-date");
    document.getElementById("myChart").removeAttribute("style");



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

    window.showCalendar = function () {
        hideAllSections(); // Hide other sections
        console.log("Show calendar function called");
        dashboard.style.display = "none";
        document.getElementById("calendar-container").style.display = "flex"; // Show the calendar
    };

    window.showcontactform = function () {
        hideAllSections(); // Hide other sections
        console.log("Show contact form function called");
        dashboard.style.display = "none";
        document.getElementById("contact").style.display = "grid";
    };

    function hideAllSections() {
        dashboard.style.display = "none"; // Hide dashboard
        document.getElementById("transactionForm").style.display = "none"; // Hide add form
        budgetForm.style.display = "none"; // Hide budget form
        contact.style.display = "none";
        document.getElementById("overview-container").style.display = "none"; // Hide calendar
        document.getElementById("calendar-container").style.display = "none"; // Hide calendar
    }

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
    

    function setBudgetAmount(amount) {
        // Set the budget amount
        document.getElementById('total-budget').value = amount;
    
        // Create the flying money animation
        const moneyIcon = document.createElement('span');
        moneyIcon.textContent = '💸'; // Emoji for money
        moneyIcon.classList.add('money-icon');
        
        // Append to the form and animate
        const budgetForm = document.getElementById('budgetForm');
        budgetForm.appendChild(moneyIcon);
        
        // Position the money icon randomly within the form
        moneyIcon.style.top = `${Math.random() * 50 + 10}%`; // Random vertical position
        moneyIcon.style.left = `${Math.random() * 80 + 10}%`; // Random horizontal position
        
        // Remove the money icon after the animation completes
        setTimeout(() => {
            if (moneyIcon && moneyIcon.parentNode === budgetForm) {
                budgetForm.removeChild(moneyIcon);
            }
        }, 1000); // 1 second to match the CSS animation duration
    }    

    // Function to add a new entry to the chart and update the line chart
    function addEntry(event) {
        const dateTime = document.getElementById('date').value;
        const type = document.getElementById('entry-type').value;
        const amount = parseFloat(document.getElementById('entry-amount').value);

        if (!isNaN(amount)) {
            const monthIndex = getMonthFromDate(dateTime);
            if (type === 'income') {
                lineChart.data.datasets[0].data[monthIndex] += amount;
            } else {
                lineChart.data.datasets[1].data[monthIndex] += amount;
            }

            // Update the charts
            lineChart.update();
        }
    }

    function updateProgress(addedAmount) {
        const type = document.getElementById('entry-type').value;
        if (type === 'income'){
            
            currentSpent += addedAmount; // Update the amount spent
            const percentageUsed = (currentSpent / totalBudget) * 100;
            document.getElementById('progress').style.width = `${percentageUsed}%`;
            document.getElementById('progress-percentage').innerText = `${percentageUsed.toFixed(0)}% of Budget Used`;
            
            // Check for celebration emojis
            if (percentageUsed >= 100) {
                triggerCelebration('🎉');
            } else if (percentageUsed >= 80) {
                triggerCelebration('💋');
            } else if (percentageUsed >= 50) {
                triggerCelebration('😎');
            } else if (percentageUsed >= 20) {
                triggerCelebration('☺️');
            }
        }
    }
    
    function triggerCelebration(emoji) {
        const flyingEmoji = document.createElement('span');
        flyingEmoji.textContent = emoji;
        flyingEmoji.classList.add('flying-emoji');
    
        // Position the emoji randomly on the screen
        flyingEmoji.style.left = `${Math.random() * window.innerWidth}px`;
        flyingEmoji.style.top = `${window.innerHeight}px`; // Start from the bottom of the screen
        document.body.appendChild(flyingEmoji);
    
        // Remove the emoji after the animation completes
        flyingEmoji.addEventListener('animationend', () => {
            flyingEmoji.remove();
        });
    }
    
    // Example: Call updateProgress with an amount spent (you can replace this with your actual spending logic)
    updateProgress(1000); // Call this function with the amount you want to add to the budget

    // Function to get the month index from the date
    function getMonthFromDate(dateString) {
        const date = new Date(dateString);
        return date.getMonth();
    }

    // Initialize Flatpickr for date input
    flatpickr("#date", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "F j, Y (h:S K)"
    });

    fetch('fetch_weekly_data.php')
    .then(response => response.json())
    .then(chartData => {
        // Process the data for Chart.js
        const incomeData = {};
        const expenseData = {};

        chartData.forEach(item => {
            const date = item.Date;
            if (item.Entry_Type === 'income') {
                incomeData[date] = item.Total;
            } else if (item.Entry_Type === 'expense') {
                expenseData[date] = item.Total;
            }
        });

        // Create labels for the current week
        const startDate = new Date();
        startDate.setDate(startDate.getDate() - startDate.getDay()); // Start from Sunday
        const labels = [];
        for (let i = 0; i < 7; i++) {
            const current = new Date(startDate);
            current.setDate(startDate.getDate() + i);
            labels.push(current.toISOString().split('T')[0]); // Format YYYY-MM-DD
        }

        // Prepare datasets
        const incomeValues = labels.map(label => incomeData[label] || 0);
        const expenseValues = labels.map(label => expenseData[label] || 0);

        // Render the chart
        const ctx = document.getElementById('linechart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Days of the week
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
                responsive: true,
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
        console.error('Error fetching chart data:', error);
    });

//     const incomeData = {};
// const expenseData = {};
// chartData.forEach(item => {
//     const date = item.Date;
//     if (item.Entry_Type === 'income') {
//         incomeData[date] = item.Total;
//     } else if (item.Entry_Type === 'expense') {
//         expenseData[date] = item.Total;
//     }
// });

// // Create labels for the current week (e.g., Monday to Sunday)
// const startDate = new Date(); 
// startDate.setDate(startDate.getDate() - startDate.getDay()); // Start from Sunday
// const labels = [];
// for (let i = 0; i < 7; i++) {
//     const current = new Date(startDate);
//     current.setDate(startDate.getDate() + i);
//     labels.push(current.toISOString().split('T')[0]); // Format YYYY-MM-DD
// }

// // Prepare datasets
// const incomeValues = labels.map(label => incomeData[label] || 0);
// const expenseValues = labels.map(label => expenseData[label] || 0);

// // Create the line chart
// const ctx = document.getElementById('linechart').getContext('2d');
// new Chart(ctx, {
//     type: 'line',
//     data: {
//         labels: labels, // Days of the week
//         datasets: [
//             {
//                 label: 'Income',
//                 data: incomeValues,
//                 borderColor: 'green',
//                 backgroundColor: 'rgba(0, 255, 0, 0.1)',
//                 fill: true,
//             },
//             {
//                 label: 'Expense',
//                 data: expenseValues,
//                 borderColor: 'red',
//                 backgroundColor: 'rgba(255, 0, 0, 0.1)',
//                 fill: true,
//             },
//         ],
//     },
//     options: {
//         responsive: true,
//         plugins: {
//             title: {
//                 display: true,
//                 text: 'Weekly Income and Expense',
//             },
//         },
//         scales: {
//             x: {
//                 title: {
//                     display: true,
//                     text: 'Date',
//                 },
//             },
//             y: {
//                 title: {
//                     display: true,
//                     text: 'Amount',
//                 },
//                 beginAtZero: true,
//             },
//         },
//     },
// });

    // Initialize the line chart with fake data for both income and expenses
//     const ctx = document.getElementById('linechart').getContext('2d');
//     const lineChart = new Chart(ctx, {
//         type: 'line',
//         data: {
//             labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
//             datasets: [
//                 {
//                     label: 'Income',
//                     data: [0, 0, 0, 0, 0, 0, 0], // Fake data for income
//                     backgroundColor: 'rgba(46, 204, 113, 0.2)', // Light green fill
//                     borderColor: '#2ecc71', // Green line
//                     pointBackgroundColor: '#2ecc71',
//                     fill: true, // Fill under the line
//                     tension: 0.4 // Smooth curve
//                 },
//                 {
//                     label: 'Expenses',
//                     data: [0, 0, 0, 0, 0, 0, 0], // Fake data for expenses
//                     backgroundColor: 'rgba(231, 76, 60, 0.2)', // Light red fill
//                     borderColor: '#e74c3c', // Red line
//                     pointBackgroundColor: '#e74c3c',
//                     fill: true, // Fill under the line
//                     tension: 0.4 // Smooth curve
//                 }
//             ]
//         },
//         options: {
//             responsive: true,
//             maintainAspectRatio: false,
//             plugins: {
//                 legend: {
//                     display: true,
//                     position: 'top'
//                 },
//                 tooltip: {
//                     callbacks: {
//                         label: function (tooltipItem) {
//                             return `${tooltipItem.dataset.label}: Rs. ${tooltipItem.raw}`;
//                         }
//                     }
//                 }
//             },
//             scales: {
//                 x: {
//                     grid: {
//                         display: false
//                     }
//                 },
//                 y: {
//                     beginAtZero: true,
//                     grid: {
//                         color: '#ddd' // Lighter grid line
//                     },
//                     ticks: {
//                         callback: function(value) {
//                             return 'Rs. ' + value;
//                         }
//                     }
//                 }
//             }
//         }
//     });
// });

fetch('get_chart_data.php')
            .then(response => response.json())
            .then(data => {
                // Process data for Chart.js
                const categories = data.map(item => item.Category);
                const totals = data.map(item => item.Total);

                // Create the chart
                const ctx = document.getElementById('lineChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line', // Change to 'pie', 'line', etc., as needed
                    data: {
                        labels: categories,
                        datasets: [{
                            label: 'Total Amount by Category',
                            data: totals,
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
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Transaction Summary'
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
            .catch(error => console.error('Error fetching data:', error));

            fetch('get_chart_data.php')
            .then(response => response.json())
            .then(data => {
                // Process data for Chart.js
                const categories = data.map(item => item.Category);
                const totals = data.map(item => item.Total);

                // Create the chart
                const ctx = document.getElementById('myChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie', // Change to 'pie', 'line', etc., as needed
                    data: {
                        labels: categories,
                        datasets: [{
                            label: 'Total Amount by Category',
                            data: totals,
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
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Transaction Summary'
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
            .catch(error => console.error('Error fetching data:', error));

let year = new Date().getFullYear();
let month = new Date().getMonth();
let activeDay;
const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

function InitializeCalendar(){
    const date = document.querySelector(".date");
    const today = new Date();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    const lastDate = lastDay.getDate();
    const day = firstDay.getDay();
    const prevDays = prevLastDay.getDate();
    const nextDays = 7 - lastDay.getDay() - 1;

    let daysContainer = document.querySelector(".days");
    
    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    console.log("Current Month: ", months[month]);
    console.log("Current Year: ", year);
    date.innerHTML = months[month] + " " + year;
    let days = "";
    
    for (let x = day; x > 0; x--) {
        days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
    }

    for (let i = 1; i <= lastDate; i++){
        if (
          i === new Date().getDate() &&
          year === new Date().getFullYear() &&
          month === new Date().getMonth()
        ) {
          activeDay = i;
          getActiveDay(i);
          days += `<div class="day today active">${i}</div>`;
        } else {
            days += `<div class="day ">${i}</div>`;
          }
        }
        for (let j = 1; j <= nextDays; j++) {
            days += `<div class="day next-date">${j}</div>`;
          }
    daysContainer.innerHTML=days;
    
}
function prevMonth(){
    month --;
    if (month < 0) {
        month = 11;
        year--;
    }
    InitializeCalendar();
    addListner();
}
function nextMonth(){
    month++;
    if (month > 11) {
        month = 0;
        year++;
    }
    InitializeCalendar();
    addListner();
}
    const prev = document.querySelector(".prev");
    const next = document.querySelector(".next");

    prev.addEventListener("click", prevMonth);
    next.addEventListener("click", nextMonth);
InitializeCalendar();

function getActiveDay(date) {
    const day = new Date(year, month, date);
    const dayName = day.toString().split(" ")[0];
    const todayDay = document.querySelector(".today-day");
    const todayDate = document.querySelector(".today-date");
    todayDay.innerHTML = dayName;
    todayDate.innerHTML = date + " " + months[month] + " " + year;
    console.log("day: ", todayDay.innerHTML);
    console.log("date: ", todayDate.innerHTML);

  }

  function addListner() {
    const days = document.querySelectorAll(".day");
    days.forEach((day) => {
      day.addEventListener("click", (e) => {
        getActiveDay(e.target.innerHTML);
        
        activeDay = Number(e.target.innerHTML);
        //remove active
        days.forEach((day) => {
          day.classList.remove("active");
        });
        //if clicked prev-date or next-date switch to that month
        if (e.target.classList.contains("prev-date")) {
          prevMonth();
          //add active to clicked day afte month is change
          setTimeout(() => {
            //add active where no prev-date or next-date
            const days = document.querySelectorAll(".day");
            days.forEach((day) => {
              if (
                !day.classList.contains("prev-date") &&
                day.innerHTML === e.target.innerHTML
              ) {
                day.classList.add("active");
              }
            });
          }, 100);
        } else if (e.target.classList.contains("next-date")) {
          nextMonth();
          //add active to clicked day afte month is changed
          setTimeout(() => {
            const days = document.querySelectorAll(".day");
            days.forEach((day) => {
              if (
                !day.classList.contains("next-date") &&
                day.innerHTML === e.target.innerHTML
              ) {
                day.classList.add("active");
              }
            });
          }, 100);
        } else {
          e.target.classList.add("active");
        }
      });
    });
  }
  
  
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
    document.getElementById("footer").style.display="flex";}