<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\TheLoai;
use Illuminate\Http\Request;

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

        $truyens = $query->paginate(18)->onEachSide(2)->withQueryString();
        $theLoais = TheLoai::sapXep()->get();

        return view('truyen.danh-sach', compact('truyens', 'theLoais'));
    }

    public function chiTiet(string $slug)
    {
        $truyen = Truyen::where('slug', $slug)
            ->daXuatBan()
            ->with('theLoai')
            ->firstOrFail();

        $chuongs = $truyen->chuongDaXuatBan()
            ->select('id', 'truyen_id', 'so_chuong', 'tieu_de', 'slug', 'tong_luot_xem', 'published_at')
            ->paginate(50)->onEachSide(2);

        $truyenLienQuan = Truyen::daXuatBan()
            ->whereHas('theLoai', function ($q) use ($truyen) {
                $q->whereIn('the_loai.id', $truyen->theLoai->pluck('id'));
            })
            ->where('id', '!=', $truyen->id)
            ->with('theLoai')
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Kiểm tra yêu thích / theo dõi
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

        // Bình luận truyện
        $binhLuans = $truyen->binhLuan()
            ->hienThi()
            ->goc()
            ->with(['nguoiDung:id,ten_hien_thi,anh_dai_dien', 'binhLuanCon' => fn($q) => $q->hienThi()->with('nguoiDung:id,ten_hien_thi,anh_dai_dien')])
            ->whereNull('chuong_id')
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

        return view('truyen.chi-tiet', compact(
            'truyen', 'chuongs', 'truyenLienQuan',
            'daYeuThich', 'daTheoDoi', 'lichSuDoc', 'binhLuans'
        ));
    }
}
