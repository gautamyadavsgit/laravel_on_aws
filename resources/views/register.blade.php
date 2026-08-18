@include('frontend.common.header', ['title' => 'Investor Registration | Gautam Real Estate'])

<div class="py-14 sm:py-20">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        <div class="card-tw p-8 sm:p-10 space-y-8 shadow-xl">
            <!-- Progress Steps Indicator -->
            <div class="flex items-center justify-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-8">
                <div class="flex items-center gap-3">
                    <div data-step-bubble="1" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-indigo-600 text-white ring-4 ring-indigo-100 dark:ring-indigo-950 transition">1</div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 hidden sm:inline">{{ __('auth.step_credentials') }}</span>
                </div>
                <div class="w-12 h-0.5 bg-slate-200 dark:bg-slate-800"></div>
                <div class="flex items-center gap-3">
                    <div data-step-bubble="2" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 transition">2</div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 hidden sm:inline">{{ __('auth.step_profile') }}</span>
                </div>
                <div class="w-12 h-0.5 bg-slate-200 dark:bg-slate-800"></div>
                <div class="flex items-center gap-3">
                    <div data-step-bubble="3" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 transition">3</div>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 hidden sm:inline">{{ __('auth.step_objectives') }}</span>
                </div>
            </div>

            <!-- Validation Errors Alert -->
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-rose-600"></i> {{ __('messages.please_fix_errors') }}
                    </div>
                    <ul class="list-disc list-inside text-xs pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="investor-wizard-form" action="{{ route('register.post') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="redirect" value="{{ request('redirect', 'properties') }}">

                <!-- Step 1: Personal & Account Credentials -->
                <div data-wizard-step="1" class="space-y-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('auth.account_profile_info') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('auth.account_profile_desc') }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="first_name">
                                {{ __('auth.first_name') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" class="input-tw" name="first_name" id="first_name" value="{{ old('first_name') }}" required placeholder="John">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="middle_name">{{ __('auth.middle_name') }}</label>
                            <input type="text" class="input-tw" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="A.">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="last_name">
                                {{ __('auth.last_name') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" class="input-tw" name="last_name" id="last_name" value="{{ old('last_name') }}" required placeholder="Doe">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="suffix">{{ __('auth.suffix') }}</label>
                            <input type="text" class="input-tw" name="suffix" id="suffix" value="{{ old('suffix') }}" placeholder="Jr., III">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="email">
                                {{ __('profile.email_address') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" class="input-tw" name="email" id="email" value="{{ old('email') }}" required placeholder="john.doe@example.com">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="phone">{{ __('auth.phone_number') }}</label>
                            <input type="tel" class="input-tw" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+1 (800) 555-0199">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="password">
                                {{ __('auth.create_password') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" class="input-tw" name="password" id="password" minlength="6" required placeholder="Minimum 6 characters">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-1.5" for="referral">{{ __('auth.referral_source') }}</label>
                            <select name="referral" id="referral" class="select-tw">
                                <option value="search" {{ old('referral') == 'search' ? 'selected' : '' }}>{{ __('auth.referral_search') }}</option>
                                <option value="referral" {{ old('referral') == 'referral' ? 'selected' : '' }}>{{ __('auth.referral_referral') }}</option>
                                <option value="social" {{ old('referral') == 'social' ? 'selected' : '' }}>{{ __('auth.referral_social') }}</option>
                                <option value="podcast" {{ old('referral') == 'podcast' ? 'selected' : '' }}>{{ __('auth.referral_podcast') }}</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2 pt-2">
                            <label class="flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                                <input type="checkbox" id="termsAgree" name="termsAgree" required checked class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800">
                                <span>{{ __('auth.agree_terms_full') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Investor Profile -->
                <div data-wizard-step="2" class="space-y-5 hidden">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('auth.investor_profile_title') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('auth.investor_profile_desc') }}</p>
                    </div>

                    <div class="space-y-3">
                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="radio" name="experience" value="beginner" checked>
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.exp_beginner') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.exp_beginner_desc') }}</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="radio" name="experience" value="intermediate">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.exp_intermediate') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.exp_intermediate_desc') }}</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded-full border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="radio" name="experience" value="accredited">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.exp_accredited') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.exp_accredited_desc') }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Step 3: Goals -->
                <div data-wizard-step="3" class="space-y-5 hidden">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('auth.investment_goals_title') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('auth.investment_goals_desc') }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="cash_flow" checked>
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.goal_cash_flow') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.goal_cash_flow_desc') }}</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="appreciation" checked>
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.goal_appreciation') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.goal_appreciation_desc') }}</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="tax_benefits">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.goal_tax_benefits') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.goal_tax_benefits_desc') }}</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-start gap-3 bg-slate-50/60 dark:bg-slate-800/40 cursor-pointer hover:border-indigo-500 transition">
                            <input class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800" type="checkbox" name="goals[]" value="diversification">
                            <div>
                                <strong class="block text-sm font-semibold text-slate-900 dark:text-white">{{ __('auth.goal_diversification') }}</strong>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('auth.goal_diversification_desc') }}</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" class="btn-secondary-tw hidden" id="wizard-prev-btn">
                        <i class="bi bi-arrow-left"></i> {{ __('auth.previous') }}
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" class="btn-primary-tw" id="wizard-next-btn">
                        {{ __('auth.next_step') }} <i class="bi bi-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn-success-tw hidden" id="wizard-submit-btn">
                        <i class="bi bi-check-circle"></i> {{ __('auth.complete_registration') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wizardForm = document.getElementById('investor-wizard-form');
    if (!wizardForm) return;

    let currentStep = 1;
    const totalSteps = 3;

    const prevBtn = document.getElementById('wizard-prev-btn');
    const nextBtn = document.getElementById('wizard-next-btn');
    const submitBtn = document.getElementById('wizard-submit-btn');

    function validateCurrentStep(step) {
        const stepContainer = document.querySelector(`[data-wizard-step="${step}"]`);
        if (!stepContainer) return true;

        const inputs = stepContainer.querySelectorAll('input, select, textarea');
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }
        return true;
    }

    function showStep(step) {
        document.querySelectorAll('[data-wizard-step]').forEach(sec => {
            const s = parseInt(sec.getAttribute('data-wizard-step'));
            if (s === step) {
                sec.classList.remove('hidden');
            } else {
                sec.classList.add('hidden');
            }
        });

        document.querySelectorAll('[data-step-bubble]').forEach(bubble => {
            const bubbleStep = parseInt(bubble.getAttribute('data-step-bubble'));
            bubble.classList.remove('bg-indigo-600', 'text-white', 'bg-emerald-600', 'bg-slate-200', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
            if (bubbleStep === step) {
                bubble.classList.add('bg-indigo-600', 'text-white', 'ring-4', 'ring-indigo-100', 'dark:ring-indigo-950');
                bubble.innerText = bubbleStep;
            } else if (bubbleStep < step) {
                bubble.classList.add('bg-emerald-600', 'text-white');
                bubble.innerHTML = '<i class="bi bi-check-lg"></i>';
            } else {
                bubble.classList.add('bg-slate-200', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400');
                bubble.innerText = bubbleStep;
            }
        });

        if (prevBtn) {
            if (step > 1) {
                prevBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.add('hidden');
            }
        }

        if (nextBtn) {
            if (step < totalSteps) {
                nextBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.add('hidden');
            }
        }

        if (submitBtn) {
            if (step === totalSteps) {
                submitBtn.classList.remove('hidden');
            } else {
                submitBtn.classList.add('hidden');
            }
        }
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (validateCurrentStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }

    if (wizardForm) {
        wizardForm.addEventListener('submit', (e) => {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2"><i class="bi bi-arrow-repeat"></i></span> Registering...';
            }
        });
    }

    showStep(1);
});
</script>

@include('frontend.common.footer')
