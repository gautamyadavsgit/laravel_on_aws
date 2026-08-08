    <!-- Site Footer -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 transition-colors" id="contact">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-sm shadow-sm">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">Gautam<span class="text-indigo-600 dark:text-indigo-400">REI</span></span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Democratizing institutional real estate investments through secure, transparent, and high-yield fractional ownership deeds.
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Investments</h3>
                    <ul class="space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="{{ url('invest') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Browse All Properties</a></li>
                        <li><a href="{{ url('property_singlepage') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Featured Listing</a></li>
                        <li><a href="{{ url('/#how-it-works') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">How Fractional Works</a></li>
                        <li><a href="{{ url('register') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Investor Accreditation</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Company</h3>
                    <ul class="space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="{{ url('/') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">About Gautam REI</a></li>
                        <li><a href="{{ url('/#features') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Asset Security</a></li>
                        <li><a href="{{ route('admin.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Admin Portal</a></li>
                        <li><a href="{{ url('/#contact') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Terms & Privacy</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-900 dark:text-white mb-4">Contact & Support</h3>
                    <ul class="space-y-2.5 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="mailto:support@gautamrei.com" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2"><i class="bi bi-envelope"></i> support@gautamrei.com</a></li>
                        <li><a href="tel:+18005550199" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-2"><i class="bi bi-telephone"></i> +1 (800) 555-0199</a></li>
                        <li class="flex items-center gap-2 text-slate-400 dark:text-slate-500"><i class="bi bi-geo-alt"></i> United States</li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
                <div>
                    © {{ date('Y') }} <strong class="font-semibold text-slate-700 dark:text-slate-200">Gautam Real Estate</strong>. All rights reserved.
                </div>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-900 dark:hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-900 dark:hover:text-white transition">Terms of Service</a>
                    <a href="#" class="hover:text-slate-900 dark:hover:text-white transition">Securities Disclaimer</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>