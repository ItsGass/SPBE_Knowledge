<?php

namespace App\Http\Controllers;

use App\Models\Scope;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScopeController extends Controller
{
    public function __construct()
    {
        // middleware: hanya admin & super_admin yang boleh akses
        $this->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin,super_admin']);
    }

    public function index()
    {
        $scopes = Scope::orderBy('created_at', 'desc')->get();
        return view('scope.index', compact('scopes'));
    }

    public function create()
    {
        return view('scope.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:scopes,name'],
        ]);

        $slug = $this->makeUniqueSlug($request->name);

        Scope::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('scope.index')->with('success', 'Scope berhasil ditambahkan.');
    }

    public function edit(Scope $scope)
    {
        return view('scope.edit', compact('scope'));
    }

    public function update(Request $request, Scope $scope)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:scopes,name,' . $scope->id],
        ]);

        // jika nama berubah, perbarui slug juga (jaga unik)
        if ($request->name !== $scope->name) {
            $slug = $this->makeUniqueSlug($request->name, $scope->id);
        } else {
            $slug = $scope->slug;
        }

        $scope->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('scope.index')->with('success', 'Scope berhasil diperbarui.');
    }

    public function destroy(Scope $scope)
    {
        $scope->delete();
        return redirect()->route('scope.index')->with('success', 'Scope berhasil dihapus.');
    }

    /**
     * Generate a URL-friendly slug and make sure it's unique in scopes table.
     *
     * @param  string  $name
     * @param  int|null $ignoreId  (optional) id to ignore when checking uniqueness (useful on update)
     * @return string
     */
    protected function makeUniqueSlug(string $name, int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (static::slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check whether a slug already exists.
     */
    protected static function slugExists(string $slug, int $ignoreId = null): bool
    {
        $query = Scope::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        return $query->exists();
    }
}
