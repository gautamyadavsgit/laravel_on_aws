@include('frontend.common.header', ['title' => 'Gautam Real Estate - Institutional Fractional Ownership'])

<!-- Hero Section -->
<section class="relative py-20 lg:py-28 overflow-hidden" id="home">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/80 text-xs font-semibold text-indigo-700 dark:text-indigo-300 mb-8 shadow-sm">
            <i class="bi bi-shield-check text-indigo-600 dark:text-indigo-400"></i> SEC Registered Fractional Real Estate Deeds
        </div>

        <!-- Title -->
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white max-w-4xl mx-auto leading-tight sm:leading-tight lg:leading-tight">
            Invest in Prime Real Estate with <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-sky-500 bg-clip-text text-transparent">Fractional Ownership</span>
        </h1>

        <!-- Subtitle -->
        <p class="mt-6 text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Earn quarterly rental cash flow dividends and long-term capital appreciation from vetted high-yield properties without the burdens of sole property management.
        </p>

        <!-- Actions -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 max-w-md mx-auto">
            <div class="w-full relative flex items-center">
                <input type="text" placeholder="Enter your email to get started..." class="input-tw pr-36 py-3 rounded-full shadow-md" aria-label="Email address">
                <a href="{{ url('register') }}" class="absolute right-1.5 px-4 py-2 rounded-full bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition flex items-center gap-1.5">
                    Get Started <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ url('invest') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition inline-flex items-center gap-1.5">
                <i class="bi bi-building"></i> Or Browse All Live Properties
            </a>
        </div>

        <!-- Stats Row -->
        <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <div class="card-tw p-6 text-center">
                <div class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">$45M+</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-1">Properties Transacted</div>
            </div>
            <div class="card-tw p-6 text-center">
                <div class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">8.8% - 12.4%</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-1">Avg. Annual Yield</div>
            </div>
            <div class="card-tw p-6 text-center">
                <div class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">100%</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-1">Asset-Backed Deeds</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-20 bg-slate-100/70 dark:bg-slate-900/40 border-y border-slate-200/80 dark:border-slate-800/80" id="how-it-works">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="badge-tw badge-info-tw">Seamless Process</span>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">How Fractional REI Works</h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm">A transparent 3-step pathway from investor onboarding to quarterly dividend payouts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-tw p-8 space-y-4 hover:-translate-y-1 transition duration-200">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-search"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">1. Curated Due Diligence</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Our team of experienced underwriters evaluates hundreds of properties, selecting only the top 1% with optimal cash flow, prime locations, and proven revenue records.
                </p>
            </div>

            <div class="card-tw p-8 space-y-4 hover:-translate-y-1 transition duration-200">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-pie-chart-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">2. Select Your Share Tier</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Purchase legal fractional deed shares starting at just $50. Diversify your portfolio across vacation rentals, residential units, and commercial assets.
                </p>
            </div>

            <div class="card-tw p-8 space-y-4 hover:-translate-y-1 transition duration-200">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">3. Collect Passive Cash Flow</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Professional property managers handle 100% of guest communications, repairs, and tenant leases. Net dividends are wired directly to your wallet every quarter.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Highlights -->
<section class="py-20" id="features">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="badge-tw badge-info-tw">Institutional Advantages</span>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Built For Smart Investors</h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Real estate stability with digital agility, full liquidity options, and robust tax advantages.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-tw p-8 space-y-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Direct Legal Ownership</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Each fractional share is backed by an LLC master deed recorded in public registries. You hold authentic legal equity in tangible real estate.
                </p>
            </div>

            <div class="card-tw p-8 space-y-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Automated Distributions</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    Real-time dashboard reporting tracks rental performance, occupancy percentages, maintenance reserves, and scheduled dividend dates.
                </p>
            </div>

            <div class="card-tw p-8 space-y-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Appreciation Upside</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    When the property appreciates and exits at the end of the holding period, all fractional deed holders receive their pro-rata share of the sale proceeds.
                </p>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ url('invest') }}" class="btn-primary-tw px-6 py-3 rounded-full text-base shadow-md">
                <i class="bi bi-building"></i> Explore Live Offerings
            </a>
        </div>
    </div>
</section>

@include('frontend.common.footer')