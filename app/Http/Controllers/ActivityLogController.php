<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Knowledge;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        // hanya super_admin yang boleh mengakses (pastikan RoleMiddleware FQCN terdaftar)
        $this->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':super_admin']);
    }

    /**
     * Menampilkan daftar log dengan filter.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter berdasarkan user (performed_by)
        if ($request->filled('user_id')) {
            $query->where('performed_by', $request->input('user_id'));
        }

        // Filter berdasarkan action (partial match)
        if ($request->filled('action')) {
            $query->where('action', 'LIKE', '%' . $request->input('action') . '%');
        }

        // Pencarian teks pada meta (json string) atau action
        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($q) use ($term) {
                $q->where('action', 'LIKE', "%{$term}%")
                  ->orWhere('meta', 'LIKE', "%{$term}%");
            });
        }

        // Filter rentang tanggal: date_from, date_to (format YYYY-MM-DD)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Single date filter (kompatibilitas)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // === NEW: Filter berdasarkan role aktor (mis. verifikator) ===
        if ($request->filled('actor_role')) {
            $role = $request->input('actor_role');
            $query->whereHas('user', function ($q) use ($role) {
                $q->where('role', $role);
            });
        }

        // Pagination size (default 20)
        $perPage = (int) $request->input('per_page', 20);

        $logs = $query->paginate($perPage)->withQueryString();

        // Format meta agar ramah user dan tambahkan properti meta_human
        foreach ($logs as $log) {
            $log->meta_human = $this->formatMeta($log);
        }

        // Untuk dropdown filter user
        $users = User::orderBy('name')->get();

        // Ringkasan counts (opsional)
        $totalCount = ActivityLog::count();
        $verifikatorCount = ActivityLog::whereHas('user', function ($q) {
            $q->where('role', 'verifikator');
        })->count();

        // Kirimkan juga filters agar view bisa menampilkan kembali nilai input
        $filters = $request->only(['user_id', 'action', 'q', 'date', 'date_from', 'date_to', 'per_page', 'actor_role']);

        return view('activity_logs.index', compact('logs', 'users', 'filters', 'totalCount', 'verifikatorCount'));
    }

    /**
     * Menampilkan detail log.
     */
    public function show(ActivityLog $activityLog)
    {
        // Hanya super_admin bisa akses karena constructor middleware sudah mengamankan controller.
        // Tambahkan meta_human juga untuk tampilan detail
        $activityLog->meta_human = $this->formatMeta($activityLog);
        return view('activity_logs.show', compact('activityLog'));
    }

    /**
     * Format meta JSON menjadi string yang mudah dimengerti.
     * Mengembalikan teks Bahasa Indonesia; jika meta berisi 'id' untuk Knowledge,
     * akan mencoba mengambil judul Knowledge (jika ada).
     */
    private function formatMeta(ActivityLog $log): string
    {
        $metaRaw = $log->meta ?? '';
        $meta = json_decode($metaRaw, true);
        if (!is_array($meta)) {
            // fallback: tampilkan raw string (atau '-' jika kosong)
            return $metaRaw ?: '-';
        }

        // Helper untuk mengambil judul knowledge berdasarkan id (jika ada)
        $tryGetKnowledgeLabel = function ($id) {
            if (! $id) return "#{$id}";
            $k = Knowledge::find($id);
            if (! $k) return "#{$id}";
            $title = strip_tags($k->title);
            $short = \Illuminate\Support\Str::limit($title, 60);
            return "#{$id} — {$short}";
        };

        // Buat human readable per action umum
        switch ($log->action) {
            case 'create_knowledge':
                return "Membuat Knowledge " . ($meta['id'] ? $tryGetKnowledgeLabel($meta['id']) : ('#' . ($meta['id'] ?? '?')));

            case 'update_knowledge':
                return "Memperbarui Knowledge " . ($meta['id'] ? $tryGetKnowledgeLabel($meta['id']) : ('#' . ($meta['id'] ?? '?')));

            case 'delete_knowledge':
                return "Menghapus Knowledge " . ($meta['id'] ? $tryGetKnowledgeLabel($meta['id']) : ('#' . ($meta['id'] ?? '?')));

            case 'verify_knowledge':
                return "Memverifikasi Knowledge " . ($meta['id'] ? $tryGetKnowledgeLabel($meta['id']) : ('#' . ($meta['id'] ?? '?')));

            case 'publish_knowledge':
                $label = $meta['id'] ? $tryGetKnowledgeLabel($meta['id']) : ('#' . ($meta['id'] ?? '?'));
                $vis = $meta['visibility'] ?? 'public';
                return "Mempublikasikan {$label} (visibility: {$vis})";

            case 'toggle_visibility':
                $from = $meta['from'] ?? '?';
                $to = $meta['to'] ?? '?';
                $idPart = isset($meta['id']) ? (' pada ' . ($meta['id'] ? $tryGetKnowledgeLabel($meta['id']) : ('#' . $meta['id']))) : '';
                return "Mengubah visibility dari " . strtoupper($from) . " → " . strtoupper($to) . $idPart;

            default:
                // Jika ada field 'id' dan action tidak dikenali, tunjukkan label Knowledge jika mungkin
                if (isset($meta['id'])) {
                    return "Aktivitas terkait Knowledge " . $tryGetKnowledgeLabel($meta['id']);
                }
                // Format kunci=>nilai menjadi kalimat singkat
                $parts = [];
                foreach ($meta as $k => $v) {
                    if (is_scalar($v)) {
                        $parts[] = "{$k}: {$v}";
                    } else {
                        $parts[] = "{$k}: " . json_encode($v);
                    }
                }
                return $parts ? implode(' · ', $parts) : '-';
        }
    }
}
