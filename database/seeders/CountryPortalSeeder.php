<?php

namespace Database\Seeders;

use App\Models\CountryPortal;
use App\Models\ByletralPortal;
use Illuminate\Database\Seeder;

class CountryPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample countries
        $countries = [
            [
                'country_name' => 'India',
                'country_code' => 'IN',
                'anlytics_code' => 'GA-123456789',
                'analytics_slug' => 'india',
                'country_name_locale' => 'भारत',
                'slogan' => 'Incredible India',
                'locale_lang' => 'hi',
                'timezone' => 'Asia/Kolkata',
            ],
            [
                'country_name' => 'United States',
                'country_code' => 'US',
                'anlytics_code' => 'GA-987654321',
                'analytics_slug' => 'united-states',
                'country_name_locale' => 'United States of America',
                'slogan' => 'Land of the Free',
                'locale_lang' => 'en',
                'timezone' => 'America/New_York',
            ],
            [
                'country_name' => 'United Kingdom',
                'country_code' => 'GB',
                'anlytics_code' => 'GA-456789123',
                'analytics_slug' => 'united-kingdom',
                'country_name_locale' => 'United Kingdom',
                'slogan' => 'Great Britain',
                'locale_lang' => 'en',
                'timezone' => 'Europe/London',
            ],
            [
                'country_name' => 'Japan',
                'country_code' => 'JP',
                'anlytics_code' => 'GA-789123456',
                'analytics_slug' => 'japan',
                'country_name_locale' => '日本',
                'slogan' => 'Land of the Rising Sun',
                'locale_lang' => 'ja',
                'timezone' => 'Asia/Tokyo',
            ]
        ];

        $createdCountries = [];
        
        foreach ($countries as $countryData) {
            $country = CountryPortal::create(array_merge($countryData, [
                'weather' => json_encode([
                    'api_key' => 'sample_weather_api_key',
                    'enabled' => true
                ]),
                'news' => json_encode([
                    'sources' => ['bbc.com', 'reuters.com'],
                    'categories' => ['politics', 'business', 'technology']
                ]),
                'local_metrics' => json_encode([
                    'population' => rand(10000000, 1000000000),
                    'gdp' => rand(1000000000, 20000000000),
                    'currency' => $countryData['country_code'] . 'D'
                ]),
                'embassy_links' => json_encode([
                    'official_site' => 'https://embassy-' . strtolower($countryData['country_code']) . '.gov',
                    'visa_info' => 'https://visa-' . strtolower($countryData['country_code']) . '.gov'
                ]),
                'important_links' => json_encode([
                    'tourism' => 'https://tourism-' . strtolower($countryData['country_code']) . '.gov',
                    'government' => 'https://gov-' . strtolower($countryData['country_code']) . '.gov'
                ])
            ]));
            
            $createdCountries[] = $country;
        }

        // Create some bilateral portals
        $bilateralPortals = [
            [
                'primary_country_id' => $createdCountries[0]->id, // India
                'secondary_country_id' => $createdCountries[1]->id, // US
                'title' => 'India-USA Strategic Partnership Portal',
                'slogan' => 'Strengthening Indo-US Relations',
                'slug' => 'india-usa-partnership',
                'content_country_code' => 'IN'
            ],
            [
                'primary_country_id' => $createdCountries[0]->id, // India
                'secondary_country_id' => $createdCountries[2]->id, // UK
                'title' => 'India-UK Trade & Investment Portal',
                'slogan' => 'Building Bridges Across Continents',
                'slug' => 'india-uk-trade',
                'content_country_code' => 'GB'
            ],
            [
                'primary_country_id' => $createdCountries[1]->id, // US
                'secondary_country_id' => $createdCountries[3]->id, // Japan
                'title' => 'US-Japan Alliance Portal',
                'slogan' => 'Pacific Partnership for Progress',
                'slug' => 'us-japan-alliance',
                'content_country_code' => 'US'
            ]
        ];

        foreach ($bilateralPortals as $portalData) {
            ByletralPortal::create(array_merge($portalData, [
                'connections' => json_encode([
                    'trade_volume' => rand(1000000000, 50000000000),
                    'diplomatic_relations_since' => rand(1947, 2000),
                    'agreements' => [
                        'trade' => true,
                        'defense' => true,
                        'cultural_exchange' => true
                    ]
                ]),
                'header_scripts' => json_encode([
                    'analytics' => '<script>console.log("Portal analytics loaded");</script>'
                ]),
                'footer_scripts' => json_encode([
                    'feedback' => '<script>console.log("Feedback system loaded");</script>'
                ]),
                'header_image' => 'images/portals/header-' . $portalData['slug'] . '.jpg',
                'footer_image' => 'images/portals/footer-' . $portalData['slug'] . '.jpg'
            ]));
        }
    }
}
