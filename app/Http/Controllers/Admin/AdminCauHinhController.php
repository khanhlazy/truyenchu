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
        $favicon = CauHinh::lay('favicon');
        $banner = CauHinh::lay('banner');
        $ten_website = CauHinh::lay('ten_website', 'TruyệnChữ');
        $mo_ta_website = CauHinh::lay('mo_ta_website', 'Website đọc truyện chữ online miễn phí');
        $banner_tieu_de = CauHinh::lay('banner_tieu_de', 'Khám Phá Thế Giới Truyện Chữ');
        $banner_mo_ta = CauHinh::lay('banner_mo_ta', 'Hàng nghìn bộ truyện hay đang chờ bạn');
        $banner_dung_gradient = CauHinh::lay('banner_dung_gradient', '0');
        
        // Donate
        $donate_bat = CauHinh::lay('donate_bat', '0');
        $donate_noi_dung = CauHinh::lay('donate_noi_dung', '');

        return view('admin.cau-hinh.giao-dien', compact(
            'logo', 'favicon', 'banner', 'ten_website', 'mo_ta_website',
            'banner_tieu_de', 'banner_mo_ta', 'banner_dung_gradient',
            'donate_bat', 'donate_noi_dung'
        ));
    }

    public function capNhatGiaoDien(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:1024',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp,ico|max:512',
            'banner' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
            'ten_website' => 'nullable|string|max:100',
            'mo_ta_website' => 'nullable|string|max:500',
            'banner_tieu_de' => 'nullable|string|max:200',
            'banner_mo_ta' => 'nullable|string|max:500',
            'donate_noi_dung' => 'nullable|string',
            'donate_qr_momo' => 'nullable|image|max:1024',
            'donate_qr_bank' => 'nullable|image|max:1024',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            $logoCu = CauHinh::lay('logo');
            if ($logoCu) Storage::disk('public')->delete($logoCu);
            CauHinh::dat('logo', $request->file('logo')->store('site', 'public'));
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            $favCu = CauHinh::lay('favicon');
            if ($favCu) Storage::disk('public')->delete($favCu);
            CauHinh::dat('favicon', $request->file('favicon')->store('site', 'public'));
        }

        // Upload banner
        if ($request->hasFile('banner')) {
            $bannerCu = CauHinh::lay('banner');
            if ($bannerCu) Storage::disk('public')->delete($bannerCu);
            CauHinh::dat('banner', $request->file('banner')->store('site', 'public'));
        }

        // Upload Donate QRs
        if ($request->hasFile('donate_qr_momo')) {
            $momoCu = CauHinh::lay('donate_qr_momo');
            if ($momoCu) Storage::disk('public')->delete($momoCu);
            CauHinh::dat('donate_qr_momo', $request->file('donate_qr_momo')->store('site', 'public'));
        }
        if ($request->hasFile('donate_qr_bank')) {
            $bankCu = CauHinh::lay('donate_qr_bank');
            if ($bankCu) Storage::disk('public')->delete($bankCu);
            CauHinh::dat('donate_qr_bank', $request->file('donate_qr_bank')->store('site', 'public'));
        }

        // Text settings
        CauHinh::dat('ten_website', $request->input('ten_website'));
        CauHinh::dat('mo_ta_website', $request->input('mo_ta_website'));
        CauHinh::dat('banner_tieu_de', $request->input('banner_tieu_de'));
        CauHinh::dat('banner_mo_ta', $request->input('banner_mo_ta'));
        CauHinh::dat('banner_dung_gradient', $request->has('banner_dung_gradient') ? '1' : '0');
        
        // Donate
        CauHinh::dat('donate_bat', $request->has('donate_bat') ? '1' : '0');
        CauHinh::dat('donate_noi_dung', $request->input('donate_noi_dung'));

        // Xử lý xóa
        if ($request->has('xoa_logo')) {
            $logoCu = CauHinh::lay('logo');
            if ($logoCu) Storage::disk('public')->delete($logoCu);
            CauHinh::dat('logo', null);
        }
        if ($request->has('xoa_favicon')) {
            $favCu = CauHinh::lay('favicon');
            if ($favCu) Storage::disk('public')->delete($favCu);
            CauHinh::dat('favicon', null);
        }
        if ($request->has('xoa_banner')) {
            $bannerCu = CauHinh::lay('banner');
            if ($bannerCu) Storage::disk('public')->delete($bannerCu);
            CauHinh::dat('banner', null);
        }

        return redirect()->route('admin.cau-hinh.giao-dien')->with('thanh_cong', 'Cập nhật cấu hình thành công!');
    }
}
