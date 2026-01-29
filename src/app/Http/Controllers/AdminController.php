<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * 検索条件をクエリに適用
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function applySearchFilters($query, Request $request)
    {
        // 名前/メールアドレス検索
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // 性別検索
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        // お問い合わせの種類検索
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 日付検索（created_atで完全一致）
        if ($request->filled('date')) {
            $date = $request->input('date');
            $startOfDay = Carbon::parse($date)->startOfDay();
            $endOfDay = Carbon::parse($date)->endOfDay();
            $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
        }
    }

    public function index(Request $request)
    {
        $query = Contact::with('category');

        $this->applySearchFilters($query, $request);

        // ページネーション: 7件（検索条件を保持）
        $contacts = $query->orderBy('created_at', 'desc')
            ->paginate(7)
            ->appends($request->query());

        // カテゴリー一覧を取得（検索フォーム用）
        $categories = Category::all();

        return view('admin', compact('contacts', 'categories'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect('/admin')->with('success', 'お問い合わせを削除しました');
    }

    public function export(Request $request)
    {
        $query = Contact::with('category');

        $this->applySearchFilters($query, $request);

        // 検索条件に一致する全件を取得
        $contacts = $query->orderBy('created_at', 'desc')->get();

        // ファイル名を生成（contacts_YYYYMMDD_HHMMSS.csv）
        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

        // レスポンスヘッダーを設定
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // CSVをストリーム出力
        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOMを追加（Excel互換性のため）
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSVヘッダー
            fputcsv($file, ['お名前', '性別', 'メールアドレス', 'お問い合わせの種類']);

            // データ行
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->last_name . ' ' . $contact->first_name,
                    $contact->gender_text,
                    $contact->email,
                    $contact->category->content,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
