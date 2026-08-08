@include('frontend.common.header', ['title' => 'Investor Registration | Gautam Real Estate'])

<div class="py-14 sm:py-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <div class="card-tw p-8 sm:p-10 space-y-8 shadow-xl">
            <!-- Progress Steps Indicator -->
            <div class="flex items-center justify-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-8">
                <div class="flex items-center gap-3">
                    <div data-step-bubble="1" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-950 transition">1</div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 hidden sm:inline">Personal</span>
                </div>
                <div class="w-12 h-0.5 bg-slate-200 dark:bg-slate-800"></div>
                <div class="flex items-center gap-3">
                    <div data-step-bubble="2" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 transition">2</div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 hidden sm:inline">Profile</span>
                </div>
                <div class="w-12 h-0.5 bg-slate-200 dark:bg-slate-800"></div>
                <div class="flex items-center gap-3">
                    <div data-step-bubble="3" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 transition">3</div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 hidden sm:inline">Objectives</span>
                </div>
            </div>

            <form id="investor-wizard-form" action="{{ url('/') }}" method="GET" class="space-y-6">
                <!-- Step 1: Personal Info -->
                <div data-wizard-step="1" class="space-y-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Personal & Contact Info</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Please provide your legal name to initialize your fractional deed registration.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="first_name">
                                First Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" class="input-tw" name="first_name" id="first_name" required placeholder="John">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="middle_name">Middle Name</label>
                            <input type="text" class="input-tw" name="middle_name" id="middle_name" placeholder="A.">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="last_name">
                                Last Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" class="input-tw" name="last_name" id="last_name" required placeholder="Doe">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="suffix">Suffix</label>
                            <input type="text" class="input-tw" name="suffix" id="suffix" placeholder="Jr., III">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="referral">How did you hear about Gautam REI?</label>
                            <select name="referral" id="referral" class="select-tw">
                                <option value="search">Search Engine (Google)</option>
                                <option value="referral">Investor Referral</option>
                                <option value="social">Social Media & News</option>
                                <option value="podcast">Real Estate Podcast</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2 pt-2">
                            <label class="flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                                <input type="checkbox" id="termsAgree" required class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800">
                                <span>I have read and agree to the <a href="#" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Terms of Service</a> & <a href="#" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Privacy Policy</a>.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Investor Profile -->
                <div data-wizard-step="2" class="space-y-5 hidden">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Investor Profile</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Select your real estate investing experience level.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="radio" name="experience" value="beginner" checked>
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Beginner Investor</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">New to fractional real estate and looking for guided onboarding.</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="radio" name="experience" value="intermediate">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Intermediate Investor</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Own traditional rental properties or REIT shares.</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="radio" name="experience" value="accredited">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Accredited / Institutional Investor</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Experienced in private placements, syndications, and high-volume deals.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Step 3: Goals -->
                <div data-wizard-step="3" class="space-y-5 hidden">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Investment Goals</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Select your primary objectives for joining Gautam Real Estate.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="cash_flow" checked>
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Quarterly Cash Flow</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Earn steady dividend payouts from rentals.</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="appreciation" checked>
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Capital Appreciation</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Capture long-term equity growth upon asset exit.</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="tax_benefits">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Tax Efficiency</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Depreciation pass-through and 1031 eligibility.</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="diversification">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">Diversification</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">Spread capital across multiple vacation markets.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" class="btn-secondary-tw hidden" id="wizard-prev-btn">
                        <i class="bi bi-arrow-left"></i> Previous
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" class="btn-primary-tw" id="wizard-next-btn">
                        Next Step <i class="bi bi-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn-success-tw hidden" id="wizard-submit-btn">
                        <i class="bi bi-check-circle"></i> Complete Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('frontend.common.footer')