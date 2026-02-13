bikin manual
helper
repositories
php artisan make:repositories

Interface
repository
provider

controller
resource
helper 

// php artisan lang:publish = manambahkan id indo

| Komponen   | Buat apa             |
| ---------- | -------------------- |
| Request    | Validasi input       |
| Controller | Alur                 |
| Model      | Relasi + query kecil |     |
| Repository | Susun query          |
| Interface  | Kontrak (opsional)   |
| Provider   | Binding              |
| Resource   | Bentuk JSON/key nya

     |

RANGKUMAN ISTILAH (VERSI SUPER RINGKAS)
Istilah	Artinya
Class	= Cetakan
Object =	Hasil jadi dari class
Property = 	Variabel milik object
Method =	Fungsi milik object
Constructor = 	Jalan otomatis saat object dibuat
$this = 	Object ini
Interface =	Aturan / kontrak
Repository= 	Tempat ambil data
DI	= Dikasi object, bukan bikin
Controller= 	Pengatur alur
Resource	= Bentuk response JSON
Scope	Potongan query


rangkuman spatie
implement spatie
composer
publish
seting di config auth untuk guard

install spatie jangan lupa untuk sactum 
bikin model untuk personal acces token
tambahkan di provider yang boot
cek uuid keduanya
