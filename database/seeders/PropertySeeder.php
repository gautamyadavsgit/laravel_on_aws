<?php

namespace Database\Seeders;

use App\Models\Admins;
use App\Models\PropertyAddress;
use App\Models\PropertyAmenity;
use App\Models\PropertyDetails;
use App\Models\PropertyDocumentModel;
use App\Models\PropertyFloorplan;
use App\Models\PropertyImageModel;
use App\Models\PropertyMetrics;
use App\Models\PropertyModel;
use App\Models\PropertyOffering;
use App\Services\PropertyMetricsService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds for fractional properties.
     */
    public function run(): void
    {
        // 1. Ensure all local sample images, floorplans, and PDFs exist
        ImageSeederHelper::ensureSeedAssets();

        $admin = Admins::first();
        $adminId = $admin ? $admin->id : 1;

        $curatedProperties = [
            [
                'name' => 'Smoky Mountain Luxury Cabin',
                'availability' => 'Available',
                'description' => 'Located in the high-demand vacation corridor of Gatlinburg, TN, this luxury short-term rental property features custom timber architecture, panoramic Great Smoky Mountain vistas, private hot tub deck, and dedicated entertainment suites. Managed by Gautam Asset Management with a historical 88.5% annual occupancy rate and prime placement across top luxury booking platforms.',
                'management_company' => 'Gautam Asset Management LLC',
                'address' => [
                    'address_1' => '742 Smoky View Ridge',
                    'address_2' => 'Cabin #12',
                    'city' => 'Gatlinburg',
                    'state' => 'Tennessee',
                    'zip' => 37738,
                ],
                'details' => [
                    'type' => 'Single Family Luxury Vacation Cabin',
                    'bedrooms' => 4,
                    'baths' => 3,
                    'half_baths' => 1,
                    'sleeps' => 12,
                    'garages' => 2,
                    'square_feets' => 2850,
                    'stories' => '2-Story Custom Timber Frame',
                    'units' => 1,
                    'lot_size' => 2,
                    'year_built' => 2022,
                    'zoning' => 'Commercial / Short-Term Rental Qualified',
                    'value' => 617000,
                ],
                'aacf' => [
                    'annual_rent_amount' => 64000,
                    'annual_rent_gross_yield' => 10.37,
                    'aacf_expences' => 14500,
                    'aacf_net' => 49500,
                ],
                'shares' => [
                    'equity_raise' => 617000,
                    'price_per_share_deed' => 50,
                    'total_developer_share_deeds' => 1000,
                    'total_raise_share_deeds' => 11340,
                    'total_share_deeds' => 12340,
                    'first_dividend_date' => '2026-09-30',
                    'seccond_dividend_date' => '2026-12-31',
                ],
                'images' => [
                    'property_images/property_1.png',
                    'property_images/property_2.png',
                    'property_images/property_3.png',
                ],
            ],
            [
                'name' => 'Blue Ridge Mountain Retreat',
                'availability' => 'Not Available',
                'description' => 'Architecturally distinct mountain estate nestled in the rolling Blue Ridge Mountains of Asheville, NC. Features vaulted cedar ceilings, heated outdoor living rooms, chef kitchen, and private walking trails with direct access to national parklands.',
                'management_company' => 'Gautam Asset Management LLC',
                'address' => [
                    'address_1' => '104 Mountain Vista Way',
                    'address_2' => 'Estate Unit A',
                    'city' => 'Asheville',
                    'state' => 'North Carolina',
                    'zip' => 28801,
                ],
                'details' => [
                    'type' => 'Mountain Contemporary Lodge',
                    'bedrooms' => 5,
                    'baths' => 4,
                    'half_baths' => 1,
                    'sleeps' => 14,
                    'garages' => 2,
                    'square_feets' => 3400,
                    'stories' => '3-Story Contemporary Wood & Stone',
                    'units' => 1,
                    'lot_size' => 3,
                    'year_built' => 2021,
                    'zoning' => 'Short-Term Rental Approved',
                    'value' => 498000,
                ],
                'aacf' => [
                    'annual_rent_amount' => 58000,
                    'annual_rent_gross_yield' => 9.80,
                    'aacf_expences' => 12800,
                    'aacf_net' => 45200,
                ],
                'shares' => [
                    'equity_raise' => 498000,
                    'price_per_share_deed' => 50,
                    'total_developer_share_deeds' => 800,
                    'total_raise_share_deeds' => 9160,
                    'total_share_deeds' => 9960,
                    'first_dividend_date' => '2026-09-30',
                    'seccond_dividend_date' => '2026-12-31',
                ],
                'images' => [
                    'property_images/property_2.png',
                    'property_images/property_3.png',
                    'property_images/property_4.png',
                ],
            ],
            [
                'name' => 'Gulf Coast Waterfront Villa',
                'availability' => 'Available',
                'description' => 'Direct beach-access Gulf Coast villa in Destin, FL. Boasts a heated private pool, wrap-around sunset terrace, private boat slip, and high rental velocity with recurring seasonal travelers.',
                'management_company' => 'Gautam Asset Management LLC',
                'address' => [
                    'address_1' => '500 Pelican Bay Boulevard',
                    'address_2' => 'Villa #4',
                    'city' => 'Destin',
                    'state' => 'Florida',
                    'zip' => 32541,
                ],
                'details' => [
                    'type' => 'Waterfront Coastal Villa',
                    'bedrooms' => 6,
                    'baths' => 5,
                    'half_baths' => 2,
                    'sleeps' => 18,
                    'garages' => 3,
                    'square_feets' => 4200,
                    'stories' => '2-Story Coastal Mediterranean',
                    'units' => 1,
                    'lot_size' => 1,
                    'year_built' => 2023,
                    'zoning' => 'Resort Commercial Waterfront',
                    'value' => 845000,
                ],
                'aacf' => [
                    'annual_rent_amount' => 99700,
                    'annual_rent_gross_yield' => 11.80,
                    'aacf_expences' => 19500,
                    'aacf_net' => 80200,
                ],
                'shares' => [
                    'equity_raise' => 845000,
                    'price_per_share_deed' => 50,
                    'total_developer_share_deeds' => 1500,
                    'total_raise_share_deeds' => 15400,
                    'total_share_deeds' => 16900,
                    'first_dividend_date' => '2026-09-30',
                    'seccond_dividend_date' => '2026-12-31',
                ],
                'images' => [
                    'property_images/property_3.png',
                    'property_images/property_4.png',
                    'property_images/property_5.png',
                ],
            ],
            [
                'name' => 'Aspen Alpine Ski Chalet',
                'availability' => 'Available',
                'description' => 'Ski-in / ski-out luxury timber alpine chalet in Aspen, CO. Equipped with an indoor heated spa, radiant heated driveway, commercial ski equipment locker room, and floor-to-ceiling glass framing snow-capped peaks.',
                'management_company' => 'Gautam Asset Management LLC',
                'address' => [
                    'address_1' => '880 Silver King Drive',
                    'address_2' => 'Chalet #8',
                    'city' => 'Aspen',
                    'state' => 'Colorado',
                    'zip' => 81611,
                ],
                'details' => [
                    'type' => 'Alpine Ski Chalet',
                    'bedrooms' => 5,
                    'baths' => 5,
                    'half_baths' => 1,
                    'sleeps' => 16,
                    'garages' => 2,
                    'square_feets' => 3950,
                    'stories' => '3-Story Alpine Cedar',
                    'units' => 1,
                    'lot_size' => 2,
                    'year_built' => 2020,
                    'zoning' => 'Resort Residential Qualified',
                    'value' => 920000,
                ],
                'aacf' => [
                    'annual_rent_amount' => 115000,
                    'annual_rent_gross_yield' => 12.50,
                    'aacf_expences' => 22000,
                    'aacf_net' => 93000,
                ],
                'shares' => [
                    'equity_raise' => 920000,
                    'price_per_share_deed' => 50,
                    'total_developer_share_deeds' => 1600,
                    'total_raise_share_deeds' => 16800,
                    'total_share_deeds' => 18400,
                    'first_dividend_date' => '2026-09-30',
                    'seccond_dividend_date' => '2026-12-31',
                ],
                'images' => [
                    'property_images/property_4.png',
                    'property_images/property_5.png',
                    'property_images/property_6.png',
                ],
            ],
            [
                'name' => 'Napa Valley Vineyard Estate',
                'availability' => 'Available',
                'description' => 'Scenic wine country sanctuary featuring private olive groves, private infinity pool, commercial chef tasting kitchen, and year-round high-ticket corporate and wedding hospitality bookings.',
                'management_company' => 'Gautam Asset Management LLC',
                'address' => [
                    'address_1' => '1200 Silverado Trail',
                    'address_2' => 'Manor House',
                    'city' => 'St. Helena',
                    'state' => 'California',
                    'zip' => 94574,
                ],
                'details' => [
                    'type' => 'Vineyard Hospitality Estate',
                    'bedrooms' => 6,
                    'baths' => 6,
                    'half_baths' => 2,
                    'sleeps' => 20,
                    'garages' => 4,
                    'square_feets' => 5200,
                    'stories' => '2-Story Spanish Revival',
                    'units' => 1,
                    'lot_size' => 5,
                    'year_built' => 2019,
                    'zoning' => 'Agricultural Hospitality Approved',
                    'value' => 1250000,
                ],
                'aacf' => [
                    'annual_rent_amount' => 145000,
                    'annual_rent_gross_yield' => 11.60,
                    'aacf_expences' => 28000,
                    'aacf_net' => 117000,
                ],
                'shares' => [
                    'equity_raise' => 1250000,
                    'price_per_share_deed' => 50,
                    'total_developer_share_deeds' => 2000,
                    'total_raise_share_deeds' => 23000,
                    'total_share_deeds' => 25000,
                    'first_dividend_date' => '2026-09-30',
                    'seccond_dividend_date' => '2026-12-31',
                ],
                'images' => [
                    'property_images/property_5.png',
                    'property_images/property_6.png',
                    'property_images/property_7.png',
                ],
            ],
            [
                'name' => 'Scottsdale Desert Oasis',
                'availability' => 'Available',
                'description' => 'Southwestern architectural showcase with a private resort-style lagoon pool, putting green, outdoor fire lounges, and prime location adjacent to TPC Scottsdale championship golf corridors.',
                'management_company' => 'Gautam Asset Management LLC',
                'address' => [
                    'address_1' => '3300 Pinnacle Peak Road',
                    'address_2' => 'Oasis #2',
                    'city' => 'Scottsdale',
                    'state' => 'Arizona',
                    'zip' => 85255,
                ],
                'details' => [
                    'type' => 'Desert Modern Luxury Villa',
                    'bedrooms' => 5,
                    'baths' => 4,
                    'half_baths' => 1,
                    'sleeps' => 14,
                    'garages' => 3,
                    'square_feets' => 3800,
                    'stories' => 'Single Story Modern Adobe',
                    'units' => 1,
                    'lot_size' => 1,
                    'year_built' => 2023,
                    'zoning' => 'Short-Term Rental Certified',
                    'value' => 775000,
                ],
                'aacf' => [
                    'annual_rent_amount' => 88000,
                    'annual_rent_gross_yield' => 11.35,
                    'aacf_expences' => 16500,
                    'aacf_net' => 71500,
                ],
                'shares' => [
                    'equity_raise' => 775000,
                    'price_per_share_deed' => 50,
                    'total_developer_share_deeds' => 1200,
                    'total_raise_share_deeds' => 14300,
                    'total_share_deeds' => 15500,
                    'first_dividend_date' => '2026-09-30',
                    'seccond_dividend_date' => '2026-12-31',
                ],
                'images' => [
                    'property_images/property_6.png',
                    'property_images/property_7.png',
                    'property_images/property_8.png',
                ],
            ],
        ];

        foreach ($curatedProperties as $index => $propData) {
            $property = PropertyModel::updateOrCreate(
                ['name' => $propData['name']],
                [
                    'admin_id' => $adminId,
                    'availability' => $propData['availability'],
                    'description' => $propData['description'],
                    'management_company' => $propData['management_company'],
                ]
            );

            $propId = $property->id;

            // 1. Address
            PropertyAddress::updateOrCreate(
                ['property_id' => $propId],
                $propData['address']
            );

            // 2. Images (3 images per property)
            PropertyImageModel::where('property_id', $propId)->delete();
            foreach ($propData['images'] as $imgUrl) {
                PropertyImageModel::create([
                    'property_id' => $propId,
                    'property_image_key' => 'property_image',
                    'property_image_value' => $imgUrl,
                ]);
            }

            // 3. Amenities
            PropertyAmenity::updateOrCreate(
                ['property_id' => $propId],
                [
                    'property_amenities' => 'Panoramic Mountain View, 8-Person Hot Tub, Starlink High Speed Internet, Stone Fireplace, Game Room & Billiards, Keyless Smart Lock, Private Swimming Pool, Custom Chef Kitchen',
                ]
            );

            // 4. Details
            PropertyDetails::updateOrCreate(
                ['property_id' => $propId],
                $propData['details']
            );

            // 5. Floorplans (2 levels)
            PropertyFloorplan::where('property_id', $propId)->delete();
            for ($f = 1; $f <= 2; $f++) {
                $planNum = (($index + $f - 1) % 6) + 1;
                PropertyFloorplan::create([
                    'property_id' => $propId,
                    'key' => 'Architectural Floorplan Level ' . $f,
                    'value' => 'floorplan_images/floorplan_' . $planNum . '.png',
                ]);
            }

            // 6. Offering Breakdown
            $purchase = (int) ($propData['details']['value'] * 0.84);
            PropertyOffering::updateOrCreate(
                ['property_id' => $propId],
                [
                    'offering_purchase' => $purchase,
                    'offering_build_cost' => 45000,
                    'offering_improvements' => 25000,
                    'offering_closing_cost' => '12000',
                    'offering_sourcing_fees' => '15000',
                ]
            );

            // 9. Legal Documents (6 PDF files)
            PropertyDocumentModel::where('property_id', $propId)->delete();
            $docList = [
                'Documents_Master_Deed' => 'property_documents/master_deed_1.pdf',
                'Documents_Expence_Calculations' => 'property_documents/expense_statement_4.pdf',
                'Documents_Rent_Calculations' => 'property_documents/rent_calculation_3.pdf',
                'Documents_Deed_Restrictions' => 'property_documents/deed_restrictions_5.pdf',
                'Documents_Closing_Statement_Example' => 'property_documents/closing_statement_6.pdf',
                'document_1' => 'property_documents/operating_agreement_2.pdf',
            ];

            foreach ($docList as $docKey => $docFile) {
                PropertyDocumentModel::create([
                    'property_id' => $propId,
                    'document_key' => $docKey,
                    'document_value' => $docFile,
                ]);
            }

            // 10. Financial Underwriting & Goal Match Metrics
            $aacf = $propData['aacf'] ?? [];
            PropertyMetricsService::syncForProperty($property, [
                'gross_annual_rent' => $aacf['annual_rent_amount'] ?? null,
                'operating_expenses' => $aacf['aacf_expences'] ?? null,
                'net_operating_income' => $aacf['aacf_net'] ?? null,
                'cap_rate' => $aacf['annual_rent_gross_yield'] ?? null,
                'annual_cash_flow' => $aacf['aacf_net'] ?? null,
                'cash_on_cash_return' => $aacf['annual_rent_gross_yield'] ?? null,
                'estimated_appreciation_rate' => 5.40,
                'is_1031_exchange_eligible' => true,
                'cost_segregation_eligible' => true,
                'diversification_score' => 9.2,
                'occupancy_rate_projected' => 88.5,
                'cash_flow_rating' => 95,
                'appreciation_rating' => 89,
                'tax_benefit_rating' => 93,
                'diversification_rating' => 91,
            ]);
        }
    }
}
