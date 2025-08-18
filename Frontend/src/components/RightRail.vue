<template>
  <!-- Right side persistent contextual area (suggested actions, stats, etc.) -->
  <aside class="hidden xl:block w-80 shrink-0" aria-label="Supplementary">
    <div class="sticky top-0 space-y-6 pt-4 pb-12">
      <!-- About box -->
      <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800">About the Platform</h3>
        <p class="mt-2 text-xs text-gray-500 leading-relaxed">
          A volunteering hub connecting people with meaningful opportunities. Track impact, join teams, and grow skills.
        </p>
        <button class="mt-3 w-full rounded-md bg-blue-600 py-2 text-xs font-medium text-white hover:bg-blue-500">Learn more</button>
      </section>

      <!-- Quick stats -->
      <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Your Snapshot</h3>
        <ul class="space-y-3 text-xs text-gray-600">
          <li class="flex items-center justify-between"><span>Hours volunteered</span><span class="font-semibold text-gray-900">142</span></li>
          <li class="flex items-center justify-between"><span>Active projects</span><span class="font-semibold text-gray-900">3</span></li>
          <li class="flex items-center justify-between"><span>Pending requests</span><span class="font-semibold text-gray-900">5</span></li>
        </ul>
      </section>

      <!-- Suggested tags -->
      <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Suggested Tags</h3>
        <div class="flex flex-wrap gap-2">
          <button class="rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-medium text-indigo-600 hover:bg-indigo-100">#Education</button>
          <button class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-medium text-emerald-600 hover:bg-emerald-100">#Environment</button>
          <button class="rounded-full bg-rose-50 px-3 py-1 text-[11px] font-medium text-rose-600 hover:bg-rose-100">#Health</button>
          <button class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-medium text-amber-600 hover:bg-amber-100">#Youth</button>
        </div>
      </section>

      <!-- Footer links -->
      <nav class="px-2 text-[11px] leading-5 text-gray-400">
        <ul class="flex flex-wrap gap-x-4 gap-y-1">
          <li><a class="hover:text-gray-600" href="#">Terms</a></li>
          <li><a class="hover:text-gray-600" href="#">Privacy</a></li>
          <li><a class="hover:text-gray-600" href="#">Help</a></li>
          <li><a class="hover:text-gray-600" href="#">Feedback</a></li>
        </ul>
        <p class="mt-2">© 2025 Tatawoe</p>
      </nav>
    </div>
  </aside>
</template>
