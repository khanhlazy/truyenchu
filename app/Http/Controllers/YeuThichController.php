<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use Illuminate\Http\Request;

class YeuThichController extends Controller
{
    public function danhSach()
    {
        $truyens = auth()->user()->yeuThich()
            ->with(['theLoai', 'chuongMoiNhat'])
            ->orderByPivot('created_at', 'desc')
            ->paginate(18);

        return view('tai-khoan.yeu-thich', compact('truyens'));
    }

    public function toggle(Truyen $truyen)
    {
        $nguoiDung = auth()->user();
        $exists = $nguoiDung->yeuThich()->where('truyen_id', $truyen->id)->exists();

        if ($exists) {
            $nguoiDung->yeuThich()->detach($truyen->id);
            $truyen->decrement('tong_luot_yeu_thich');
            $message = 'Đã bỏ yêu thích truyện.';
        } else {
            $nguoiDung->yeuThich()->attach($truyen->id, ['created_at' => now()]);
            $truyen->increment('tong_luot_yeu_thich');
            $message = 'Đã thêm vào yêu thích!';
        }

        if (request()->ajax()) {
            return response()->json(['trang_thai' => !$exists, 'thong_bao' => $message]);
        }

        return back()->with('thanh_cong', $message);
    }
}
