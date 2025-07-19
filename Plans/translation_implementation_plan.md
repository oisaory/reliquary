# Translation Implementation Plan for Reliquary Application

This document outlines the detailed plan for implementing translation functionality in the Reliquary application. The plan is organized by controller, with specific steps for each controller and its associated templates.

The application will support two languages:
- English (en): Fully implemented with complete translations
- Portuguese Brazil (pt_BR): Initially stubbed with English text, to be translated in the future

## General Setup

Before implementing translations for specific controllers, we need to set up the translation infrastructure:

1. **Create Translation Directory Structure**
   ```bash
   mkdir -p translations
   ```

2. **Create Base Translation Files**
   - Create `translations/messages.en.yaml` (English - default)
   - Create `translations/messages.pt_BR.yaml` (Portuguese Brazil - stubbed)

3. **Add Locale Switcher Component**
   - Create a reusable Twig partial: `templates/_locale_switcher.html.twig`
   - Include it in the base template: `templates/base.html.twig`

4. **Create Locale Controller**
   - Create `src/Controller/LocaleController.php` to handle locale switching
   - Add route for changing locale: `/change-locale/{locale}`
   - Configure controller to support only English (en) and Portuguese Brazil (pt_BR)

5. **Update Configuration**
   - Ensure `config/packages/translation.yaml` is properly configured
   - Update `config/services.yaml` if needed for locale-related services

## Controller-Specific Implementation Plans

### 1. HomeController

#### Files to Update:
- `src/Controller/HomeController.php`
- `templates/home/index.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
home:
  title: 'Reliquary'
  welcome: 'Welcome to Reliquary'
  description: 'Manage your sacred relics collection'
  get_started: 'Get Started'
  features:
    title: 'Features'
    relic_management: 'Relic Management'
    saint_database: 'Saint Database'
    geolocation: 'Geolocation'
```

#### Implementation Steps:
1. Update template to use translation keys:
   ```twig
   <h1>{{ 'home.title'|trans }}</h1>
   <p>{{ 'home.description'|trans }}</p>
   ```

2. Update controller to pass locale information if needed

### 2. RelicController

#### Files to Update:
- `src/Controller/RelicController.php`
- `templates/relic/index.html.twig`
- `templates/relic/_header.html.twig`
- `templates/relic/_relic_list_desktop.html.twig`
- `templates/relic/_relic_list_mobile.html.twig`
- `templates/relic/new.html.twig`
- `templates/relic/edit.html.twig`
- `templates/relic/show.html.twig`
- `templates/relic/_form.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
relic:
  title: 'Relics'
  my_relics: 'My Relics'
  description: 'Manage your sacred relics collection'
  add_new: 'Add New Relic'
  no_relics: 'No relics found. Click "Add New Relic" to create one.'
  edit_title: 'Edit Relic'
  new_title: 'New Relic'
  show_title: 'Relic Details'
  table:
    saint: 'Saint'
    address: 'Address'
    location: 'Specific Location'
    actions: 'Actions'
  form:
    saint: 'Saint'
    address: 'Address'
    location: 'Specific Location'
    submit: 'Save'
  actions:
    view: 'View'
    edit: 'Edit'
    delete: 'Delete'
    back: 'Back to list'
  messages:
    created: 'Relic created successfully'
    updated: 'Relic updated successfully'
    deleted: 'Relic deleted successfully'
```

#### Implementation Steps:
1. Update templates to use translation keys:
   ```twig
   <!-- In _header.html.twig -->
   <h1 class="display-5 fw-bold">{{ title is defined ? title|trans : 'relic.title'|trans }}</h1>
   <p class="text-muted">{{ 'relic.description'|trans }}</p>
   <a href="{{ path('app_relic_new') }}" class="btn btn-primary">
       <svg class="bi me-2" width="16" height="16">
           <use href="#plus-circle"></use>
       </svg>
       {{ 'relic.add_new'|trans }}
   </a>
   ```

2. Update controller flash messages:
   ```php
   $this->addFlash('success', $translator->trans('relic.messages.created'));
   ```

### 3. SaintController

#### Files to Update:
- `src/Controller/SaintController.php`
- `templates/saint/index.html.twig`
- `templates/saint/new.html.twig`
- `templates/saint/edit.html.twig`
- `templates/saint/show.html.twig`
- `templates/saint/_form.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
saint:
  title: 'Saints'
  description: 'Browse and manage saints in the database'
  add_new: 'Add New Saint'
  no_saints: 'No saints found. Click "Add New Saint" to create one.'
  edit_title: 'Edit Saint'
  new_title: 'New Saint'
  show_title: 'Saint Details'
  table:
    name: 'Name'
    feast_day: 'Feast Day'
    actions: 'Actions'
  form:
    name: 'Name'
    feast_day: 'Feast Day'
    description: 'Description'
    submit: 'Save'
  actions:
    view: 'View'
    edit: 'Edit'
    delete: 'Delete'
    back: 'Back to list'
  messages:
    created: 'Saint created successfully'
    updated: 'Saint updated successfully'
    deleted: 'Saint deleted successfully'
```

#### Implementation Steps:
1. Update templates to use translation keys
2. Update controller flash messages

### 4. SecurityController

#### Files to Update:
- `src/Controller/SecurityController.php`
- `templates/security/login.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
security:
  login:
    title: 'Login'
    username: 'Email'
    password: 'Password'
    submit: 'Sign in'
    remember_me: 'Remember me'
    forgot_password: 'Forgot your password?'
  logout: 'Logout'
  error:
    invalid_credentials: 'Invalid credentials'
```

#### Implementation Steps:
1. Update login template to use translation keys
2. Update security messages in controller

### 5. RegistrationController

#### Files to Update:
- `src/Controller/RegistrationController.php`
- `templates/registration/register.html.twig`
- `templates/registration/confirmation_email.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
registration:
  title: 'Register'
  form:
    email: 'Email'
    password: 'Password'
    agree_terms: 'I agree to the terms'
    submit: 'Register'
  email:
    subject: 'Please Confirm your Email'
    confirm: 'Confirm my Email'
    hello: 'Hello'
    message: 'Please confirm your email address by clicking the following link'
  messages:
    check_email: 'A confirmation email has been sent to your email address. Please check your inbox and confirm your registration.'
    email_confirmed: 'Your email address has been verified.'
    already_verified: 'Your email address has already been verified.'
```

#### Implementation Steps:
1. Update registration templates to use translation keys
2. Update email templates to use translation keys
3. Update controller flash messages

### 6. UserController

#### Files to Update:
- `src/Controller/UserController.php`
- `templates/user/index.html.twig`
- `templates/user/new.html.twig`
- `templates/user/edit.html.twig`
- `templates/user/show.html.twig`
- `templates/user/_form.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
user:
  title: 'Users'
  description: 'Manage users'
  add_new: 'Add New User'
  no_users: 'No users found.'
  edit_title: 'Edit User'
  new_title: 'New User'
  show_title: 'User Details'
  table:
    email: 'Email'
    roles: 'Roles'
    verified: 'Verified'
    actions: 'Actions'
  form:
    email: 'Email'
    password: 'Password'
    roles: 'Roles'
    submit: 'Save'
  actions:
    view: 'View'
    edit: 'Edit'
    delete: 'Delete'
    back: 'Back to list'
  messages:
    created: 'User created successfully'
    updated: 'User updated successfully'
    deleted: 'User deleted successfully'
```

#### Implementation Steps:
1. Update templates to use translation keys
2. Update controller flash messages

### 7. LogController

#### Files to Update:
- `src/Controller/LogController.php`
- `templates/log/index.html.twig`
- `templates/log/show.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
log:
  title: 'Logs'
  description: 'System activity logs'
  no_logs: 'No logs found.'
  show_title: 'Log Details'
  table:
    action: 'Action'
    entity: 'Entity'
    entity_id: 'Entity ID'
    user: 'User'
    timestamp: 'Timestamp'
    actions: 'Actions'
  actions:
    view: 'View'
    back: 'Back to list'
```

#### Implementation Steps:
1. Update templates to use translation keys

### 8. GeolocationController

#### Files to Update:
- `src/Controller/GeolocationController.php`
- `templates/geolocation/map.html.twig`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
geolocation:
  title: 'Relic Map'
  description: 'View relics on a world map'
  allow_location: 'Allow location access to center the map on your position'
  denied_location: 'Location access denied. Using default map center.'
  loading: 'Loading map...'
```

#### Implementation Steps:
1. Update templates to use translation keys
2. Update JavaScript messages for geolocation

### 9. AddressAutocompleteController

#### Files to Update:
- `src/Controller/AddressAutocompleteController.php`

#### Translation Keys:
```yaml
# translations/messages.en.yaml
address:
  search: 'Search for an address'
  no_results: 'No results found'
  error: 'Error fetching address suggestions'
```

#### Implementation Steps:
1. Update any JavaScript messages for address autocomplete

## Implementation Timeline

1. **Week 1: Setup and Core Controllers**
   - Set up translation infrastructure
   - Implement English translations for HomeController, SecurityController, and RegistrationController
   - Create stubbed Portuguese Brazil translations

2. **Week 2: Main Feature Controllers**
   - Implement English translations for RelicController and SaintController
   - Update stubbed Portuguese Brazil translations

3. **Week 3: Administrative and Utility Controllers**
   - Implement English translations for UserController, LogController, GeolocationController, and AddressAutocompleteController
   - Complete stubbed Portuguese Brazil translations

4. **Week 4: Testing and Refinement**
   - Test English translations thoroughly
   - Verify that the stubbed Portuguese Brazil translations are complete
   - Refine translation keys and organization
   - Add any missing translations

## Testing Strategy

1. **Manual Testing**
   - Test each page in English to ensure all translations are working correctly
   - Verify that the locale switcher correctly changes between English and Portuguese Brazil
   - Confirm that the stubbed Portuguese Brazil translations appear correctly (even though they're in English)
   - Test locale switching functionality and persistence across page loads

2. **Automated Testing**
   - Update functional tests to work with translations
   - Add specific tests for locale switching between English and Portuguese Brazil
   - Ensure tests pass regardless of the active locale

## Maintenance Considerations

1. **Translation Management**
   - Consider using a translation management system for larger projects
   - Document the process for adding new translation keys
   - Maintain a process for updating the stubbed Portuguese Brazil translations when actual translations become available

2. **New Features**
   - Establish guidelines for adding translations when developing new features
   - Include translation requirements in code review process
   - Ensure all new features include English translations and stubbed Portuguese Brazil translations

3. **Future Language Support**
   - Document the process for adding support for additional languages in the future
   - Prioritize completing the Portuguese Brazil translations before adding new languages
