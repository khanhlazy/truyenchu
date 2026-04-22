<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CauHinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCauHinhController extends Controller
{
    public function giaoD()
    {
        $logo = CauHinh::lay('logo');
        $banner = CauHinh::lay('banner');
        $ten_website = CauHinh::lay('ten_website', 'TruyệnChữ');
        $mo_ta_website = CauHinh::lay('mo_ta_website', 'Website đọc truyện chữ online miễn phí');
        $banner_tieu_de = CauHinh::lay('banner_tieu_de', 'Khám Phá Thế Giới Truyện Chữ');
        $banner_mo_ta = CauHinh::lay('banner_mo_ta', 'Hàng nghìn bộ truyện hay đang chờ bạn');

        return view('admin.cau-hinh.giao-dien', compact(
            'logo', 'banner', 'ten_website', 'mo_ta_website',
            'banner_tieu_de', 'banner_mo_ta'
        ));
    }

    public function capNhatGiaoDien(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'banner' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
            'ten_website' => 'nullable|string|max:100',
            'mo_ta_website' => 'nullable|string|max:500',
            'banner_tieu_de' => 'nullable|string|max:200',
            'banner_mo_ta' => 'nullable|string|max:500',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            // Xóa logo cũ
            $logoCu = CauHinh::lay('logo');
            if ($logoCu) {
                Storage::disk('public')->delete($logoCu);
            }
            $logoPath = $request->file('logo')->store('site', 'public');
            CauHinh::dat('logo', $logoPath);
        }

        // Upload banner
        if ($request->hasFile('banner')) {
            $bannerCu = CauHinh::lay('banner');
            if ($bannerCu) {
                Storage::disk('public')->delete($bannerCu);
            }
            $bannerPath = $request->file('banner')->store('site', 'public');
            CauHinh::dat('banner', $bannerPath);
        }

        // Text settings
        if ($request->filled('ten_website')) {
            CauHinh::dat('ten_website', $request->ten_website);
        }
        if ($request->has('mo_ta_website')) {
            CauHinh::dat('mo_ta_website', $request->mo_ta_website);
        }
        if ($request->has('banner_tieu_de')) {
            CauHinh::dat('banner_tieu_de', $request->banner_tieu_de);
        }
        if ($request->has('banner_mo_ta')) {
            CauHinh::dat('banner_mo_ta', $request->banner_mo_ta);
        }

        // Xóa logo nếu tick
        if ($request->has('xoa_logo')) {
            $logoCu = CauHinh::lay('logo');
            if ($logoCu) {
                Storage::disk('public')->delete($logoCu);
            }
            CauHinh::dat('logo', null);
        }

        // Xóa banner nếu tick
        if ($request->has('xoa_banner')) {
            $bannerCu = CauHinh::lay('banner');
            if ($bannerCu) {
                Storage::disk('public')->delete($bannerCu);
            }
            CauHinh::dat('banner', null);
        }

        return redirect()->route('admin.cau-hinh.giao-dien')
            ->with('thanh_cong', 'Cập nhật giao diện thành công!');
    }
}
