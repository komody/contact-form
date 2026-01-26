<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:8'],
            'last_name' => ['required', 'string', 'max:8'],
            'gender' => ['required', 'integer', 'in:1,2,3'],
            'email' => ['required', 'email'],
            'tel1' => ['required', 'string', 'max:5', 'regex:/^[0-9]+$/'],
            'tel2' => ['required', 'string', 'max:5', 'regex:/^[0-9]+$/'],
            'tel3' => ['required', 'string', 'max:5', 'regex:/^[0-9]+$/'],
            'address' => ['required', 'string'],
            'building' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'detail' => ['required', 'string', 'max:120'],
        ], [
            'first_name.required' => 'お名前（姓）は必須です。',
            'first_name.max' => 'お名前（姓）は8文字以内で入力してください。',
            'last_name.required' => 'お名前（名）は必須です。',
            'last_name.max' => 'お名前（名）は8文字以内で入力してください。',
            'gender.required' => '性別は必須です。',
            'gender.in' => '性別を正しく選択してください。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => 'メールアドレスの形式が正しくありません。',
            'tel1.required' => '電話番号は必須です。',
            'tel1.regex' => '電話番号は半角数字で入力してください。',
            'tel1.max' => '電話番号は5桁以内で入力してください。',
            'tel2.required' => '電話番号は必須です。',
            'tel2.regex' => '電話番号は半角数字で入力してください。',
            'tel2.max' => '電話番号は5桁以内で入力してください。',
            'tel3.required' => '電話番号は必須です。',
            'tel3.regex' => '電話番号は半角数字で入力してください。',
            'tel3.max' => '電話番号は5桁以内で入力してください。',
            'address.required' => '住所は必須です。',
            'category_id.required' => 'お問い合わせの種類は必須です。',
            'category_id.exists' => 'お問い合わせの種類を正しく選択してください。',
            'detail.required' => 'お問い合わせ内容は必須です。',
            'detail.max' => 'お問い合わせ内容は120文字以内で入力してください。',
        ]);

        // お名前の合計文字数チェック（姓と名を合わせて8文字以内）
        $fullNameLength = mb_strlen($validated['first_name'] . $validated['last_name']);
        if ($fullNameLength > 8) {
            return back()->withErrors([
                'first_name' => 'お名前は姓と名を合わせて8文字以内で入力してください。',
            ])->withInput();
        }

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
