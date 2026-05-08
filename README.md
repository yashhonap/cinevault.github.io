# CineVault Affiliate WordPress Theme

A dark, premium WordPress theme for affiliate movie and web series recommendation blogs.

## What Is Included

- Custom `Recommendation` post type for movies and web series.
- Affiliate/watch URL, platform, type, year, runtime, and curator rating fields.
- Dark premium front page with hero, recommendation cards, subscription plans, and blog section.
- Authentication interface using WordPress login, registration, and password reset URLs.
- Browser-based favorites drawer so visitors can save movies and open the affiliate/watch link later.
- Archive and single templates for recommendations.

## Install

1. Copy this folder into `wp-content/themes/cinevault-affiliate`.
2. In WordPress admin, go to **Appearance > Themes** and activate **CineVault Affiliate**.
3. Go to **Settings > Permalinks** and click **Save Changes** once so the recommendation archive URL is registered.
4. Add a menu under **Appearance > Menus** and assign it to **Primary Menu**.

## Create Recommendation Posts

1. Open **Recommendations > Add New**.
2. Add the review or recommendation content.
3. Add a featured image.
4. Fill the **Affiliate Recommendation Details** box:
   - Affiliate or watch URL
   - Type
   - Main platform
   - Release year
   - Curator rating
   - Runtime or seasons

The card's **Open Movie** button uses the affiliate URL and marks it as `nofollow sponsored`.

## Subscription Plans

The pricing section is ready for conversion links. Connect the buttons to your membership plugin checkout pages after installing a plugin such as WooCommerce Memberships, MemberPress, Paid Memberships Pro, or SureMembers.
