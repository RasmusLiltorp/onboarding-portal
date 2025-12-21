# Assignment 1

## Summary

This assignment implements a full CRUD application for "Onboardo" - a repository onboarding guide manager. The app allows users to create, view, edit, and delete repositories with their onboarding guides.

### What Was Built

The application has a **header** and **footer** that appear on every page, built as reusable Blade components. The header contains the app name "Onboardo" and navigation links to the home page and create page. The footer displays a dynamic copyright notice.

For the CRUD functionality, I created four pages: an **index page** that lists all repositories with View, Edit, and Delete buttons for each item; a **create page** with a form to add new repositories (with fields for name, URL, description, and onboarding guide); a **show page** that displays all details of a single repository; and an **edit page** with a prefilled form to update existing repositories.

The **CSS** is organized into multiple files by purpose - variables, base layout, component styles, and page-specific styles. All colors are defined as CSS custom properties for consistency. The styling includes a sticky header, card-style list items, form styling with focus states, and a sticky footer that stays at the bottom even with little content.

For **JavaScript**, I implemented client-side form validation that checks required fields, URL format, and minimum length for the guide. Errors are shown in real-time as users fill out the form. I also added a delete confirmation dialog that asks users to confirm before deleting a repository.

**Routes** are defined in `web.php` using explicit route definitions with named routes for best practice URL generation.

### Project Structure

```
portal-app/
├── app/
│   ├── Http/Controllers/
│   │   └── RepositoryController.php   # CRUD operations
│   └── Models/
│       └── Repository.php             # Eloquent model
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php      # Main layout (includes header/footer)
│   │   ├── components/
│   │   │   ├── header.blade.php       # Site header component
│   │   │   └── footer.blade.php       # Site footer component
│   │   └── repositories/
│   │       ├── index.blade.php        # List all repositories
│   │       ├── create.blade.php       # Create form
│   │       ├── show.blade.php         # View single repository
│   │       └── edit.blade.php         # Edit form
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
├── routes/
│   └── web.php                        # All routes with named routes
└── database/
    ├── migrations/                    # Database schema
    └── seeders/                       # Test data
```

---

## Header and Footer

### ✅ Create a header using the correct HTML tags
**How I did it:** 
I created the header as a Laravel component. These components can either be class-based or anonymous. I chose to create it as an anonymous component, since it's a simple header without any logic. Then, I included the component in the main layout file using `<x-header />`. The header uses the semantic `<header>` HTML tag which tells browsers and screen readers this is the page header.

**Files:**
- [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)
- [resources/views/layouts/app.blade.php](../../portal-app/resources/views/layouts/app.blade.php)

### ✅ The header contains links to at least the home page and the create page
**How I did it:** 
I added a `<nav>` element inside the header component with anchor tags linking to the home page and create page. I used Laravel's `route()` helper with named routes (`route('home')` and `route('repositories.create')`) instead of hardcoding URLs. This is best practice because if the URL structure changes, the links won't break - Laravel resolves them from the route names defined in the routes file.

**Files:**
- [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)
- [routes/web.php](../../portal-app/routes/web.php)

### ✅ The header contains other appropriate elements (Like company/app name, a logo, etc.)
**How I did it:** 
I added the app name "Onboardo" as a brand link in the left corner of the header using an anchor tag with class `header__brand`. The brand links back to the home page using `route('home')`. I used flexbox with `justify-content: space-between` on the container to push the brand to the left and navigation to the right - a common header layout pattern.

**File:** [resources/css/components/header.css](../../portal-app/resources/css/components/header.css)

### ✅ Create a footer using the correct HTML tags
**How I did it:** 
I created the footer as an anonymous Blade component, the same approach as the header. I used the semantic `<footer>` HTML tag. The footer is included in the layout using `<x-footer />`.

**File:** [resources/views/components/footer.blade.php](../../portal-app/resources/views/components/footer.blade.php)

### ✅ The footer contains appropriate elements (Like trademark/copyright, made by, contact info, etc.)
**How I did it:** 
The footer displays a copyright notice with the app name. I used `{{ date('Y') }}` to dynamically display the current year, so it automatically updates each year.

**Files:**
- [resources/views/components/footer.blade.php](../../portal-app/resources/views/components/footer.blade.php)
- [resources/css/components/footer.css](../../portal-app/resources/css/components/footer.css)

---

## Index Page

### ✅ The page has a header and a footer
**How I did it:** 
The index page extends the main layout (`@extends('layouts.app')`) which already includes the header and footer components. This means every page that extends this layout automatically gets the header and footer.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ A list of all the elements for a given resource is shown
**How I did it:** 
I created the index view which displays all repositories in an unordered list. The page receives a `$repositories` collection from the controller and loops through it using Blade's `@foreach` directive. Each repository shows its name and description.

**Files:**
- [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)
- [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)
- [resources/css/pages/_repositories.css](../../portal-app/resources/css/pages/_repositories.css)

### ✅ The first element has a link/button to see details of the element
**How I did it:** 
Each repository item (not just the first) has a "View" link that goes to the show page. I used `route('repositories.show', $repository)` to generate the URL with the repository model. This links to `/{id}` which displays the full repository details.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ The first element has a link/button to update the element
**How I did it:** 
Each repository item has an "Edit" link using `route('repositories.edit', $repository)`. This links to `/{id}/edit` which shows a form with the current values prefilled.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ Each element has a link/button to delete the element
**How I did it:** 
Each repository item has a Delete button inside a form. The form uses `@method('DELETE')` for HTTP method spoofing and submits to `route('repositories.destroy', $repository)`.

**Files:**
- [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)
- [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

---

## Create Page

### ✅ The page has a header and a footer
**How I did it:** 
The create page extends `layouts.app` using `@extends('layouts.app')`, which includes the header and footer automatically.

**File:** [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)

### ✅ A form element has been created, with the proper HTTP Method
**How I did it:** 
I created a `<form>` element with `method="POST"` and `action="{{ route('repositories.store') }}"`. POST is the correct HTTP method for creating new resources. The form also includes `@csrf` which generates a hidden CSRF token field - this is required by Laravel for security to prevent cross-site request forgery attacks.

**File:** [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)

### ✅ The form contains the appropriate input fields for the resource data, identified by labels
**How I did it:** 
The form has four fields: name, url, description, and guide. Each input has a `<label>` element with a `for` attribute matching the input's `id`. This associates the label with the input for accessibility - clicking the label focuses the input, and screen readers announce the label when the input is focused.

**File:** [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)

### ✅ Each input field has the appropriate type for the data
**How I did it:** 
I used appropriate HTML5 input types: `type="text"` for name and description, `type="url"` for the repository URL (which validates URL format), and `<textarea>` for the guide content (multi-line text). The URL type provides built-in browser validation for URL format.

**File:** [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)

### ✅ Each input field has an appropriate name
**How I did it:** 
Each input has a `name` attribute that matches the data field: `name="name"`, `name="url"`, `name="description"`, `name="guide"`. These names are used by the server to identify which field the data belongs to when the form is submitted.

**File:** [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)

### ✅ The form has a submit button
**How I did it:** 
I added an `<input type="submit">` at the bottom of the form with the value "Create Repository". Using input instead of button for better compatibility with Dusk tests.

**File:** [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)

---

## Show Page

### ✅ The page has a header and a footer
**How I did it:** 
The show page extends `layouts.app` using `@extends('layouts.app')`.

**File:** [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php)

### ✅ The page shows all the resource information
**How I did it:** 
The show page displays all repository fields: name (as the page heading), URL (as a clickable link with `target="_blank"` to open in new tab), description, and the onboarding guide. Each field has a label and value displayed using the `.repo-details` CSS classes. The guide is displayed in a styled box with `white-space: pre-wrap` to preserve line breaks.

**Files:**
- [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php)
- [resources/css/pages/_repositories.css](../../portal-app/resources/css/pages/_repositories.css)

### ✅ The page has a link to edit the resource
**How I did it:** 
There's an "Edit" button in the header section next to the repository name. It uses `route('repositories.edit', $repository)` to link to the edit page. There's also a "Back to list" link at the bottom using `route('home')` to return to the index page.

**File:** [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php)

---

## Update Page

### ✅ The page has a header and a footer
**How I did it:** 
The edit page extends `layouts.app` using `@extends('layouts.app')`.

**File:** [resources/views/repositories/edit.blade.php](../../portal-app/resources/views/repositories/edit.blade.php)

### ✅ The page displays a similar form from the Create Page, with similar technical characteristics
**How I did it:** 
The edit form has the exact same structure as the create form: same fields (name, url, description, guide), same labels, same input types, same CSS classes. The only differences are: 1) the form action points to the update route using `route('repositories.update', $repository)`, 2) it includes `@method('PUT')` for method spoofing since HTML forms don't support PUT, and 3) the submit button says "Update Repository" instead of "Create Repository".

**File:** [resources/views/repositories/edit.blade.php](../../portal-app/resources/views/repositories/edit.blade.php)

### ✅ The form has the fields prefilled with the correct data
**How I did it:** 
Each input has a `value` attribute set to the current repository data, e.g., `value="{{ $repository->name }}"`. For the textarea, the content goes between the opening and closing tags: `<textarea>{{ $repository->guide }}</textarea>`. The `$repository` variable is passed from the controller using Route Model Binding.

**Files:**
- [resources/views/repositories/edit.blade.php](../../portal-app/resources/views/repositories/edit.blade.php)
- [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

---

## CSS

### ✅ Appropriate .css file(s) has been created and used
**How I did it:** 
I organized CSS into multiple files by purpose:

| File | Purpose |
|------|---------|
| [css/app.css](../../portal-app/resources/css/app.css) | Main entry point that imports all other CSS files |
| [css/_variables.css](../../portal-app/resources/css/_variables.css) | CSS custom properties (colors, sizes) used throughout |
| [css/_base.css](../../portal-app/resources/css/_base.css) | Base layout styles (body flex container for sticky footer) |
| [css/components/header.css](../../portal-app/resources/css/components/header.css) | Header component styles |
| [css/components/footer.css](../../portal-app/resources/css/components/footer.css) | Footer component styles |
| [css/pages/_repositories.css](../../portal-app/resources/css/pages/_repositories.css) | Repository list and show page styles |
| [css/pages/_forms.css](../../portal-app/resources/css/pages/_forms.css) | Form input and validation styles |

All files are imported in `app.css` and compiled by Vite into a single optimized bundle.

### ✅ Header and Footer style is consistent across pages
**How I did it:** 
The header and footer are Blade components included in the layout, so they appear identically on every page. They share CSS variables from `_variables.css` (e.g., `--header-bg`, `--header-border`) ensuring consistent colors. Both use the same `max-width: 1200px` and padding so content aligns.

**File:** [resources/css/_variables.css](../../portal-app/resources/css/_variables.css)

### ✅ Index page has a proper style
**How I did it:** 
The repository list uses `.repo-list` styles. Each item is a flexbox row with the info on the left and action buttons on the right. Items have padding, borders, and rounded corners for a card-like appearance. The action buttons have consistent hover states.

**File:** [resources/css/pages/_repositories.css](../../portal-app/resources/css/pages/_repositories.css)

### ✅ Create/Update page has a proper style
**How I did it:** 
Forms use styles from `_forms.css`. The form has a `max-width: 600px` for readability. Form groups have `margin-bottom: 1.5rem` for spacing. Inputs have consistent padding, borders, and focus states (border turns blue). Labels are bold and positioned above inputs.

**File:** [resources/css/pages/_forms.css](../../portal-app/resources/css/pages/_forms.css)

### ✅ Show page has a proper style
**How I did it:** 
The show page uses `.repo-details` styles. Each detail item has a small gray label above the value. The guide content is displayed in a light gray box with padding. The header uses flexbox to put the title and edit button on the same line.

**File:** [resources/css/pages/_repositories.css](../../portal-app/resources/css/pages/_repositories.css)

### ✅ Colors are consistent across pages
**How I did it:** 
All colors are defined as CSS custom properties:

| Variable | Value | Usage |
|----------|-------|-------|
| `--text-primary` | `#111827` | Main text color |
| `--text-secondary` | `#6b7280` | Muted/secondary text |
| `--brand-color` | `#2563eb` | Blue accent for links and buttons |
| `--header-bg` | `#ffffff` | White backgrounds |
| `--header-border` | `#e5e7eb` | Light gray borders |
| `--hover-bg` | `#f3f4f6` | Hover state background |

These variables are used throughout all CSS files ensuring consistency.

**File:** [resources/css/_variables.css](../../portal-app/resources/css/_variables.css)

### ✅ Extra cool style points!
**How I did it:** 
- **Smooth transitions on hover states** - Added `transition: background-color 0.15s` to buttons and links so color changes animate smoothly instead of snapping
- **Sticky header** - Used `position: sticky; top: 0;` on the header so it stays visible when scrolling down the page
- **Responsive design** - Added `@media (max-width: 768px)` queries in `header.css` to reduce padding and font sizes on mobile screens
- **Focus states on inputs** - Inputs change border color to blue on focus using `.form__input:focus { border-color: var(--brand-color); }` for accessibility

**Files:**
- [resources/css/components/header.css](../../portal-app/resources/css/components/header.css)
- [resources/css/pages/_forms.css](../../portal-app/resources/css/pages/_forms.css)

---

## JavaScript

### ✅ Appropriate .js file(s) has been created and used
**How I did it:** 
I created separate JavaScript modules:

| File | Purpose |
|------|---------|
| [js/app.js](../../portal-app/resources/js/app.js) | Main entry point that imports other modules |
| [js/validation.js](../../portal-app/resources/js/validation.js) | Form validation logic |
| [js/delete-confirm.js](../../portal-app/resources/js/delete-confirm.js) | Delete confirmation dialog logic |

These are bundled by Vite and loaded via `@vite(['resources/js/app.js'])` in the layout.

### ✅ Form validation blocks the form submission when resource data is invalid
**How I did it:** 
I add a submit event listener to all forms with class `.form`. When submitted, it validates all inputs. If any validation fails, `event.preventDefault()` is called to stop the form from submitting. The form only submits when all fields pass validation.

**File:** [resources/js/validation.js](../../portal-app/resources/js/validation.js)

### ✅ When the form is invalid, it displays a proper error message
**How I did it:** 
The `showError()` function creates a `<span class="form__error">` element below the invalid input with a specific message explaining the problem (e.g., "This field is required", "Please enter a valid URL", "Guide must be at least 10 characters"). Each field has appropriate validation rules checked in `validateField()`.

**File:** [resources/js/validation.js](../../portal-app/resources/js/validation.js)

### ✅ Error messages have a proper styling (e.g., red marks indicating errors)
**How I did it:** 
Invalid inputs get the class `.form__input--error` which adds a red border (`border-color: #dc2626`). The error message text is also red and displayed below the input. These styles are defined under the "Validation Error States" section.

**File:** [resources/css/pages/_forms.css](../../portal-app/resources/css/pages/_forms.css)

### ✅ The delete button deletes the appropriate list element and nothing else
**How I did it:** 
Each delete button is inside a form with the action set to `route('repositories.destroy', $repository)`. The form uses `@method('DELETE')` for HTTP method spoofing. The controller's `destroy()` method deletes only that specific repository.

**Files:**
- [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)
- [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ When pressing the delete button, a confirmation dialog appears
**How I did it:** 
Clicking delete triggers `handleDeleteClick()` which calls `confirm()` - the browser's native confirmation dialog. It shows the repository name in the message: "Are you sure you want to delete 'repo-name'? This action cannot be undone." Only if the user clicks OK does it proceed to submit the form.

**File:** [resources/js/delete-confirm.js](../../portal-app/resources/js/delete-confirm.js)

---

## Cool Features

### 1. Named Routes Throughout
- **What:** All URLs are generated using Laravel's `route()` helper with named routes instead of hardcoded strings.
- **How:** Each route in `web.php` has a name (e.g., `->name('home')`, `->name('repositories.create')`). Views use `route('repositories.show', $repository)` to generate URLs. This means if URLs change, no view code needs updating.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### 2. Route Model Binding
- **What:** Controller methods receive actual Eloquent models instead of IDs.
- **How:** By type-hinting `Repository $repository` in controller methods, Laravel automatically fetches the model from the database. If not found, it returns a 404.

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### 3. CSS Custom Properties
- **What:** All colors and sizes are defined as reusable variables.
- **How:** I defined variables on `:root` like `--brand-color: #2563eb` and `--text-primary: #111827`. Then in other CSS files, I use `color: var(--brand-color)` instead of hardcoding values. To change the entire color scheme, I only need to edit one file.

**File:** [resources/css/_variables.css](../../portal-app/resources/css/_variables.css)

### 4. BEM CSS Naming
- **What:** CSS classes follow a consistent naming pattern.
- **How:** I used BEM (Block Element Modifier) convention: `.header` is the block, `.header__brand` is an element inside it, `.form__input--error` is a modifier for error state. This prevents CSS conflicts and makes it clear what each class does.

### 5. Blade Components
- **What:** Header and footer are reusable components, not copy-pasted HTML.
- **How:** Created files in the components folder. Laravel automatically makes these available as `<x-header />` and `<x-footer />`. I include them once in the layout and every page that extends the layout gets them automatically.

**Files:**
- [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)
- [resources/views/components/footer.blade.php](../../portal-app/resources/views/components/footer.blade.php)
- [resources/views/layouts/app.blade.php](../../portal-app/resources/views/layouts/app.blade.php)

### 6. Extensive Comments
- **What:** All code files have detailed comments explaining what each section does.
- **How:** Every CSS file has section headers with `/* ==== */` separators. Each CSS rule has a comment above it explaining its purpose. JavaScript functions have JSDoc-style comments. Blade files have `{{-- --}}` comments at the top explaining the file's purpose and usage. The Controller has a full explanation of what a Controller is in MVC.

### 7. Real-time Validation
- **What:** Form inputs show errors immediately when you leave a field, not just when you submit.
- **How:** I added blur event listeners to each input: `input.addEventListener('blur', () => validateField(input))`. When you tab out of a field, it validates immediately and shows/clears the error message.

**File:** [resources/js/validation.js](../../portal-app/resources/js/validation.js)

### 8. Sticky Footer
- **What:** The footer stays at the bottom of the viewport even when page content is short.
- **How:** I set `body { min-height: 100vh; display: flex; flex-direction: column; }` and `main { flex: 1; }`. This makes the body a flex container, and `flex: 1` on main makes it grow to fill available space, pushing the footer down.

**File:** [resources/css/_base.css](../../portal-app/resources/css/_base.css)
