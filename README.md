![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20)
![Code Coverage: 100%](https://img.shields.io/badge/Code%20Coverage-100%25-green "Code Coverage: 100%")
![SemVer](https://img.shields.io/badge/SemVer-0.1.0-blue)

![Warden](https://raw.githubusercontent.com/stevie-mccomb/warden/trunk/assets/warden-logo.webp "Warden")

## Capabilities-based security system for Laravel.

Easily generate a set of roles and capabilities from a config file and then assign them to your users via database relations. Useful for building web apps that support custom roles that are editable via UI.

## Installation

```
composer require stevie-mccomb/warden
```

## Publish Assets

```
php artisan vendor:publish --tag=warden
```

This will publish the built-in migrations and config file. The files that will be published are:
```php
// Migrations
database/migrations/{date}_create_capabilities_table.php
database/migrations/{date}_create_roles_tables.php
database/migrations/{date}_create_capability_capability_tables.php
database/migrations/{date}_create_capability_role_tables.php

// Config
config/warden.php
```

## Quick-start / Basic Usage

1. Run the built-in migrations.

```php
php artisan migrate
```

2. Configure your capabilities and roles.

```php
// config/warden.php

'capabilities' => [
    [ 1, 'Manage Users', 'manage-users' ],
    [ 2, 'Create Posts', 'create-posts' ],
    [ 3, 'Publish Posts', 'publish-posts' ],
    [ 4, 'Archive Posts', 'archive-posts' ],
],

'roles' => [
    [ 1, 'Administrator', 'admin' ],
    [ 2, 'Editor', 'editor' ],
],

'capability_role_map' => [
    'admin' => '*', // grant all capabilities
    'editor' => [ // grant specific capabilities
        'create-posts',
        'publish-posts',
        'archive-posts',
    ],
],
```

3. Update your database with your configured capabilities/roles.

```
php artisan warden:update
```

4. Add the `Stevie\Warden\HasRoles` trait to your `App\Models\User` model.

```php
use Stevie\Warden\Traits\HasRoles;

class User
{
    use HasRoles;
}
```

5. Assign roles to your users.

```php
use Stevie\Warden\Models\Role;

$user->roles()->sync(
    Role::where('slug', 'editor')->pluck('id')
);
```

6. Check user capabilities.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController
{
    public function publish(Request $request)
    {
        // Using a gate:
        if (!Gate::allows('publish-posts')) {
            abort(403);
        }

        // Using Laravel's user capability method:
        if (!$request->user()->can('publish-posts')) {
            abort(403);
        }

        // Or manually, if complex logic is needed:
        if (!$request->user()->capabilities()->where('slug', 'publish-posts')->exists()) {
            abort(403);
        }

        // ...
    }
}
```

> Note: Warden is not a replacement for Laravel's [Policies](https://laravel.com/docs/authorization#creating-policies) and is instead meant to be used alongside them. You can use Warden's global security [Gate](https://laravel.com/docs/authorization#gates) by itself, or inside of your [Policy](https://laravel.com/docs/authorization#creating-policies) methods. Check the [usage examples](#usage-examples) section for examples of both use-cases.

## Configuration

```
php artisan vendor:publish --tag=warden
```

This will publish a config file at `config/warden.php` that can be edited to control the behavior of Warden. The starter config file contains the following fields:

### Capabilities

You can define the list of capabilities that you would like to grant to your application's users. Warden provides a global security [Gate](https://laravel.com/docs/authorization#gates) that can be used to check if an authenticated user has access to perform some action.

### Roles

Use this option to define the roles that act as a wrapper around a group of capabilities. This role-based system is the heart of Warden, and makes it easy to group a set of capabilities together and assign them to users en masse. It also makes it easy to update a given group of users' capabilities later by simply updating their role when new capabilities are created.

### Capability/Role Map

This config option tracks the relationship between capabilities and roles. Use this to determine which roles grant which capabilities to your users.

### Capability Dependency Map

This is an optional feature that allows you to assign capabilities as dependencies for other capabilities. While this doesn't do anything on the surface in a default installation, you can use this feature within your own UI or back-end code to control the granting of necessary capabilities that may depend on each other.

For example, let's say your application has a dedicated admin panel where all administrative pages live. One of the administrative operations of your application may be user management. You may have a `view-admin` capability and a `manage-users` capability, but you can't manage users without accessing the admin area, so it makes sense for `manage-users` to depend on `view-admin` and for your application to automatically grant that dependency when `manage-users` is granted.

If you want to make use of this feature when syncing capabilities to roles in your own logic, there is a helper method called `syncWithDependencies` on the relation returned from `Role::capabilities` which makes it easy to sync capabilities *and* their dependencies without having all of the dependencies selected in the UI, though it's recommended you still select the dependencies in the UI for a better UX and to avoid user confusion. See example below for usage of `syncWithDependencies`:

```php
// config/warden.php
'capability_dependency_map' => [
    'update-posts' => [
        'index-posts',
    ],
],

---

// Controller
use Stevie\Warden\Models\Capability;
use Stevie\Warden\Models\Role;

$capabilities = Capability::where('slug', 'update-posts')->value('id');

$role = Role::where('slug', 'example-role')->first();

$role->capabilities()->syncWithDependencies($capabilities);

dd($role->capabilities->toArray()); // [ 'view-posts', 'update-posts' ]
```

### Database Tables

You can change the names of Warden's default tables, if desired. The keys of this array should not be changed as those are Warden's internal identifiers for the tables; the values are the table names.

### Class Map

If you want to override or replace a subset of functionality within the package, you can replace one of the class paths in this class map with your own class. At runtime, Warden will use the config file class map for determining which classes should be handling its features.

### Broadcasting Channels

The two built-in Laravel [Models](https://laravel.com/docs/eloquent) (Capability and Role) utilize all of the [model events](https://laravel.com/docs/11.x/eloquent#events) defined in Laravel's documentation and automatically dispatch these events whenever the models are retrieved, updated, deleted, etc. You can make use of these events in two ways:

1. Bind your own [Listeners](https://laravel.com/docs/events#defining-listeners) to them.
2. Listen for them on the front-end with [Laravel Echo](https://laravel.com/docs/broadcasting).

In the event that you would like to utilize [Laravel Echo](https://laravel.com/docs/broadcasting), you can configure this option to use whatever broadcast channel your application prefers.

For a full list of events that your app can listen for, check the package's [src/Events](https://github.com/stevie-mccomb/warden/tree/trunk/src/Events) directory.

## Usage Examples

- [Assigning roles to your users](#assigning-roles-to-your-users)
- [Checking user capabilities with a gate](#checking-user-capabilities-with-a-gate)
- [Checking user capabilities via Laravel can method](#checking-user-capabilities-via-laravel-can-method)
- [Checking user capabilities manually](#checking-user-capabilities-manually)
- [Checking user capabilities inside a policy](#checking-user-capabilities-inside-a-policy)

### Assigning roles to your users

```php
use Illuminate\Http\Request;

class ExampleController
{
    public function updateUserRoles(Request $request, User $user)
    {
        $rolesTable = config('warden.tables.roles');

        $safe = $request->validate([
            'roles' => 'required|array',
            'roles.*' => "required|exists:$rolesTable,id",
        ]);

        $user->roles()->sync($safe['roles']);
    }
}
```

### Checking user capabilities with a gate

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExampleController
{
    public function example(Request $request)
    {
        if (!Gate::allows('example-capability')) {
            abort(403);
        }

        // ...
    }
}
```

### Checking user capabilities via Laravel can method

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExampleController
{
    public function example(Request $request)
    {
        if (!$request->user()->can('example-capability')) {
            abort(403);
        }

        // ...
    }
}
```

### Checking user capabilities manually

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExampleController
{
    public function example(Request $request)
    {
        if (
            !$request->user()->capabilities()
                ->where('slug', 'example-capability')
                ->exists()
        ) {
            abort(403);
        }

        // ...
    }
}
```

### Checking user capabilities inside a policy

```php
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PostPolicy
{
    public function publish(User $user, Post $post): bool
    {
        return $user->can('publish-posts') && $user->is($post->user);
    }
}
```

### Allow user-defined roles in your application

First, override the built-in role seeders to prevent user-defined roles from being overwritten in future database updates. You will need to define your own logic for how you want to seed these tables (or even just make empty seeders that do nothing if you don't plan on having any built-in roles).

```php
// config/warden.php

'class_map' => [
    'seeders' => [
        // ...
        'roles' => \Database\Seeders\RolesTableSeeder::class,
        'capability_role' => \Database\Seeders\CapabilityRoleTableSeeder::class,
    ],

    // ...
],
```

A common approach is to have reserved role names like "Super Administrator" or "Client Administrator" which you seed in your back-end and have special logic within the application, while allowing some users (maybe those Client Administrators, for example) to define their own roles.

This way, your seeders can seed your application's reserved roles while your users can create their own roles and they can both co-exist while using Warden's logic internally. See example custom seeder below.

```php
use Illuminate\Support\Database\Schema;

class ExampleRolesTableSeeder
{
    public function run()
    {
        $roles = config('warden.tables.roles');

        DB::statement("
            INSERT INTO
                $roles (`name`, `slug`)
            VALUES
                (1, 'Super Administrator', 'super-administrator'),
                (2, 'Client Administrator', 'client-administrator')
            ON DUPLICATE KEY UPDATE
                `name` = VALUES(`name`),
                `slug` = VALUES(`slug`),
                `updated_at` = CURRENT_TIMESTAMP;
        ");
    }
}
```

In the example above, these two roles with ID 1 and 2 can be added into your application early on, before users can define their own roles and they'll be seeded when you run the `artisan warden:update` command. If you wanted to prevent users from defining their own roles with the same names, you could do that in your validation logic.

Next, create routes and controllers for defining roles and attaching them to your users.

```php
// e.g. POST /admin/roles
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stevie\Warden\Models\Role;

class RoleController
{
    /**
     * Create a new role with the given capabilities
     * and attach it to the given users.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the input.
        $capabilitiesTable = config('warden.tables.capabilities');
        $rolesTable = config('warden.tables.roles');
        $safe = $request->validate([
            'name' => "required|string|max:255|unique:$rolesTable",
            'slug' => "required|string|max:255|unique:$rolesTable",
            'capabilities' => 'nullable|array',
            'capabilities.*' => "required|integer|exists:$capabilitiesTable",
            'users' => 'nullable|array',
            'users.*' => 'required|integer|exists:users',
        ]);

        // Create the role.
        $role = Role::create([
            'name' => $safe['name'],
            'slug' => $safe['slug'],
        ]);

        // Attach the capabilities to the role.
        $role->capabilities()->sync($safe['capabilities']);

        // Attach the role to the given users.
        $role->users()->sync($safe['users']);

        // Redirect.
        return to_route('roles.index')
            ->with('success', 'Role successfully created.');
    }
}
```
