# Assignment 1

## Summary

This assignment implements a full CRUD application for "Onboardo" - a repository onboarding guide manager. The app allows users to create, view, edit, and delete repositories with their onboarding guides.

### What Was Built

The application has a **header** and **footer** that appear on every page, built as reusable Blade components. The header contains the app name "Onboardo" and navigation links to the home page and create page. The footer displays a dynamic copyright notice.

For the CRUD functionality, I created four pages: an **index page** that lists all repositories with View, Edit, and Delete buttons for each item; a **create page** with a form to add new repositories (with fields for name, URL, description, and onboarding guide); a **show page** that displays all details of a single repository; and an **edit page** with a prefilled form to update existing repositories.

The **CSS** is organized into multiple files by purpose - variables, base layout, component styles, and page-specific styles. All colors are defined as CSS custom properties for consistency. The styling includes a sticky header, card-style list items, form styling with focus states, and a sticky footer that stays at the bottom even with little content.

For **JavaScript**, I implemented client-side form validation that checks required fields, URL format, and minimum length for the guide. Errors are shown in real-time as users fill out the form. I also added a delete confirmation dialog that asks users to confirm before deleting a repository.

**Routes** are organized into separate files (`web.php` for main routes, `repositories.php` for CRUD routes) following Laravel best practices for larger applications.

### Project Structure

```
portal-app/
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php      # Main layout (includes header/footer)
│   │   ├── components/
│   │   │   ├── header.blade.php       # Site header component
│   │   │   └── footer.blade.php       # Site footer component
│   │   ├── repositories/
│   │   │   ├── index.blade.php        # List all repositories
│   │   │   ├── create.blade.php       # Create form
│   │   │   ├── show.blade.php         # View single repository
│   │   │   └── edit.blade.php         # Edit form
│   │   └── welcome.blade.php          # Landing page
│   ├── css/
│   │   ├── app.css                    # Main CSS entry (imports all)
│   │   ├── _variables.css             # Color/size variables
│   │   ├── _base.css                  # Layout basics
│   │   ├── components/                # Component styles
│   │   └── pages/                     # Page-specific styles
│   └── js/
│       ├── app.js                     # Main JS entry (imports all)
│       ├── validation.js              # Form validation
│       └── delete-confirm.js          # Delete confirmation
└── routes/
    ├── web.php                        # Main routes
    └── repositories.php               # Repository CRUD routes
```

---

## Header and Footer

### ✅ Create a header using the correct HTML tags
**How I did it:** 
I created the header as a Laravel component in `resources/views/components/header.blade.php`. These components can either be class-based or anonymous. I chose to create it as an anonymous component, since it's a simple header without any logic. Then, I included the component in the main layout file `resources/views/layouts/app.blade.php` using `<x-header />`. The header uses the semantic `<header>` HTML tag which tells browsers and screen readers this is the page header.

### ✅ The header contains links to at least the home page and the create page
**How I did it:** 
I added a `<nav>` element inside the header component with anchor tags linking to the home page and create page. I used Laravel's `route()` helper with named routes (`route('home')` and `route('repositories.create')`) instead of hardcoding URLs. This is best practice because if the URL structure changes, the links won't break - Laravel resolves them from the route names defined in `routes/web.php`. See the nav section in `resources/views/components/header.blade.php`.

### ✅ The header contains other appropriate elements (Like company/app name, a logo, etc.)
**How I did it:** 
I added the app name "Onboardo" as a brand link in the left corner of the header using an anchor tag with class `header__brand`. The brand links back to the home page using `route('home')`. I used flexbox with `justify-content: space-between` on the container to push the brand to the left and navigation to the right - a common header layout pattern. See `resources/css/components/header.css` for the styling.

### ✅ Create a footer using the correct HTML tags
**How I did it:** 
I created the footer as an anonymous Blade component in `resources/views/components/footer.blade.php`, the same approach as the header. I used the semantic `<footer>` HTML tag. The footer is included in the layout using `<x-footer />`.

### ✅ The footer contains appropriate elements (Like trademark/copyright, made by, contact info, etc.)
**How I did it:** 
The footer displays a copyright notice with the app name. I used `{{ date('Y') }}` to dynamically display the current year, so it automatically updates each year. The styling is in `resources/css/components/footer.css`.

---

## Index Page

### ✅ The page has a header and a footer
**How I did it:** 
The index page extends the main layout (`@extends('layouts.app')`) which already includes the header and footer components. This means every page that extends this layout automatically gets the header and footer. See `resources/views/repositories/index.blade.php`.

### ✅ A list of all the elements for a given resource is shown
**How I did it:** 
I created `resources/views/repositories/index.blade.php` which displays all repositories in an unordered list. The page receives a `$repositories` array from the route (`routes/repositories.php`) and loops through it using Blade's `@foreach` directive. Each repository shows its name and description. The list is styled with CSS in `resources/css/pages/_repositories.css`.

### ✅ The first element has a link/button to see details of the element
**How I did it:** 
Each repository item (not just the first) has a "View" link that goes to the show page. I used `route('repositories.show', $repository['id'])` to generate the URL with the repository ID. This links to `/repositories/{id}` which displays the full repository details.

### ✅ The first element has a link/button to update the element
**How I did it:** 
Each repository item has an "Edit" link using `route('repositories.edit', $repository['id'])`. This links to `/repositories/{id}/edit` which shows a form with the current values prefilled.

### ✅ Each element has a link/button to delete the element
**How I did it:** 
Each repository item has a "Delete" button with `data-delete-id` and `data-delete-name` attributes. These are used by JavaScript (`resources/js/delete-confirm.js`) to show a confirmation dialog and submit a DELETE request to the server.

---

## Create Page

### ✅ The page has a header and a footer
**How I did it:** 
The create page extends `layouts.app` using `@extends('layouts.app')`, which includes the header and footer automatically. See `resources/views/repositories/create.blade.php`.

### ✅ A form element has been created, with the proper HTTP Method
**How I did it:** 
I created a `<form>` element with `method="POST"` and `action="{{ route('repositories.store') }}"`. POST is the correct HTTP method for creating new resources. The form also includes `@csrf` which generates a hidden CSRF token field - this is required by Laravel for security to prevent cross-site request forgery attacks.

### ✅ The form contains the appropriate input fields for the resource data, identified by labels
**How I did it:** 
The form has four fields: name, url, description, and guide. Each input has a `<label>` element with a `for` attribute matching the input's `id`. This associates the label with the input for accessibility - clicking the label focuses the input, and screen readers announce the label when the input is focused.

### ✅ Each input field has the appropriate type for the data
**How I did it:** 
I used appropriate HTML5 input types: `type="text"` for name and description, `type="url"` for the repository URL (which validates URL format), and `<textarea>` for the guide content (multi-line text). The URL type provides built-in browser validation for URL format.

### ✅ Each input field has an appropriate name
**How I did it:** 
Each input has a `name` attribute that matches the data field: `name="name"`, `name="url"`, `name="description"`, `name="guide"`. These names are used by the server to identify which field the data belongs to when the form is submitted.

### ✅ The form has a submit button
**How I did it:** 
I added a `<button type="submit">` at the bottom of the form with the text "Create Repository". The button uses the `.btn .btn--primary` classes for styling (blue background, white text). When clicked, it submits the form to the store route.

---

## Show Page

### ✅ The page has a header and a footer
**How I did it:** 
The show page extends `layouts.app` using `@extends('layouts.app')`. See `resources/views/repositories/show.blade.php`.

### ✅ The page shows all the resource information
**How I did it:** 
The show page displays all repository fields: name (as the page heading), URL (as a clickable link with `target="_blank"` to open in new tab), description, and the onboarding guide. Each field has a label and value displayed using the `.repo-details` CSS classes. The guide is displayed in a styled box with `white-space: pre-wrap` to preserve line breaks.

### ✅ The page has a link to edit the resource
**How I did it:** 
There's an "Edit" button in the header section next to the repository name. It uses `route('repositories.edit', $repository['id'])` to link to the edit page. There's also a "Back to list" link at the bottom to return to the index page.

---

## Update Page

### ✅ The page has a header and a footer
**How I did it:** 
The edit page extends `layouts.app` using `@extends('layouts.app')`. See `resources/views/repositories/edit.blade.php`.

### ✅ The page displays a similar form from the Create Page, with similar technical characteristics
**How I did it:** 
The edit form has the exact same structure as the create form: same fields (name, url, description, guide), same labels, same input types, same CSS classes. The only differences are: 1) the form action points to the update route, 2) it includes `@method('PUT')` for method spoofing since HTML forms don't support PUT, and 3) the submit button says "Update Repository" instead of "Create Repository".

### ✅ The form has the fields prefilled with the correct data
**How I did it:** 
Each input has a `value` attribute set to the current repository data, e.g., `value="{{ $repository['name'] }}"`. For the textarea, the content goes between the opening and closing tags: `<textarea>{{ $repository['guide'] }}</textarea>`. The `$repository` variable is passed from the route in `routes/repositories.php`.

---

## CSS

### ✅ Appropriate .css file(s) has been created and used
**How I did it:** 
I organized CSS into multiple files by purpose:

| File | Purpose |
|------|---------|
| `css/app.css` | Main entry point that imports all other CSS files |
| `css/_variables.css` | CSS custom properties (colors, sizes) used throughout |
| `css/_base.css` | Base layout styles (body flex container for sticky footer) |
| `css/components/header.css` | Header component styles |
| `css/components/footer.css` | Footer component styles |
| `css/pages/_landing.css` | Landing page hero section styles |
| `css/pages/_repositories.css` | Repository list and show page styles |
| `css/pages/_forms.css` | Form input and validation styles |

All files are imported in `app.css` and compiled by Vite into a single optimized bundle.

### ✅ Header and Footer style is consistent across pages
**How I did it:** 
The header and footer are Blade components included in the layout, so they appear identically on every page. They share CSS variables from `_variables.css` (e.g., `--header-bg`, `--header-border`) ensuring consistent colors. Both use the same `max-width: 1200px` and padding so content aligns.

### ✅ Index page has a proper style
**How I did it:** 
The repository list uses `.repo-list` styles in `_repositories.css`. Each item is a flexbox row with the info on the left and action buttons on the right. Items have padding, borders, and rounded corners for a card-like appearance. The action buttons have consistent hover states.

### ✅ Create/Update page has a proper style
**How I did it:** 
Forms use styles from `_forms.css`. The form has a `max-width: 600px` for readability. Form groups have `margin-bottom: 1.5rem` for spacing. Inputs have consistent padding, borders, and focus states (border turns blue). Labels are bold and positioned above inputs.

### ✅ Show page has a proper style
**How I did it:** 
The show page uses `.repo-details` styles. Each detail item has a small gray label above the value. The guide content is displayed in a light gray box with padding. The header uses flexbox to put the title and edit button on the same line.

### ✅ Colors are consistent across pages
**How I did it:** 
All colors are defined as CSS custom properties in `_variables.css`:

| Variable | Value | Usage |
|----------|-------|-------|
| `--text-primary` | `#111827` | Main text color |
| `--text-secondary` | `#6b7280` | Muted/secondary text |
| `--brand-color` | `#2563eb` | Blue accent for links and buttons |
| `--header-bg` | `#ffffff` | White backgrounds |
| `--header-border` | `#e5e7eb` | Light gray borders |
| `--hover-bg` | `#f3f4f6` | Hover state background |

These variables are used throughout all CSS files ensuring consistency.

### ✅ Extra cool style points!
**How I did it:** 
- **Smooth transitions on hover states** - Added `transition: background-color 0.15s` to buttons and links so color changes animate smoothly instead of snapping
- **Sticky header** - Used `position: sticky; top: 0;` on the header so it stays visible when scrolling down the page
- **Responsive design** - Added `@media (max-width: 768px)` queries in `header.css` to reduce padding and font sizes on mobile screens
- **Focus states on inputs** - Inputs change border color to blue on focus using `.form__input:focus { border-color: var(--brand-color); }` for accessibility
- **Landing page hero section** - Used flexbox with `flex-direction: column; align-items: center; text-align: center;` to center the welcome message and CTA buttons

---

## JavaScript

### ✅ Appropriate .js file(s) has been created and used
**How I did it:** 
I created separate JavaScript modules:

| File | Purpose |
|------|---------|
| `js/app.js` | Main entry point that imports other modules |
| `js/validation.js` | Form validation logic |
| `js/delete-confirm.js` | Delete confirmation dialog logic |

These are bundled by Vite and loaded via `@vite(['resources/js/app.js'])` in the layout.

### ✅ Form validation blocks the form submission when resource data is invalid
**How I did it:** 
In `validation.js`, I add a submit event listener to all forms with class `.form`. When submitted, it validates all inputs. If any validation fails, `event.preventDefault()` is called to stop the form from submitting. The form only submits when all fields pass validation.

### ✅ When the form is invalid, it displays a proper error message
**How I did it:** 
The `showError()` function creates a `<span class="form__error">` element below the invalid input with a specific message explaining the problem (e.g., "This field is required", "Please enter a valid URL", "Guide must be at least 10 characters"). Each field has appropriate validation rules checked in `validateField()`.

### ✅ Error messages have a proper styling (e.g., red marks indicating errors)
**How I did it:** 
Invalid inputs get the class `.form__input--error` which adds a red border (`border-color: #dc2626`). The error message text is also red and displayed below the input. These styles are defined in `_forms.css` under the "Validation Error States" section.

### ✅ The delete button deletes the appropriate list element and nothing else
**How I did it:** 
Each delete button has `data-delete-id` attribute with the repository's ID. When clicked, `delete-confirm.js` reads this ID and creates a form that submits a DELETE request to `/repositories/{id}`. The route handler (`routes/repositories.php`) then deletes only that specific repository.

### ✅ When pressing the delete button, a confirmation dialog appears
**How I did it:** 
In `delete-confirm.js`, clicking delete triggers `handleDeleteClick()` which calls `confirm()` - the browser's native confirmation dialog. It shows the repository name in the message: "Are you sure you want to delete 'repo-name'? This action cannot be undone." Only if the user clicks OK does it proceed to call `deleteItem()` and submit the delete request.

---

## Cool Features

### 1. Landing Page
- **What:** A dedicated welcome page with a hero section and call-to-action buttons, separate from the repository index.
- **How:** Created `resources/views/welcome.blade.php` with a `.hero` section containing a title, tagline, and two buttons. The hero is styled in `resources/css/pages/_landing.css` using flexbox to center content. The route `/` points to this page while `/repositories` shows the list.

### 2. Organized Route Files
- **What:** Routes are split into separate files instead of one big `web.php`.
- **How:** Created `routes/repositories.php` for all repository CRUD routes. In `routes/web.php`, I load it using `Route::prefix('repositories')->name('repositories.')->group(base_path('routes/repositories.php'))`. This adds `/repositories` prefix to all URLs and `repositories.` prefix to all route names automatically.

### 3. CSS Custom Properties
- **What:** All colors and sizes are defined as reusable variables.
- **How:** In `resources/css/_variables.css`, I defined variables on `:root` like `--brand-color: #2563eb` and `--text-primary: #111827`. Then in other CSS files, I use `color: var(--brand-color)` instead of hardcoding values. To change the entire color scheme, I only need to edit `_variables.css`.

### 4. BEM CSS Naming
- **What:** CSS classes follow a consistent naming pattern.
- **How:** I used BEM (Block Element Modifier) convention: `.header` is the block, `.header__brand` is an element inside it, `.form__input--error` is a modifier for error state. This prevents CSS conflicts and makes it clear what each class does. See any CSS file for examples.

### 5. Blade Components
- **What:** Header and footer are reusable components, not copy-pasted HTML.
- **How:** Created files in `resources/views/components/` folder. Laravel automatically makes these available as `<x-header />` and `<x-footer />`. I include them once in `layouts/app.blade.php` and every page that extends the layout gets them automatically.

### 6. Extensive Comments
- **What:** All code files have detailed comments explaining what each section does.
- **How:** Every CSS file has section headers with `/* ==== */` separators. Each CSS rule has a comment above it explaining its purpose. JavaScript functions have JSDoc-style comments. Blade files have `{{-- --}}` comments at the top explaining the file's purpose and usage.

### 7. Real-time Validation
- **What:** Form inputs show errors immediately when you leave a field, not just when you submit.
- **How:** In `resources/js/validation.js`, I added blur event listeners to each input: `input.addEventListener('blur', () => validateField(input))`. When you tab out of a field, it validates immediately and shows/clears the error message.

### 8. Sticky Footer
- **What:** The footer stays at the bottom of the viewport even when page content is short.
- **How:** In `resources/css/_base.css`, I set `body { min-height: 100vh; display: flex; flex-direction: column; }` and `main { flex: 1; }`. This makes the body a flex container, and `flex: 1` on main makes it grow to fill available space, pushing the footer down.
