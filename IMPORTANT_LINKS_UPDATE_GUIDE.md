# Important Links Update Guide

## Overview
The "Important Links" field in the Country Portal has been updated to save data in a new categorized JSON format.

## New Data Format

### Before (Old Format)
```json
{
  "tourism": "https://example.com/tourism",
  "government": "https://example.com/gov",
  "facebook": "https://facebook.com/page"
}
```

### After (New Format)
```json
{
  "tourist_places": [
    "https://example.com/place-1",
    "https://example.com/place-2"
  ],
  "community_pages": [
    "https://facebook.com/community-1",
    "https://example.com/community-2"
  ],
  "resources": [
    "https://gov.example.com/resource-1",
    "https://example.com/resource-2"
  ]
}
```

## Changes Made

### 1. Model (`app/Models/CountryPortal.php`)
- No changes required
- Already has `'important_links' => 'array'` in the `$casts` property

### 2. Controller (`app/Http/Controllers/Admin/CountryPortalController.php`)
- No changes required
- Already handles JSON encoding/decoding properly

### 3. Views

#### Edit Page (`resources/views/admin/country_portal/edit.blade.php`)
- Updated the Important Links section with three categories:
  - **Tourist Places**: For tourist destinations and attractions
  - **Community Pages**: For social media and community links
  - **Resources**: For government, official, and other resource links
- Each category has its own "Add" button and displays existing links
- Links are stored as arrays of URLs (not key-value pairs)

#### Create Page (`resources/views/admin/country_portal/create.blade.php`)
- Same structure as the edit page
- Three categorized sections for links

#### Show Page (`resources/views/admin/country_portal/show.blade.php`)
- Updated to display categorized links in a user-friendly format
- Shows each category with color-coded headings
- Displays links as clickable items with external link icons
- Handles both old and new formats gracefully

### 4. Migration
Created: `database/migrations/2025_10_04_000452_migrate_important_links_to_categorized_format.php`

This migration automatically converts existing data from the old format to the new format by:
- Categorizing links based on keywords in their names
- Tourist-related keywords → `tourist_places`
- Community-related keywords → `community_pages`
- Government/official keywords → `resources`
- Default fallback → `resources`

## How to Use

### Adding Important Links in the UI

1. **Edit or Create Country Portal**
2. Scroll to the "Important Links" section
3. You'll see three collapsible cards:
   - Tourist Places
   - Community Pages
   - Resources

4. **To Add Links:**
   - Click the "+ Add" button under the relevant category
   - Enter the full URL (e.g., `https://example.com/page`)
   - Click the "×" button to remove a link
   - Add as many links as needed in each category

5. **Save the Form**
   - All links will be saved in the categorized JSON format

### Viewing Important Links

When viewing a country portal:
- Links are displayed in organized sections by category
- Each link is clickable and opens in a new tab
- Color-coded headers for easy identification:
  - Blue for Tourist Places
  - Green for Community Pages
  - Cyan for Resources

## Running the Migration

To convert existing data to the new format:

```bash
php artisan migrate --path=database/migrations/2025_10_04_000452_migrate_important_links_to_categorized_format.php
```

Or run all pending migrations:

```bash
php artisan migrate
```

## Database Structure

The `country_portals` table's `important_links` column remains as:
- Type: `JSON`
- Nullable: `true`
- Cast to array in the model

## Backward Compatibility

- The migration includes a `down()` method to revert to the old format if needed
- The show page can display both old and new formats
- However, once you edit a record with the new UI, it will be saved in the new format

## Testing Checklist

- [ ] Create a new country portal with important links
- [ ] Edit an existing country portal and add links to each category
- [ ] Verify links are saved in the correct JSON format
- [ ] View a country portal and ensure links display correctly
- [ ] Click on links to ensure they open correctly
- [ ] Remove links and verify they are deleted
- [ ] Run the migration on existing data

## Notes

- Each category stores an **array of URLs**, not key-value pairs
- Links are filtered to remove empty values before saving
- The UI prevents adding empty links
- All three categories are always present in the JSON, even if empty
