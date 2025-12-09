# PlantUML Diagrams for Final Report

Berikut adalah kode PlantUML untuk men-generate gambar-gambar yang dibutuhkan di Bab 3. Anda bisa copy-paste kode ini ke [PlantText](https://www.planttext.com/) atau editor PlantUML lainnya untuk mendapatkan gambarnya.

## 1. Diagram Alur Penelitian (Flowchart)
*Gambar 3.1*

```plantuml
@startuml
!theme plain
start

:Mulai;

:Analisis Kebutuhan;
note right
- Spesifikasi VPS
- Kebutuhan Software
end note

:Perancangan Arsitektur;
note right
- Topologi Cloud
- Skema Database
end note

:Implementasi (Deployment);
partition "Cloud Environment" {
  :Setup VPS (Ubuntu);
  :Install LAMP Stack;
  :Konfigurasi DuckDNS;
  :Deploy Aplikasi Laravel;
}

:Integrasi AI;
note right
- Google Gemini API
- Fitur Business Insight
end note

:Pengujian Sistem;
if (Sistem Berjalan Normal?) then (Ya)
  :Dokumentasi & Laporan;
  :Selesai;
else (Tidak)
  :Debugging & Perbaikan;
  stop
endif

@enduml
```

## 2. Topologi Arsitektur Cloud
*Gambar 3.2*

```plantuml
@startuml
!theme plain
actor "User (Admin/Kasir)" as User
cloud "Internet" {
  node "DuckDNS Service" as DNS
}

node "Cloud VPS (Atlantic-Server)" {
    component "Apache Web Server" as Apache
    component "Laravel App (Gudangku)" as App
    database "MySQL Database" as DB
}

cloud "Google Cloud Platform" {
    component "Gemini AI API" as AI
}

User --> DNS : 1. Akses gudangku.duckdns.org
DNS --> Apache : 2. Resolve IP Publik
Apache --> App : 3. Forward Request
App <--> DB : 4. Query Data Transaksi
App <--> AI : 5. Request Analisis Bisnis

@enduml
```

## 3. Use Case Diagram
*Gambar 3.3*

```plantuml
@startuml
!theme plain
left to right direction
actor "Administrator (Pemilik)" as Admin
actor "Kasir" as Kasir

rectangle "Sistem POS (Gudangku)" {
  usecase "Login" as UC1
  usecase "Kelola Produk & Stok" as UC2
  usecase "Transaksi Penjualan" as UC3
  usecase "Lihat Laporan Penjualan" as UC4
  usecase "Lihat Business Insight (AI)" as UC5
  usecase "Kelola User" as UC6
}

Admin --> UC1
Admin --> UC2
Admin --> UC4
Admin --> UC5
Admin --> UC6

Kasir --> UC1
Kasir --> UC3
Kasir --> UC2 : (View Only)

@enduml
```

## 4. Entity Relationship Diagram (ERD)
*Gambar 3.4*

```plantuml
@startuml
!theme plain
hide circle
skinparam linetype ortho

entity "Users" as user {
  *id : bigint
  --
  name : varchar
  email : varchar
  password : varchar
  role : enum
}

entity "Products" as product {
  *id : bigint
  --
  category_id : bigint
  product_name : varchar
  product_code : varchar
  price : decimal
  quantity : int
}

entity "Categories" as category {
  *id : bigint
  --
  category_name : varchar
  category_code : varchar
}

entity "Sales" as sale {
  *id : bigint
  --
  customer_id : bigint
  user_id : bigint
  date : date
  reference : varchar
  total_amount : decimal
  status : enum
}

entity "Sale_Details" as sale_detail {
  *id : bigint
  --
  sale_id : bigint
  product_id : bigint
  price : decimal
  quantity : int
  sub_total : decimal
}

entity "Customers" as customer {
  *id : bigint
  --
  customer_name : varchar
  customer_phone : varchar
}

user ||..o{ sale : "melayani"
category ||..o{ product : "memiliki"
product ||..o{ sale_detail : "terjual dalam"
sale ||..|{ sale_detail : "memiliki"
customer ||..o{ sale : "melakukan"

@enduml
```

## 5. Sequence Diagram Integrasi AI
*Gambar 3.5*

```plantuml
@startuml
!theme plain
autonumber

actor "Administrator" as Admin
participant "Dashboard View" as View
participant "AIController" as Controller
participant "AIService" as Service
database "MySQL Database" as DB
participant "Gemini API" as Gemini

Admin -> View : Pilih Rentang Waktu (1/3/7 Hari)
View -> Controller : Request Insight (days)
activate Controller

Controller -> Service : generateDailyInsight(days)
activate Service

Service -> DB : Get Sales Data (Last X Days)
activate DB
DB --> Service : Return Sales Data
deactivate DB

Service -> Service : Format Prompt (Text)
note right of Service
  "Total Omzet: Rp X
  Item Terlaris: Y
  Periode: Z Hari"
end note

Service -> Gemini : Send Prompt
activate Gemini
Gemini --> Service : Return Analysis (Text)
deactivate Gemini

Service --> Controller : Return Insight Text
deactivate Service

Controller --> View : Return JSON Response
deactivate Controller

View --> Admin : Tampilkan Analisis Bisnis
@enduml
```
