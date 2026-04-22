<?php

namespace Database\Seeders;

use App\Models\Truyen;
use App\Models\TheLoai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TruyenSeeder extends Seeder
{
    public function run(): void
    {
        $truyens = [
            [
                'tieu_de' => 'Đấu Phá Thương Khung',
                'tac_gia' => 'Thiên Tàm Thổ Đậu',
                'mo_ta_ngan' => 'Truyện kể về Tiêu Viêm, một thiên tài sa sút, trên hành trình khôi phục vinh quang và trở thành cường giả đỉnh cao.',
                'mo_ta_day_du' => 'Tiêu Viêm, vốn là thiên tài tu luyện, bỗng nhiên mất đi tất cả sức mạnh vào năm 12 tuổi. Trong ba năm tiếp theo, cậu bị chế giễu và khinh thường. Nhưng một ngày, chiếc nhẫn bí ẩn trên tay cậu thức tỉnh, mở ra con đường tu luyện mới, đưa cậu vào thế giới của Đấu Khí rộng lớn nơi cường giả là tôn.',
                'trang_thai' => 'hoan_thanh',
                'the_loai' => ['tien-hiep', 'huyen-huyen'],
            ],
            [
                'tieu_de' => 'Phàm Nhân Tu Tiên',
                'tac_gia' => 'Vong Ngữ',
                'mo_ta_ngan' => 'Hàn Lập, một thiếu niên bình thường, bước vào con đường tu tiên đầy gian nan, từng bước trở thành tiên nhân.',
                'mo_ta_day_du' => 'Hàn Lập xuất thân từ một gia đình nghèo khó, bước vào thế giới tu tiên nhờ một cơ duyên tình cờ. Không có tư chất xuất chúng, không có bối cảnh mạnh mẽ, chỉ dựa vào sự cẩn thận, thông minh và kiên trì để dần dần tiến bước trên con đường tu luyện, từ Luyện Khí kỳ cho đến đỉnh cao Tu Chân giới.',
                'trang_thai' => 'hoan_thanh',
                'the_loai' => ['tien-hiep'],
            ],
            [
                'tieu_de' => 'Thần Ấn Vương Tọa',
                'tac_gia' => 'Đường Gia Tam Thiếu',
                'mo_ta_ngan' => 'Long Hạo Thần trên hành trình trở thành Anh Hùng Thần Ấn, chiến đấu chống lại ma tộc bảo vệ nhân loại.',
                'mo_ta_day_du' => 'Thế giới bị ma tộc xâm lược, nhân loại chỉ có thể sống trong sáu đại thánh miếu. Long Hạo Thần, một thiếu niên với ước mơ trở thành Kỵ sĩ, bắt đầu hành trình tu luyện của mình. Với thể chất đặc biệt và ý chí kiên cường, cậu dần dần vươn lên, trở thành hy vọng của cả nhân loại.',
                'trang_thai' => 'hoan_thanh',
                'the_loai' => ['huyen-huyen', 'he-thong'],
            ],
            [
                'tieu_de' => 'Vũ Động Càn Khôn',
                'tac_gia' => 'Thiên Tàm Thổ Đậu',
                'mo_ta_ngan' => 'Lâm Động của gia tộc sa sút tìm được Phù Đồ Thạch bí ẩn, mở ra con đường tu luyện mạnh mẽ.',
                'mo_ta_day_du' => 'Lâm Động, thiếu niên của Lâm gia, một gia tộc đã suy bại, trong một lần tình cờ đã nhặt được một viên đá bí ẩn. Từ đó, cuộc đời cậu hoàn toàn thay đổi. Viên đá kia chính là Phù Đồ Thạch huyền thoại, ẩn chứa một sức mạnh to lớn. Lâm Động bắt đầu hành trình tu luyện, bước vào thế giới rộng lớn của Yêu Khí và Nguyên Lực.',
                'trang_thai' => 'hoan_thanh',
                'the_loai' => ['tien-hiep', 'huyen-huyen'],
            ],
            [
                'tieu_de' => 'Toàn Chức Pháp Sư',
                'tac_gia' => 'Loạn',
                'mo_ta_ngan' => 'Mạc Phàm tỉnh dậy trong một thế giới nơi phép thuật thay thế khoa học, bắt đầu hành trình trở thành pháp sư mạnh nhất.',
                'mo_ta_day_du' => 'Mạc Phàm, một học sinh bình thường, bỗng phát hiện thế giới xung quanh đã thay đổi. Khoa học không còn tồn tại, thay vào đó là phép thuật và ma thú. Dù xuất thân nghèo khó, cậu sở hữu hai hệ phép thuật hiếm có, bắt đầu con đường trở thành Toàn Chức Pháp Sư đầu tiên trong lịch sử.',
                'trang_thai' => 'dang_ra',
                'the_loai' => ['huyen-huyen', 'do-thi'],
            ],
            [
                'tieu_de' => 'Ngã Dục Phong Thiên',
                'tac_gia' => 'Nhĩ Căn',
                'mo_ta_ngan' => 'Một thiếu niên mồ côi bước vào con đường tu tiên, phát hiện bí mật về thân thế và thế giới tu chân.',
                'mo_ta_day_du' => 'Một ngàn năm trước, cường giả đỉnh nhất của Tu Chân giới, Vong Lam giáo chủ, phá vỡ hư không, biến mất không dấu vết. Một ngàn năm sau, cậu bé mồ côi Vương Lâm bước vào con đường tu tiên đầy chông gai, dần dần khám phá ra bí mật đằng sau thân thế của mình và những âm mưu lớn đang bao trùm toàn bộ Tu Chân giới.',
                'trang_thai' => 'hoan_thanh',
                'the_loai' => ['tien-hiep'],
            ],
            [
                'tieu_de' => 'Thiếu Niên Ca Hành',
                'tac_gia' => 'Tây Hồ Tiểu Bạch',
                'mo_ta_ngan' => 'Câu chuyện kiếm hiệp lãng mạn về một thiếu niên du hành giang hồ, ngắm nhìn thế giới và tìm kiếm ý nghĩa cuộc sống.',
                'mo_ta_day_du' => 'Mộ Dung Vấn, một thiếu niên mang trong mình bí mật lớn, bước vào giang hồ đầy sóng gió. Cậu du hành khắp thiên hạ, gặp gỡ những anh hùng hào kiệt, tham gia vào những trận chiến kinh thiên động địa. Câu chuyện pha trộn giữa kiếm hiệp cổ điển và tình cảm sâu lắng, tạo nên một tác phẩm đầy màu sắc.',
                'trang_thai' => 'dang_ra',
                'the_loai' => ['kiem-hiep'],
            ],
            [
                'tieu_de' => 'Đô Thị Chi Tối Cường Cuồng Binh',
                'tac_gia' => 'Lão Thi',
                'mo_ta_ngan' => 'Một binh vương trở về thành phố, bảo vệ người thân và đối mặt với những thế lực hắc ám.',
                'mo_ta_day_du' => 'Trần Dương, vị Binh Vương huyền thoại, sau năm năm chinh chiến sa trường, trở về thành phố quê hương. Nhưng chờ đợi anh không phải bình yên mà là những thế lực hắc ám đang nhòm ngó gia đình. Với sức mạnh vô đối và mạng lưới quan hệ rộng lớn, anh sẽ bảo vệ tất cả những gì mình yêu thương.',
                'trang_thai' => 'dang_ra',
                'the_loai' => ['do-thi', 'quan-su'],
            ],
            [
                'tieu_de' => 'Truy Sát Trong Đêm',
                'tac_gia' => 'Lâm Thanh Sơn',
                'mo_ta_ngan' => 'Một thám tử tài năng điều tra loạt án mạng bí ẩn, phát hiện âm mưu đen tối đe dọa cả thành phố.',
                'mo_ta_day_du' => 'Thanh Vũ, một thám tử tư với quá khứ bí ẩn, nhận được lời mời điều tra loạt án mạng liên hoàn tại thành phố Hải Đông. Mỗi nạn nhân đều nhận được một lá thư cảnh báo trước khi chết. Khi Thanh Vũ đào sâu vào vụ án, anh phát hiện ra một tổ chức ngầm đang kiểm soát cả thành phố từ bóng tối.',
                'trang_thai' => 'dang_ra',
                'the_loai' => ['trinh-tham', 'kinh-di'],
            ],
            [
                'tieu_de' => 'Yêu Em Từ Cái Nhìn Đầu Tiên',
                'tac_gia' => 'Cố Mạn',
                'mo_ta_ngan' => 'Câu chuyện tình yêu lãng mạn giữa một cô gái bình thường và chàng tổng tài lạnh lùng nhưng si tình.',
                'mo_ta_day_du' => 'Vi Vỹ, một cô gái giản dị và hiền lành, tình cờ gặp gỡ Hà Dĩ Thâm, luật sư tài giỏi nhưng lạnh lùng. Không ai biết rằng, đằng sau vẻ ngoài lạnh lùng ấy, Hà Dĩ Thâm đã lặng lẽ yêu Vi Vỹ suốt bảy năm. Một câu chuyện tình yêu đẹp đẽ, chậm rãi nhưng sâu lắng.',
                'trang_thai' => 'hoan_thanh',
                'the_loai' => ['ngon-tinh', 'do-thi'],
            ],
        ];

        foreach ($truyens as $index => $data) {
            $theLoaiSlugs = $data['the_loai'];
            unset($data['the_loai']);

            $data['slug'] = Str::slug($data['tieu_de']);
            $data['tong_luot_xem'] = rand(10000, 500000);
            $data['tong_luot_theo_doi'] = rand(500, 20000);
            $data['tong_luot_yeu_thich'] = rand(300, 15000);
            $data['is_published'] = true;
            $data['published_at'] = now()->subDays(rand(1, 365));

            $truyen = Truyen::create($data);

            $theLoaiIds = TheLoai::whereIn('slug', $theLoaiSlugs)->pluck('id');
            $truyen->theLoai()->attach($theLoaiIds);
        }
    }
}
