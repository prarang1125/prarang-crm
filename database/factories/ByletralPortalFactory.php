<?php

namespace Database\Factories;

use App\Models\ByletralPortal;
use App\Models\CountryPortal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ByletralPortal>
 */
class ByletralPortalFactory extends Factory
{
    protected $model = ByletralPortal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(4, false);
        
        return [
            'primary_country_id' => CountryPortal::factory(),
            'secondary_country_id' => CountryPortal::factory(),
            'title' => $title,
            'slogan' => $this->faker->sentence(6),
            'slug' => \Str::slug($title) . '-' . $this->faker->randomNumber(4),
            'content_country_code' => $this->faker->countryCode(),
            'connections' => json_encode([
                'trade_volume' => $this->faker->numberBetween(1000000, 10000000000),
                'diplomatic_relations' => $this->faker->year(),
                'agreements' => [
                    'trade' => $this->faker->boolean(),
                    'visa_free' => $this->faker->boolean(),
                    'defense' => $this->faker->boolean()
                ]
            ]),
            'header_scripts' => json_encode([
                'analytics' => '<script>console.log("Analytics loaded");</script>',
                'tracking' => '<script>console.log("Tracking loaded");</script>'
            ]),
            'footer_scripts' => json_encode([
                'chat' => '<script>console.log("Chat widget loaded");</script>',
                'feedback' => '<script>console.log("Feedback widget loaded");</script>'
            ]),
            'header_image' => $this->faker->imageUrl(1200, 400, 'business'),
            'footer_image' => $this->faker->imageUrl(1200, 200, 'abstract')
        ];
    }

    /**
     * Create a bilateral portal with existing countries
     */
    public function withExistingCountries(int $primaryCountryId, int $secondaryCountryId): static
    {
        return $this->state(fn (array $attributes) => [
            'primary_country_id' => $primaryCountryId,
            'secondary_country_id' => $secondaryCountryId,
        ]);
    }
}
