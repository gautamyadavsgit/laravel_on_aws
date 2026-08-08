<?php

namespace Database\Factories;

use App\Models\Admins;
use App\Models\CalcPreset;
use App\Models\MarketDetails;
use App\Models\PropertyAacf;
use App\Models\PropertyAddress;
use App\Models\PropertyAmenity;
use App\Models\PropertyDetails;
use App\Models\PropertyDocumentModel;
use App\Models\PropertyExtraDetails;
use App\Models\PropertyFinancialDetail;
use App\Models\PropertyFloorplan;
use App\Models\PropertyImageModel;
use App\Models\PropertyModel;
use App\Models\PropertyOffering;
use App\Models\PropertyShare;
use App\Models\PropertyTaxModel;
use App\Models\PropertyUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyDataFactory extends Factory
{
    protected $model = PropertyModel::class;

    public function definition(): array
    {
        $admin = Admins::first();
        $adminId = $admin ? $admin->id : 1;

        $propertyNames = [
            'Smoky Mountain Luxury Cabin',
            'Blue Ridge Mountain Retreat',
            'Gulf Coast Waterfront Villa',
            'Aspen Alpine Ski Chalet',
            'Napa Valley Vineyard Estate',
            'Scottsdale Desert Oasis',
            'Lake Tahoe Waterfront Haven',
            'Jackson Hole Mountain Lodge',
            'Key West Coastal Sanctuary',
            'Sedona Red Rock Manor'
        ];

        return [
            'admin_id' => $adminId,
            'name' => fake()->randomElement($propertyNames),
            'availability' => 'Active Capital Raise',
            'description' => 'Institutional-grade short-term rental property located in a premier vacation destination with historical revenue performance and professional asset management.',
            'management_company' => 'Gautam Asset Management LLC',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (PropertyModel $property) {
            // 1. Taxes (3 tiers)
            for ($i = 1; $i <= 3; $i++) {
                PropertyTaxModel::factory()->create([
                    'property_id' => $property->id,
                    'tax_key' => 'tax_' . $i,
                    'tax_value' => (string) (2500 + $i * 700),
                ]);
            }

            // 2. Address
            PropertyAddress::factory()->create([
                'property_id' => $property->id,
            ]);

            // 3. Images (3 images per property)
            for ($i = 1; $i <= 3; $i++) {
                $imgNum = (($property->id + $i - 2) % 10) + 1;
                PropertyImageModel::factory()->create([
                    'property_id' => $property->id,
                    'property_image_key' => 'property_image',
                    'property_image_value' => 'property_images/property_' . $imgNum . '.png',
                ]);
            }

            // 4. Amenities
            PropertyAmenity::factory()->create([
                'property_id' => $property->id,
            ]);

            // 5. Details
            PropertyDetails::factory()->create([
                'property_id' => $property->id,
            ]);

            // 6. Market Details
            $marketNum = (($property->id - 1) % 6) + 1;
            MarketDetails::factory()->create([
                'property_id' => $property->id,
                'market_image' => 'market_image/market_' . $marketNum . '.png',
            ]);

            // 7. Floorplans (2 floorplans per property)
            for ($i = 1; $i <= 2; $i++) {
                $planNum = (($property->id + $i - 2) % 6) + 1;
                PropertyFloorplan::factory()->create([
                    'property_id' => $property->id,
                    'key' => 'Floorplan Level ' . $i,
                    'value' => 'floorplan_images/floorplan_' . $planNum . '.png',
                ]);
            }

            // 8. Extra Details
            PropertyExtraDetails::factory()->create([
                'property_id' => $property->id,
            ]);

            // 9. AACF Cash Flow
            PropertyAacf::factory()->create([
                'property_id' => $property->id,
            ]);

            // 10. External Listing URLs
            PropertyUrl::factory()->create([
                'property_id' => $property->id,
            ]);

            // 11. Offering Structure
            PropertyOffering::factory()->create([
                'property_id' => $property->id,
            ]);

            // 12. Shares & Deeds
            PropertyShare::factory()->create([
                'property_id' => $property->id,
            ]);

            // 13. Calculator Multiplier Presets (1 to 6)
            for ($i = 1; $i <= 6; $i++) {
                CalcPreset::factory()->create([
                    'property_id' => $property->id,
                    'key' => 'calc_preset_' . $i,
                    'value' => (string) ($i * 10),
                ]);
            }

            // 14. Legal Documents (6 verified documents)
            $docs = [
                'Documents_Master_Deed' => 'property_documents/master_deed_1.pdf',
                'Documents_Expence_Calculations' => 'property_documents/expense_statement_4.pdf',
                'Documents_Rent_Calculations' => 'property_documents/rent_calculation_3.pdf',
                'Documents_Deed_Restrictions' => 'property_documents/deed_restrictions_5.pdf',
                'Documents_Closing_Statement_Example' => 'property_documents/closing_statement_6.pdf',
                'document_1' => 'property_documents/operating_agreement_2.pdf',
            ];

            foreach ($docs as $key => $file) {
                PropertyDocumentModel::factory()->create([
                    'property_id' => $property->id,
                    'document_key' => $key,
                    'document_value' => $file,
                ]);
            }

            // 15. Financial Details
            PropertyFinancialDetail::factory()->create([
                'property_id' => $property->id,
            ]);
        });
    }
}
