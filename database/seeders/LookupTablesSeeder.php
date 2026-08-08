<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupTablesSeeder extends Seeder
{
    /**
     * Run the database seeds for all application lookup tables.
     */
    public function run(): void
    {
        // 1. US States
        $states = [
            'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut',
            'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa',
            'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan',
            'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
            'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio',
            'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota',
            'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia',
            'Wisconsin', 'Wyoming'
        ];
        foreach ($states as $state) {
            DB::table('states')->updateOrInsert(['state_name' => $state]);
        }

        // 2. Experience Level
        $experienceLevels = [
            'Beginner - First-time real estate investor',
            'Intermediate - Own traditional rental properties or REITs',
            'Advanced - Real estate developer or syndicator',
            'Accredited / Institutional Investor'
        ];
        foreach ($experienceLevels as $level) {
            DB::table('experiance_level')->updateOrInsert(['value' => $level]);
        }

        // 3. Reasons for Investing
        $reasons = [
            'Passive Quarterly Rental Dividends',
            'Long-Term Capital Growth & Appreciation',
            'Tax Depreciation & 1031 Exchange Advantages',
            'Portfolio Diversification into Hard Assets',
            'Hedge Against Inflation'
        ];
        foreach ($reasons as $reason) {
            DB::table('reason_for_investing')->updateOrInsert(['value' => $reason]);
        }

        // 4. Investment Sources
        $sources = [
            'Personal Savings / Cash Reserves',
            'Self-Directed IRA / 401(k)',
            'Family Office / Trust Capital',
            'Corporate Treasury / Entity Funds',
            'Real Estate Equity Rollover'
        ];
        foreach ($sources as $source) {
            DB::table('investment_sources')->updateOrInsert(['value' => $source]);
        }

        // 5. Investment Timeline
        $timelines = [
            'Immediately (Ready to deploy capital within 30 days)',
            'Within 1 - 3 months',
            'Within 3 - 6 months',
            'Researching & evaluating opportunities'
        ];
        foreach ($timelines as $timeline) {
            DB::table('investment_timeline')->updateOrInsert(['value' => $timeline]);
        }

        // 6. Investment Goals
        $goals = [
            'Generate Steady Quarterly Cash Flow',
            'Build Generational Real Estate Wealth',
            'Maximize Tax Depreciation Offsets',
            'Protect Capital from Inflationary Loss'
        ];
        foreach ($goals as $goal) {
            DB::table('investment_goals')->updateOrInsert(['value' => $goal]);
        }

        // 7. Investment Time Horizon / Length
        $timelengths = [
            'Short Term (1 - 3 Years)',
            'Medium Term (3 - 5 Years)',
            'Long Term (5 - 10 Years)',
            'Generational Hold (10+ Years)'
        ];
        foreach ($timelengths as $timelength) {
            DB::table('investment_timelength')->updateOrInsert(['value' => $timelength]);
        }

        // 8. Accreditation Status
        $statuses = [
            'Accredited Investor ($200k+ Individual / $300k+ Joint Income)',
            'Accredited Investor ($1M+ Net Worth excluding primary residence)',
            'Qualified Purchaser ($5M+ in investable assets)',
            'Non-Accredited / Sophisticated Retail Investor'
        ];
        foreach ($statuses as $status) {
            DB::table('accreditation_status')->updateOrInsert(['value' => $status]);
        }

        // 9. Users Net Worth Tiers
        $netWorths = [
            'Under $100,000',
            '$100,000 - $250,000',
            '$250,000 - $500,000',
            '$500,000 - $1,000,000',
            '$1,000,000 - $5,000,000',
            '$5,000,000+'
        ];
        foreach ($netWorths as $netWorth) {
            DB::table('users_net_worth')->updateOrInsert(['value' => $netWorth]);
        }

        // 10. How Did You Hear About Us
        $hearOptions = [
            'Google / Search Engine',
            'Investor Referral / Word of Mouth',
            'Real Estate Podcast / YouTube',
            'Social Media (LinkedIn, Twitter, Instagram)',
            'Financial News / Press Release',
            'Real Estate Conference'
        ];
        foreach ($hearOptions as $option) {
            DB::table('hear_about_us')->updateOrInsert(['value' => $option]);
        }
    }
}
