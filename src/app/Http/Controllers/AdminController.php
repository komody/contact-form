<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function index(Request $request)
  {
    // ページネーション: 7件
    $contacts = Contact::with('category')
      ->orderBy('created_at', 'desc')
      ->paginate(7);

    return view('admin', compact('contacts'));
  }

  public function destroy($id)
  {
    $contact = Contact::findOrFail($id);
    $contact->delete();

    return redirect('/admin')->with('success', 'お問い合わせを削除しました');
  }
}
