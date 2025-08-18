<template>
    <div class="relative min-h-screen lg:flex">
        <Sidebar />
                <main id="content" class="flex-1 pb-12 space-y-6 overflow-y-auto bg-gray-100 lg:h-screen md:space-y-8">
            <Navigation ىى  />
            <section class="flex flex-col w-full px-6 md:justify-between md:items-center md:flex-row">
                <div>
                    <h2 class="text-3xl font-medium text-gray-800">Dashboard</h2>
                    <p class="mt-2 text-sm text-gray-500">Mobile UX/UI Design Course</p>
                </div>

                <div class="flex flex-col mt-6 md:flex-row md:-mx-1 md:mt-0">
                    <button class="px-6 py-3 focus:outline-none text-gray-500 transition-colors duration-300 rounded-lg md:mx-1 hover:bg-gray-400 hover:text-white">
                        <div class="flex items-center justify-center -mx-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
    
                            <span class="mx-1 text-sm capitalize">Manage dashboard</span>
                        </div>
                    </button>

                    <button class="px-6 py-3 focus:outline-none mt-4 text-white bg-blue-600 rounded-lg md:mt-0 md:mx-1 hover:bg-blue-500">
                        <div class="flex items-center justify-center -mx-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
    
                            <span class="mx-1 text-sm capitalize">Create new course</span>
                        </div>
                    </button>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-8 px-6 xl:grid-cols-3 2xl:grid-cols-4 md:grid-cols-2 ">
                <div class="flex items-center px-6 py-8 bg-white rounded-lg shadow-md shadow-gray-200 ">
                    <div class="flex items-center -mx-2">
                        <svg class="mx-2" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="35" fill="#713BDB" fill-opacity="0.05" />
                            <path d="M26 44C26 40.625 30.5 40.625 32.75 38.375C33.875 37.25 30.5 37.25 30.5 31.625C30.5 27.8754 31.9996 26 35 26C38.0004 26 39.5 27.8754 39.5 31.625C39.5 37.25 36.125 37.25 37.25 38.375C39.5 40.625 44 40.625 44 44" stroke="#6F52ED" stroke-width="2" stroke-linecap="square" />
                        </svg>
    
                        <div class="mx-2">
                            <h3 class="text-2xl font-medium text-gray-800">62</h3>
                            <p class="mt-1 text-sm text-gray-500">Students</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center px-6 py-8 bg-white rounded-lg shadow-md shadow-gray-200 ">
                    <div class="flex items-center -mx-2">
                        <svg class="mx-2" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="35" fill="#33D69F" fill-opacity="0.07" />
                            <path d="M26 39L31 34" stroke="#21B8C7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M32 34C32.5523 34 33 33.5523 33 33C33 32.4477 32.5523 32 32 32C31.4477 32 31 32.4477 31 33C31 33.5523 31.4477 34 32 34Z"
                                stroke="#21B8C7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M37 39C37.5523 39 38 38.5523 38 38C38 37.4477 37.5523 37 37 37C36.4477 37 36 37.4477 36 38C36 38.5523 36.4477 39 37 39Z"
                                stroke="#21B8C7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M38 37L44 31M33 34L36 37L33 34Z" stroke="#21B8C7" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
    
                        <div class="mx-2">
                            <h3 class="text-2xl font-medium text-gray-800">6.8</h3>
                            <p class="mt-1 text-sm text-gray-500">Average mark</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center px-6 py-8 bg-white rounded-lg shadow-md shadow-gray-200 ">
                    <div class="flex items-center -mx-2">
                        <svg class="mx-2" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="35" fill="#FF4C61" fill-opacity="0.05" />
                            <path d="M26 32L32 38L36 34L43.405 41.405" stroke="#FF4C61" stroke-width="2"
                                stroke-linecap="square" />
                            <path d="M43.405 41.405L44 42" stroke="#FF4C61" stroke-width="2" stroke-linecap="round" />
                            <path d="M44 39V42H41" stroke="#FF4C61" stroke-width="2" stroke-linecap="square" />
                        </svg>
    
                        <div class="mx-2">
                            <h3 class="text-2xl font-medium text-gray-800">9 <span class="text-xl text-gray-600">(14%)</span></h3>
                            <p class="mt-1 text-sm text-gray-500">Underperforming students</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center px-6 py-8 bg-white rounded-lg shadow-md shadow-gray-200 ">
                    <div class="flex items-center -mx-2">
                        <svg class="mx-2" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="35" fill="#4CB8FF" fill-opacity="0.07" />
                            <path
                                d="M42 26V44H31C30.2044 44 29.4413 43.6839 28.8787 43.1213C28.3161 42.5587 28 41.7956 28 41V29C28 28.2044 28.3161 27.4413 28.8787 26.8787C29.4413 26.3161 30.2044 26 31 26H42Z"
                                stroke="#4CB8FF" stroke-width="2" stroke-linecap="square" />
                            <path d="M28 41C28 40.2044 28.3161 39.4413 28.8787 38.8787C29.4413 38.3161 30.2044 38 31 38H42"
                                stroke="#4CB8FF" stroke-width="2" stroke-linecap="square" />
                        </svg>
    
                        <div class="mx-2">
                            <h3 class="text-2xl font-medium text-gray-800">92%</h3>
                            <p class="mt-1 text-sm text-gray-500">Finished homeworks</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-center px-8 py-6 bg-white rounded-lg shadow-md shadow-gray-200 md:col-span-2 md:row-span-2 gap-y-4 gap-x-8 ">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <h2 class="font-medium text-gray-700">The number of applied and left students per month</h2>

                        <div class="flex items-center mt-4 -mx-2 sm:mt-0">
                            <span class="flex items-center text-gray-600">
                                <p class="mx-2">Applied</p>
                                
                                <span class="w-2 h-2 mx-2 bg-orange-500 rounded-full"></span>
                            </span>

                            <span class="flex items-center text-gray-600">
                                <p class="mx-2">Left</p>
                                <span class="w-2 h-2 mx-2 bg-indigo-500 rounded-full"></span>
                            </span>
                        </div>
                    </div>

                    <canvas class="max-w-3xl max-h-96" id="secondChart"></canvas>
                </div>

                <div class="row-span-3 bg-white rounded-lg shadow-md shadow-gray-200">
                    <div class="px-6 py-5 border-b border-gray-100 sm:flex sm:items-center sm:justify-between">
                        <h2 class="font-medium text-gray-700">Students by average mark</h2>

                        <button type="button" class="inline-flex focus:outline-none justify-center mt-2 text-sm font-medium leading-5 text-gray-500 bg-white rounded-lg sm:mt-0 hover:text-gray-600">
                            <span>Descending</span>
                            
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80">
                                <span class="mx-3 text-gray-600">Annette Watson</span>
                            </div>
                            <span class="font-semibold text-gray-600">9.3</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80">
                                <span class="mx-3 text-gray-600">Calvin Steward</span>
                            </div>
                            <span class="font-semibold text-gray-600">8.3</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80">
                                <span class="mx-3 text-gray-600">Ralph Richards</span>
                            </div>
                            <span class="font-semibold text-gray-600">8.9</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80">
                                <span class="mx-3 text-gray-600">Bernard Murphy</span>
                            </div>
                            <span class="font-semibold text-gray-600">8.2</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=761&q=80">
                                <span class="mx-3 text-gray-600">Arlene Robertson</span>
                            </div>
                            <span class="font-semibold text-gray-600">7.8</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=764&q=80">
                                <span class="mx-3 text-gray-600">Jane Lane</span>
                            </div>
                            <span class="font-semibold text-gray-600">9.2</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80">
                                <span class="mx-3 text-gray-600">Pat Mckinney</span>
                            </div>
                            <span class="font-semibold text-gray-600">6.9</span>
                        </div>

                        <div class="flex items-center justify-between ">
                            <div class="flex items-center">
                                <img class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80">
                                <span class="mx-3 text-gray-600">Norman Walters</span>
                            </div>
                            <span class="font-semibold text-gray-600">9.9</span>
                        </div>
                    </div>
                </div>

                <div class=" bg-white rounded-lg shadow-md shadow-gray-200 lg:row-span-2">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h2 class="font-medium text-gray-700">Students by type of studying</h2>
                    </div>

                    <div class="flex items-center p-8">
                        <canvas class="max-w-sm" id="myChart"> </canvas>
                    </div>
                </div>

                <div class="flex items-center px-6 py-8 bg-white rounded-lg shadow-md shadow-gray-200 ">
                    <div class="flex items-center -mx-2">
                        <svg class="mx-2" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="35" fill="#FFB800" fill-opacity="0.07" />
                            <path d="M30 44H40M29 25H41V34C41 37.3137 38.3137 40 35 40C31.6863 40 29 37.3137 29 34V25Z"
                                stroke="#FFB800" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M35 40V44" stroke="#FFB800" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M29 28V34H28C26.3431 34 25 32.6569 25 31C25 29.3431 26.3431 28 28 28H29ZM41 34V28H42C43.6569 28 45 29.3431 45 31C45 32.6569 43.6569 34 42 34H41Z"
                                stroke="#FFB800" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
    
                        <div class="mx-2">
                            <h3 class="text-2xl font-medium text-gray-800">25</h3>
                            <p class="mt-1 text-sm text-gray-500">Lections left</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center px-6 py-8 bg-white rounded-lg shadow-md shadow-gray-200 ">
                    <div class="flex items-center -mx-2">
                        <svg class="mx-2" width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="35" fill="#4CB8FF" fill-opacity="0.07" />
                            <path
                                d="M35 45C40.5228 45 45 40.5228 45 35C45 29.4772 40.5228 25 35 25C29.4772 25 25 29.4772 25 35C25 40.5228 29.4772 45 35 45Z"
                                stroke="#4CB8FF" stroke-width="2" stroke-linecap="square" />
                            <path d="M35 28V35L39 39" stroke="#4CB8FF" stroke-width="2" stroke-linecap="square" />
                        </svg>
    
                        <div class="mx-2">
                            <h3 class="text-2xl font-medium text-gray-800">139</h3>
                            <p class="mt-1 text-sm text-gray-500">Hours spent on lections</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
import Sidebar from './components/Sidebar.vue';
import Navigation from './components/Navigation.vue';

export default {
    components: {
        Sidebar,
        Navigation
    },

    mounted() {
        const charData = {
            type: 'doughnut',

            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple'],
                datasets: [{
                    label: '# of Votes',
                    data: [20, 20, 20, 10, 10],

                    backgroundColor: [
                        'rgba(153, 102, 255)',
                        'rgba(255, 206, 86)',
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)',
                        'rgba(75, 192, 192)',
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        fullSize: false,
                        align: 'start',
                        labels: {
                            padding: 10,
                            usePointStyle: true,
                            pointSytle: 'circle'
                        }
                    }
                },
            }
        };

        const data = [
            { x: 'Jan', applied: 19, left: 16 },
            { x: 'Feb', applied: 15, left: 8 },
            { x: 'Mar', applied: 24, left: 14 },
            { x: 'Apr', applied: 8, left: 7 },
            { x: 'May', applied: 11, left: 7 },
            { x: 'Jun', applied: 10, left: 6 },
            { x: 'Jul', applied: 17, left: 15 },
            { x: 'Aug', applied: 19, left: 18 },
            { x: 'Sep', applied: 18, left: 12 },
            { x: 'Oct', applied: 19, left: 20 },
            { x: 'Nov', applied: 18, left: 8 },
            { x: 'Dec', applied: 17, left: 12 },
        ];
        
        const secondcharData = {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Applied students',
                    borderRadius: 5,
                    data: data,
                    backgroundColor: '#FF7A00',
                    maxBarThickness: 5,
                    parsing: {
                        yAxisKey: 'applied'
                    },
                },
                {
                    label: 'Left students',
                    borderRadius: 5,
                    maxBarThickness: 5,
                    data: data,
                    backgroundColor: '#6F52ED',

                    parsing: {
                        yAxisKey: 'left'
                    },
                },

                ]
            },
            options: {
                responsive: true,


                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    y: {
                        beginAtZero: true,
                        max: 25
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    },
                },

                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const ctx = document.getElementById('myChart');
        const second = document.getElementById('secondChart');
        const myChart = new Chart(ctx, charData);
        const secondChart = new Chart(second, secondcharData);

        myChart;
        secondChart;
    }
}
</script>