<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use Illuminate\Http\Request;

class TheoDoiController extends Controller
{
    public function danhSach()
    {
        $truyens = auth()->user()->theoDoi()
            ->with(['theLoai', 'chuongMoiNhat'])
            ->orderByPivot('updated_at', 'desc')
            ->paginate(18);

        return view('tai-khoan.theo-doi', compact('truyens'));
    }

    public function toggle(Truyen $truyen)
    {
        $nguoiDung = auth()->user();
        $exists = $nguoiDung->theoDoi()->where('truyen_id', $truyen->id)->exists();

        if ($exists) {
            $nguoiDung->theoDoi()->detach($truyen->id);
            $truyen->decrement('tong_luot_theo_doi');
            $message = 'Đã bỏ theo dõi truyện.';
        } else {
            $nguoiDung->theoDoi()->attach($truyen->id, ['created_at' => now(), 'updated_at' => now()]);
            $truyen->increment('tong_luot_theo_doi');
            $message = 'Đã theo dõi truyện!';
        }

        if (request()->ajax()) {
            return response()->json(['trang_thai' => !$exists, 'thong_bao' => $message]);
        }

        return back()->with('thanh_cong', $message);
    }
}
