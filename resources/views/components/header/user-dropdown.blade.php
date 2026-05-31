<div class="relative flex items-center">
    <!-- User Profile -->
    <div class="flex items-center text-gray-700 dark:text-gray-400">
        <span class="mr-3 overflow-hidden rounded-full h-11 w-11">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? 'User') }}&background=random" alt="User" />
        </span>
        <span class="block font-medium text-theme-sm">{{ Auth::user()->nama ?? 'User' }}</span>
    </div>
</div>
