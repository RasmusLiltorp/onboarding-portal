# Assignment 3

## Summary

This assignment adds authentication and authorization to the CRUD application from Assignment 2. Users can register, log in, and log out. Different features are visible/accessible depending on whether the user is authenticated. A personalization feature (e.g., Wishlist) allows authenticated users to register specific resources.

---

## Task 0: Data and Testing Configuration

- [ ] Create/update migration files for users table
- [ ] Create pivot table migration for user-repository relationship
- [ ] Create user seeder with at least 2 users (password: "password")
- [ ] Ensure at least 4 resources in database via seeder

---

## Task 1: Registration

- [ ] Header contains link to registration page with text "Sign Up"
- [ ] Registration form has fields: name, email, password
- [ ] Submit button has text "Submit"
- [ ] Route name is `registration.create` (GET) and `registration.store` (POST)
- [ ] After registration, user is logged in and redirected to index
- [ ] Header displays the new user's name

---

## Task 2: Log In

- [ ] Header contains link to login page with text "Log In"
- [ ] Login form has fields: email, password
- [ ] Route name is `login` (GET) and `login.store` (POST)
- [ ] After login, user is redirected to index with name in header
- [ ] If credentials are incorrect, show message "Incorrect email or password"

---

## Task 3: Log Out

- [ ] Header contains logout form with button text "Log Out"
- [ ] Form has `dusk="logout"` attribute and uses POST method
- [ ] Route name is `login.destroy`
- [ ] After logout, user is redirected to index page

---

## Task 4: Authorization - View

- [ ] Registration and Login links only visible to guest users
- [ ] Logout button and user name only visible to authenticated users

---

## Task 5: Authorization - Controller

- [ ] Apply `guest` middleware to registration routes (`registration.create`, `registration.store`)
- [ ] Apply `guest` middleware to login routes (`login`, `login.store`)
- [ ] Apply `auth` middleware to logout route (`login.destroy`)

---

## Task 6: Personalization (Wishlist)

- [ ] Add "Add Resource" button on show page (only for authenticated users)
- [ ] Form has `dusk="resource-registration"` attribute and POST method
- [ ] Button action only accessible by authenticated users
- [ ] Add "Registered Resources" link in header (only for authenticated users)
- [ ] Route name is `registered.index`
- [ ] Page displays names of user's registered resources
- [ ] Define belongsToMany relationship in User and Repository models

---

## Files Changed/Created

| File | Purpose |
|------|---------|
| | |
