<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'tel',
        'address',
        'building',
        'detail',
    ];

    /**
     * カテゴリーとのリレーション
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 性別を文字列で取得
     */
    public function getGenderTextAttribute()
    {
        return match($this->gender) {
            1 => '男性',
            2 => '女性',
            3 => 'その他',
            default => '-',
        };
    }
}
