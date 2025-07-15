# UltraLight Shop Theme

UltraLight Shop Theme is a lightweight, modern, and responsive WordPress theme designed for e-commerce and blogging, with built-in dark mode support and RTL compatibility. It features custom product and order post types, user registration/login, AJAX-powered navigation, and a price slider for search filtering.

## Features

- **Dark Mode & Light Mode**: Adapts to system color scheme preferences.
- **Custom Post Types**: Products and Orders, with meta fields for price and SKU.
- **User System**: Registration, login, and a user panel for viewing orders.
- **AJAX Navigation**: Smooth page transitions using `assets/js/lazyload.js`.
- **Price Slider**: jQuery UI slider for filtering products by price in search.
- **SEO & Business Settings**: Admin pages for SEO meta and business info.
- **RTL Support**: Automatic right-to-left layout for RTL languages.
- **Responsive Design**: Mobile-first CSS with custom fonts.

## File Structure

```
404.php
footer.php
functions.php
header.php
index.php
page-login.php
page-register.php
page-user-panel.php
page.php
searchform.php
single-product.php
single.php
style.css
assets/
  js/
    lazyload.js
    search-slider.js
fonts/
  lalezar/
    Lalezar-Regular.ttf
  sahel/
    Sahel-Regular.ttf
```

## Installation

1. **Copy the theme folder** to your WordPress `wp-content/themes/` directory.
2. **Activate** the "UltraLight Shop Theme" from the WordPress admin dashboard.
3. **Set up menus** under Appearance > Menus and assign to "Top Menu".
4. **Create pages** for Login (`/login`), Register (`/register`), and User Panel (`/user-panel`) using the provided templates.

## Usage

- **Products**: Add new products via the "Products" custom post type. Set price and SKU in the meta box.
- **Orders**: Orders are managed as a custom post type (not public).
- **User Registration/Login**: Use `[register]` and `[login]` shortcodes on the respective pages.
- **User Panel**: Shows logged-in user's orders via `[orders]` shortcode.
- **Search**: Use the search form with price slider for product filtering.

## Customization

- **Fonts**: Uses Sahel (body) and Lalezar (headings) fonts, loaded from the `fonts/` directory.
- **Colors**: Easily customizable via CSS variables in `style.css`.
- **Admin Settings**: SEO and business info can be set in the WordPress admin under "Theme Settings".

## Scripts

- `assets/js/lazyload.js`: Handles AJAX navigation and content transitions.
- `assets/js/search-slider.js`: Adds a jQuery UI price slider to the search form.

## Accessibility

- Uses semantic HTML5 and ARIA-friendly forms.
- Includes screen-reader text for search.

## License

GNU General Public License v2 or later. See [style.css](style.css) for details.

---

**Author:** [Your Name](http://gordarg.com/en/author/tayyebi)  
**Theme URI:** http://gordarg.com
