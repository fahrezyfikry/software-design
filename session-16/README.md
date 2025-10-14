# Library Management System - Design Patterns Implementation

Aplikasi Java sederhana yang mengimplementasikan **semua kriteria** dari Module Practicum 4, mencakup Software Interfaces, APIs, dan berbagai Design Patterns.

## 📋 Kriteria yang Dipenuhi

### ✅ 1. Software Interfaces and APIs
- **LibraryService**: Interface modular untuk operasi perpustakaan (peminjaman, pengembalian, pencarian)
- Desain interface yang sesuai dengan prinsip OOP dan dapat digunakan ulang

### ✅ 2. Characterization of Larger Software Elements  
- **BookLibrary**: Pustaka yang dapat digunakan independen dari aplikasi utama
- Metode untuk mencari, menambah, dan menghapus buku
- Dokumentasi dan contoh implementasi yang jelas

### ✅ 3. Creational Design Patterns
- **Singleton Pattern**: `LibraryManager` - memastikan hanya satu instance
- **Builder Pattern**: `Book.BookBuilder` - membangun objek Book dengan berbagai atribut

### ✅ 4. Structural Design Patterns
- **Adapter Pattern**: `ExternalLibraryAdapter` - menghubungkan dengan API eksternal
- **Facade Pattern**: `LibraryFacade` - menyederhanakan akses ke subsistem kompleks

### ✅ 5. Behavioral Design Patterns
- **Observer Pattern**: `BookStatusObserver` - memantau perubahan status buku
- **Strategy Pattern**: `SearchStrategy` - strategi pencarian berdasarkan judul/penulis/tahun

## 🏗️ Arsitektur Sistem

```
LibraryManagementDemo (Main)
├── LibraryFacade (Facade Pattern)
│   ├── LibraryManager (Singleton Pattern)
│   │   └── BookLibrary (Reusable Library)
│   ├── NotificationSystem (Observer)
│   └── MembershipSystem
├── Book (Builder Pattern)
├── SearchStrategy (Strategy Pattern)
│   ├── TitleSearchStrategy
│   ├── AuthorSearchStrategy
│   └── YearSearchStrategy
└── ExternalLibraryAdapter (Adapter Pattern)
```

## 📁 Struktur File

```
library-management-system/
├── Book.java                    # Model dengan Builder Pattern
├── LibraryService.java          # Interface untuk API
├── BookLibrary.java             # Pustaka reusable + Strategy + Observer
├── LibraryManager.java          # Singleton + Facade + Adapter patterns
├── LibraryManagementDemo.java   # Main class dengan demonstrasi
└── README.md                    # Dokumentasi ini
```

## 🚀 Cara Menjalankan

### Kompilasi
```bash
cd library-management-system
javac *.java
```

### Eksekusi
```bash
java LibraryManagementDemo
```

## 💡 Design Patterns yang Diimplementasikan

### 1. **Creational Patterns**

#### Singleton Pattern - `LibraryManager`
```java
LibraryManager manager = LibraryManager.getInstance();
// Memastikan hanya ada satu instance di seluruh aplikasi
```

#### Builder Pattern - `Book.BookBuilder`
```java
Book book = new Book.BookBuilder()
    .setTitle("Java: The Complete Reference")
    .setAuthor("Herbert Schildt")
    .setYear(2021)
    .setIsbn("978-0071808552")
    .build();
```

### 2. **Structural Patterns**

#### Facade Pattern - `LibraryFacade`
```java
LibraryFacade facade = new LibraryFacade();
facade.borrowBook("978-0071808552", "MEM001");
// Menyederhanakan operasi kompleks yang melibatkan multiple subsystems
```

#### Adapter Pattern - `ExternalLibraryAdapter`
```java
ExternalLibraryAdapter adapter = new ExternalLibraryAdapter(localLibrary);
adapter.borrowBook(isbn, memberId);
// Mengintegrasikan dengan sistem perpustakaan eksternal
```

### 3. **Behavioral Patterns**

#### Observer Pattern - `BookStatusObserver`
```java
library.addBookStatusObserver(new NotificationSystem());
// Otomatis mengirim notifikasi saat status buku berubah
```

#### Strategy Pattern - `SearchStrategy`
```java
// Pencarian berdasarkan judul
SearchStrategy titleStrategy = new TitleSearchStrategy();
List<Book> results = library.searchBooks("Java", titleStrategy);

// Pencarian berdasarkan penulis  
SearchStrategy authorStrategy = new AuthorSearchStrategy();
results = library.searchBooks("Herbert", authorStrategy);
```

## 📊 Output Demonstrasi

Ketika menjalankan aplikasi, akan ditampilkan:

```
===========================================
   LIBRARY MANAGEMENT SYSTEM DEMO
   Design Patterns Implementation
===========================================

🔹 DEMONSTRATING SINGLETON PATTERN
-------------------------------------------
LibraryManager instance created (Singleton)
manager1 == manager2: true
Both references point to same instance: 123456789
✓ Singleton pattern demonstrated

🔹 DEMONSTRATING BUILDER PATTERN
-------------------------------------------
Book created with Builder: Book{title='Effective Java', author='Joshua Bloch', year=2018, isbn='978-0134685991', status=AVAILABLE}
✓ Builder pattern demonstrated

🔹 DEMONSTRATING FACADE PATTERN
-------------------------------------------
Using Facade to perform complex operations:
=== Library Status ===
Available books: 3
Borrowed books: 0
✓ Book borrowing process completed successfully
✓ Facade pattern demonstrated

🔹 DEMONSTRATING STRATEGY PATTERN
-------------------------------------------
=== Search Results ===
- Book{title='Java: The Complete Reference', author='Herbert Schildt', year=2021, isbn='978-0071808552', status=BORROWED}
- Book{title='Effective Java', author='Joshua Bloch', year=2018, isbn='978-0134685991', status=AVAILABLE}
✓ Strategy pattern demonstrated

🔹 DEMONSTRATING OBSERVER PATTERN
-------------------------------------------
📧 NOTIFICATION: Book 'Design Patterns' status changed from AVAILABLE to BORROWED
🔔 CUSTOM OBSERVER: Status change detected for 'Design Patterns'
📧 NOTIFICATION: Book 'Design Patterns' status changed from BORROWED to AVAILABLE
🔔 CUSTOM OBSERVER: Status change detected for 'Design Patterns'
✓ Observer pattern demonstrated

🔹 DEMONSTRATING ADAPTER PATTERN
-------------------------------------------
External API: Checking availability for book ID: 978-9999999999
External API: Requesting transfer of book 978-9999999999 to LOCAL_LIB_001
Book requested from external library network
✓ Adapter pattern demonstrated
```

## 🎯 Keunggulan Implementasi

### **Modularitas**
- Setiap komponen dapat dikembangkan dan ditest secara independen
- Interface yang jelas memisahkan contract dari implementasi

### **Reusability**  
- `BookLibrary` dapat digunakan di berbagai konteks aplikasi
- Design patterns memungkinkan ekstensibilitas tanpa mengubah kode existing

### **Maintainability**
- Kode terstruktur dengan separation of concerns
- Penggunaan design patterns membuat kode lebih mudah dipahami

### **Scalability**
- Facade pattern memudahkan penambahan subsistem baru
- Strategy pattern memungkinkan penambahan algoritma pencarian baru
- Observer pattern mendukung sistem notifikasi yang fleksibel

## 📖 Konsep OOP yang Diterapkan

- **Encapsulation**: Setiap class memiliki tanggung jawab yang spesifik
- **Inheritance**: Interface implementation dan abstract patterns
- **Polymorphism**: Strategy pattern dan interface-based programming  
- **Abstraction**: Interface dan abstract classes untuk contract definition

## 🔧 Ekstensibilitas

Sistem ini mudah diperluas dengan:
- **SearchStrategy baru**: Pencarian berdasarkan kategori, rating, dll
- **Observer baru**: Email notifications, SMS alerts, dll  
- **Adapter baru**: Integrasi dengan database, web services, dll
- **Facade methods**: Operasi kompleks tambahan

---

## 📚 Referensi Design Patterns

1. **Gang of Four Design Patterns** - Foundational patterns reference
2. **Java Design Patterns** - Modern implementation approaches  
3. **Clean Code** - Best practices untuk maintainable code

---

**📧 Developed for Module Practicum 4 - Software Design**  
*Implementasi lengkap semua kriteria: Interfaces, APIs, dan Design Patterns*
