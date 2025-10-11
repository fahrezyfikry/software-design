# Bouncing Ball Animation - Multi-threaded Java Application

Program animasi bola memantul yang menggunakan threading untuk memanipulasi setiap bola secara independen.

## Arsitektur 4+1 View Model

Model 4+1 View adalah arsitektur yang menggambarkan sistem dari 5 perspektif berbeda, dengan Use Cases sebagai pusat yang menghubungkan semua view.

### 1. Logical View (Struktur Statis)

**Stakeholders:** End-users, Developers

**Tujuan:** Menggambarkan struktur statis sistem dan fungsionalitas yang disediakan kepada end-users.

**Komponen Utama:**
```
BouncingBallApp (Controller/Main)
├── BallPanel (View)
│   └── List<Ball> (Model)
└── List<BallThread> (Worker Threads)
    └── Memanipulasi Ball
```

**Class Responsibilities:**

- **Ball**: Model class yang menyimpan state (posisi x,y, velocity dx,dy, radius, warna). Mengandung logika untuk movement dan collision detection.

- **BallPanel**: View class (extends JPanel) yang bertanggung jawab untuk rendering. Menyimpan list of balls dan menggambar mereka ke layar.

- **BallThread**: Worker thread yang secara kontinyu mengupdate posisi ball dan meminta repaint ke panel. Setiap ball punya thread sendiri.

- **BouncingBallApp**: Main controller class yang mengatur lifecycle aplikasi, menangani user input, dan mengkoordinasi antara model dan view.

**Design Patterns:**
- **MVC Pattern**: Pemisahan antara Model (Ball), View (BallPanel), dan Controller (BouncingBallApp)
- **Thread-per-Object**: Setiap Ball object punya dedicated thread sendiri

**Key Abstractions:**
- Encapsulation: Setiap class memiliki tanggung jawab yang jelas
- Polymorphism: Thread dan Component dari Java framework
- Modularity: Komponen dapat dikembangkan dan ditest secara independen

### 2. Development View (Physical View)

**Stakeholders:** Developers, Management

**Tujuan:** Menggambarkan organisasi software modules, libraries, dan dependencies untuk developer dan manajemen proyek.

**Package Structure:**
```
bouncing-ball/
├── model/
│   └── Ball.java              # Domain model
├── view/
│   └── BallPanel.java         # UI components
├── controller/
│   └── BouncingBallApp.java   # Application controller
└── thread/
    └── BallThread.java        # Concurrency components
```

**Dependencies:**
```
Java Standard Library
├── java.awt.*          (Graphics, Color, Dimension)
├── javax.swing.*       (JFrame, JPanel, JButton)
└── java.util.*         (List, ArrayList, Random)

External Dependencies: None (Pure Java)
```

**Build & Deployment:**
- **Build Tool**: javac (Java Compiler)
- **Runtime**: JRE 8 or higher
- **Package**: Standalone JAR (optional)

**Code Organization:**
- Source files dalam satu package untuk simplicity
- Dapat di-refactor ke multiple packages untuk scalability
- Clear separation of concerns antar layer

### 3. Process View (Concurrency & Runtime)

**Stakeholders:** End-users, Testers, Developers

**Tujuan:** Menggambarkan aspek runtime sistem, termasuk concurrency, threads, dan komunikasi antar process.

**Thread Architecture:**

```
Main Thread (Event Dispatch Thread - EDT)
├── Menangani UI events (button clicks)
├── Melakukan rendering (paintComponent)
└── Mengkoordinasi BallThreads

BallThread-1
├── Update Ball-1 position
└── Request repaint → EDT

BallThread-2
├── Update Ball-2 position
└── Request repaint → EDT

BallThread-N
├── Update Ball-N position
└── Request repaint → EDT
```

**Thread Interaction:**

1. **BallThread → Ball (Model Update)**
   - BallThread memanggil `ball.move()` secara periodik
   - Update terjadi di worker thread, bukan EDT
   - Setiap ball bergerak independen dengan kecepatan sendiri

2. **BallThread → BallPanel (Repaint Request)**
   - Setelah update posisi, BallThread memanggil `panel.repaint()`
   - `repaint()` adalah thread-safe dan akan schedule paint di EDT
   - EDT kemudian memanggil `paintComponent()` untuk render

3. **Synchronization**
   - Access ke `List<Ball>` di BallPanel di-synchronize
   - Mencegah race condition saat add/remove ball
   - `paintComponent()` lock list saat iteration

4. **Thread Lifecycle**
   - Threads dibuat saat "Add Ball" diklik
   - Berjalan dalam loop dengan sleep delay (16ms ≈ 60 FPS)
   - Dapat di-stop dengan flag `volatile boolean running`

**Concurrency Benefits:**
- Setiap ball bergerak independen
- Smooth animation karena parallel execution
- Tidak blocking UI thread

**Concurrency Challenges:**
- Thread synchronization untuk shared data
- Memory overhead untuk multiple threads
- Potential race conditions

**Performance Characteristics:**
- Target: 60 FPS (16ms per frame)
- Scalability: Bergantung pada jumlah balls dan CPU cores
- Memory: O(n) dimana n = jumlah balls dan threads

### 4. Deployment View (Physical Deployment)

**Stakeholders:** System Engineers, IT Personnel

**Tujuan:** Menggambarkan bagaimana sistem di-deploy ke infrastruktur hardware/network.

**Deployment Topology:**
```
┌─────────────────────────────┐
│   End-User Workstation      │
│                             │
│  ┌───────────────────────┐  │
│  │   Operating System    │  │
│  │   (Windows/Mac/Linux) │  │
│  │                       │  │
│  │  ┌─────────────────┐  │  │
│  │  │   JVM (Java 8+) │  │  │
│  │  │                 │  │  │
│  │  │  ┌───────────┐  │  │  │
│  │  │  │ Bouncing  │  │  │  │
│  │  │  │   Ball    │  │  │  │
│  │  │  │   App     │  │  │  │
│  │  │  └───────────┘  │  │  │
│  │  └─────────────────┘  │  │
│  └───────────────────────┘  │
└─────────────────────────────┘
```

**System Requirements:**
- **Hardware:**
  - CPU: Dual-core processor (minimum)
  - RAM: 512 MB (minimum), 1 GB (recommended)
  - Display: 800x600 resolution minimum
  - Input: Mouse/Keyboard

- **Software:**
  - JRE/JDK 8 or higher
  - Operating System: Windows 7+, macOS 10.10+, Linux (any modern distro)

**Deployment Steps:**
1. Install JRE/JDK jika belum ada
2. Copy semua .java files atau .jar ke target machine
3. Compile: `javac *.java` (jika source)
4. Run: `java BouncingBallApp`

**Distribution Options:**
- **Source Distribution**: Distribute .java files
- **Binary Distribution**: Create executable JAR with manifest
- **Platform-Specific**: Bundle dengan JRE (jpackage Java 14+)

**Network Requirements:**
- None (standalone application, no network connectivity required)

### 5. Use Cases (Scenarios) - The "+1"

**Stakeholders:** All stakeholders

**Tujuan:** Menghubungkan semua views dengan requirements dan validasi sistem.

**Primary Use Cases:**

**UC1: Add Bouncing Ball**
- **Actor:** User
- **Flow:**
  1. User clicks "Add Ball" button
  2. System generates random ball properties (position, velocity, color, size)
  3. System creates Ball object and BallThread
  4. System starts animation
- **Postcondition:** New ball appears and bounces in panel

**UC2: Clear All Balls**
- **Actor:** User
- **Flow:**
  1. User clicks "Clear All" button
  2. System stops all BallThreads
  3. System clears ball collection
  4. System repaints empty panel
- **Postcondition:** All balls removed, threads terminated

**UC3: Stop Animation**
- **Actor:** User
- **Flow:**
  1. User clicks "Stop All" button
  2. System signals all threads to stop
  3. Threads complete current iteration and pause
  4. Balls remain visible but static
- **Postcondition:** Animation paused

**UC4: Resume Animation**
- **Actor:** User
- **Flow:**
  1. User clicks "Start All" button
  2. System creates new threads for existing balls
  3. System starts all threads
  4. Animation resumes
- **Postcondition:** Balls continue bouncing

**UC5: Resize Window**
- **Actor:** User
- **Flow:**
  1. User drags window corner to resize
  2. System detects resize event
  3. System updates panel dimensions for all balls
  4. Balls continue bouncing within new boundaries
- **Postcondition:** Bounce boundaries updated

**Cross-View Traceability:**
- **Logical View**: Defines Ball, BallPanel, BallThread classes
- **Development View**: Organizes code in maintainable structure
- **Process View**: Implements multi-threading for smooth animation
- **Deployment View**: Ensures compatibility across platforms
- **Use Cases**: Drive all design decisions and validate implementation

## Struktur File

```
bouncing-ball/
├── Ball.java              # Model class
├── BallThread.java        # Worker thread class
├── BallPanel.java         # View class (JPanel)
├── BouncingBallApp.java   # Main controller
└── README.md              # Dokumentasi ini
```

## Cara Menjalankan

```bash
# Compile
javac *.java

# Run
java BouncingBallApp
```

## Cara Menggunakan

1. **Add Ball**: Tambah bola baru dengan posisi dan kecepatan random
2. **Clear All**: Hapus semua bola dan stop semua thread
3. **Stop All**: Pause semua thread tanpa menghapus bola
4. **Start All**: Resume thread yang sudah di-stop

## Fitur

- ✅ Multiple balls dengan thread terpisah untuk masing-masing
- ✅ Collision detection dengan tepi panel
- ✅ Bounce physics (membalik velocity saat kena tepi)
- ✅ Random colors, positions, velocities, sizes
- ✅ Thread-safe rendering
- ✅ Dynamic window resizing support
- ✅ Thread lifecycle management (start/stop)

## Threading Model

- **Event Dispatch Thread (EDT)**: Menangani UI events dan rendering
- **BallThread(s)**: Update posisi ball secara parallel
- **Communication**: Via repaint requests (thread-safe)
- **Synchronization**: Synchronized blocks untuk shared collections

## Key Concepts Demonstrated

1. **Multi-threading**: Setiap ball punya independent thread
2. **Thread synchronization**: Safe access ke shared data
3. **MVC Architecture**: Separation of concerns
4. **Swing threading model**: EDT vs worker threads
5. **Physics simulation**: Basic bounce mechanics
6. **Object-oriented design**: Encapsulation, inheritance
