<?php

namespace Database\Factories;

use App\Models\CountryPortal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CountryPortal>
 */
class CountryPortalFactory extends Factory
{
    protected $model = CountryPortal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countryName = $this->faker->country();
        $countryCode = $this->faker->countryCode();
        
        return [
            'country_name' => $countryName,
            'country_code' => $countryCode,
            'anlytics_code' => 'GA-' . $this->faker->randomNumber(8),
            'analytics_slug' => \Str::slug($countryName),
            'country_name_locale' => $countryName,
            'slogan' => $this->faker->sentence(6),
            'locale_lang' => $this->faker->languageCode(),
            'maps' => $this->faker->url(),
            'weather' => json_encode([
                'api_key' => $this->faker->uuid(),
                'city' => $this->faker->city(),
                'enabled' => true
            ]),
            'news' => json_encode([
                'sources' => [$this->faker->url(), $this->faker->url()],
                'categories' => ['politics', 'business', 'sports']
            ]),
            'local_metrics' => json_encode([
                'population' => $this->faker->numberBetween(1000000, 100000000),
                'gdp' => $this->faker->numberBetween(1000000000, 10000000000),
                'currency' => $this->faker->currencyCode()
            ]),
            'embassy_links' => json_encode([
                'official_site' => $this->faker->url(),
                'visa_info' => $this->faker->url(),
                'contact' => $this->faker->email()
            ]),
            'timezone' => $this->faker->timezone(),
            'important_links' => json_encode([
                'tourism' => $this->faker->url(),
                'government' => $this->faker->url(),
                'trade' => $this->faker->url()
            ])
        ];
    }
}
