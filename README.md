# Laravel Modular Skeleton

A **lightweight modular system for Laravel** that helps you organize your application into independent, self-contained modules. Each module has its own `Controllers`, `Models`, `Providers`, `Views`, and `Routes`, making large-scale apps easier to manage, scale, and maintain.  

---

## ✨ Features

- ✅ **Generate modules easily** with folder structure (`Controllers`, `Models`, `Providers`, `Views`, `Routes`).  
- ✅ **Automatic autoloading** via `Modules\\` namespace.  
- ✅ **Per-module ServiceProvider bootstrapping**.  
- ✅ `php artisan make:module {name} --api` → API-only module (controller + `routes/api.php`).  
- ✅ **Migration file generation** per module.  
- ✅ `php artisan module:list` → Show all modules in `/modules` with status.  
- ✅ `php artisan module:remove {name}` → Safely remove modules.  

---

## 📦 Installation

1. Install via Composer (if you package this later):  
   ```bash
   composer require your-vendor/laravel-modular-skeleton
   ```

   Or if it’s internal:  
   - Copy the package into your Laravel project.  
   - Add the service provider in `config/app.php` if not auto-discovered.  

2. Publish configuration (optional):  
   ```bash
   php artisan vendor:publish --tag=modules-config
   ```

---

## 🚀 Usage

### Create a Module
```bash
php artisan make:module Blog
```
This will generate:
```
/modules
  └── Blog
      ├── Controllers
      ├── Models
      ├── Providers
      ├── Routes
      └── Views
```

### Create an API-Only Module
```bash
php artisan make:module Blog --api
```
Generates only:
```
/modules/Blog
  ├── Controllers
  └── Routes/api.php
```

### Generate a Migration
Inside a module, run:
```bash
php artisan make:migration create_posts_table --module=Blog
```

### List All Modules
```bash
php artisan module:list
```
Displays available modules with status.

### Remove a Module
```bash
php artisan module:remove Blog
```

---

## 🗂 Folder Structure

```
/modules
  └── {ModuleName}
      ├── Controllers
      ├── Models
      ├── Providers
      ├── Routes
      │   ├── web.php
      │   └── api.php
      └── Views
```

---

## 🔧 Example

Creating a module named `Blog`:
```bash
php artisan make:module Blog
```

Then register routes in `/modules/Blog/Routes/web.php`:
```php
Route::get('/blog', [\Modules\Blog\Controllers\BlogController::class, 'index']);
```

And define the controller in `/modules/Blog/Controllers/BlogController.php`:
```php
namespace Modules\Blog\Controllers;

use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        return view('Blog::index');
    }
}
```

---

## 📌 Roadmap
- Add module publishing (config, assets).  
- Module enable/disable support.  
- Module testing scaffolding.  

---

## 📝 License
This project is open-sourced under the [MIT license](LICENSE).  
