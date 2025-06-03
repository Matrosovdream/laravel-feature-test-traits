# Laravel Feature Test Trait: `EntityTestable`

This trait provides a convenient way to write standardized **CRUD feature tests** for your Laravel application. It includes reusable helper methods that streamline the testing of record creation, updating, and deletion within your feature test classes.

## ✨ Features

- Simple and consistent API for testing resourceful controllers  
- Automatically tracks and deletes created records between tests  
- Works with Laravel's built-in `TestCase`  
- Flexible route and model configuration  

---

## 📦 Installation

1. Create the trait file at: `tests/Feature/Traits/EntityTestable.php`
2. Copy the full trait code into that file
3. Use it in any of your `Feature` test classes

---

## 🧪 Usage Example

```php
use Tests\Feature\Traits\EntityTestable;

class MyEntityTest extends TestCase
{
    use EntityTestable;

    protected $model;
    protected $user;
    protected $routes = [
        'index' => 'entity.index',
        'store' => 'entity.store',
        'update' => 'entity.update',
        'destroy' => 'entity.destroy',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new \App\Models\Entity();
        $this->user = \App\Models\User::find(1);
    }

    public function test_store_record()
    {
        $this->storeRecordTest(
            $this->getRoute('store'),
            ['new' => ['title' => 'Test', 'description' => 'Demo']],
            true
        );
    }
}
```

---

## 🧰 Provided Methods

| Method | Description |
|--------|-------------|
| `createRecord(array $data)` | Create and track a model instance |
| `updateRecord($id, array $data)` | Update a model by ID |
| `deleteRecord($id)` | Delete a model by ID |
| `deleteAllRecords()` | Delete all created records (used in teardown) |
| `findRecord(array $filter)` | Find the first record matching filter |
| `getRoute(string $key, array $params = [])` | Get route from defined `$routes` |
| `storeRecordTest(string $route, array $values, bool $cleanUp)` | Test creating a record via POST |
| `updateRecordTest(string $createRoute, string $updateRoute, array $values, bool $cleanUp)` | Create and update a record |
| `deleteRecordTest(string $route, array $values, bool $cleanUp)` | Create and delete a record |

---

## ✅ Example Test

```php
public function test_delete_record()
{
    $this->deleteRecordTest(
        $this->routes['destroy'],
        ['new' => ['title' => 'Delete Me', 'message' => 'Remove this']],
        true
    );
}
```

---

## 📄 License

MIT – Use, share, and modify freely.