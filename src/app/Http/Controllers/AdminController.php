<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    $query = Contact::with('category');

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
      $query->whereDate('created_at', $date);
    }

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
}
