<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Repository Model
 *
 * A Model is the "M" in MVC (Model-View-Controller) architecture.
 * It represents the data layer of the application and is responsible for:
 *
 * 1. Defining the structure of data (which fields exist)
 * 2. Interacting with the database (CRUD operations)
 * 3. Defining relationships between entities (e.g., hasMany, belongsTo)
 * 4. Encapsulating business logic related to the data
 *
 * In Laravel, Models use Eloquent ORM (Object-Relational Mapping) which allows
 * you to interact with the database using PHP objects instead of raw SQL.
 *
 * Each Model corresponds to a database table:
 * - Repository model -> repositories table (Laravel auto-pluralizes)
 *
 * The $fillable property defines which fields can be mass-assigned,
 * protecting against mass-assignment vulnerabilities where attackers
 * could inject unexpected fields into your database.
 */
class Repository extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * Mass assignment is when you pass an array of data to create() or update().
     * Only fields listed here will be saved - others are ignored for security.
     *
     * Example: Repository::create(['name' => 'foo', 'admin' => true])
     * If 'admin' is not in $fillable, it won't be saved even if passed.
     *
     * @var array<string>
     */
    protected $fillable = [
        "name", // Repository name (e.g., "laravel-app")
        "url", // Repository URL (e.g., "https://github.com/...")
        "description", // Short description of the repository
        "guide", // Onboarding guide content (markdown/text)
    ];
}
