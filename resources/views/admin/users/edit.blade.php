@extends('admin.common.page')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition mb-2">
                <i class="bi bi-arrow-left"></i> Back to Profile
            </a>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-600/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl font-bold">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Edit User: {{ $user->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Update investor credentials, profile details, and preferences.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-secondary-tw">
                <i class="bi bi-eye"></i> View Profile
            </a>
        </div>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert-tw alert-danger-tw">
            <i class="bi bi-exclamation-triangle-fill text-lg text-rose-600 shrink-0 mt-0.5"></i>
            <div class="flex-1 text-xs space-y-1">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Personal Information -->
        <div class="card-tw">
            <div class="card-header-tw">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-person text-indigo-600 dark:text-indigo-400"></i> Personal Information
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Basic identity and personal credentials.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="first_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">First Name <span class="text-rose-500">*</span></label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="input-tw @error('first_name') border-rose-500 @enderror" required />
                    @error('first_name') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="middile_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Middle Name</label>
                    <input type="text" id="middile_name" name="middile_name" value="{{ old('middile_name', $user->middile_name) }}" class="input-tw" />
                </div>

                <div>
                    <label for="last_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="input-tw @error('last_name') border-rose-500 @enderror" />
                    @error('last_name') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="suffix" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Suffix</label>
                    <input type="text" id="suffix" name="suffix" value="{{ old('suffix', $user->suffix) }}" class="input-tw" />
                </div>

                <div>
                    <label for="dob" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}" class="input-tw" />
                </div>

                <div>
                    <label for="social_security_number" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">SSN / Tax ID</label>
                    <input type="text" id="social_security_number" name="social_security_number" value="{{ old('social_security_number', $user->social_security_number) }}" class="input-tw" />
                </div>

                <div class="sm:col-span-2">
                    <label for="company_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Company / Entity Name</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}" class="input-tw" />
                </div>
            </div>
        </div>

        <!-- 2. Account Credentials & Status -->
        <div class="card-tw">
            <div class="card-header-tw">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-shield-lock text-indigo-600 dark:text-indigo-400"></i> Account & Security
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Login credentials and account verification flags.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="input-tw @error('email') border-rose-500 @enderror" required />
                    @error('email') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Password <span class="text-slate-400 font-normal">(Leave blank to keep unchanged)</span></label>
                    <input type="password" id="password" name="password" class="input-tw @error('password') border-rose-500 @enderror" placeholder="••••••••" />
                    @error('password') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="hidden" name="email_verified" value="0" />
                    <input type="checkbox" name="email_verified" value="1" {{ old('email_verified', $user->email_verified_at ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Email Verified</span>
                </label>

                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="hidden" name="phone_verified" value="0" />
                    <input type="checkbox" name="phone_verified" value="1" {{ old('phone_verified', $user->phone_verified ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Phone Verified</span>
                </label>

                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="hidden" name="app_connected" value="0" />
                    <input type="checkbox" name="app_connected" value="1" {{ old('app_connected', $user->app_connected ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300">App Connected</span>
                </label>
            </div>
        </div>

        <!-- 3. Contact & Address -->
        <div class="card-tw">
            <div class="card-header-tw">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-geo-alt text-indigo-600 dark:text-indigo-400"></i> Contact & Address
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Telephone and residential or mailing address.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Primary Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="input-tw" />
                </div>

                <div>
                    <label for="alternate_phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alternate Phone</label>
                    <input type="text" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone', $user->alternate_phone) }}" class="input-tw" />
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Address</label>
                    <textarea id="address" name="address" rows="2" class="textarea-tw">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4. Investor Profile & Preferences -->
        <div class="card-tw">
            <div class="card-header-tw">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-graph-up-arrow text-indigo-600 dark:text-indigo-400"></i> Investor Profile & Suitability
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Accreditation, experience, and investment objectives.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Accreditation Status -->
                <div>
                    <label for="accreditation_status" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Accreditation Status</label>
                    <select id="accreditation_status" name="accreditation_status" class="select-tw text-xs">
                        <option value="">-- Select Status --</option>
                        @foreach($accreditationStatuses as $status)
                            <option value="{{ $status->id }}" {{ old('accreditation_status', $user->accreditation_status) == $status->id ? 'selected' : '' }}>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Net Worth Tier -->
                <div>
                    <label for="users_net_worth" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Net Worth Tier</label>
                    <select id="users_net_worth" name="users_net_worth" class="select-tw text-xs">
                        <option value="">-- Select Net Worth --</option>
                        @foreach($userNetWorths as $netWorth)
                            <option value="{{ $netWorth->id }}" {{ old('users_net_worth', $user->users_net_worth) == $netWorth->id ? 'selected' : '' }}>
                                {{ $netWorth->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Experience Level -->
                <div>
                    <label for="experiance_level" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Experience Level</label>
                    <select id="experiance_level" name="experiance_level" class="select-tw text-xs">
                        <option value="">-- Select Experience --</option>
                        @foreach($experienceLevels as $exp)
                            <option value="{{ $exp->id }}" {{ old('experiance_level', $user->experiance_level) == $exp->id ? 'selected' : '' }}>
                                {{ $exp->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Investing Reason -->
                <div>
                    <label for="investing_reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Reason for Investing</label>
                    <select id="investing_reason" name="investing_reason" class="select-tw text-xs">
                        <option value="">-- Select Reason --</option>
                        @foreach($investingReasons as $reason)
                            <option value="{{ $reason->id }}" {{ old('investing_reason', $user->investing_reason) == $reason->id ? 'selected' : '' }}>
                                {{ $reason->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Investment Source -->
                <div>
                    <label for="investment_sources" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Source of Funds</label>
                    <select id="investment_sources" name="investment_sources" class="select-tw text-xs">
                        <option value="">-- Select Source --</option>
                        @foreach($investmentSources as $source)
                            <option value="{{ $source->id }}" {{ old('investment_sources', $user->investment_sources) == $source->id ? 'selected' : '' }}>
                                {{ $source->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Investment Timeline -->
                <div>
                    <label for="investing_timeline" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Investment Timeline</label>
                    <select id="investing_timeline" name="investing_timeline" class="select-tw text-xs">
                        <option value="">-- Select Timeline --</option>
                        @foreach($investmentTimelines as $tl)
                            <option value="{{ $tl->id }}" {{ old('investing_timeline', $user->investing_timeline) == $tl->id ? 'selected' : '' }}>
                                {{ $tl->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Investment Goals -->
                <div>
                    <label for="investment_goals" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Primary Goal</label>
                    <select id="investment_goals" name="investment_goals" class="select-tw text-xs">
                        <option value="">-- Select Goal --</option>
                        @foreach($investmentGoals as $goal)
                            <option value="{{ $goal->id }}" {{ old('investment_goals', $user->investment_goals) == $goal->id ? 'selected' : '' }}>
                                {{ $goal->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Investment Time Length -->
                <div>
                    <label for="investment_timelength" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Time Horizon</label>
                    <select id="investment_timelength" name="investment_timelength" class="select-tw text-xs">
                        <option value="">-- Select Time Horizon --</option>
                        @foreach($investmentTimelengths as $horizon)
                            <option value="{{ $horizon->id }}" {{ old('investment_timelength', $user->investment_timelength) == $horizon->id ? 'selected' : '' }}>
                                {{ $horizon->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hear About Us -->
                <div>
                    <label for="hear_about_us" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">How They Found Us</label>
                    <select id="hear_about_us" name="hear_about_us" class="select-tw text-xs">
                        <option value="">-- Select Channel --</option>
                        @foreach($hearAboutOptions as $channel)
                            <option value="{{ $channel->id }}" {{ old('hear_about_us', $user->hear_about_us) == $channel->id ? 'selected' : '' }}>
                                {{ $channel->value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-secondary-tw">
                Cancel
            </a>
            <button type="submit" class="btn-primary-tw">
                <i class="bi bi-save-fill"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
