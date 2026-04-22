<?php

namespace Database\Seeders;

use App\Models\Truyen;
use App\Models\Chuong;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChuongSeeder extends Seeder
{
    public function run(): void
    {
        $truyens = Truyen::all();

        $noiDungMau = [
            'Ánh nắng chiếu xuyên qua tán lá, rải những vệt sáng vàng óng trên mặt đất. Gió nhẹ thổi qua, mang theo hương hoa dại thoang thoảng. Trong khu rừng cổ thụ, một bóng người đang di chuyển nhanh như gió, bước chân nhẹ nhàng không để lại dấu vết.',
            'Trên đỉnh ngọn núi cao chót vót, một người ngồi xếp bằng, mắt nhắm nghiền, toàn thân tỏa ra ánh sáng dịu nhẹ. Luồng khí lực trong cơ thể vận hành theo quỹ đạo đặc biệt, mỗi vòng tuần hoàn lại mạnh mẽ hơn trước. Đã ba ngày ba đêm, người đó không hề nhúc nhích.',
            'Thành phố về đêm lung linh ánh đèn, dòng người tấp nập qua lại trên phố. Quán trà nhỏ ở góc đường vắng vẻ lạ thường, chỉ có một ông lão ngồi bên bàn, tay xoay xoay tách trà đã nguội. Ông ta dường như đang chờ đợi ai đó.',
            'Tiếng kiếm xé gió vang lên, ánh bạc lóe qua như tia chớp. Hai bóng người đan xen vào nhau, tốc độ nhanh đến mức mắt thường khó lòng theo kịp. Đất đá nứt vỡ bắn tung tóe, cây cối xung quanh bị cắt đứt gọn gàng.',
            'Căn phòng nhỏ ấm cúng, ánh lửa bập bùng trong lò sưởi. Trên bàn gỗ cũ kỹ bày la liệt sách vở và bản đồ. Cô gái trẻ chăm chú đọc cuốn sách cổ đã ố vàng, đôi mắt sáng lên mỗi khi phát hiện điều gì mới mẻ. Đây là bí mật mà cô tìm kiếm suốt nhiều năm.',
            'Mưa rơi tầm tã, sấm sét vang rền khắp bầu trời. Trên cánh đồng hoang vu, một đoàn người đang gấp rút di chuyển. Họ mặc áo choàng đen, bước đi vội vã nhưng trật tự. Dẫn đầu là người đàn ông cao lớn, khuôn mặt lạnh lùng nghiêm nghị.',
        ];

        foreach ($truyens as $truyen) {
            $soChuong = rand(5, 10);

            for ($i = 1; $i <= $soChuong; $i++) {
                // Tạo nội dung dài hơn bằng cách ghép nhiều đoạn
                $noiDung = '';
                $soDoan = rand(3, 6);
                for ($j = 0; $j < $soDoan; $j++) {
                    $noiDung .= $noiDungMau[array_rand($noiDungMau)] . "\n\n";
                }

                Chuong::create([
                    'truyen_id' => $truyen->id,
                    'so_chuong' => $i,
                    'tieu_de' => "Chương {$i}: " . $this->tieuDeChuong($i),
                    'slug' => "chuong-{$i}",
                    'noi_dung' => $noiDung,
                    'so_tu' => str_word_count($noiDung),
                    'tong_luot_xem' => rand(500, 50000),
                    'is_published' => true,
                    'published_at' => now()->subDays(rand(1, 365))->subHours(rand(1, 24)),
                ]);
            }
        }
    }

    private function tieuDeChuong(int $so): string
    {
        $tieuDes = [
            'Khởi Đầu Mới',
            'Cuộc Gặp Gỡ Định Mệnh',
            'Bí Mật Của Rừng Sâu',
            'Thử Thách Khó Khăn',
            'Sức Mạnh Thức Tỉnh',
            'Người Lạ Mặt',
            'Trận Chiến Đầu Tiên',
            'Con Đường Phía Trước',
            'Huyết Chiến',
            'Ánh Sáng Cuối Đường Hầm',
            'Lời Hứa Của Gió',
            'Bóng Tối Lan Rộng',
            'Ngọn Lửa Hy Vọng',
            'Vùng Đất Mới',
            'Kẻ Thù Xuất Hiện',
        ];

        return $tieuDes[($so - 1) % count($tieuDes)];
    }
}
