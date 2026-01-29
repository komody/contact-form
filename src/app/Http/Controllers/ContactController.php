<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactConfirmRequest;
use App\Models\Contact;
use App\Models\Category;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // セッションからデータを取得（修正ボタンから戻ってきた場合）
        $oldData = $request->session()->get('contact_data', []);

        // 電話番号を分割（修正ボタンから戻ってきた場合）
        if (!empty($oldData['tel']) && empty($oldData['tel1'])) {
            $tel = $oldData['tel'];
            $oldData['tel1'] = substr($tel, 0, 3);
            $oldData['tel2'] = substr($tel, 3, 4);
            $oldData['tel3'] = substr($tel, 7);
        }

        $categories = Category::all();
        return view('index', compact('categories', 'oldData'));
    }

    public function confirm(ContactConfirmRequest $request)
    {
        $validated = $request->validated();

        // 電話番号を結合（ハイフンなし）
        $tel = $validated['tel1'] . $validated['tel2'] . $validated['tel3'];

        $contact = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'tel' => $tel,
            'address' => $validated['address'],
            'building' => $validated['building'] ?? null,
            'category_id' => $validated['category_id'],
            'detail' => $validated['detail'],
            // 修正ボタン用に電話番号の各部分も保存
            'tel1' => $validated['tel1'],
            'tel2' => $validated['tel2'],
            'tel3' => $validated['tel3'],
        ];

        // セッションに保存
        $request->session()->put('contact_data', $contact);

        // カテゴリー名を取得
        $category = Category::find($validated['category_id']);

        return view('confirm', compact('contact', 'category'));
    }

    public function store(Request $request)
    {
        // セッションからデータを取得
        $contactData = $request->session()->get('contact_data');

        if (!$contactData) {
            return redirect('/')->with('error', 'セッションが切れています。再度入力してください。');
        }

        // データベースに保存するデータ（tel1, tel2, tel3は除外）
        $saveData = [
            'category_id' => $contactData['category_id'],
            'first_name' => $contactData['first_name'],
            'last_name' => $contactData['last_name'],
            'gender' => $contactData['gender'],
            'email' => $contactData['email'],
            'tel' => $contactData['tel'],
            'address' => $contactData['address'],
            'building' => $contactData['building'] ?? null,
            'detail' => $contactData['detail'],
        ];

        // データベースに保存
        Contact::create($saveData);

        // セッションをクリア
        $request->session()->forget('contact_data');

        return redirect('/thanks');
    }
}
