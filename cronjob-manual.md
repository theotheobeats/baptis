##Untuk publish invoice

<!-- Change default editor dari vim jadi nano -->
export EDITOR=/usr/bin/nano

crontab -e -l -r
-e = edit
-l = list pekerjaan
-r = hapus pekerjaan

<!-- Baris dibawah berarti setiap menit cd ke folder project dan publish invoice -->
* * * * * cd /folder project && php artisan invoice:publish >> /lokasi log/cronlog 2>&1


<!-- 59 23 28-31 * * menentukan waktu eksekusi: Pada menit ke-59 dan jam ke-23 pada tanggal 28 hingga 31 setiap bulan.-->
<!-- && digunakan untuk menjalankan perintah hanya jika ekspresi sebelumnya bernilai benar. -->
<!-- $(date +\%d -d tomorrow) akan mengambil tanggal besok. -->
<!-- == "01" akan memeriksa apakah tanggal besok adalah tanggal 1, yang menandakan bahwa hari besok adalah awal bulan baru. -->

59 23 28-31 * * [ "$(date +\%d -d tomorrow)" == "01" ] && cd /folder project && php artisan invoice:publish >> /lokasi log/cronlog 2>&1

