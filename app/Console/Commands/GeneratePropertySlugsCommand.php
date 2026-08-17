<?php

namespace App\Console\Commands;

use App\Models\PropertyModel;
use Illuminate\Console\Command;

class GeneratePropertySlugsCommand extends Command
{
    protected $signature = 'properties:generate-slugs';

    protected $description = 'Generate unique slugs for all properties';

    public function handle(): int
    {
        $properties = PropertyModel::all();
        $count = 0;

        foreach ($properties as $property) {
            if (empty($property->slug)) {
                $property->slug = PropertyModel::generateUniqueSlug($property->name, $property->id);
                $property->save();
                $this->info("Generated slug for [{$property->name}]: {$property->slug}");
                $count++;
            } else {
                $this->line("Existing slug for [{$property->name}]: {$property->slug}");
            }
        }

        $this->info("Slug sync complete! Processed {$count} properties.");

        return self::SUCCESS;
    }
}
