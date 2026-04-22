<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chuong;

class RepairChapterFormatting extends Command
{
    protected $signature = 'app:fix-chapters';
    protected $description = 'Sửa định dạng xuống dòng cho các chương cũ đã cào';

    public function handle()
    {
        $this->info('Đang quét danh sách chương cần sửa...');
        
        $count = Chuong::where('noi_dung', 'not like', '%<p>%')->count();
        
        if ($count === 0) {
            $this->info('Không tìm thấy chương nào cần sửa định dạng.');
            return;
        }

        $this->info("Tìm thấy $count chương. Bắt đầu sửa...");
        $processed = 0;

        Chuong::where('noi_dung', 'not like', '%<p>%')->chunkById(100, function ($chuongs) use (&$processed) {
            foreach ($chuongs as $ch) {
                // Tách theo xuống dòng
                $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $ch->noi_dung));
                $newContent = '';
                
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $newContent .= "<p>{$line}</p>";
                    }
                }

                if (!empty($newContent)) {
                    $ch->update(['noi_dung' => $newContent]);
                }
                
                $processed++;
            }
            $this->output->write('.');
        });

        $this->newLine();
        $this->info("Đã sửa thành công $processed chương!");
    }
}
