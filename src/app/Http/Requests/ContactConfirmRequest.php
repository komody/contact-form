<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:8'],
            'last_name' => ['required', 'string', 'max:8'],
            'gender' => ['required', 'integer', 'in:1,2,3'],
            'email' => ['required', 'email'],
            'tel1' => ['required', 'string', 'max:5'],
            'tel2' => ['required', 'string', 'max:5'],
            'tel3' => ['required', 'string', 'max:5'],
            'address' => ['required', 'string'],
            'building' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'detail' => ['required', 'string', 'max:120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => '姓を入力してください',
            'first_name.max' => 'お名前（姓）は8文字以内で入力してください。',
            'last_name.required' => '名を入力してください',
            'last_name.max' => 'お名前（名）は8文字以内で入力してください。',
            'gender.required' => '性別を選択してください',
            'gender.in' => '性別を選択してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'tel1.required' => '電話番号を入力してください',
            'tel1.max' => '電話番号は5桁まで数字で入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel2.max' => '電話番号は5桁まで数字で入力してください',
            'tel3.required' => '電話番号を入力してください',
            'tel3.max' => '電話番号は5桁まで数字で入力してください',
            'address.required' => '住所を入力してください',
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.exists' => 'お問い合わせの種類を選択してください',
            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max' => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // 電話番号の全角チェック
            $telFields = ['tel1', 'tel2', 'tel3'];
            foreach ($telFields as $field) {
                $value = $this->input($field);
                if ($value === null || $value === '') {
                    continue;
                }

                // 全角数字または半角数字以外の文字をチェック
                if (preg_match('/[０-９]/u', $value) || !preg_match('/^[0-9]+$/', $value)) {
                    $validator->errors()->add($field, '電話番号は半角英数字で入力してください');
                }
            }

            // お名前の合計文字数チェック（姓と名を合わせて8文字以内）
            $fullNameLength = mb_strlen($this->input('first_name') . $this->input('last_name'));
            if ($fullNameLength > 8) {
                $validator->errors()->add('first_name', 'お名前は姓と名を合わせて8文字以内で入力してください');
            }
        });
    }
}
