<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TruyenController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = Truyen::daXuatBan()->with(['theLoai', 'chuongMoiNhat']);

        // Lọc theo thể loại
        if ($request->filled('the_loai')) {
            $query->theoTheLoai($request->input('the_loai'));
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->trangThai($request->input('trang_thai'));
        }

        // Tìm kiếm
        if ($request->filled('tu_khoa')) {
            $query->timKiem($request->input('tu_khoa'));
        }

        // Sắp xếp
        $query->sapXep($request->input('sap_xep', 'moi_cap_nhat'));

        $truyens = $query->paginate(18)->withQueryString();

        // Cache danh sách thể loại 1 giờ (ít thay đổi)
        $theLoais = Cache::remember('danh_sach_the_loai', 3600, function () {
            return TheLoai::sapXep()->get();
        });

        return view('truyen.danh-sach', compact('truyens', 'theLoais'));
    }

    public function chiTiet(string $slug)
    {
        // Cache truyện chi tiết 30 phút
        $truyen = Cache::remember("truyen_chi_tiet_{$slug}", 1800, function () use ($slug) {
            return Truyen::where('slug', $slug)
                ->daXuatBan()
                ->with('theLoai')
                ->firstOrFail();
        });

        $chuongs = $truyen->chuongDaXuatBan()
            ->select('id', 'truyen_id', 'so_chuong', 'tieu_de', 'slug', 'tong_luot_xem', 'published_at')
            ->paginate(50);

        // Cache truyện liên quan 1 giờ — query nặng vì dùng whereHas + inRandomOrder
        $truyenLienQuan = Cache::remember("truyen_lien_quan_{$truyen->id}", 3600, function () use ($truyen) {
            return Truyen::daXuatBan()
                ->whereHas('theLoai', function ($q) use ($truyen) {
                    $q->whereIn('the_loai.id', $truyen->theLoai->pluck('id'));
                })
                ->where('id', '!=', $truyen->id)
                ->with('theLoai')
                ->inRandomOrder()
                ->take(6)
                ->get();
        });

        // Kiểm tra yêu thích / theo dõi — chỉ query khi đã đăng nhập
        $daYeuThich = false;
        $daTheoDoi = false;
        $lichSuDoc = null;

        if (auth()->check()) {
            $daYeuThich = auth()->user()->yeuThich()->where('truyen_id', $truyen->id)->exists();
            $daTheoDoi = auth()->user()->theoDoi()->where('truyen_id', $truyen->id)->exists();
            $lichSuDoc = auth()->user()->lichSuDoc()
                ->where('truyen_id', $truyen->id)
                ->with('chuong:id,so_chuong,tieu_de,slug')
                ->orderByDesc('thoi_diem_doc_cuoi')
                ->first();
        }

        // Cache bình luận truyện 5 phút
        $binhLuans = Cache::remember("binh_luan_truyen_{$truyen->id}", 300, function () use ($truyen) {
            return $truyen->binhLuan()
                ->hienThi()
                ->goc()
                ->with(['nguoiDung:id,ten_hien_thi,anh_dai_dien', 'binhLuanCon' => fn($q) => $q->hienThi()->with('nguoiDung:id,ten_hien_thi,anh_dai_dien')])
                ->whereNull('chuong_id')
                ->orderByDesc('created_at')
                ->take(20)
                ->get();
        });

        return view('truyen.chi-tiet', compact(
            'truyen', 'chuongs', 'truyenLienQuan',
            'daYeuThich', 'daTheoDoi', 'lichSuDoc', 'binhLuans'
        ));
    }
}
