# Abstract Factory Pattern - Button Example

Program ini mengimplementasikan pola Abstract Factory untuk membuat dua jenis tombol:

1. **HoverButton**: Tombol yang mengubah warna latar belakang saat mouse diarahkan (hover)
2. **ClickButton**: Tombol yang mengubah dua warna (background dan text) saat diklik

## Struktur Design Pattern

```
ButtonFactory (Abstract Factory)
├── HoverButtonFactory (Concrete Factory)
│   └── creates HoverButton
└── ClickButtonFactory (Concrete Factory)
    └── creates ClickButton

Button (Abstract Product)
├── HoverButton (Concrete Product)
└── ClickButton (Concrete Product)
```

## Komponen

- **Button**: Interface untuk semua jenis button (Abstract Product)
- **HoverButton**: Button dengan efek hover (Concrete Product)
- **ClickButton**: Button dengan efek click dan toggle warna (Concrete Product)
- **ButtonFactory**: Interface untuk factory (Abstract Factory)
- **HoverButtonFactory**: Factory untuk membuat HoverButton (Concrete Factory)
- **ClickButtonFactory**: Factory untuk membuat ClickButton (Concrete Factory)
- **Main**: Client code untuk demonstrasi

## Cara Menjalankan

```bash
# Compile semua file
javac *.java

# Jalankan program
java Main
```

## Output yang Diharapkan

Program akan mendemonstrasikan:
- Pembuatan HoverButton dan simulasi mouse enter/leave
- Pembuatan ClickButton dan simulasi klik dengan toggle warna
- Penggunaan polymorphism dengan factory pattern

## Keuntungan Abstract Factory Pattern

1. **Encapsulation**: Proses pembuatan object ter-encapsulasi dalam factory
2. **Flexibility**: Mudah menambah jenis button baru tanpa mengubah client code
3. **Consistency**: Memastikan produk yang dibuat konsisten dengan family-nya
4. **Loose Coupling**: Client code tidak perlu tahu detail implementasi concrete class
