<template>
    <!-- Single feed card component. Previously wrapped in a 2-col grid which left an empty column on large screens. -->
    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-lg">
                            <header class="flex items-start gap-4">
                                <img
                                    src="https://images.unsplash.com/photo-1614644147724-2d4785d69962?auto=format&fit=crop&w=160&q=80"
                                    alt="avatar"
                                    class="w-12 h-12 rounded-full object-cover" />
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-base font-semibold text-gray-900">Claire Mac</h3>
                                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Instructor</span>
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500">3 hours ago · Public</p>
                                </div>
                                <button class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                    </svg>
                                </button>
                            </header>

                            <div class="mt-4 space-y-3">
                                <h4 class="text-lg font-medium text-gray-900">New Advanced Course Update</h4>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    We added new modules covering performance optimization and user experience improvements. Share your feedback and ideas to help us enhance the content.
                                </p>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
                                    <img
                                        src="https://images.unsplash.com/photo-1731690415686-e68f78e2b5bd?auto=format&fit=crop&w=800&q=80"
                                        alt="post cover" class="w-full h-48 object-cover">
                                </div>

                                <div class="flex flex-wrap gap-2 pt-1">
                                    <span class="text-xs font-medium rounded-full bg-indigo-50 px-3 py-1 text-indigo-600">#Frontend</span>
                                    <span class="text-xs font-medium rounded-full bg-emerald-50 px-3 py-1 text-emerald-600">#Vue</span>
                                    <span class="text-xs font-medium rounded-full bg-rose-50 px-3 py-1 text-rose-600">#UI</span>
                                </div>
                            </div>

                            <footer class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
                                <div class="flex items-center gap-4 text-xs">
                                    <button class="flex items-center gap-1.5 px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14 9l-1-6H6a2 2 0 00-2 2v9h3l1 6h4l1-6h3l3-5h-7z" />
                                        </svg>
                                        <span>132</span>
                                    </button>
                                    <button class="flex items-center gap-1.5 px-2.5 py-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v6a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        <span>24 Comments</span>
                                    </button>
                                </div>
                                <button class="bg-blue-600 px-3 py-1.5 text-xs font-medium text-white rounded-md hover:bg-blue-500">
                                    Request
                                </button>
                            </footer>
    </article>
</template>